function togglePrivacyPopup() {
    const savedState = localStorage.getItem('privacyPopupState');
    const close = document.querySelector('.policy-popup__close');
    const popup = document.querySelector('.policy-popup');
    if (!popup) return;
    if (savedState === 'true') {
        popup.style.display = 'none';
    } else {
        popup.style.display = '';
    }
    close.addEventListener('click', function () {
        popup.style.display = 'none';
        localStorage.setItem('privacyPopupState', 'true');
    });
}

togglePrivacyPopup();
