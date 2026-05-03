// CNIC Auto Format
const cnicInput = document.getElementById('cnic');
if (cnicInput) {
    cnicInput.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 5 && value.length <= 12) {
            value = value.slice(0, 5) + '-' + value.slice(5);
        } else if (value.length > 12) {
            value = value.slice(0, 5) + '-' + value.slice(5, 12) + '-' + value.slice(12, 13);
        }
        e.target.value = value;
    });
}

// Search and Filter Elections
const searchInput = document.getElementById('searchInput');
const filterStatus = document.getElementById('filterStatus');

function filterElections() {
    const search = searchInput ? searchInput.value.toLowerCase() : '';
    const status = filterStatus ? filterStatus.value : 'all';
    const cards = document.querySelectorAll('.election-card');

    cards.forEach(card => {
        const title = card.querySelector('h5').textContent.toLowerCase();
        const cardStatus = card.getAttribute('data-status');

        const matchSearch = title.includes(search);
        const matchStatus = status === 'all' || cardStatus === status;

        card.style.display = matchSearch && matchStatus ? 'block' : 'none';
    });
}

if (searchInput) searchInput.addEventListener('input', filterElections);
if (filterStatus) filterStatus.addEventListener('change', filterElections);

// Add/Remove Candidate Button
const addCandidate = document.getElementById('addCandidate');
if (addCandidate) {
    let count = 3;
    addCandidate.addEventListener('click', function () {
        const div = document.createElement('div');
        div.classList.add('mb-2', 'd-flex', 'gap-2');
        div.innerHTML = `
            <input type="text" name="candidates[]" class="form-control" placeholder="Candidate ${count}">
            <button type="button" class="btn btn-danger btn-sm remove-candidate">Remove</button>
        `;
        document.getElementById('candidateList').appendChild(div);
        count++;
    });

    document.getElementById('candidateList').addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-candidate')) {
            e.target.parentElement.remove();
        }
    });
}