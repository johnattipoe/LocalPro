const bookingForms = document.querySelectorAll<HTMLFormElement>('[data-booking-form]');

bookingForms.forEach((form) => {
    form.addEventListener('submit', () => {
        const submit = form.querySelector<HTMLButtonElement>('button[type="submit"]');
        if (!submit) return;
        submit.disabled = true;
        submit.dataset.originalLabel = submit.textContent ?? '';
        submit.textContent = 'Sending…';
    });
});

