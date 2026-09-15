<style>
    /*
     * Global ACES Admin modal layout.
     * Keep headers/action bars visible and make only the content area scroll.
     * This also supports the common ACES pattern where a <form> wraps
     * .modal-header, .modal-body and .modal-footer inside .modal-content.
     */
    .modal .modal-dialog {
        max-height: calc(100dvh - 24px);
        margin-top: 12px;
        margin-bottom: 12px;
    }

    .modal .modal-dialog-scrollable {
        height: auto !important;
        max-height: calc(100dvh - 24px) !important;
    }

    .modal .modal-content,
    .modal .ao-booking-modal-content,
    .modal .ao-manual-content {
        display: flex !important;
        flex-direction: column !important;
        min-height: 0 !important;
        max-height: calc(100dvh - 24px) !important;
        overflow: hidden !important;
    }

    .modal .modal-content > form,
    .modal .ao-booking-modal-content > form,
    .modal .ao-manual-content > form {
        display: flex !important;
        flex: 1 1 auto !important;
        flex-direction: column !important;
        width: 100%;
        min-height: 0 !important;
        max-height: inherit !important;
        overflow: hidden !important;
    }

    .modal .modal-header,
    .modal .modal-footer,
    .modal .ao-booking-modal-header,
    .modal .ao-booking-modal-footer,
    .modal .ao-manual-head,
    .modal .ao-manual-footer,
    .modal .wizard-top,
    .modal .wizard-footer {
        flex: 0 0 auto !important;
    }

    .modal .modal-body,
    .modal .ao-booking-modal-body,
    .modal .ao-booking-edit-body,
    .modal .ao-manual-body,
    .modal .wizard-body {
        flex: 1 1 auto !important;
        min-height: 0 !important;
        max-height: none !important;
        overflow-x: hidden;
        overflow-y: auto !important;
        overscroll-behavior: contain;
        -webkit-overflow-scrolling: touch;
    }

    /* The manual booking wizard previously forced a tall body, which could
       move its Create Booking action below smaller laptop/mobile viewports. */
    .modal .ao-manual-body,
    .modal .wizard-body {
        min-height: 0 !important;
    }

    .modal .modal-footer,
    .modal .ao-booking-modal-footer,
    .modal .ao-manual-footer,
    .modal .wizard-footer {
        position: relative;
        z-index: 5;
        gap: 10px;
        flex-wrap: wrap;
        background: #fff !important;
        box-shadow: 0 -8px 20px rgba(23, 16, 33, .035);
    }

    .modal .modal-footer .btn,
    .modal .modal-footer button,
    .modal .ao-booking-modal-footer button,
    .modal .ao-manual-footer button,
    .modal .wizard-footer button {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        min-height: 42px;
        line-height: 1.15;
        white-space: nowrap;
    }

    /* Add/Edit Member is the modal that exposed the issue first. Its local
       70vh body limit is deliberately overridden by the shared flex layout. */
    .member-modal .modal-body {
        max-height: none !important;
        overflow-y: auto !important;
    }

    .member-modal .modal-content > form {
        height: auto !important;
        max-height: calc(100dvh - 24px) !important;
    }

    /* Add Credits has a nested button group in the footer. Keep every action
       visible when the viewport is narrow. */
    #acesAddCreditsModal .modal-footer > .d-flex,
    .modal .ao-manual-footer-right {
        flex-wrap: wrap;
    }

    @media (max-width: 575.98px) {
        .modal .modal-dialog,
        .member-modal .modal-dialog,
        .ao-booking-detail-dialog,
        .ao-booking-edit-dialog,
        .ao-manual-dialog {
            width: auto !important;
            max-width: none !important;
            margin: 8px !important;
            max-height: calc(100dvh - 16px) !important;
        }

        .modal .modal-content,
        .modal .ao-booking-modal-content,
        .modal .ao-manual-content,
        .member-modal .modal-content > form {
            max-height: calc(100dvh - 16px) !important;
        }

        .modal .modal-footer,
        .modal .ao-booking-modal-footer,
        .modal .ao-manual-footer,
        .modal .wizard-footer {
            padding: 12px 14px !important;
        }

        .modal .modal-footer > .btn,
        .modal .modal-footer > button,
        .modal .ao-booking-modal-footer > button,
        .modal .wizard-footer > button {
            flex: 1 1 120px;
        }

        #acesAddCreditsModal .modal-footer {
            align-items: stretch !important;
        }

        #acesAddCreditsModal .modal-footer > button,
        #acesAddCreditsModal .modal-footer > .d-flex,
        #acesAddCreditsModal .modal-footer > .d-flex > button,
        .modal .ao-manual-footer > button,
        .modal .ao-manual-footer-right,
        .modal .ao-manual-footer-right > button {
            width: 100% !important;
        }

        #acesAddCreditsModal .modal-footer > .d-flex,
        .modal .ao-manual-footer-right {
            display: grid !important;
            grid-template-columns: 1fr !important;
        }
    }
</style>
