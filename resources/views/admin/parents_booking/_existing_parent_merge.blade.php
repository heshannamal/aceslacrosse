<style>
    .member-page .email-note {
        display: none;
        margin-top: 8px;
        padding: 9px 11px;
        border-radius: 11px;
        font-size: 12px;
        line-height: 1.4;
        font-weight: 800;
    }

    .member-page .email-note.found {
        display: block;
        color: #92400e;
        background: #fff7ed;
        border: 1px solid #fdba74;
    }

    .member-page .email-note.selected {
        display: block;
        color: #166534;
        background: #ecfdf3;
        border: 1px solid #86efac;
    }

    .member-page .email-note.checking {
        display: block;
        color: #611eb2;
        background: #f4edfc;
        border: 1px solid #d7baf1;
    }
</style>

<script>
(function () {
    const checkParentUrl = @json(route('admin.parents.booking.parents.check-email'));
    const requestTokens = new WeakMap();

    function escapeHtml(value) {
        return String(value == null ? '' : value).replace(/[&<>"']/g, function (char) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[char];
        });
    }

    function fieldsFor(input) {
        const form = input.closest('form');
        const prefix = input.dataset.parentEmail;
        if (!form || !prefix) return null;

        return {
            form: form,
            prefix: prefix,
            id: form.querySelector('[name="' + prefix + '_id"]'),
            email: input,
            firstName: form.querySelector('[name="' + prefix + '_first_name"]'),
            lastName: form.querySelector('[name="' + prefix + '_last_name"]'),
            phone: form.querySelector('[name="' + prefix + '_phone"]'),
            note: form.querySelector('[data-email-note="' + prefix + '"]')
        };
    }

    function setNote(note, message, status) {
        if (!note) return;
        note.className = 'email-note ' + status;
        note.textContent = message;
    }

    function clearNote(note) {
        if (!note) return;
        note.className = 'email-note';
        note.textContent = '';
    }

    function clearSelectedParent(fields) {
        if (fields && fields.id) fields.id.value = '';
        if (fields) clearNote(fields.note);
    }

    function fillExistingParent(fields, parent) {
        if (fields.id) fields.id.value = parent.id || '';
        if (fields.firstName) fields.firstName.value = parent.first_name || '';
        if (fields.lastName) fields.lastName.value = parent.last_name || '';
        if (fields.email) fields.email.value = parent.email || '';
        if (fields.phone) fields.phone.value = parent.phone || '';

        const parentName = ((parent.first_name || '') + ' ' + (parent.last_name || '')).trim() || 'Existing parent';
        setNote(
            fields.note,
            'Existing parent selected: ' + parentName + ' (' + (parent.email || '') + '). This child will be linked to this parent.',
            'selected'
        );
    }

    async function checkExistingParent(input) {
        const fields = fieldsFor(input);
        if (!fields) return;

        const email = (input.value || '').trim();
        if (!email || !input.checkValidity()) {
            clearSelectedParent(fields);
            return;
        }

        const token = Date.now() + Math.random();
        requestTokens.set(input, token);
        setNote(fields.note, 'Checking for an existing parent...', 'checking');

        try {
            const response = await fetch(checkParentUrl + '?email=' + encodeURIComponent(email), {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();
            if (requestTokens.get(input) !== token) return;

            if (!response.ok) {
                throw new Error(data.message || 'Could not check this email.');
            }

            if (!data.exists || !data.customer) {
                if (fields.id) fields.id.value = '';
                setNote(fields.note, 'No existing parent found. A new parent record will be created.', 'found');
                return;
            }

            const parent = data.customer;
            const selectedId = fields.id ? String(fields.id.value || '') : '';

            // Editing an already-linked parent should not ask for confirmation again.
            if (selectedId && selectedId === String(parent.id)) {
                fillExistingParent(fields, parent);
                return;
            }

            const parentName = ((parent.first_name || '') + ' ' + (parent.last_name || '')).trim() || 'Existing parent';
            setNote(fields.note, 'Existing parent found: ' + parentName + ' (' + parent.email + '). Confirm to use this parent.', 'found');

            const result = await Swal.fire({
                icon: 'warning',
                title: 'Existing Parent Found',
                html:
                    '<div style="text-align:left">' +
                        '<p><strong>' + escapeHtml(parentName) + '</strong> already exists with this email.</p>' +
                        '<p><strong>Email:</strong> ' + escapeHtml(parent.email || '-') + '</p>' +
                        '<p><strong>Phone:</strong> ' + escapeHtml(parent.phone || '-') + '</p>' +
                        '<p class="mb-0">Do you want to link this child to the existing parent?</p>' +
                    '</div>',
                showCancelButton: true,
                confirmButtonText: 'Yes, Use Existing Parent',
                cancelButtonText: 'No, Cancel',
                confirmButtonColor: '#611eb2',
                cancelButtonColor: '#6b7280',
                reverseButtons: true,
                allowOutsideClick: false
            });

            if (requestTokens.get(input) !== token) return;

            if (result.isConfirmed) {
                fillExistingParent(fields, parent);

                await Swal.fire({
                    icon: 'success',
                    title: 'Parent Selected',
                    text: 'This child will be linked to the existing parent account.',
                    confirmButtonColor: '#611eb2',
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                if (fields.id) fields.id.value = '';
                input.value = '';
                clearNote(fields.note);

                await Swal.fire({
                    icon: 'info',
                    title: 'Existing Parent Not Selected',
                    text: 'The email was cleared. Enter a different parent email to continue.',
                    confirmButtonColor: '#611eb2',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        } catch (error) {
            if (requestTokens.get(input) !== token) return;
            if (fields.id) fields.id.value = '';
            clearNote(fields.note);

            Swal.fire({
                icon: 'error',
                title: 'Email Check Failed',
                text: error && error.message ? error.message : 'Could not check this email now. Please try again.',
                confirmButtonColor: '#611eb2'
            });
        }
    }

    // The original ACES page had a silent auto-select listener attached directly
    // to these inputs. Handling input in capture phase prevents that old handler
    // from silently merging a parent before the confirmation above is shown.
    document.addEventListener('input', function (event) {
        const input = event.target;
        if (!input || !input.matches('[data-parent-email]')) return;

        event.stopImmediatePropagation();
        const fields = fieldsFor(input);
        if (!fields) return;

        if (fields.id) fields.id.value = '';
        clearNote(fields.note);
        requestTokens.set(input, null);
    }, true);

    document.addEventListener('blur', function (event) {
        const input = event.target;
        if (!input || !input.matches('[data-parent-email]')) return;
        checkExistingParent(input);
    }, true);
})();
</script>
