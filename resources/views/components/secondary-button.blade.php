<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-neutral-medium dark:border-gray-600 rounded-md font-semibold text-xs text-neutral-dark dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-neutral-light dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
