
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

// ---------------------

// Search and Filter Elections
const searchInput = document.getElementById('searchInput');
const filterStatus = document.getElementById('filterStatus');

function filterElections() {
    const search = searchInput ? searchInput.value.toLowerCase() : '';
    const status = filterStatus ? filterStatus.value : 'all';
    const cards = document.querySelectorAll('.election-card');
    let visible = 0;

    cards.forEach(card => {
        const title = card.querySelector('h5').textContent.toLowerCase();
        const cardStatus = card.getAttribute('data-status');
        const matchSearch = title.includes(search);
        const matchStatus = status === 'all' || cardStatus === status;
        card.style.display = matchSearch && matchStatus ? 'block' : 'none';
        if (matchSearch && matchStatus) visible++;
    });

    const empty = document.getElementById('emptyState');
    if (empty) empty.style.display = visible === 0 ? 'block' : 'none';
}

if (searchInput) searchInput.addEventListener('input', filterElections);
if (filterStatus) filterStatus.addEventListener('change', filterElections);

// ---------------------

// Add/Remove Candidate Button
const addCandidate = document.getElementById('addCandidate');
if (addCandidate) {
    let count = 3;
    addCandidate.addEventListener('click', function() {
        const div = document.createElement('div');
        div.classList.add('mb-3', 'p-3', 'border', 'rounded', 'candidate-item');
        div.innerHTML = `
			<div class="row g-2">
				<div class="col-md-6">
					<label class="form-label">Candidate Name</label>
					<input type="text" name="candidates[]" class="form-control" placeholder="Candidate ${count}" required>
				</div>
				<div class="col-md-4">
					<label class="form-label">Photo</label>
					<input type="file" name="candidate_photos[]" class="form-control" accept="image/*" required>
				</div>
				<div class="col-md-2">
					<button type="button" class="btn btn-danger btn-sm remove-candidate w-100" style="margin-top: 30px; height: 42px;">Remove</button>
				</div>
			</div>
		`;
        document.getElementById('candidateList').appendChild(div);
        count++;
    });

    document.getElementById('candidateList').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-candidate')) {
            e.target.closest('.candidate-item').remove();
        }
    });
}

// ---------------------

// Candidate Card Highlight
document.querySelectorAll('.vote-card').forEach(card => {
    card.addEventListener('click', function () {
        document.querySelectorAll('.vote-card').forEach(c => c.classList.remove('selected'));
        this.classList.add('selected');
    });
});

// ---------------------

// Bootstrap Form Validation
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function (e) {
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        form.classList.add('was-validated');
    });
});

// ---------------------

// Password Checklist
const passwordInput = document.getElementById('password');
const checklist = document.getElementById('passwordChecklist');

if (passwordInput) {
    // Show on focus
    passwordInput.addEventListener('focus', function() {
        if (this.value.length > 0 || true) checklist.style.display = 'block';
    });

    // Hide on blur if all checks pass
    passwordInput.addEventListener('blur', function() {
        const val = this.value;
        const allPass = val.length >= 8 && /[A-Z]/.test(val) && /[a-z]/.test(val) && /[0-9]/.test(val) && /[\W]/.test(val);
        if (allPass) checklist.style.display = 'none';
    });

    passwordInput.addEventListener('input', function() {
        const val = this.value;

        function check(id, condition) {
            const el = document.getElementById(id);
            if (condition) {
                el.textContent = el.textContent.replace('✕', '✓');
                el.className = 'text-success';
            } else {
                el.textContent = el.textContent.replace('✓', '✕');
                el.className = 'text-danger';
            }
        }

        check('check-length', val.length >= 8);
        check('check-upper', /[A-Z]/.test(val));
        check('check-lower', /[a-z]/.test(val));
        check('check-number', /[0-9]/.test(val));
        check('check-special', /[\W]/.test(val));
    });
}

// Confirm Password Match
const confirmInput = document.getElementById('confirm_password');
const matchText = document.getElementById('passwordMatch');

if (confirmInput) {
    // Show only on focus
    confirmInput.addEventListener('focus', function() {
        matchText.style.display = 'block';
    });

    confirmInput.addEventListener('blur', function() {
        if (this.value === passwordInput.value) matchText.style.display = 'none';
    });

    confirmInput.addEventListener('input', function() {
        if (this.value === passwordInput.value) {
            matchText.textContent = '✓ Passwords match';
            matchText.style.color = '#198754';
        } else {
            matchText.textContent = '✕ Passwords do not match';
            matchText.style.color = '#dc3545';
        }
    });
}

// ---------------------

// Auto dismiss alerts after 5 seconds
document.querySelectorAll('.alert').forEach(alert => {
    setTimeout(() => {
        alert.style.transition = 'opacity 0.5s ease';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    }, 5000);
});

// ---------------------