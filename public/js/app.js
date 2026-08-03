'use strict';

document.addEventListener('DOMContentLoaded', function () {
    const firstInvalidField = document.querySelector('[aria-invalid="true"]');

    if (firstInvalidField) {
        firstInvalidField.focus();
    }
});
