<x-admin-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="p-6 space-y-4">
                    @csrf
                    @method('PATCH')
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Update Account</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300">First name</label>
                            <input name="firstName" value="{{ old('firstName', $user->firstName) }}" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" required />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300">Middle name</label>
                            <input name="middleName" value="{{ old('middleName', $user->middleName) }}" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300">Last name</label>
                            <input name="lastName" value="{{ old('lastName', $user->lastName) }}" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" required />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" required />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300">Role</label>
                            <select name="role" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                <option value="user" @selected($user->role==='user')>TAPtivist</option>
                                <option value="admin" @selected($user->role==='admin')>TAPmin</option>
                            </select>
                        </div>
                    </div>
                    <div class="pt-4">
                        <button class="text-white px-4 py-2 rounded-md text-sm font-medium"
                                style="background-color: #F3A261;"
                                onmouseover="this.style.backgroundColor='#E8944F'"
                                onmouseout="this.style.backgroundColor='#F3A261'">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>


