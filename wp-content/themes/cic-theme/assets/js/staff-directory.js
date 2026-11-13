document.addEventListener('DOMContentLoaded', () => {
    const directory = document.querySelector('.staff-directory');
    if (!directory) return;
    const controls = directory.querySelector('.staff-directory-controls');
    const search = directory.querySelector('#staff-search');
    const checkboxes = [...directory.querySelectorAll('.staff-directory-filters input')];
    const all = checkboxes.find(input => input.value === 'all');
    const cards = [...directory.querySelectorAll('[data-staff-category]')];
    const status = directory.querySelector('.staff-directory-results');
    const empty = directory.querySelector('.staff-directory-empty');
    const normalize = text => text.normalize('NFKC').toLocaleLowerCase().trim();
    function filter() {
        const selected = checkboxes.filter(input => input.checked).map(input => input.value);
        const query = normalize(search.value);
        let count = 0;
        cards.forEach(card => {
            card.hidden = !(selected.includes('all') || selected.includes(card.dataset.staffCategory))
                || !normalize(card.dataset.staffSearch).includes(query);
            if (!card.hidden) count++;
        });
        status.textContent = `${count} of ${cards.length} staff shown`;
        if (empty) empty.hidden = count !== 0;
    }
    checkboxes.forEach(input => input.addEventListener('change', () => {
        if (input === all && all.checked) checkboxes.forEach(other => { if (other !== all) other.checked = false; });
        else if (input !== all) all.checked = false;
        if (!checkboxes.some(other => other.checked)) all.checked = true;
        filter();
    }));
    search.addEventListener('input', filter);
    controls.hidden = false;
    filter();
});
