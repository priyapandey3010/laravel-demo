<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <img src="{{ asset('img/logo.png') }}" alt="{{ __('National Company Law Appellate Tribunal') }}" />
            </a>
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form id="form" method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="mt-4">
                {!! NoCaptcha::renderJs() !!}
                {!! NoCaptcha::display() !!}
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-button class="ml-3" type="button" id="login-button">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>
<script src="{{ asset('admin-theme/vendor/jquery/jquery.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js" integrity="sha512-E8QSvWZ0eCLGk4km3hxSsNmGWbLtSCSUcewDQPQWZF6pEU8GlT8a5fF32wOl1i8ftdMhssTrF/OhyGWwonTcXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        function crypto(secret) {
            if (secret.length > 0) {
                var salt = CryptoJS.enc.Hex.parse("{{ $crypto_salt }}");
                var iv = CryptoJS.enc.Hex.parse("{{ $crypto_iv }}");
                var key = CryptoJS.PBKDF2(
                    "{{ $crypto_key }}", 
                    salt, { 
                        hasher: CryptoJS.algo.SHA512, 
                        keySize: {{ $crypto_key_size }}, 
                        iterations: {{ $crypto_iterations }} 
                    }
                ); 
                var encrypted = CryptoJS.AES.encrypt(secret, key, {iv: iv});
                var encryptedData = {
                    ciphertext : CryptoJS.enc.Base64.stringify(encrypted.ciphertext),
                    salt : CryptoJS.enc.Hex.stringify(salt),
                    iv : CryptoJS.enc.Hex.stringify(iv)    
                };  
                return encryptedData;
            }
        }
        
    $("#login-button").click(function(e) {
        e.preventDefault();
        var password = $("#password").val();
        if (password) {
            password = crypto(password).ciphertext;
            $("#password").val(password);
            $("#form").submit();
        }
    })
</script>
    </x-auth-card>
</x-guest-layout>