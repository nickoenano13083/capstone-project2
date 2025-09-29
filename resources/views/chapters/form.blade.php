@props(['chapter' => null])

<form method="POST" action="{{ $chapter ? route('chapters.update', $chapter) : route('chapters.store') }}" class="space-y-6">
    @csrf
    @if($chapter)
        @method('PUT')
    @endif

    <div>
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $chapter?->name)" required autofocus />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>

    <div>
        <x-input-label for="location" :value="__('Location')" />
        <x-text-input id="location" name="location" type="text" class="mt-1 block w-full" :value="old('location', $chapter?->location)" required />
        <x-input-error class="mt-2" :messages="$errors->get('location')" />
    </div>

    <div>
        <x-input-label for="leader" :value="__('Leader')" />
        <x-text-input id="leader" name="leader" type="text" class="mt-1 block w-full" :value="old('leader', $chapter?->leader)" required />
        <x-input-error class="mt-2" :messages="$errors->get('leader')" />
    </div>

    <div class="flex items-center gap-4">
        <x-primary-button>{{ $chapter ? __('Update Chapter') : __('Create Chapter') }}</x-primary-button>
        <a href="{{ route('chapters.index') }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
    </div>
</form> 