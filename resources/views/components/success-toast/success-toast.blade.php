@if(!empty($message))
    <aside class="success-toast" role="status" aria-live="polite" data-success-toast>
        <span class="success-toast-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24"><path d="m5 12 4 4L19 6"/></svg>
        </span>
        <span class="success-toast-copy">
            <strong>{{ $title ?? 'Success' }}</strong>
            <span>{{ $message }}</span>
        </span>
        <button class="success-toast-close" type="button" aria-label="Dismiss message" data-success-toast-close>
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
        </button>
    </aside>
@endif
