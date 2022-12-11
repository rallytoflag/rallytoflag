<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Models\EventImage;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      if (Auth::check()){
        return view('events.create');
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
        'title' => 'required',
        'description' => 'required',
        'location' => 'required',
        'start_date' => 'required',
        'end_date' => 'required',
      ]);

      $event = Event::create([
        'title' => $request->title,
        'description' => $request->description,
        'location' => $request->location,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'user_id' => Auth::user()->id,
      ]);

      return redirect()->route('event.show', $event, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
      return view('events.show', [
        'event' => Event::find($event)
      ],
      );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
      return view('events.update', [
        'event' => Event::find($event)
      ],
      );
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

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
      Event::destroy($event);
      return response('Event Deleted', 204);
    }
}