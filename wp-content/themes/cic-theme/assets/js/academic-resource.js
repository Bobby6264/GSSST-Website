document.addEventListener('DOMContentLoaded', () => {
    const page = document.querySelector('.academic-resource-page');
    if (!page) return;
    const search = page.querySelector('.academic-resource-search');
    const input = page.querySelector('#academic-resource-query');
    const result = page.querySelector('.academic-resource-results');
    const rows = [...page.querySelectorAll('.entry-content table tbody tr')];
    if (!rows.length) return;
    search.hidden = false;
    input.addEventListener('input', () => {
        const term = input.value.trim().toLocaleLowerCase();
        let visible = 0;
        rows.forEach(row => {
            row.hidden = !row.textContent.toLocaleLowerCase().includes(term);
            if (!row.hidden) visible++;
        });
        result.textContent = term ? `${visible} of ${rows.length} rows match your search.` : '';
    });
});
