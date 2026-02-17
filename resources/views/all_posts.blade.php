<!DOCTYPE html>
<html lang="en">
<head>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-P7ZNRWKS7Z"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-P7ZNRWKS7Z');
    </script>

    <meta charset="utf-8">
    <meta name="author" content="omoha Ekenedilichukwu Francis">
    <meta name="description" content="Manage your SupperAge posts">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />
    <meta http-equiv="X-UA-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Posts - SupperAge</title>

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/post.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/finebtn.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobilenavbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/searchuser.css') }}">

    <style>
    .ap-page {
        max-width: 720px;
        margin: 0 auto;
        padding: 20px 15px 100px;
    }

    /* Page header */
    .ap-page-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }

    .ap-page-header .back-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #e4e6eb;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #050505;
        font-size: 16px;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.2s;
    }

    .ap-page-header .back-btn:hover {
        background: #d8dadf;
        text-decoration: none;
        color: #050505;
    }

    .ap-page-header h4 {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        color: #050505;
    }

    .ap-page-header .post-count {
        margin-left: auto;
        font-size: 13px;
        color: #65676b;
        font-weight: 500;
    }

    /* Tab buttons */
    .ap-tabs {
        display: flex;
        gap: 8px;
        margin-bottom: 16px;
    }

    .ap-tab {
        padding: 8px 20px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
    }

    .ap-tab.active {
        background: #1877f2;
        color: #fff;
    }

    .ap-tab.active:hover {
        background: #1565c0;
        color: #fff;
        text-decoration: none;
    }

    .ap-tab:not(.active) {
        background: #e4e6eb;
        color: #050505;
    }

    .ap-tab:not(.active):hover {
        background: #d8dadf;
        text-decoration: none;
        color: #050505;
    }

    /* Search & filter bar */
    .ap-filter-bar {
        background: #fff;
        border-radius: 12px;
        padding: 14px 18px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        margin-bottom: 16px;
    }

    .ap-filter-row {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }

    .ap-search-input {
        flex: 1;
        min-width: 180px;
        padding: 9px 14px 9px 36px;
        border: 2px solid #e4e6eb;
        border-radius: 20px;
        font-size: 14px;
        background: #f0f2f5 url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2365676b' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'/%3E%3C/svg%3E") no-repeat 12px center;
        transition: border-color 0.2s;
    }

    .ap-search-input:focus {
        outline: none;
        border-color: #1877f2;
        background-color: #fff;
    }

    .ap-filter-select {
        padding: 9px 14px;
        border: 2px solid #e4e6eb;
        border-radius: 8px;
        font-size: 13px;
        color: #050505;
        background: #f0f2f5;
        cursor: pointer;
        min-width: 120px;
    }

    .ap-filter-select:focus {
        outline: none;
        border-color: #1877f2;
    }

    .ap-filter-btn {
        padding: 9px 18px;
        background: #1877f2;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }

    .ap-filter-btn:hover {
        background: #1565c0;
    }

    /* Bulk actions bar */
    .ap-bulk-bar {
        background: #fff;
        border-radius: 12px;
        padding: 12px 18px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }

    .ap-bulk-checks {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .ap-check-label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #65676b;
        font-weight: 500;
        cursor: pointer;
        margin: 0;
    }

    .ap-check-label input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: #1877f2;
        cursor: pointer;
    }

    .ap-delete-selected {
        padding: 8px 16px;
        background: #fff;
        color: #e41e3f;
        border: 2px solid #e41e3f;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .ap-delete-selected:hover {
        background: #e41e3f;
        color: #fff;
    }

    .ap-selected-count {
        display: none;
        font-size: 13px;
        color: #1877f2;
        font-weight: 600;
    }

    /* Post card */
    .ap-post-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        margin-bottom: 12px;
        overflow: hidden;
        transition: box-shadow 0.2s;
    }

    .ap-post-card:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.12);
    }

    .ap-post-card.selected {
        box-shadow: 0 0 0 2px #1877f2, 0 2px 8px rgba(24,119,242,0.15);
    }

    .ap-post-top {
        display: flex;
        align-items: flex-start;
        padding: 16px 18px 0;
        gap: 12px;
    }

    .ap-post-checkbox {
        margin-top: 3px;
        flex-shrink: 0;
    }

    .ap-post-checkbox input[type="checkbox"] {
        width: 17px;
        height: 17px;
        accent-color: #1877f2;
        cursor: pointer;
    }

    .ap-post-info {
        flex: 1;
        min-width: 0;
    }

    .ap-post-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 6px;
        flex-wrap: wrap;
    }

    .ap-post-time {
        font-size: 12px;
        color: #65676b;
    }

    .ap-badge-draft {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 10px;
        background: #fef3cd;
        color: #856404;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }

    .ap-badge-scheduled {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 10px;
        background: #d1ecf1;
        color: #0c5460;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }

    .ap-badge-engagement {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 10px;
        background: #e8f5e9;
        color: #2e7d32;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }

    /* Post content preview */
    .ap-post-preview {
        margin: 10px 18px;
        border-radius: 10px;
        padding: 14px 16px;
        min-height: 50px;
    }

    .ap-post-preview p {
        margin: 0;
        font-size: 14px;
        word-wrap: break-word;
        white-space: pre-wrap;
        line-height: 1.5;
    }

    /* Post media */
    .ap-post-media {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        padding: 0 18px;
        margin-bottom: 10px;
    }

    .ap-media-item {
        border-radius: 8px;
        overflow: hidden;
        max-height: 140px;
        flex: 0 0 auto;
    }

    .ap-media-item img {
        height: 140px;
        width: auto;
        max-width: 200px;
        object-fit: cover;
        display: block;
    }

    .ap-media-item video {
        height: 140px;
        width: auto;
        max-width: 200px;
        object-fit: cover;
        display: block;
    }

    /* Tags */
    .ap-tags {
        padding: 0 18px;
        margin-bottom: 8px;
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
    }

    .ap-tag {
        padding: 2px 10px;
        background: #e4e6eb;
        color: #1877f2;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }

    /* Stats bar */
    .ap-stats {
        display: flex;
        padding: 8px 18px;
        border-top: 1px solid #e4e6eb;
        gap: 4px;
    }

    .ap-stat {
        flex: 1;
        text-align: center;
        padding: 6px 4px;
    }

    .ap-stat-value {
        font-size: 15px;
        font-weight: 700;
        color: #050505;
        display: block;
    }

    .ap-stat-label {
        font-size: 11px;
        color: #65676b;
        font-weight: 500;
    }

    /* Action buttons */
    .ap-actions {
        display: flex;
        border-top: 1px solid #e4e6eb;
    }

    .ap-action-btn {
        flex: 1;
        padding: 10px;
        border: none;
        background: none;
        font-size: 13px;
        font-weight: 600;
        color: #65676b;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: background 0.15s, color 0.15s;
        text-decoration: none;
    }

    .ap-action-btn:hover {
        background: #f0f2f5;
        text-decoration: none;
    }

    .ap-action-btn.edit-btn:hover {
        color: #1877f2;
    }

    .ap-action-btn.delete-btn {
        color: #65676b;
    }

    .ap-action-btn.delete-btn:hover {
        color: #e41e3f;
        background: #fef2f2;
    }

    .ap-action-btn.cancel-btn:hover {
        color: #e67e22;
    }

    /* Empty state */
    .ap-empty {
        text-align: center;
        padding: 60px 20px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }

    .ap-empty i {
        font-size: 48px;
        color: #d8dadf;
        margin-bottom: 16px;
    }

    .ap-empty h5 {
        color: #050505;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .ap-empty p {
        color: #65676b;
        font-size: 14px;
    }

    /* Pagination */
    .ap-pagination {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    .ap-pagination .pagination {
        gap: 4px;
    }

    .ap-pagination .page-link {
        border-radius: 8px;
        border: none;
        color: #1877f2;
        font-weight: 500;
        padding: 8px 14px;
        font-size: 14px;
    }

    .ap-pagination .page-item.active .page-link {
        background: #1877f2;
        color: #fff;
    }

    /* Trashed section */
    .ap-trash-section {
        margin-top: 24px;
    }

    .ap-trash-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
        cursor: pointer;
        user-select: none;
    }

    .ap-trash-header h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #e41e3f;
    }

    .ap-trash-header .trash-count {
        background: #e41e3f;
        color: #fff;
        padding: 1px 8px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 600;
    }

    .ap-trash-header .trash-toggle {
        margin-left: auto;
        color: #65676b;
        transition: transform 0.3s;
    }

    .ap-trash-header .trash-toggle.collapsed {
        transform: rotate(-90deg);
    }

    .ap-trash-card {
        background: #fff;
        border-radius: 10px;
        border-left: 4px solid #e41e3f;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        padding: 14px 18px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }

    .ap-trash-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .ap-trash-info i {
        color: #e41e3f;
        font-size: 16px;
    }

    .ap-trash-info span {
        font-size: 13px;
        color: #65676b;
    }

    .ap-trash-actions {
        display: flex;
        gap: 8px;
    }

    .ap-restore-btn {
        padding: 6px 14px;
        background: #e8f5e9;
        color: #2e7d32;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }

    .ap-restore-btn:hover {
        background: #c8e6c9;
    }

    .ap-perma-delete-btn {
        padding: 6px 14px;
        background: #fef2f2;
        color: #e41e3f;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }

    .ap-perma-delete-btn:hover {
        background: #fde8e8;
    }

    /* Confirm modal */
    .ap-modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 9998;
        align-items: center;
        justify-content: center;
    }

    .ap-modal-overlay.show {
        display: flex;
    }

    .ap-modal {
        background: #fff;
        border-radius: 12px;
        width: 90%;
        max-width: 420px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.2);
        overflow: hidden;
        animation: apModalIn 0.2s ease;
    }

    @keyframes apModalIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }

    .ap-modal-header {
        padding: 18px 20px;
        border-bottom: 1px solid #e4e6eb;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .ap-modal-header i {
        color: #e41e3f;
        font-size: 20px;
    }

    .ap-modal-header h5 {
        margin: 0;
        font-size: 17px;
        font-weight: 700;
        color: #050505;
    }

    .ap-modal-body {
        padding: 18px 20px;
        font-size: 14px;
        color: #65676b;
        line-height: 1.5;
    }

    .ap-modal-footer {
        padding: 14px 20px;
        border-top: 1px solid #e4e6eb;
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }

    .ap-modal-cancel {
        padding: 8px 20px;
        background: #e4e6eb;
        color: #050505;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
    }

    .ap-modal-confirm {
        padding: 8px 20px;
        background: #e41e3f;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }

    .ap-modal-confirm:hover {
        background: #c0392b;
    }

    /* Toast */
    .ap-toast {
        position: fixed;
        bottom: 80px;
        left: 50%;
        transform: translateX(-50%) translateY(20px);
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        z-index: 9999;
        opacity: 0;
        transition: all 0.3s ease;
        pointer-events: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        max-width: 90vw;
    }

    .ap-toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }
    .ap-toast.success { background: #00a400; color: #fff; }
    .ap-toast.error { background: #e41e3f; color: #fff; }

    /* ===== Dark Mode ===== */
    body.dark-mode .ap-page-header h4 { color: #E4E6EB; }
    body.dark-mode .ap-page-header .post-count { color: #B0B3B8; }
    body.dark-mode .ap-page-header .back-btn { background: #3A3B3C; color: #E4E6EB; }
    body.dark-mode .ap-page-header .back-btn:hover { background: #4E4F50; }

    body.dark-mode .ap-tab:not(.active) { background: #3A3B3C; color: #E4E6EB; }
    body.dark-mode .ap-tab:not(.active):hover { background: #4E4F50; }

    body.dark-mode .ap-filter-bar { background: #242526; box-shadow: 0 1px 3px rgba(0,0,0,0.3); }
    body.dark-mode .ap-search-input {
        background-color: #3A3B3C;
        border-color: #3E4042;
        color: #E4E6EB;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23B0B3B8' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'/%3E%3C/svg%3E");
    }
    body.dark-mode .ap-search-input:focus { border-color: #2D88FF; background-color: #3A3B3C; }
    body.dark-mode .ap-filter-select { background: #3A3B3C; border-color: #3E4042; color: #E4E6EB; }
    body.dark-mode .ap-filter-select:focus { border-color: #2D88FF; }

    body.dark-mode .ap-bulk-bar { background: #242526; box-shadow: 0 1px 3px rgba(0,0,0,0.3); }
    body.dark-mode .ap-check-label { color: #B0B3B8; }
    body.dark-mode .ap-selected-count { color: #2D88FF; }
    body.dark-mode .ap-delete-selected { background: #242526; color: #e41e3f; border-color: #e41e3f; }
    body.dark-mode .ap-delete-selected:hover { background: #e41e3f; color: #fff; }

    body.dark-mode .ap-post-card { background: #242526; box-shadow: 0 1px 3px rgba(0,0,0,0.3); }
    body.dark-mode .ap-post-card:hover { box-shadow: 0 2px 8px rgba(0,0,0,0.4); }
    body.dark-mode .ap-post-card.selected { box-shadow: 0 0 0 2px #2D88FF, 0 2px 8px rgba(45,136,255,0.2); }
    body.dark-mode .ap-post-time { color: #B0B3B8; }
    body.dark-mode .ap-stats { border-top-color: #3E4042; }
    body.dark-mode .ap-stat-value { color: #E4E6EB; }
    body.dark-mode .ap-stat-label { color: #B0B3B8; }
    body.dark-mode .ap-actions { border-top-color: #3E4042; }
    body.dark-mode .ap-action-btn { color: #B0B3B8; }
    body.dark-mode .ap-action-btn:hover { background: #3A3B3C; }
    body.dark-mode .ap-action-btn.edit-btn:hover { color: #2D88FF; }
    body.dark-mode .ap-action-btn.delete-btn:hover { color: #e41e3f; background: #3A3B3C; }
    body.dark-mode .ap-tag { background: #3A3B3C; color: #2D88FF; }
    body.dark-mode .ap-badge-draft { background: #3A3B3C; color: #ffc107; }
    body.dark-mode .ap-badge-scheduled { background: #3A3B3C; color: #17a2b8; }
    body.dark-mode .ap-badge-engagement { background: #3A3B3C; color: #4caf50; }

    body.dark-mode .ap-empty { background: #242526; box-shadow: 0 1px 3px rgba(0,0,0,0.3); }
    body.dark-mode .ap-empty i { color: #3E4042; }
    body.dark-mode .ap-empty h5 { color: #E4E6EB; }
    body.dark-mode .ap-empty p { color: #B0B3B8; }

    body.dark-mode .ap-trash-header h5 { color: #e41e3f; }
    body.dark-mode .ap-trash-header .trash-toggle { color: #B0B3B8; }
    body.dark-mode .ap-trash-card { background: #242526; box-shadow: 0 1px 3px rgba(0,0,0,0.3); }
    body.dark-mode .ap-trash-info span { color: #B0B3B8; }
    body.dark-mode .ap-restore-btn { background: #1e3a2b; color: #4caf50; }
    body.dark-mode .ap-restore-btn:hover { background: #2e4a3b; }
    body.dark-mode .ap-perma-delete-btn { background: #3a1e1e; color: #e41e3f; }
    body.dark-mode .ap-perma-delete-btn:hover { background: #4a2e2e; }

    body.dark-mode .ap-modal { background: #242526; }
    body.dark-mode .ap-modal-header { border-bottom-color: #3E4042; }
    body.dark-mode .ap-modal-header h5 { color: #E4E6EB; }
    body.dark-mode .ap-modal-body { color: #B0B3B8; }
    body.dark-mode .ap-modal-footer { border-top-color: #3E4042; }
    body.dark-mode .ap-modal-cancel { background: #3A3B3C; color: #E4E6EB; }

    body.dark-mode .ap-pagination .page-link { color: #2D88FF; background: #242526; }
    body.dark-mode .ap-pagination .page-item.active .page-link { background: #2D88FF; color: #fff; }

    /* Responsive */
    @media (max-width: 600px) {
        .ap-page { padding: 12px 10px 100px; }
        .ap-filter-row { flex-direction: column; }
        .ap-search-input { min-width: unset; width: 100%; }
        .ap-filter-select { width: 100%; }
        .ap-bulk-bar { flex-direction: column; align-items: flex-start; }
        .ap-post-preview { margin: 10px 14px; padding: 12px; }
        .ap-post-media { padding: 0 14px; }
        .ap-tags { padding: 0 14px; }
        .ap-stats { padding: 8px 14px; }
        .ap-post-top { padding: 14px 14px 0; }
        .ap-media-item img, .ap-media-item video { height: 100px; max-width: 140px; }
        .ap-stat-value { font-size: 14px; }
        .ap-stat-label { font-size: 10px; }
    }
    </style>
</head>
<body>
@extends('layouts.app')
@include('layouts.navbar')

@section('seo_title', 'My Posts - SupperAge')
@section('seo_description', 'View and manage all your posts on SupperAge. Edit, delete, or reshare your content.')

@section('content')
<div class="ap-page">
    <!-- Page Header -->
    <div class="ap-page-header">
        <a href="{{ url('/update') }}" class="back-btn"><i class="fas fa-arrow-left"></i></a>
        <h4>My Posts</h4>
        <span class="post-count">{{ $posts->total() }} {{ Str::plural('post', $posts->total()) }}</span>
    </div>

    <!-- Tabs: Published / Drafts -->
    <div class="ap-tabs">
        <a href="{{ route('posts.all', ['view' => 'published']) }}" class="ap-tab {{ request('view', 'published') === 'published' ? 'active' : '' }}">
            <i class="fas fa-globe-africa"></i> Published
        </a>
        <a href="{{ route('posts.all', ['view' => 'drafts']) }}" class="ap-tab {{ request('view') === 'drafts' ? 'active' : '' }}">
            <i class="fas fa-file-alt"></i> Drafts
        </a>
    </div>

    <!-- Search & Filter -->
    <form method="GET" action="{{ route('posts.all') }}" class="ap-filter-bar">
        <input type="hidden" name="view" value="{{ request('view', 'published') }}">
        <div class="ap-filter-row">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search your posts..." class="ap-search-input">
            <select name="filter" class="ap-filter-select">
                <option value="">All Media</option>
                <option value="videos" {{ request('filter') === 'videos' ? 'selected' : '' }}>Videos Only</option>
            </select>
            <select name="sort" class="ap-filter-select">
                <option value="">Latest First</option>
                <option value="likes" {{ request('sort') === 'likes' ? 'selected' : '' }}>Most Liked</option>
                <option value="views" {{ request('sort') === 'views' ? 'selected' : '' }}>Most Viewed</option>
            </select>
            <button type="submit" class="ap-filter-btn"><i class="fas fa-search"></i> Search</button>
        </div>
    </form>

    <!-- Bulk Actions -->
    <form id="bulkDeleteForm" method="POST" action="{{ route('posts.bulkDelete') }}">
        @csrf
        @method('DELETE')

        <div class="ap-bulk-bar">
            <div class="ap-bulk-checks">
                <label class="ap-check-label">
                    <input type="checkbox" id="selectAll"> Select All
                </label>
                <label class="ap-check-label">
                    <input type="checkbox" id="selectDrafts"> Drafts Only
                </label>
                <span class="ap-selected-count" id="selectedCount">0 selected</span>
            </div>
            <button type="button" class="ap-delete-selected" id="confirmDeleteBtn">
                <i class="fas fa-trash-alt"></i> Delete Selected
            </button>
        </div>

        <!-- Post Cards -->
        @forelse($posts as $post)
            @php
                $likes = $post->likes ?? 0;
                $comments = DB::table('comments')->where('tale_id', $post->id)->count();
                $views = $post->views ?? 0;
                $shares = $post->shares ?? 0;
                $engagementRate = round(($likes + $comments + $shares) / max($views, 1) * 100, 2);
                $timeAgo = \Carbon\Carbon::parse($post->created_at)->diffForHumans();
                $media = json_decode($post->file_path, true);
                $hashtags = DB::table('hashtags')->where('post_id', $post->id)->pluck('tag');
                $bgColor = $post->bgnd_color ?? '#ffffff';
                $textColor = $post->text_color ?? '#000000';
                $isScheduled = $post->status === 'draft' && $post->scheduled_at && \Carbon\Carbon::parse($post->scheduled_at)->isFuture();
            @endphp

            <div class="ap-post-card" data-post-id="{{ $post->id }}" data-status="{{ $post->status }}">
                <div class="ap-post-top">
                    <div class="ap-post-checkbox">
                        <input class="post-checkbox" type="checkbox" name="post_ids[]" value="{{ $post->id }}">
                    </div>
                    <div class="ap-post-info">
                        <div class="ap-post-meta">
                            <span class="ap-post-time"><i class="far fa-clock"></i> {{ $timeAgo }}</span>
                            @if($post->status === 'draft')
                                <span class="ap-badge-draft"><i class="fas fa-file-alt"></i> Draft</span>
                            @endif
                            @if($isScheduled)
                                <span class="ap-badge-scheduled"><i class="far fa-calendar-check"></i> Scheduled</span>
                            @endif
                            @if($engagementRate > 0)
                                <span class="ap-badge-engagement"><i class="fas fa-chart-line"></i> {{ $engagementRate }}%</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Content preview with post colors -->
                <div class="ap-post-preview" style="background-color: {{ $bgColor }}; color: {{ $textColor }};">
                    <p>{{ \Illuminate\Support\Str::limit($post->post_content, 200) }}</p>
                </div>

                <!-- Media grid -->
                @if(!empty($media))
                    <div class="ap-post-media">
                        @foreach(array_slice($media, 0, 4) as $idx => $file)
                            @php
                                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                $isVideo = in_array($ext, ['mp4', 'webm', 'ogg']);
                            @endphp
                            <div class="ap-media-item">
                                @if($isImage)
                                    <img src="{{ asset($file) }}" alt="Post media">
                                @elseif($isVideo)
                                    <video src="{{ asset($file) }}" muted></video>
                                @endif
                            </div>
                        @endforeach
                        @if(count($media) > 4)
                            <div class="ap-media-item" style="display:flex;align-items:center;justify-content:center;background:#e4e6eb;width:80px;height:140px;border-radius:8px;font-size:16px;font-weight:700;color:#65676b;">
                                +{{ count($media) - 4 }}
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Hashtags -->
                @if($hashtags->isNotEmpty())
                    <div class="ap-tags">
                        @foreach($hashtags as $tag)
                            <span class="ap-tag">#{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif

                <!-- Stats -->
                <div class="ap-stats">
                    <div class="ap-stat">
                        <span class="ap-stat-value">{{ $likes }}</span>
                        <span class="ap-stat-label"><i class="far fa-heart"></i> Likes</span>
                    </div>
                    <div class="ap-stat">
                        <span class="ap-stat-value">{{ $comments }}</span>
                        <span class="ap-stat-label"><i class="far fa-comment"></i> Comments</span>
                    </div>
                    <div class="ap-stat">
                        <span class="ap-stat-value">{{ $views }}</span>
                        <span class="ap-stat-label"><i class="far fa-eye"></i> Views</span>
                    </div>
                    <div class="ap-stat">
                        <span class="ap-stat-value">{{ $shares }}</span>
                        <span class="ap-stat-label"><i class="fas fa-share"></i> Shares</span>
                    </div>
                </div>

                <!-- Action buttons -->
                <div class="ap-actions">
                    <a href="{{ route('posts.edit', $post->id) }}" class="ap-action-btn edit-btn">
                        <i class="fas fa-pen"></i> Edit
                    </a>
                    <button type="button" class="ap-action-btn delete-btn" onclick="confirmSingleDelete({{ $post->id }}, '{{ route('posts.delete', $post->id) }}')">
                        <i class="fas fa-trash-alt"></i> Delete
                    </button>
                    @if($isScheduled)
                        <form method="POST" action="{{ route('posts.cancelSchedule', $post->id) }}" style="flex:1;display:flex;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="ap-action-btn cancel-btn" style="flex:1;">
                                <i class="fas fa-times-circle"></i> Cancel Schedule
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="ap-empty">
                <i class="fas fa-feather-alt"></i>
                <h5>No posts yet</h5>
                <p>Your {{ request('view') === 'drafts' ? 'draft' : 'published' }} posts will appear here.</p>
            </div>
        @endforelse
    </form>

    <!-- Pagination -->
    @if($posts->hasPages())
        <div class="ap-pagination">
            {{ $posts->appends(request()->query())->links() }}
        </div>
    @endif

    <!-- Trashed Posts -->
    @if($trashedPosts->count() > 0)
        <div class="ap-trash-section">
            <div class="ap-trash-header" onclick="toggleTrash()">
                <i class="fas fa-trash-alt" style="color:#e41e3f;"></i>
                <h5>Recently Deleted</h5>
                <span class="trash-count">{{ $trashedPosts->count() }}</span>
                <i class="fas fa-chevron-down trash-toggle" id="trashToggle"></i>
            </div>
            <div id="trashList">
                @foreach($trashedPosts as $post)
                    <div class="ap-trash-card">
                        <div class="ap-trash-info">
                            <i class="far fa-file-alt"></i>
                            <span>{{ \Illuminate\Support\Str::limit($post->post_content ?? 'Untitled post', 50) }} &mdash; deleted {{ \Carbon\Carbon::parse($post->deleted_at)->diffForHumans() }}</span>
                        </div>
                        <div class="ap-trash-actions">
                            <form method="POST" action="{{ route('posts.restore', $post->id) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="ap-restore-btn"><i class="fas fa-undo"></i> Restore</button>
                            </form>
                            <form method="POST" action="{{ route('posts.forceDelete', $post->id) }}" class="d-inline" onsubmit="return confirm('Permanently delete this post? This cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ap-perma-delete-btn"><i class="fas fa-times"></i> Delete Forever</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div class="ap-modal-overlay" id="deleteModal">
    <div class="ap-modal">
        <div class="ap-modal-header">
            <i class="fas fa-exclamation-triangle"></i>
            <h5 id="deleteModalTitle">Delete Posts</h5>
        </div>
        <div class="ap-modal-body" id="deleteModalBody">
            Are you sure you want to delete the selected posts? They will be moved to trash.
        </div>
        <div class="ap-modal-footer">
            <button type="button" class="ap-modal-cancel" onclick="closeDeleteModal()">Cancel</button>
            <button type="button" class="ap-modal-confirm" id="deleteModalConfirm">Delete</button>
        </div>
    </div>
</div>

<!-- Single delete form (hidden) -->
<form id="singleDeleteForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<div class="ap-toast" id="apToast"></div>
@endsection

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
<script src="{{ asset('myjs/bar.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
<script src="{{ asset('myjs/more_lesstext.js') }}"></script>
<script src="{{ asset('myjs/menu_pop_up_post.js') }}"></script>
<script src="{{ asset('myjs/allpost.js') }}"></script>
<script src="{{ asset('myjs/tales.js') }}"></script>
<script src="{{ asset('myjs/mobilenavbar.js') }}"></script>
<script src="{{ asset('myjs/searchuser.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var checkboxes = document.querySelectorAll('.post-checkbox');
    var selectAll = document.getElementById('selectAll');
    var selectDrafts = document.getElementById('selectDrafts');
    var selectedCount = document.getElementById('selectedCount');

    function updateCount() {
        var count = document.querySelectorAll('.post-checkbox:checked').length;
        if (count > 0) {
            selectedCount.style.display = 'inline';
            selectedCount.textContent = count + ' selected';
        } else {
            selectedCount.style.display = 'none';
        }
        // Update card highlighting
        checkboxes.forEach(function(cb) {
            var card = cb.closest('.ap-post-card');
            if (card) {
                if (cb.checked) card.classList.add('selected');
                else card.classList.remove('selected');
            }
        });
    }

    // Select All
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(function(cb) { cb.checked = selectAll.checked; });
            updateCount();
        });
    }

    // Select Drafts only
    if (selectDrafts) {
        selectDrafts.addEventListener('change', function() {
            checkboxes.forEach(function(cb) {
                var card = cb.closest('.ap-post-card');
                if (card && card.getAttribute('data-status') === 'draft') {
                    cb.checked = selectDrafts.checked;
                }
            });
            updateCount();
        });
    }

    // Individual checkbox change
    checkboxes.forEach(function(cb) {
        cb.addEventListener('change', updateCount);
    });

    // Bulk delete button
    var confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            var selected = document.querySelectorAll('.post-checkbox:checked');
            if (selected.length === 0) {
                showApToast('Please select at least one post to delete.', 'error');
                return;
            }
            document.getElementById('deleteModalTitle').textContent = 'Delete ' + selected.length + ' Post' + (selected.length > 1 ? 's' : '');
            document.getElementById('deleteModalBody').textContent = 'Are you sure you want to delete ' + selected.length + ' selected post' + (selected.length > 1 ? 's' : '') + '? They will be moved to trash.';
            document.getElementById('deleteModalConfirm').onclick = function() {
                document.getElementById('bulkDeleteForm').submit();
            };
            document.getElementById('deleteModal').classList.add('show');
        });
    }
});

// Single delete
function confirmSingleDelete(postId, actionUrl) {
    document.getElementById('deleteModalTitle').textContent = 'Delete Post';
    document.getElementById('deleteModalBody').textContent = 'Are you sure you want to delete this post? It will be moved to trash.';
    document.getElementById('deleteModalConfirm').onclick = function() {
        var form = document.getElementById('singleDeleteForm');
        form.action = actionUrl;
        form.submit();
    };
    document.getElementById('deleteModal').classList.add('show');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('show');
}

// Close modal on overlay click
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});

// Trash toggle
function toggleTrash() {
    var list = document.getElementById('trashList');
    var toggle = document.getElementById('trashToggle');
    if (list.style.display === 'none') {
        list.style.display = 'block';
        toggle.classList.remove('collapsed');
    } else {
        list.style.display = 'none';
        toggle.classList.add('collapsed');
    }
}

// Toast
function showApToast(msg, type) {
    var t = document.getElementById('apToast');
    t.textContent = msg;
    t.className = 'ap-toast ' + type;
    t.offsetHeight;
    t.classList.add('show');
    setTimeout(function() { t.classList.remove('show'); }, 3000);
}

// Show success/error from session
@if(session('success'))
    showApToast("{{ session('success') }}", 'success');
@endif
@if(session('error'))
    showApToast("{{ session('error') }}", 'error');
@endif
</script>

</body>
</html>
