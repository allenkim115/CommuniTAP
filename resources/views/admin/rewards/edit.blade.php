<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-3xl font-semibold text-gray-900 leading-tight">{{ __('Edit Reward') }}</h2>
                <p class="text-sm text-gray-500">Refresh details while keeping the experience on brand.</p>
            </div>
            <a href="{{ route('admin.rewards.index') }}" class="btn-muted text-sm px-4 py-2 w-fit">
                Back to rewards
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="card-surface p-6">
                <form method="POST" action="{{ route('admin.rewards.update', $reward) }}" enctype="multipart/form-data" class="space-y-8" novalidate>
                    @csrf
                    @method('PATCH')
                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700">Sponsor name</label>
                            <input name="sponsor_name" value="{{ old('sponsor_name', $reward->sponsor_name) }}" minlength="10" class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm shadow-sm focus:border-brand-teal focus:ring-brand-teal" required>
                            @error('sponsor_name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700">Reward name</label>
                            <input name="reward_name" value="{{ old('reward_name', $reward->reward_name) }}" minlength="10" class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm shadow-sm focus:border-brand-teal focus:ring-brand-teal" required>
                            @error('reward_name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Description</label>
                        <textarea name="description" rows="4" minlength="10" class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm shadow-sm focus:border-brand-teal focus:ring-brand-teal" required>{{ old('description', $reward->description) }}</textarea>
                        @error('description') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid gap-6 md:grid-cols-3">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700">Points cost</label>
                            <input type="number" min="1" name="points_cost" value="{{ old('points_cost', $reward->points_cost) }}" class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm shadow-sm focus:border-brand-teal focus:ring-brand-teal" required>
                            @error('points_cost') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700">Quantity</label>
                            <input type="number" min="0" name="QTY" value="{{ old('QTY', $reward->QTY) }}" class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm shadow-sm focus:border-brand-teal focus:ring-brand-teal" required>
                            @error('QTY') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700">Status</label>
                            <select name="status" class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm shadow-sm focus:border-brand-teal focus:ring-brand-teal">
                                <option value="active" {{ old('status', $reward->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $reward->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="text-sm font-semibold text-gray-700">Image upload</label>
                        <div class="rounded-2xl border-2 border-dashed border-gray-200 p-6 text-center">
                            <input type="file" name="image" accept="image/*,.webp,image/webp" class="w-full text-sm text-gray-600">
                            <p class="mt-2 text-xs text-gray-400">Upload a refreshed banner if the visual has changed. JPG, PNG, or WEBP preferred.</p>
                        </div>
                        @if($reward->image_path)
                            <div class="rounded-2xl border border-gray-100 p-3">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Current image</p>
                                <img src="{{ Storage::url($reward->image_path) }}" alt="Current reward image" class="h-40 w-full rounded-xl object-cover">
                            </div>
                        @endif
                        @error('image') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                        <a href="{{ route('admin.rewards.index') }}" class="btn-muted w-full sm:w-auto justify-center">
                            Cancel
                        </a>
                        <button type="submit" class="btn-brand w-full sm:w-auto justify-center">
                            Save changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
