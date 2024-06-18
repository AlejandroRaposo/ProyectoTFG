<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Información de perfil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Actualiza tu información y tu email.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" name="upda" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="facebook" :value="__('Facebook')" />
            <x-text-input id="faceboo" name="facebook" type="text" class="mt-1 block w-full" :value="old('facebook', $user->facebook)" autofocus />
            <x-input-error class="mt-2" :messages="$errors->get('facebook')" />

            <x-input-label for=twitter :value="__('Twitter')" />
            <x-text-input id=twitter name=twitter type="text" class="mt-1 block w-full" :value="old('twitter', $user->twitter)" autofocus />
            <x-input-error class="mt-2" :messages="$errors->get('twitter')" />

            <x-input-label for=instagram :value="__('Instagram')" />
            <x-text-input id=instagram name=instagram type="text" class="mt-1 block w-full" :value="old('instagram', $user->instagram)" autofocus />
            <x-input-error class="mt-2" :messages="$errors->get('instagram')" />

            <x-input-label for=discord :value="__('Discord')" />
            <x-text-input id=discord name=discord type="text" class="mt-1 block w-full" :value="old('discord', $user->discord)" autofocus />
            <x-input-error class="mt-2" :messages="$errors->get('discord')" />

            <x-input-label for=patreon :value="__('Patreon')" />
            <x-text-input id=patreon name=patreon type="text" class="mt-1 block w-full" :value="old('patreon', $user->patreon)" autofocus />
            <x-input-error class="mt-2" :messages="$errors->get('patreon')" />

            <x-input-label for=kofi :value="__('Kofi')" />
            <x-text-input id=kofi name=kofi type="text" class="mt-1 block w-full" :value="old('kofi', $user->kofi)" autofocus />
            <x-input-error class="mt-2" :messages="$errors->get('kofi')" />

        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div>
                <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                    {{ __('Tu email no está verificado.') }}

                    <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                        {{ __('Pulsa aqui para reenviar el email de verificación.') }}
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                    {{ __('Un nuevo email de verificación se ha enviado a tu email.') }}
                </p>
                @endif
            </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Guardar') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600 dark:text-gray-400">{{ __('Guardado.') }}</p>
            @endif
        </div>
    </form>
</section>