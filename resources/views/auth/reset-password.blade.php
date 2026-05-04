<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <x-form-field label="{{ __('Email') }}" for="email" type="email" name="email" :value="old('email', $request->email)"
            autocomplete="username" required autofocus />

        <!-- Password -->
        <x-form-field label="{{ __('Password') }}" for="password" type="password" name="password"
            autocomplete="new-password" required />

        <!-- Confirm Password -->
        <x-form-field label="{{ __('Confirm Password') }}" for="password_confirmation" type="password"
            name="password_confirmation" autocomplete="new-password" required />

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
