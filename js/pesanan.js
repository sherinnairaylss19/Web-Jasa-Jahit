const searchInput = document.querySelector('.search-box input');
const tableRows = document.querySelectorAll('tbody tr');

searchInput.addEventListener('keyup', function(e) {
    const text = e.target.value.toLowerCase();
    
    tableRows.forEach(row => {
        const nama = row.cells[1].textContent.toLowerCase();
        if (nama.includes(text)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});