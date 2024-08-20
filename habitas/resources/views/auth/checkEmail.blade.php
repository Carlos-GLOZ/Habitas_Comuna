<x-guest-layout>
    <div class="w-screen h-screen flex flex-col items-center justify-center">
        <h1>{{__("Verify Email")}}</h1>
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-button type="submit">
                    {{ __('Send Verification Email') }}
                </x-button>
            </div>
        </form>
    </div>
</x-guest-layout>
