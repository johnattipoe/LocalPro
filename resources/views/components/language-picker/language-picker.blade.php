<div class="language-picker {{ $class ?? '' }}">
    <details class="language-picker-menu">
        <summary aria-label="{{ __('ui.language') }}" title="{{ __('ui.language') }}">
            <svg class="language-globe" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false">
                <circle cx="12" cy="12" r="9"></circle>
                <path d="M3 12h18M12 3a15 15 0 0 1 0 18M12 3a15 15 0 0 0 0 18"></path>
            </svg>
        </summary>
        <form method="POST" action="{{ route('language.update') }}" class="language-picker-options">
            @csrf
            @foreach(config('locales.locales') as $localeCode => $localeName)
                <button type="submit" name="locale" value="{{ $localeCode }}" @if(app()->getLocale() === $localeCode) aria-current="true" @endif>
                    <span>{{ $localeName }}</span>
                    @if(app()->getLocale() === $localeCode)<i data-lucide="check" aria-hidden="true"></i>@endif
                </button>
            @endforeach
        </form>
    </details>
</div>
