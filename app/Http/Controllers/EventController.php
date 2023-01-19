<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Models\EventImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Geocoder\Query\GeocodeQuery;
use Geocoder\Query\ReverseQuery;
use GuzzleHttp\Client;

class EventController extends Controller
{
    /*
     * Add an image to an event
     * @param array $images
     * @param \App\Models\Event $event
     * @return void
     */
    private function uploadImages($images, Event $event)
    {
      foreach ($images as $image) {
        if (env('APP_ENV') === 'production') {
          $event->addMedia($image)->toMediaCollection('images', 'do');
        } else if (env('APP_ENV') === 'local') {
          $event->addMedia($image)->toMediaCollection('images', 'public');
        }
      }
    }

    /*
     * Geocode the location based on address
     * @param string $location
     * @return array
     */
    private function GeoCode($location)
    {
      //geocode the location
      $httpClient = new \GuzzleHttp\Client();
      $provider = new \Geocoder\Provider\Mapbox\Mapbox($httpClient, env('VITE_MAPBOX'));
      $geocoder = new \Geocoder\StatefulGeocoder($provider, 'en');

      $result = $geocoder->geocodeQuery(GeocodeQuery::create($location));

      //assign the latitude and longitude to the event
      $latitude = $result->first()->getCoordinates()->getLatitude();
      $longitude = $result->first()->getCoordinates()->getLongitude();

      return array($latitude, $longitude);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return inertia('Events/Index', [
        'events' => Event::query()
          ->when($request = request(['search', 'date']), function ($query, $request) {
            if ($request['search']) {
              $query->where('location', 'like', '%' . $request['search'] . '%');
            }
            if ($request['date']) {
              $query->where('start_date', '>=', $request['date']);
            }
          })
          ->with('getFirstImage')
          ->orderBy('created_at', 'desc')
          ->paginate(9)
          ->withQueryString(),
        'filters' => Request::only(['search', 'date']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      if (Auth::check()){
        return inertia('Events/Create');
      }
      else {
        return redirect(route('login'));
      }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreEventRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEventRequest $request)
    {
      $fields = $request->validate([
        'title' => 'required|max:255',
        'description' => 'required',
        'location' => 'required',
        'start_date' => 'required|date',
        'end_date' => 'nullable|date|after:start_date',
        'url' => 'nullable|url',
        'start_time' => 'nullable',
        'images' => 'nullable|array|max:6',
        'images.*' => 'nullable|file|image|mimes:jpeg,webp,png,jpg,gif,svg|max:2048',
      ]);

      //geocode the location
      $httpClient = new Client();
      $provider = new \Geocoder\Provider\Mapbox\Mapbox($httpClient, env('VITE_MAPBOX'));
      $geocoder = new \Geocoder\StatefulGeocoder($provider, 'en');

      $result = $geocoder->geocodeQuery(GeocodeQuery::create($fields['location']));

      //assign the latitude and longitude to the event
      $latitude = $result->first()->getCoordinates()->getLatitude();
      $longitude = $result->first()->getCoordinates()->getLongitude();

      $event = Event::create([
        'title' => $request->title,
        'description' => $request->description,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'user_id' => Auth::user()->id,
        'location' => $request->location,
        'url' => $request->url,
        'start_time' => $request->start_time,
        'latitude' => $latitude,
        'longitude' => $longitude,
      ]);

      if ($request->hasFile('images')) {
        $images = $request->file('images');
        $this->uploadImages($images, $event);
      }

      return redirect()->route('event.show', $event);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
      $event = Event::where('id', $event->id)
        ->with('media')
        ->get();
      return inertia('Events/Show', [
        'event' => $event,
      ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
      if (Auth::check() && Auth::user()->id == $event->user_id){
        return inertia('Events/Edit', [
          'event' => Event::findOrFail($event->id),
          'images' => EventImage::where('event_id', $event->id)->get(),
        ]);
      }
      else {
        return redirect(route('login'));
      }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEventRequest  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
      $fields = $request->validate([
        'title' => 'required|max:255',
        'description' => 'required',
        'location' => 'required',
        'start_date' => 'required|date',
        'end_date' => 'nullable|date|after:start_date',
        'url' => 'nullable|url',
        'start_time' => 'nullable',
        'images' => 'nullable|array|max:6',
        'images.*' => 'nullable|file|image|mimes:jpeg,webp,png,jpg,gif,svg|max:2048',
      ]);

      if($fields['location'] != $event->location){
        //geocode the location
        $httpClient = new \GuzzleHttp\Client();
        $provider = new \Geocoder\Provider\Mapbox\Mapbox($httpClient, env('VITE_MAPBOX'));
        $geocoder = new \Geocoder\StatefulGeocoder($provider, 'en');

        $result = $geocoder->geocodeQuery(GeocodeQuery::create($fields['location']));

        //assign the latitude and longitude to the event
        $latitude = $result->first()->getCoordinates()->getLatitude();
        $longitude = $result->first()->getCoordinates()->getLongitude();
      }
      else {
        $latitude = $event->latitude;
        $longitude = $event->longitude;
      }

      $event->update([
        'title' => $request->title,
        'description' => $request->description,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'user_id' => Auth::user()->id,
        'location' => $request->location,
        'url' => $request->url,
        'start_time' => $request->start_time,
      ]);

      if ($request->hasFile('images')) {
        // Delete old images
        EventImage::where('event_id', $event->id)->delete();

        // Add new images
        $images = $request->file('images');
        foreach ($images as $image) {
          $path = $image->store('images', 'public');
          EventImage::create([
            'path' => $path,
            'event_id' => $event->id,
          ]);
        }
      }

      return redirect()->route('event.show', $event);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
      if(Event::destroy($event->id) && $event->getMedia('images')->delete()){
        return redirect()->route('dashboard')->with('success', 'Event deleted successfully');
      }
      else {
        return redirect()->route('dashboard')->with('error', 'Event could not be deleted');
      }
    }
}
