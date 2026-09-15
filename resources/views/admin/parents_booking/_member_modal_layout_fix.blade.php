<style>
/*
 * Add Members page modal layout.
 * Use an explicit header/body/footer grid so action buttons can never be
 * pushed outside the viewport by the long member form.
 */
.member-modal,
#importModal {
    --accent: #611eb2;
    --accent-dark: #4d168f;
    --soft: #f4edfc;
}

body .member-modal .modal-dialog {
    display: flex !important;
    width: calc(100% - 32px) !important;
    max-width: 920px !important;
    height: min(760px, calc(100dvh - 32px)) !important;
    max-height: calc(100dvh - 32px) !important;
    margin: 16px auto !important;
}

body .member-modal .modal-content {
    display: block !important;
    width: 100% !important;
    height: 100% !important;
    min-height: 0 !important;
    max-height: none !important;
    overflow: hidden !important;
    border: 0 !important;
    border-radius: 22px !important;
}

body .member-modal .modal-content > form {
    display: grid !important;
    grid-template-rows: auto minmax(0, 1fr) auto !important;
    width: 100% !important;
    height: 100% !important;
    min-height: 0 !important;
    max-height: none !important;
    overflow: hidden !important;
}

body .member-modal .modal-header {
    grid-row: 1 !important;
    flex: none !important;
    min-height: 68px;
    padding: 18px 24px !important;
}

body .member-modal .modal-body {
    grid-row: 2 !important;
    min-height: 0 !important;
    max-height: none !important;
    overflow-x: hidden !important;
    overflow-y: auto !important;
    padding: 22px 24px !important;
}

body .member-modal .modal-footer {
    grid-row: 3 !important;
    display: flex !important;
    flex: none !important;
    align-items: center !important;
    justify-content: flex-end !important;
    gap: 10px !important;
    width: 100% !important;
    min-height: 72px !important;
    padding: 14px 24px !important;
    margin: 0 !important;
    overflow: visible !important;
    background: #fff !important;
    border-top: 1px solid #e5e7eb !important;
    box-shadow: 0 -8px 22px rgba(23, 16, 33, .05) !important;
    position: relative !important;
    z-index: 20 !important;
}

body .member-modal .modal-footer .btn {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-width: 120px !important;
    min-height: 44px !important;
    margin: 0 !important;
    visibility: visible !important;
    opacity: 1 !important;
}

body .member-modal .modal-footer .accent-btn,
#importModal .modal-footer .accent-btn {
    background: #611eb2 !important;
    border: 1px solid #611eb2 !important;
    color: #fff !important;
    font-weight: 800 !important;
    border-radius: 999px !important;
    box-shadow: 0 8px 20px rgba(97, 30, 178, .18) !important;
}

body .member-modal .modal-footer .accent-btn:hover,
body .member-modal .modal-footer .accent-btn:focus,
#importModal .modal-footer .accent-btn:hover,
#importModal .modal-footer .accent-btn:focus {
    background: #4d168f !important;
    border-color: #4d168f !important;
    color: #fff !important;
}

/* Import Members is shorter, but give it the same guaranteed footer behavior. */
#importModal .modal-dialog {
    width: calc(100% - 32px) !important;
    max-width: 560px !important;
    margin: 16px auto !important;
}

#importModal .modal-content {
    display: block !important;
    width: 100% !important;
    max-height: calc(100dvh - 32px) !important;
    overflow: hidden !important;
}

#importModal .modal-content > form {
    display: grid !important;
    grid-template-rows: auto minmax(0, auto) auto !important;
    width: 100% !important;
    max-height: calc(100dvh - 32px) !important;
    overflow: hidden !important;
}

#importModal .modal-body {
    min-height: 0 !important;
    max-height: calc(100dvh - 190px) !important;
    overflow-y: auto !important;
}

#importModal .modal-footer {
    display: flex !important;
    align-items: center !important;
    justify-content: flex-end !important;
    gap: 10px !important;
    min-height: 68px !important;
    padding: 14px 20px !important;
    margin: 0 !important;
    overflow: visible !important;
    background: #fff !important;
    position: relative !important;
    z-index: 20 !important;
}

#importModal .modal-footer .btn {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-height: 42px !important;
    margin: 0 !important;
    visibility: visible !important;
    opacity: 1 !important;
}

@media (max-width: 575.98px) {
    body .member-modal .modal-dialog,
    #importModal .modal-dialog {
        width: calc(100% - 16px) !important;
        height: calc(100dvh - 16px) !important;
        max-height: calc(100dvh - 16px) !important;
        margin: 8px auto !important;
    }

    body .member-modal .modal-footer,
    #importModal .modal-footer {
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        padding: 12px 14px !important;
    }

    body .member-modal .modal-footer .btn,
    #importModal .modal-footer .btn {
        width: 100% !important;
        min-width: 0 !important;
    }
}
</style>
