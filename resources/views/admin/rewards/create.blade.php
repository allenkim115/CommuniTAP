<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-3xl font-semibold text-gray-900 leading-tight">{{ __('Create Reward') }}</h2>
                <p class="text-sm text-gray-500">Give volunteers something exciting to look forward to.</p>
            </div>
            <a href="{{ route('admin.rewards.index') }}" class="btn-muted text-sm px-4 py-2 w-fit">
                Back to rewards
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="card-surface p-6">
                <form method="POST" action="{{ route('admin.rewards.store') }}" enctype="multipart/form-data" class="space-y-8" novalidate>
                    @csrf
                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700">Sponsor name</label>
                            <input name="sponsor_name" value="{{ old('sponsor_name') }}" minlength="10" class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm shadow-sm focus:border-brand-teal focus:ring-brand-teal" required>
                            @error('sponsor_name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700">Reward name</label>
                            <input name="reward_name" value="{{ old('reward_name') }}" minlength="10" class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm shadow-sm focus:border-brand-teal focus:ring-brand-teal" required>
                            @error('reward_name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Description</label>
                        <textarea name="description" rows="4" minlength="10" class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm shadow-sm focus:border-brand-teal focus:ring-brand-teal" placeholder="Describe what makes this reward special..." required>{{ old('description') }}</textarea>
                        @error('description') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid gap-6 md:grid-cols-3">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700">Points cost</label>
                            <input type="number" min="1" name="points_cost" value="{{ old('points_cost') }}" class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm shadow-sm focus:border-brand-teal focus:ring-brand-teal" required>
                            @error('points_cost') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700">Quantity</label>
                            <input type="number" min="0" name="QTY" value="{{ old('QTY') }}" class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm shadow-sm focus:border-brand-teal focus:ring-brand-teal" required>
                            @error('QTY') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700">Status</label>
                            <select name="status" class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm shadow-sm focus:border-brand-teal focus:ring-brand-teal">
                                <option value="active" {{ old('status') === 'inactive' ? '' : 'selected' }}>Active</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Image upload</label>
                        <div class="rounded-2xl border-2 border-dashed border-gray-200 p-6 text-center">
                            <input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-600">
                            <p class="mt-2 text-xs text-gray-400">Use 1200x800 JPG or PNG for best presentation.</p>
                        </div>
                        @error('image') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                        <a href="{{ route('admin.rewards.index') }}" class="btn-muted w-full sm:w-auto justify-center">
                            Cancel
                        </a>
                        <button type="submit" class="btn-brand w-full sm:w-auto justify-center">
                            Create reward
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
