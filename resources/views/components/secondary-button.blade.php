<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border rounded-md font-semibold text-xs uppercase tracking-widest shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 dark:text-gray-300 dark:border-gray-500 dark:hover:bg-gray-700']) }} style="border-color: #2B9D8D; color: #2B9D8D;" onmouseover="this.style.backgroundColor='#FED2B3'" onmouseout="this.style.backgroundColor='white'">
    {{ $slot }}
</button>
