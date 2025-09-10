<x-admin-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 space-y-2">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Account Profile</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300"><strong>Name:</strong> {{ $user->full_name ?? ($user->firstName.' '.$user->lastName) }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-300"><strong>Email:</strong> {{ $user->email }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-300"><strong>Role:</strong> {{ $user->role }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-300"><strong>Status:</strong> {{ ucfirst($user->status) }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-300"><strong>Points:</strong> {{ $user->points }}</p>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>


