<x-admin-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @if ($errors->any())
                    <div class="m-6 rounded-md p-3 text-sm" style="background-color: rgba(43, 157, 141, 0.1); border-color: #2B9D8D; border-width: 1px; color: #2B9D8D;">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('admin.users.store') }}" class="p-6 space-y-4">
                    @csrf
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Create Account</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300">First name</label>
                            <input name="firstName" value="{{ old('firstName') }}" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" required />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300">Middle name</label>
                            <input name="middleName" value="{{ old('middleName') }}" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300">Last name</label>
                            <input name="lastName" value="{{ old('lastName') }}" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" required />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" required />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300">Password</label>
                            <input type="password" name="password" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" required />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300">Role</label>
                            <select name="role" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                <option value="user" @selected(old('role')==='user')>TAPtivist</option>
                                <option value="admin" @selected(old('role')==='admin')>TAPmin</option>
                            </select>
                        </div>
                    </div>
                    <div class="pt-4">
                        <button class="text-white px-4 py-2 rounded-md text-sm font-medium"
                                style="background-color: #F3A261;"
                                onmouseover="this.style.backgroundColor='#E8944F'"
                                onmouseout="this.style.backgroundColor='#F3A261'">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>


