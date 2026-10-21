import * as AOS from 'aos';
import 'aos/dist/aos.css';
import {
    ArrowRight,
    ArrowLeft,
    ArrowUp,
    Bell,
    Bolt,
    BriefcaseBusiness,
    Brush,
    Camera,
    CalendarDays,
    ChartNoAxesColumnIncreasing,
    ChartNoAxesCombined,
    Check,
    ChevronDown,
    CircleDollarSign,
    CircleAlert,
    Cookie,
    Compass,
    Database,
    ClipboardCheck,
    ClipboardList,
    CircleHelp,
    Clock3,
    CreditCard,
    Droplets,
    Flag,
    FileCheck,
    FileText,
    Eye,
    EyeOff,
    Handshake,
    Hammer,
    History,
    Home,
    LifeBuoy,
    LockKeyhole,
    LogIn,
    LogOut,
    Mail,
    Map,
    MapPin,
    MapPinned,
    Menu,
    PanelLeftClose,
    MessageCircle,
    Search,
    Share2,
    Settings,
    Shirt,
    ShieldCheck,
    Store,
    Smartphone,
    Snowflake,
    Sparkles,
    Scissors,
    Star,
    Tag,
    Truck,
    UserRound,
    UsersRound,
    Wallet,
    Workflow,
    Info,
    Wrench,
    X,
    createIcons,
} from 'lucide';

const roleSelect = document.querySelector<HTMLSelectElement>('[data-account-role]');
const providerFields = document.querySelector<HTMLElement>('[data-provider-fields]');
const googleSignupLink = document.querySelector<HTMLAnchorElement>('[data-google-signin]');
const pricingType = document.querySelector<HTMLSelectElement>('[data-pricing-type]');
const servicePrice = document.querySelector<HTMLInputElement>('[data-service-price]');
const successToast = document.querySelector<HTMLElement>('[data-success-toast]');
const successToastClose = document.querySelector<HTMLButtonElement>('[data-success-toast-close]');

// Keep the signed-in account menus convenient for pointer, touch, and keyboard users.
const accountMenus = Array.from(document.querySelectorAll<HTMLDetailsElement>('.cp-account-menu, .pp-account-menu, .public-account-menu, .admin-account-menu'));
accountMenus.forEach((menu) => {
    menu.addEventListener('toggle', () => {
        if (!menu.open) return;
        accountMenus.forEach((otherMenu) => {
            if (otherMenu !== menu) otherMenu.open = false;
        });
    });
});
document.addEventListener('pointerdown', (event) => {
    const target = event.target;
    if (!(target instanceof Node)) return;
    accountMenus.forEach((menu) => {
        if (menu.open && !menu.contains(target)) menu.open = false;
    });
});
document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') return;
    const openMenu = accountMenus.find((menu) => menu.open);
    if (!openMenu) return;
    openMenu.open = false;
    openMenu.querySelector<HTMLElement>('summary')?.focus();
});

function dismissSuccessToast(): void {
    if (!successToast) return;
    successToast.classList.add('is-dismissed');
    window.setTimeout(() => successToast.remove(), 250);
}

successToastClose?.addEventListener('click', dismissSuccessToast);
if (successToast) window.setTimeout(dismissSuccessToast, 9000);

const geolocateProviderButton = document.querySelector<HTMLButtonElement>('[data-geolocate-provider]');
const providerLatitudeInput = document.querySelector<HTMLInputElement>('[data-provider-latitude]');
const providerLongitudeInput = document.querySelector<HTMLInputElement>('[data-provider-longitude]');
const geolocateProviderStatus = document.querySelector<HTMLElement>('[data-geolocate-status]');
geolocateProviderButton?.addEventListener('click', () => {
    if (!navigator.geolocation) {
        if (geolocateProviderStatus) geolocateProviderStatus.textContent = 'Location is unavailable in this browser. You can enter the coordinates manually.';
        return;
    }

    geolocateProviderButton.disabled = true;
    if (geolocateProviderStatus) geolocateProviderStatus.textContent = 'Finding your location...';
    navigator.geolocation.getCurrentPosition(
        ({ coords }) => {
            if (providerLatitudeInput) providerLatitudeInput.value = coords.latitude.toFixed(7);
            if (providerLongitudeInput) providerLongitudeInput.value = coords.longitude.toFixed(7);
            if (geolocateProviderStatus) geolocateProviderStatus.textContent = 'Map pin added. Save your profile to publish this location.';
            geolocateProviderButton.disabled = false;
        },
        (error) => {
            if (geolocateProviderStatus) {
                geolocateProviderStatus.textContent = error.code === error.PERMISSION_DENIED
                    ? 'Location permission was denied. Allow location access or enter coordinates manually.'
                    : 'Could not find your location. Try again or enter coordinates manually.';
            }
            geolocateProviderButton.disabled = false;
        },
        { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 },
    );
});

const sidebarToggles = Array.from(document.querySelectorAll<HTMLButtonElement>('[data-sidebar-toggle]'));
sidebarToggles.forEach((button) => {
    const sidebarId = button.getAttribute('aria-controls');
    const sidebar = sidebarId ? document.getElementById(sidebarId) : null;
    if (!sidebar) return;

    const isDrawer = button.dataset.sidebarMode === 'drawer';
    const mobileDrawer = window.matchMedia('(max-width: 760px)');
    const setOpen = (open: boolean) => {
        if (isDrawer && mobileDrawer.matches) {
            sidebar.classList.toggle('is-open', open);
            sidebar.classList.remove('is-collapsed');
        } else {
            sidebar.classList.remove('is-open');
            sidebar.classList.toggle('is-collapsed', !open);
            if (!isDrawer) sidebar.parentElement?.classList.toggle('sidebar-collapsed', !open);
        }
        button.setAttribute('aria-expanded', String(open));
        const sidebarName = isDrawer ? 'admin' : sidebarId === 'customerSidebar' ? 'customer' : 'provider';
        button.setAttribute('aria-label', isDrawer
            ? `${open ? 'Close' : 'Open'} ${sidebarName} sidebar`
            : `${open ? 'Collapse' : 'Expand'} ${sidebarName} sidebar`);
        const icon = button.querySelector<HTMLElement>('[data-lucide]');
        if (icon) icon.dataset.lucide = open && !isDrawer ? 'panel-left-close' : 'menu';
        createIcons({ icons: { Menu, PanelLeftClose }, attrs: { 'stroke-width': 1.8 }, root: button });
    };

    if (isDrawer) {
        setOpen(!mobileDrawer.matches);
        mobileDrawer.addEventListener('change', (event) => setOpen(!event.matches));
    } else {
        setOpen(true);
    }

    button.addEventListener('click', () => setOpen(button.getAttribute('aria-expanded') !== 'true'));
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && isDrawer && sidebar.classList.contains('is-open')) {
            setOpen(false);
            button.focus();
        }
    });
});

type CookiePreferences = {
    version: 1;
    necessary: true;
    analytics: boolean;
    marketing: boolean;
    updatedAt: string;
};

const cookieConsent = document.querySelector<HTMLElement>('[data-cookie-consent]');
if (cookieConsent) {
    const cookieBanner = cookieConsent.querySelector<HTMLElement>('[data-cookie-banner]');
    const cookieDialog = cookieConsent.querySelector<HTMLDialogElement>('[data-cookie-settings]');
    const categoryInputs = Array.from(cookieConsent.querySelectorAll<HTMLInputElement>('[data-consent-category]'));
    const consentCookieName = 'localpro_cookie_consent';

    function readCookiePreferences(): CookiePreferences | null {
        const entry = document.cookie.split('; ').find((cookie) => cookie.startsWith(`${consentCookieName}=`));
        if (!entry) return null;
        try {
            const parsed = JSON.parse(decodeURIComponent(entry.slice(consentCookieName.length + 1))) as Partial<CookiePreferences>;
            if (parsed.version !== 1 || parsed.necessary !== true) return null;
            return {
                version: 1,
                necessary: true,
                analytics: parsed.analytics === true,
                marketing: parsed.marketing === true,
                updatedAt: typeof parsed.updatedAt === 'string' ? parsed.updatedAt : '',
            };
        } catch {
            return null;
        }
    }

    function publishCookiePreferences(preferences: CookiePreferences): void {
        window.dispatchEvent(new CustomEvent<CookiePreferences>('localpro:cookie-consent', { detail: preferences }));
    }

    function saveCookiePreferences(analytics: boolean, marketing: boolean): void {
        const preferences: CookiePreferences = {
            version: 1,
            necessary: true,
            analytics,
            marketing,
            updatedAt: new Date().toISOString(),
        };
        const secure = window.location.protocol === 'https:' ? '; Secure' : '';
        document.cookie = `${consentCookieName}=${encodeURIComponent(JSON.stringify(preferences))}; Path=/; Max-Age=15552000; SameSite=Lax${secure}`;
        cookieBanner?.setAttribute('hidden', '');
        if (cookieDialog?.open) cookieDialog.close();
        publishCookiePreferences(preferences);
    }

    function openCookieSettings(): void {
        const current = readCookiePreferences();
        categoryInputs.forEach((input) => {
            input.checked = current?.[input.dataset.consentCategory as 'analytics' | 'marketing'] ?? false;
        });
        if (cookieDialog && !cookieDialog.open) cookieDialog.showModal();
    }

    const savedPreferences = readCookiePreferences();
    if (savedPreferences) {
        if (cookieBanner) cookieBanner.hidden = true;
        publishCookiePreferences(savedPreferences);
    } else if (cookieBanner) {
        cookieBanner.hidden = false;
    }

    cookieConsent.querySelectorAll<HTMLButtonElement>('[data-consent-accept]').forEach((button) => {
        button.addEventListener('click', () => saveCookiePreferences(true, true));
    });
    cookieConsent.querySelectorAll<HTMLButtonElement>('[data-consent-reject]').forEach((button) => {
        button.addEventListener('click', () => saveCookiePreferences(false, false));
    });
    cookieConsent.querySelectorAll<HTMLButtonElement>('[data-consent-settings]').forEach((button) => {
        button.addEventListener('click', openCookieSettings);
    });
    cookieConsent.querySelectorAll<HTMLButtonElement>('[data-consent-save]').forEach((button) => {
        button.addEventListener('click', () => {
            const analytics = cookieConsent.querySelector<HTMLInputElement>('[data-consent-category="analytics"]')?.checked ?? false;
            const marketing = cookieConsent.querySelector<HTMLInputElement>('[data-consent-category="marketing"]')?.checked ?? false;
            saveCookiePreferences(analytics, marketing);
        });
    });
    document.querySelectorAll<HTMLButtonElement>('[data-open-cookie-settings]').forEach((button) => {
        button.addEventListener('click', openCookieSettings);
    });
}

document.querySelectorAll<HTMLButtonElement>('[data-password-toggle]').forEach((toggle) => {
    toggle.addEventListener('click', () => {
        const inputId = toggle.dataset.passwordToggle;
        const input = inputId ? document.getElementById(inputId) : null;
        if (!(input instanceof HTMLInputElement)) return;

        const showPassword = input.type === 'password';
        input.type = showPassword ? 'text' : 'password';
        toggle.setAttribute('aria-pressed', String(showPassword));
        toggle.setAttribute('aria-label', `${showPassword ? 'Hide' : 'Show'} ${inputId === 'password' ? 'new' : 'confirmed'} password`);
        const icon = toggle.querySelector<HTMLElement>('[data-lucide]');
        if (icon) icon.dataset.lucide = showPassword ? 'eye-off' : 'eye';
        createIcons({ icons: { Eye, EyeOff }, attrs: { 'stroke-width': 1.8 } });
    });
});

const pageRoots = document.querySelectorAll<HTMLElement>('main, .site-footer');
let animationTargetCount = 0;

pageRoots.forEach((pageRoot) => {
    const animatedElements = pageRoot.querySelectorAll<HTMLElement>([
        ':scope > div',
        ':scope > form',
        ':scope > section',
        ':scope > article',
        ':scope > .requests-heading',
        '.cp-page-heading',
        '.pp-page-heading',
        '.directory-category-intro',
        '.directory-filters',
        '.directory-results-summary',
        '.directory-empty',
        '.site-footer-intro',
        '.site-footer-column',
        '.site-footer-promise',
        '.site-footer-bottom',
        '.home-page .hero-copy > *',
        '.home-page .hero-art',
        '.home-page .hero-art .float-tag',
        '.home-page .trust-row > span',
        '.home-page .section-heading',
        '.home-page .provider-empty',
        '.home-page .join-section > *',
        '.home-page .home-faq .faq-list > details',
        '.legal-toc > *',
        '.legal-summary',
        '.legal-card > h2',
        '.legal-list > li',
        '.auth-form-heading',
        '.auth-signup-prompt',
        '.category-grid > *',
        '.provider-grid > *',
        '.how-steps > article',
        '.steps > article',
        '.faq-list > details',
        '.public-service-grid > *',
        '.public-review',
        '.about-values-grid > *',
        '.about-audience-grid > *',
        '.about-principles-list > *',
        '.about-faq-list > details',
        '.contact-followup-grid > *',
        '.how-audience-grid > *',
        '.how-safety-grid > *',
        '.cp-booking-detail-grid > *',
        '.cp-dashboard-grid > *',
        '.cp-center > *',
        '.cp-right-rail > *',
        '.cp-metric-grid > *',
        '.cp-category-grid > *',
        '.cp-attention-item',
        '.cp-trust-strip > *',
        '.cp-record-row',
        '.cp-review-row',
        '.cp-form-panel',
        '.cp-settings-grid > *',
        '.cp-panel',
        '.pp-booking-detail-grid > *',
        '.pp-dashboard-grid > *',
        '.pp-center > *',
        '.pp-right-rail > *',
        '.pp-metric-grid > *',
        '.pp-managed-service',
        '.pp-settings-panel',
        '.pp-earnings-summary > *',
        '.pp-panel',
        '.admin-metric-card',
        '.admin-panel',
        '.admin-content',
        '.admin-page-content > *',
        '.admin-analytics-metrics > article',
        '.admin-analytics-panels > section',
        '.admin-settings-card',
        '.admin-user-management .admin-provider-card',
        '.auth-welcome-panel',
        '.auth-form-panel',
        '.request-form-card',
        '.request-detail-card',
        '.message-panel',
        '.profile-card',
        '.public-profile-section',
    ].join(','));

    animatedElements.forEach((element, index) => {
        if (element.hasAttribute('data-aos')) return;
        element.dataset.aos = 'fade-up';
        element.dataset.aosDuration = '550';
        element.dataset.aosDelay = String((index % 4) * 70);
    });

    animationTargetCount += animatedElements.length;
});

if (animationTargetCount > 0) {
    AOS.init({
        duration: 550,
        easing: 'ease-out-cubic',
        offset: 48,
        once: true,
        disable: () => window.matchMedia('(prefers-reduced-motion: reduce)').matches,
    });
}

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

function syncServicePriceRequirement(): void {
    if (!pricingType || !servicePrice) return;
    const requiresPrice = pricingType.value === 'fixed' || pricingType.value === 'hourly';
    servicePrice.required = requiresPrice;
    servicePrice.setAttribute('aria-required', String(requiresPrice));
}

pricingType?.addEventListener('change', syncServicePriceRequirement);
syncServicePriceRequirement();

import '../booking/booking';
import '../dashboard/dashboard';
import '../messaging/messaging';
import '../search/search';

const pageLoader = document.createElement('div');
pageLoader.className = 'page-loader';
pageLoader.setAttribute('role', 'status');
pageLoader.setAttribute('aria-live', 'polite');
const pageLoaderLogo = document.createElement('img');
pageLoaderLogo.className = 'page-loader-mark';
pageLoaderLogo.src = document.querySelector<HTMLLinkElement>('link[rel="icon"]')?.href ?? '/logo/localpro-mark.png';
pageLoaderLogo.alt = '';
pageLoaderLogo.setAttribute('aria-hidden', 'true');
pageLoader.innerHTML = '<strong>LocalPro</strong><span class="page-loader-spinner" aria-hidden="true"></span><span class="page-loader-label">Getting things ready...</span>';
pageLoader.prepend(pageLoaderLogo);
document.body.append(pageLoader);

let loaderHidden = false;
function hidePageLoader(): void {
    if (loaderHidden) return;
    loaderHidden = true;
    window.setTimeout(() => pageLoader.classList.add('is-hidden'), 180);
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', hidePageLoader, { once: true });
} else {
    window.requestAnimationFrame(hidePageLoader);
}
window.setTimeout(hidePageLoader, 1800);

const backToTop = document.createElement('button');
backToTop.className = 'back-to-top';
backToTop.type = 'button';
backToTop.setAttribute('aria-label', 'Back to top');
backToTop.title = 'Back to top';
backToTop.innerHTML = '<i data-lucide="arrow-up" aria-hidden="true"></i>';
backToTop.hidden = true;
document.body.append(backToTop);

const pageIconBySegment: Record<string, string> = {
    about: 'circle-help',
    analytics: 'chart-no-axes-combined',
    availability: 'clock-3',
    bookings: 'calendar-days',
    categories: 'tag',
    contact: 'life-buoy',
    dashboard: 'home',
    earnings: 'circle-dollar-sign',
    'forgot-password': 'lock-keyhole',
    history: 'history',
    'how-it-works': 'clipboard-check',
    login: 'lock-keyhole',
    messages: 'message-circle',
    notifications: 'bell',
    payments: 'credit-card',
    payouts: 'wallet',
    privacy: 'file-text',
    professionals: 'search',
    profile: 'user-round',
    providers: 'briefcase-business',
    register: 'user-round',
    reports: 'chart-no-axes-combined',
    requests: 'clipboard-list',
    'reset-password': 'lock-keyhole',
    reviews: 'star',
    search: 'search',
    services: 'wrench',
    settings: 'settings',
    support: 'life-buoy',
    terms: 'file-text',
    users: 'users-round',
    verification: 'shield-check',
};
const pagePathSegments = window.location.pathname.split('/').filter(Boolean).reverse();
const pageTitleIcon = pagePathSegments
    .map((segment) => pageIconBySegment[segment])
    .find((icon) => icon !== undefined);
const pageTitle = document.querySelector<HTMLElement>(
    'main h1, .auth-layout h1',
);

if (pageTitleIcon && pageTitle && !pageTitle.querySelector('svg, [data-lucide]')) {
    const titleIcon = document.createElement('i');
    titleIcon.dataset.lucide = pageTitleIcon;
    titleIcon.className = 'page-title-icon';
    titleIcon.setAttribute('aria-hidden', 'true');
    pageTitle.prepend(titleIcon, document.createTextNode(' '));
}

createIcons({
    icons: {
        ArrowLeft,
        ArrowRight,
        ArrowUp,
        Bell,
        Bolt,
        BriefcaseBusiness,
        Brush,
        Camera,
        CalendarDays,
        ChartNoAxesColumnIncreasing,
        ChartNoAxesCombined,
        Check,
        ChevronDown,
        CircleDollarSign,
        CircleAlert,
        Cookie,
        Compass,
        Database,
        ClipboardCheck,
        ClipboardList,
        CircleHelp,
        Clock3,
        CreditCard,
        Water: Droplets,
        Flag,
        FileCheck,
        FileText,
        Eye,
        EyeOff,
        Handshake,
        Hammer,
        History,
        Home,
        LifeBuoy,
        LockKeyhole,
        LogIn,
        LogOut,
        Mail,
        Map,
        MapPin,
        MapPinned,
        Menu,
        PanelLeftClose,
        MessageCircle,
        Search,
        Share2,
        Settings,
        Shirt,
        ShieldCheck,
        Store,
        Phone: Smartphone,
        Snowflake,
        Sparkles,
        Scissors,
        Needle: Shirt,
        Tool: Wrench,
        Star,
        Tag,
        Truck,
        UserRound,
        UsersRound,
        Wallet,
        Workflow,
        Info,
        Wrench,
        X,
    },
    attrs: {
        'stroke-width': 1.8,
    },
});

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
