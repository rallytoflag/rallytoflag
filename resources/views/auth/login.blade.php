<x-layouts.app>
  <x-card>
      <!-- Session Status -->
      <x-auth-session-status class="mb-4" :status="session('status')" />

      <form method="POST" action="{{ route('login') }}">
          @csrf

          <!-- Email Address -->
          <div>
              <x-input-label for="email" :value="__('Email')" />
              <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
              <x-input-error :messages="$errors->get('email')" class="mt-2" />
          </div>

          <!-- Password -->
          <div class="mt-4">
              <x-input-label for="password" :value="__('Password')" />

              <x-input id="password" class="block mt-1 w-full"
                              type="password"
                              name="password"
                              required autocomplete="current-password" />

              <x-input-error :messages="$errors->get('password')" class="mt-2" />
          </div>

          <!-- Remember Me -->
          <div class="block mt-4">
              <label for="remember_me" class="inline-flex items-center">
                  <input id="remember_me" type="checkbox" 
                  class="rounded border-gray-300 text-violet-900 shadow-sm focus:ring-violet-500" 
                  name="remember">
                  <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                    {{ __('Remember me') }}
                  </span>
              </label>
          </div>

          <div class="flex items-center justify-end mt-4">
              @if (Route::has('password.request'))
                  <a class="underline text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100
                  rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" 
                  href="{{ route('password.request') }}">
                      {{ __('Forgot your password?') }}
                  </a>
              @endif

              <x-primary-button class="ml-3">
                  {{ __('Log in') }}
              </x-primary-button>
          </div>
      </form>
  </x-card>
</x-layouts.app>
