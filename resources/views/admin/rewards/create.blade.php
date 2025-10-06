<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Create Reward') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('admin.rewards.store') }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm mb-1">Sponsor Name</label>
                            <input name="sponsor_name" class="w-full rounded border dark:bg-gray-900" required />
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Reward Name</label>
                            <input name="reward_name" class="w-full rounded border dark:bg-gray-900" required />
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm mb-1">Image Upload</label>
                                <input type="file" name="image" accept="image/*" class="w-full rounded border dark:bg-gray-900" />
                            </div>
                            <!-- Removed URL field to avoid unknown column errors -->
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Description</label>
                            <textarea name="description" class="w-full rounded border dark:bg-gray-900" rows="4" required></textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm mb-1">Points Cost</label>
                                <input type="number" name="points_cost" class="w-full rounded border dark:bg-gray-900" required min="1" />
                            </div>
                            <div>
                                <label class="block text-sm mb-1">Quantity</label>
                                <input type="number" name="QTY" class="w-full rounded border dark:bg-gray-900" required min="0" />
                            </div>
                            <div>
                                <label class="block text-sm mb-1">Status</label>
                                <select name="status" class="w-full rounded border dark:bg-gray-900">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <x-primary-button>Create</x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>


