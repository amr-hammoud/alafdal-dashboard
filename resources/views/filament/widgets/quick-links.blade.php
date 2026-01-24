<x-filament-widgets::widget>
    <x-filament::section>
        <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 1rem;">
            @foreach ($this->getLinks() as $link)
            <a
                href="{{ $link['url'] }}"
                style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 1.5rem; border-radius: 0.5rem; text-decoration: none;"
                class="border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors group">
                @svg($link['icon'], 'w-12 h-12 text-gray-400 group-hover:text-primary-500 transition-colors mb-3')
                <span class="text-sm font-medium text-gray-700 dark:text-gray-200 text-center">
                    {{ $link['label'] }}
                </span>
            </a>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>