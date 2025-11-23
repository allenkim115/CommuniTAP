<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150']) }} style="background-color: #F3A261;" onmouseover="this.style.backgroundColor='#E8944F'" onmouseout="this.style.backgroundColor='#F3A261'">
    {{ $slot }}
</button>
