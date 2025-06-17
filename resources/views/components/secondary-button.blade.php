<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white border border-neutral-medium rounded-md font-semibold text-xs text-neutral-dark uppercase tracking-widest shadow-sm hover:bg-neutral-light focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
