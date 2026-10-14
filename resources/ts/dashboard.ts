const filters = document.querySelector<HTMLFormElement>('[data-admin-filter]');
filters?.querySelectorAll('select').forEach((select) => {
    select.addEventListener('change', () => filters.requestSubmit());
});

