import { Heart, createIcons } from 'lucide';

const favoriteToggles = Array.from(document.querySelectorAll<HTMLButtonElement>('[data-favorite-btn]'));

favoriteToggles.forEach((button) => {
    button.addEventListener('click', async (event) => {
        event.preventDefault();
        const form = button.closest<HTMLFormElement>('[data-favorite-toggle]');
        if (!form) return;

        const csrfToken = document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content ?? '';
        const isFavorited = button.classList.contains('is-favorited');

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: new FormData(form),
            });

            if (!response.ok) {
                throw new Error('Request failed');
            }

            const data = await response.json() as { favorited: boolean };
            button.classList.toggle('is-favorited', data.favorited);
            const icon = button.querySelector<HTMLElement>('[data-lucide]');
            if (icon) icon.dataset.lucide = data.favorited ? 'heart' : 'heart';
            const label = button.querySelector('span');
            if (label) label.textContent = data.favorited ? 'Saved' : 'Save';
            button.setAttribute('aria-label', data.favorited ? 'Remove from saved' : 'Save to favorites');
            createIcons({ icons: { Heart }, attrs: { 'stroke-width': 1.8 }, root: button });
        } catch (error) {
            console.error('Favorite toggle failed:', error);
        }
    });
});

document.querySelector('[data-use-my-location]')?.addEventListener('click', () => {
    if (!navigator.geolocation) {
        console.warn('Geolocation is not available in this browser.');
        return;
    }
    navigator.geolocation.getCurrentPosition(
        ({ coords }) => {
            const latInput = document.querySelector<HTMLInputElement>('input[name="lat"]');
            const lngInput = document.querySelector<HTMLInputElement>('input[name="lng"]');
            if (latInput) latInput.value = coords.latitude.toFixed(7);
            if (lngInput) lngInput.value = coords.longitude.toFixed(7);
            const radiusInput = document.querySelector<HTMLInputElement>('input[name="radius"]');
            if (radiusInput && !radiusInput.value) radiusInput.value = '20';
            latInput?.dispatchEvent(new Event('change'));
        },
        (error) => {
            console.error('Geolocation error:', error.message);
        },
        { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 },
    );
});