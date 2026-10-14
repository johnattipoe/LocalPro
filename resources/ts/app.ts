const roleSelect = document.querySelector<HTMLSelectElement>('[data-account-role]');
const providerFields = document.querySelector<HTMLElement>('[data-provider-fields]');
const googleSignupLink = document.querySelector<HTMLAnchorElement>('[data-google-signin]');

function syncRegistrationFields(): void {
    if (roleSelect && providerFields) {
        providerFields.hidden = roleSelect.value !== 'provider';
    }

    if (roleSelect && googleSignupLink) {
        const googleUrl = new URL(googleSignupLink.href);
        googleUrl.searchParams.set('role', roleSelect.value);
        googleSignupLink.href = googleUrl.toString();
    }
}

roleSelect?.addEventListener('change', syncRegistrationFields);
syncRegistrationFields();

import './booking';
import './dashboard';
import './messaging';
import './search';

const pageLoader = document.createElement('div');
pageLoader.className = 'page-loader';
pageLoader.setAttribute('role', 'status');
pageLoader.setAttribute('aria-live', 'polite');
pageLoader.innerHTML = '<span class="page-loader-mark" aria-hidden="true">&#10003;</span><strong>LocalPro</strong><span class="page-loader-spinner" aria-hidden="true"></span><span class="page-loader-label">Getting things ready...</span>';
document.body.append(pageLoader);

let loaderHidden = false;
function hidePageLoader(): void {
    if (loaderHidden) return;
    loaderHidden = true;
    window.setTimeout(() => pageLoader.classList.add('is-hidden'), 180);
}

if (document.readyState === 'complete') {
    hidePageLoader();
} else {
    window.addEventListener('load', hidePageLoader, { once: true });
    window.setTimeout(hidePageLoader, 8000);
}

const backToTop = document.createElement('button');
backToTop.className = 'back-to-top';
backToTop.type = 'button';
backToTop.setAttribute('aria-label', 'Back to top');
backToTop.title = 'Back to top';
backToTop.innerHTML = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 19V5M6 11l6-6 6 6" /></svg>';
backToTop.hidden = true;
document.body.append(backToTop);

function updateBackToTop(): void {
    backToTop.hidden = window.scrollY < 240;
}

window.addEventListener('scroll', updateBackToTop, { passive: true });
updateBackToTop();
backToTop.addEventListener('click', () => {
    window.scrollTo({
        top: 0,
        behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth',
    });
});
