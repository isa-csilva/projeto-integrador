'use strict';

document.addEventListener('DOMContentLoaded', function () {
    const firstInvalidField = document.querySelector('[aria-invalid="true"]');

    if (firstInvalidField) {
        firstInvalidField.focus();
        return;
    }

    const formAlert = document.querySelector('.alert[role="alert"][tabindex="-1"]');

    if (formAlert) {
        formAlert.focus();
    }
});
