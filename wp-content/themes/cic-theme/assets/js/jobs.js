document.addEventListener('DOMContentLoaded', () => {
    const page = document.querySelector('.jobs-page');
    if (!page) return;
    const filters = page.querySelector('.job-filters');
    if (!filters) return;
    const search = filters.querySelector('#job-search');
    const category = filters.querySelector('#job-category');
    const status = filters.querySelector('#job-status');
    const cards = [...page.querySelectorAll('.job-card')];
    filters.hidden = false;
    function update() {
        const term = search.value.trim().toLocaleLowerCase();
        let shown = 0;
        cards.forEach(card => {
            card.hidden = !(category.value === 'all' || category.value === card.dataset.category)
                || !(status.value === 'all' || status.value === card.dataset.status)
                || !card.textContent.toLocaleLowerCase().includes(term);
            if (!card.hidden) shown++;
        });
        page.querySelector('.job-result-count').textContent = `${shown} ${shown === 1 ? 'listing' : 'listings'}`;
        page.querySelector('.job-no-results').hidden = shown !== 0;
    }
    filters.addEventListener('input', update);
    filters.addEventListener('change', update);
    update();
});
