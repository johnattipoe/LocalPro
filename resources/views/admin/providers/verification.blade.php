<section class="request-detail-card admin-verification-empty">
    <div class="verification-empty-icon" aria-hidden="true">✓</div>
    <div class="verification-empty-copy">
        <span class="eyebrow">VERIFICATION WORKSPACE</span>
        <h2>Provider identity checks</h2>
        <p>Review a professional’s profile and submitted identity documents before approving their account. The pending queue shows every profile waiting for your review.</p>
        <a class="button" href="{{ route('admin.providers.index',['status'=>'pending']) }}">Open pending verification queue <span aria-hidden="true">→</span></a>
    </div>
</section>
