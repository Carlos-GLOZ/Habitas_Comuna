<x-action-section submit="/">
    <x-slot name="title">
        {{ __('Communities') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Manage your communities, join a new community or create a new one.') }}
    </x-slot>

    <x-slot name="content">
        <div class="col-span-6 sm:col-span-4">
            <form action="{{ route('comunidad_vista') }}" method="get" class="col-span-6 sm:col-span-4">
                <x-secondary-button class="mt-2 mr-2" type="submit">
                    {{ __('Manage Communities') }}
                </x-secondary-button>
            </form>
        </div>
    </x-slot>
</x-action-section>