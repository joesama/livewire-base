<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif
        <form method="POST" action="{{ route('file-upload') }}" enctype="multipart/form-data">
            @csrf

            <div>
                <x-jet-label for="upload" value="{{ __('Upload') }}" />
                <x-jet-input id="upload" class="block mt-1 w-full" type="file" name="upload"  autofocus />
            </div>
            <div>
                @if($photo)
                    <img src="{{ asset($photo) }}"></div>
                @endif
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-jet-button class="ml-4">
                    {{ __('Upload') }}
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>
