const searchForm = document.querySelector<HTMLFormElement>('[data-provider-search]');
searchForm?.addEventListener('reset', () => {
    window.setTimeout(() => searchForm.querySelector<HTMLInputElement>('input[name="q"]')?.focus(), 0);
});

