<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-primary hover:bg-primary-hover text-white text-sm font-semibold rounded-lg shadow-lg shadow-primary/20 hover:shadow-primary/30 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-background-dark']) }}>
    {{ $slot }}
</button>
