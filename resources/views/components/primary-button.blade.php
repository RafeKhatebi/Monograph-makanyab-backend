<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-primary border border-transparent rounded-lg font-semibold text-sm text-white tracking-wide hover:bg-primary-dark focus:bg-primary-dark active:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition ease-in-out duration-200']) }}
    style="background: #10B981; color: #FFFFFF; border-radius: 8px; letter-spacing: 0.025em;">
    {{ $slot }}
</button>
