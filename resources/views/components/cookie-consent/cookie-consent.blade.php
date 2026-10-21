    <section class="cookie-consent" data-cookie-consent aria-label="{{ __('ui.cookie_settings') }}">
    <div class="cookie-consent-card" data-cookie-banner hidden role="region" aria-labelledby="cookie-consent-title" aria-describedby="cookie-consent-description">
        <span class="cookie-consent-icon" aria-hidden="true"><i data-lucide="cookie"></i></span>
        <div class="cookie-consent-copy">
            <h2 id="cookie-consent-title">{{ __('ui.privacy_choices') }}</h2>
            <p id="cookie-consent-description">{{ __('ui.cookie_description') }} <a href="{{ route('privacy') }}">{{ __('ui.read_privacy') }}</a>.</p>
        </div>
        <div class="cookie-consent-actions">
            <button type="button" class="cookie-button cookie-button-quiet" data-consent-reject>{{ __('ui.reject_optional') }}</button>
            <button type="button" class="cookie-button cookie-button-settings" data-consent-settings>{{ __('ui.settings') }}</button>
            <button type="button" class="cookie-button cookie-button-primary" data-consent-accept>{{ __('ui.accept_all') }}</button>
        </div>
    </div>

    <dialog class="cookie-settings-dialog" data-cookie-settings aria-labelledby="cookie-settings-title">
        <form method="dialog" class="cookie-settings-card">
            <button class="cookie-settings-close" value="cancel" aria-label="{{ __('ui.close_settings') }}"><i data-lucide="x" aria-hidden="true"></i></button>
            <span class="cookie-settings-kicker">{{ __('ui.localpro_privacy') }}</span>
            <h2 id="cookie-settings-title">{{ __('ui.cookie_settings') }}</h2>
            <p class="cookie-settings-intro">{{ __('ui.choose_optional_cookies') }}</p>

            <div class="cookie-category cookie-category-locked">
                <div><strong>{{ __('ui.essential_cookies') }}</strong><small>{{ __('ui.essential_cookie_reason') }}</small></div>
                <span class="cookie-required">{{ __('ui.always_on') }}</span>
            </div>
            <label class="cookie-category" for="cookie-analytics">
                <span><strong>{{ __('ui.analytics_cookies') }}</strong><small>{{ __('ui.analytics_reason') }}</small></span>
                <input id="cookie-analytics" type="checkbox" data-consent-category="analytics">
            </label>
            <label class="cookie-category" for="cookie-marketing">
                <span><strong>{{ __('ui.marketing_cookies') }}</strong><small>{{ __('ui.marketing_reason') }}</small></span>
                <input id="cookie-marketing" type="checkbox" data-consent-category="marketing">
            </label>
            <p class="cookie-no-trackers">{{ __('ui.no_trackers') }}</p>
            <div class="cookie-settings-actions">
                <button type="button" class="cookie-button cookie-button-quiet" data-consent-reject>{{ __('ui.reject_optional') }}</button>
                <button type="button" class="cookie-button cookie-button-primary" data-consent-save>{{ __('ui.save_choices') }}</button>
            </div>
        </form>
    </dialog>
</section>
