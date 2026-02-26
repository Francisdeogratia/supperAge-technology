<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Groups - SupperAge</title>
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/finebtn.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobilenavbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/searchuser.css') }}">
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2892124627300694" crossorigin="anonymous"></script>

    <style>
        /* ── Base ── */
        *, *::before, *::after { box-sizing: border-box; }

        .grp-page {
            max-width: 860px;
            margin: 76px auto 60px;
            padding: 0 14px;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        /* ── Hero header ── */
        .grp-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 28px 28px 24px;
            margin-bottom: 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            box-shadow: 0 8px 32px rgba(102,126,234,.35);
        }
        .grp-hero-left h1 {
            margin: 0 0 4px;
            font-size: 1.7em;
            font-weight: 800;
            color: #fff;
            letter-spacing: -.3px;
        }
        .grp-hero-left p {
            margin: 0;
            color: rgba(255,255,255,.8);
            font-size: .9em;
        }
        .grp-hero-stats {
            display: flex;
            gap: 20px;
            color: #fff;
            font-size: .82em;
            margin-top: 12px;
        }
        .grp-hero-stats span { display: flex; align-items: center; gap: 5px; }
        .grp-hero-stats i { font-size: 1em; opacity: .85; }
        .btn-create {
            background: #fff;
            color: #667eea;
            border: none;
            padding: 11px 22px;
            border-radius: 50px;
            font-weight: 700;
            font-size: .88em;
            cursor: pointer;
            white-space: nowrap;
            box-shadow: 0 4px 14px rgba(0,0,0,.15);
            transition: transform .2s, box-shadow .2s;
            flex-shrink: 0;
        }
        .btn-create:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,.2); }
        .btn-create i { margin-right: 6px; }

        /* ── Section label ── */
        .grp-section { margin-bottom: 32px; }
        .grp-section-label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1em;
            font-weight: 700;
            color: #333;
            margin-bottom: 14px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f2f5;
        }
        .grp-section-label .lbl-icon {
            width: 32px; height: 32px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: .9em; color: #fff;
            background: linear-gradient(135deg, #667eea, #764ba2);
            flex-shrink: 0;
        }
        .grp-section-label .lbl-count {
            margin-left: auto;
            background: #f0f2f5;
            border-radius: 20px;
            padding: 2px 10px;
            font-size: .78em;
            color: #65676b;
            font-weight: 600;
        }

        /* ── Group card (list style for My Groups / Joined) ── */
        .grp-card {
            position: relative;
            background: #fff;
            border-radius: 14px;
            padding: 14px 16px;
            margin-bottom: 10px;
            box-shadow: 0 1px 6px rgba(0,0,0,.07);
            display: flex;
            align-items: center;
            gap: 14px;
            transition: box-shadow .2s, transform .2s;
            overflow: hidden;
        }
        .grp-card:hover { box-shadow: 0 4px 18px rgba(0,0,0,.12); transform: translateY(-1px); }

        /* Avatar */
        .grp-avatar {
            width: 54px; height: 54px; min-width: 54px;
            border-radius: 14px;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 1.4em; font-weight: 700;
            flex-shrink: 0;
        }
        .grp-avatar img { width: 100%; height: 100%; border-radius: 14px; object-fit: cover; }

        /* Info block — must not overflow */
        .grp-card-body {
            flex: 1;
            min-width: 0; /* critical */
        }
        .grp-card-body a { text-decoration: none; display: block; min-width: 0; }
        .grp-title {
            font-weight: 700;
            font-size: .98em;
            color: #1c1e21;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .grp-meta {
            font-size: .78em;
            color: #65676b;
            margin-top: 2px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .grp-meta .dot { opacity: .4; }

        /* Last message — THE FIX */
        .grp-last-msg {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 5px;
            min-width: 0; /* critical */
        }
        .grp-last-msg-text {
            flex: 1;
            min-width: 0; /* critical */
            font-size: .8em;
            color: #65676b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .grp-last-msg-text strong { color: #444; }
        .grp-last-msg-time {
            font-size: .72em;
            color: #aaa;
            white-space: nowrap;
            flex-shrink: 0;
        }

        /* Unread dot */
        .grp-unread {
            position: absolute;
            top: 12px; right: 12px;
            background: #e74c3c;
            color: #fff;
            border-radius: 20px;
            min-width: 20px; height: 20px;
            font-size: .7em; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            padding: 0 5px;
        }

        /* Action buttons on the right */
        .grp-card-actions {
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex-shrink: 0;
        }
        .grp-btn {
            border: none; cursor: pointer;
            border-radius: 20px;
            font-size: .75em; font-weight: 600;
            padding: 5px 12px;
            white-space: nowrap;
            transition: opacity .2s, transform .2s;
        }
        .grp-btn:hover { opacity: .85; transform: scale(1.03); }
        .grp-btn-purple { background: linear-gradient(135deg,#667eea,#764ba2); color:#fff; }
        .grp-btn-teal   { background: #17a2b8; color:#fff; }
        .grp-btn-red    { background: #e74c3c; color:#fff; }
        .grp-btn-grey   { background: #dee2e6; color:#555; cursor:default; }
        .grp-btn-green  { background: #27ae60; color:#fff; }
        .grp-btn-yellow { background: #f39c12; color:#fff; cursor:default; }

        /* ── Discover grid ── */
        .grp-discover-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 14px;
        }
        .grp-discover-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 1px 8px rgba(0,0,0,.08);
            overflow: hidden;
            transition: box-shadow .2s, transform .2s;
            display: flex;
            flex-direction: column;
        }
        .grp-discover-card:hover { box-shadow: 0 6px 24px rgba(0,0,0,.14); transform: translateY(-3px); }
        .grp-discover-banner {
            height: 70px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex; align-items: center; justify-content: center;
            font-size: 2em; font-weight: 800; color: #fff;
            position: relative;
        }
        .grp-discover-banner img {
            width: 100%; height: 100%; object-fit: cover;
        }
        .grp-discover-body { padding: 14px; flex: 1; display: flex; flex-direction: column; }
        .grp-discover-name {
            font-weight: 700; font-size: .97em; color: #1c1e21;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            margin-bottom: 4px;
        }
        .grp-discover-desc {
            font-size: .8em; color: #65676b; line-height: 1.5;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            flex: 1;
            margin-bottom: 10px;
        }
        .grp-discover-footer {
            display: flex; align-items: center; justify-content: space-between;
            gap: 8px;
        }
        .grp-discover-members { font-size: .75em; color: #888; }

        /* ── Empty state ── */
        .grp-empty {
            text-align: center; padding: 40px 20px;
            color: #aaa; background: #fff; border-radius: 14px;
            box-shadow: 0 1px 6px rgba(0,0,0,.06);
        }
        .grp-empty i { font-size: 2.8em; margin-bottom: 12px; display: block; opacity: .4; }
        .grp-empty p { margin: 0; font-size: .9em; }

        /* ── Modal ── */
        .grp-modal-overlay {
            display: none;
            position: fixed; inset: 0; z-index: 9000;
            background: rgba(0,0,0,.5);
            align-items: center; justify-content: center;
            animation: gFadeIn .25s;
        }
        .grp-modal-overlay.show { display: flex; }
        .grp-modal {
            background: #fff;
            border-radius: 18px;
            padding: 28px;
            width: 90%; max-width: 480px;
            max-height: 88vh; overflow-y: auto;
            animation: gSlideUp .25s;
            box-shadow: 0 16px 48px rgba(0,0,0,.22);
        }
        @keyframes gFadeIn { from{opacity:0} to{opacity:1} }
        @keyframes gSlideUp { from{transform:translateY(40px);opacity:0} to{transform:translateY(0);opacity:1} }
        .grp-modal-head {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 22px;
        }
        .grp-modal-head h4 { margin: 0; font-size: 1.15em; font-weight: 700; color: #1c1e21; }
        .grp-modal-close {
            background: #f0f2f5; border: none; width: 32px; height: 32px;
            border-radius: 50%; font-size: 1.1em; cursor: pointer; color: #555;
            display: flex; align-items: center; justify-content: center;
            transition: background .2s;
        }
        .grp-modal-close:hover { background: #ddd; }
        .grp-field { margin-bottom: 16px; }
        .grp-field label { display: block; font-weight: 600; font-size: .87em; color: #333; margin-bottom: 6px; }
        .grp-field input, .grp-field textarea, .grp-field select {
            width: 100%; padding: 10px 14px;
            border: 2px solid #e4e6eb; border-radius: 10px;
            font-size: .9em; outline: none; transition: border-color .2s;
            font-family: inherit; color: #1c1e21;
        }
        .grp-field input:focus, .grp-field textarea:focus, .grp-field select:focus { border-color: #667eea; }
        .grp-field textarea { resize: vertical; }
        .grp-submit {
            width: 100%; padding: 13px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff; border: none; border-radius: 10px;
            font-weight: 700; font-size: .95em; cursor: pointer;
            transition: opacity .2s, transform .2s;
            margin-top: 4px;
        }
        .grp-submit:hover { opacity: .9; transform: translateY(-1px); }
        .grp-submit:disabled { opacity: .6; cursor: not-allowed; transform: none; }

        /* Info modal details */
        .grp-info-row { margin-bottom: 14px; }
        .grp-info-row .info-lbl {
            font-size: .78em; font-weight: 700; text-transform: uppercase;
            letter-spacing: .5px; color: #667eea; margin-bottom: 4px;
        }
        .grp-info-row .info-val { font-size: .92em; color: #333; }

        /* Friends list inside modal */
        .friend-item {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 0; border-bottom: 1px solid #f0f2f5; cursor: pointer;
        }
        .friend-item:last-child { border-bottom: none; }
        .friend-item img { width: 38px; height: 38px; border-radius: 50%; object-fit: cover; }
        .friend-item label { flex: 1; cursor: pointer; }
        .friend-item label small { display: block; font-size: .75em; color: #888; }

        /* Dark mode */
        body.dark-mode .grp-section-label { color: #e4e6eb; border-bottom-color: #3e4042; }
        body.dark-mode .grp-section-label .lbl-count { background: #3a3b3c; color: #b0b3b8; }
        body.dark-mode .grp-card, body.dark-mode .grp-discover-card, body.dark-mode .grp-empty { background: #242526; box-shadow: 0 1px 6px rgba(0,0,0,.3); }
        body.dark-mode .grp-title, body.dark-mode .grp-discover-name { color: #e4e6eb; }
        body.dark-mode .grp-meta, body.dark-mode .grp-last-msg-text, body.dark-mode .grp-discover-desc { color: #b0b3b8; }
        body.dark-mode .grp-last-msg-text strong { color: #ccc; }
        body.dark-mode .grp-modal { background: #242526; }
        body.dark-mode .grp-modal-head h4 { color: #e4e6eb; }
        body.dark-mode .grp-modal-close { background: #3a3b3c; color: #ccc; }
        body.dark-mode .grp-field input, body.dark-mode .grp-field textarea, body.dark-mode .grp-field select { background: #3a3b3c; border-color: #3e4042; color: #e4e6eb; }
        body.dark-mode .grp-info-row .info-val { color: #ccc; }
        body.dark-mode .friend-item { border-bottom-color: #3e4042; }
        body.dark-mode .friend-item label { color: #e4e6eb; }
        body.dark-mode .grp-btn-grey { background: #3a3b3c; color: #b0b3b8; }
        body.dark-mode .grp-empty i { opacity: .3; }

        /* ── Search bar ── */
        .grp-search-wrap {
            position: relative;
            margin-bottom: 24px;
        }
        .grp-search-input {
            width: 100%;
            padding: 12px 18px 12px 44px;
            border: 2px solid #e4e6eb;
            border-radius: 50px;
            font-size: .93em;
            outline: none;
            background: #fff;
            color: #1c1e21;
            transition: border-color .2s, box-shadow .2s;
            font-family: inherit;
        }
        .grp-search-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102,126,234,.12);
        }
        .grp-search-icon {
            position: absolute;
            left: 16px; top: 50%;
            transform: translateY(-50%);
            color: #aaa; font-size: .95em;
            pointer-events: none;
        }
        .grp-search-clear {
            position: absolute;
            right: 14px; top: 50%;
            transform: translateY(-50%);
            background: #e4e6eb; border: none;
            width: 22px; height: 22px; border-radius: 50%;
            font-size: .75em; color: #555; cursor: pointer;
            display: none; align-items: center; justify-content: center;
            transition: background .2s;
        }
        .grp-search-clear.visible { display: flex; }
        .grp-search-clear:hover { background: #ccc; }
        .grp-search-no-result {
            text-align: center; padding: 28px 20px;
            color: #aaa; font-size: .9em;
            display: none;
        }
        .grp-search-no-result i { font-size: 2em; display: block; margin-bottom: 8px; opacity: .4; }
        body.dark-mode .grp-search-input { background: #242526; border-color: #3e4042; color: #e4e6eb; }
        body.dark-mode .grp-search-input:focus { border-color: #667eea; }
        body.dark-mode .grp-search-clear { background: #3a3b3c; color: #ccc; }

        @media (max-width: 600px) {
            .grp-page { margin-top: 62px; }
            .grp-hero { flex-direction: column; align-items: flex-start; }
            .btn-create { width: 100%; text-align: center; justify-content: center; }
            .grp-discover-grid { grid-template-columns: 1fr 1fr; }
            .grp-card-actions { flex-direction: row; flex-wrap: wrap; }
        }
        @media (max-width: 400px) {
            .grp-discover-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    @include('layouts.navbar')

    <div class="grp-page">

        {{-- Hero --}}
        <div class="grp-hero">
            <div class="grp-hero-left">
                <h1><i class="fas fa-layer-group"></i> Groups</h1>
                <p>Connect, share and grow together</p>
                <div class="grp-hero-stats">
                    <span><i class="fas fa-users"></i> {{ $myGroups->count() + $memberGroups->count() }} joined</span>
                    <span><i class="fas fa-compass"></i> {{ $otherGroups->count() }} to discover</span>
                </div>
            </div>
            <button class="btn-create" onclick="openCreateModal()">
                <i class="fas fa-plus"></i> New Group
            </button>
        </div>

        {{-- Search --}}
        <div class="grp-search-wrap">
            <i class="fas fa-search grp-search-icon"></i>
            <input
                type="text"
                class="grp-search-input"
                id="grpSearchInput"
                placeholder="Search groups by name or description…"
                oninput="handleGroupSearch(this.value)"
                autocomplete="off"
            >
            <button class="grp-search-clear" id="grpSearchClear" onclick="clearGroupSearch()" title="Clear">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="grp-search-no-result" id="grpNoResult">
            <i class="fas fa-search-minus"></i>
            No groups match "<span id="grpNoResultTerm"></span>"
        </div>

        {{-- My Groups --}}
        <div class="grp-section">
            <div class="grp-section-label">
                <span class="lbl-icon"><i class="fas fa-crown"></i></span>
                My Groups
                <span class="lbl-count">{{ $myGroups->count() }}</span>
            </div>

            @forelse($myGroups as $group)
                <div class="grp-card" data-name="{{ strtolower($group->name) }}" data-desc="{{ strtolower($group->description ?? '') }}">
                    @if($group->unread_count > 0)
                        <div class="grp-unread">{{ $group->unread_count > 99 ? '99+' : $group->unread_count }}</div>
                    @endif

                    <div class="grp-avatar">
                        @if($group->group_image)
                            <img src="{{ $group->group_image }}" alt="{{ $group->name }}">
                        @else
                            {{ strtoupper(substr($group->name, 0, 1)) }}
                        @endif
                    </div>

                    <div class="grp-card-body">
                        <a href="{{ route('groups.show', $group->id) }}">
                            <div class="grp-title" title="{{ $group->name }}">{{ $group->name }}</div>
                            <div class="grp-meta">
                                <i class="fas fa-users" style="font-size:.75em;"></i>
                                {{ $group->members_count }} {{ $group->members_count == 1 ? 'member' : 'members' }}
                                @if($group->privacy === 'private')
                                    <span class="dot">·</span>
                                    <i class="fas fa-lock" style="font-size:.72em;"></i> Private
                                @endif
                            </div>
                            @if(isset($group->last_message) && $group->last_message)
                                <div class="grp-last-msg">
                                    <span class="grp-last-msg-text">
                                        <strong>{{ Str::limit($group->last_message['sender'], 12) }}:</strong>
                                        {{ Str::limit($group->last_message['text'], 40) }}
                                    </span>
                                    <span class="grp-last-msg-time">{{ $group->last_message['time'] }}</span>
                                </div>
                            @endif
                        </a>
                    </div>

                    <div class="grp-card-actions">
                        <button class="grp-btn grp-btn-teal" onclick="openAddMembersModal({{ $group->id }}, '{{ addslashes($group->name) }}')">
                            <i class="fas fa-user-plus"></i> Add
                        </button>
                        <button class="grp-btn grp-btn-purple" onclick="openEditModal({{ $group->id }}, '{{ addslashes($group->name) }}', '{{ addslashes($group->description ?? '') }}', '{{ $group->privacy }}')">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="grp-btn grp-btn-red" onclick="deleteGroup({{ $group->id }})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="grp-empty">
                    <i class="fas fa-folder-open"></i>
                    <p>You haven't created any groups yet.</p>
                </div>
            @endforelse
        </div>

        {{-- Groups I'm In --}}
        @if($memberGroups->count() > 0)
        <div class="grp-section">
            <div class="grp-section-label">
                <span class="lbl-icon"><i class="fas fa-check"></i></span>
                Groups I'm In
                <span class="lbl-count">{{ $memberGroups->count() }}</span>
            </div>

            @foreach($memberGroups as $group)
                <div class="grp-card" data-name="{{ strtolower($group->name) }}" data-desc="{{ strtolower($group->description ?? '') }}">
                    @if($group->unread_count > 0)
                        <div class="grp-unread">{{ $group->unread_count > 99 ? '99+' : $group->unread_count }}</div>
                    @endif

                    <div class="grp-avatar">
                        @if($group->group_image)
                            <img src="{{ $group->group_image }}" alt="{{ $group->name }}">
                        @else
                            {{ strtoupper(substr($group->name, 0, 1)) }}
                        @endif
                    </div>

                    <div class="grp-card-body">
                        <a href="{{ route('groups.show', $group->id) }}">
                            <div class="grp-title" title="{{ $group->name }}">{{ $group->name }}</div>
                            <div class="grp-meta">
                                <i class="fas fa-users" style="font-size:.75em;"></i>
                                {{ $group->members_count }} {{ $group->members_count == 1 ? 'member' : 'members' }}
                                @if($group->privacy === 'private')
                                    <span class="dot">·</span>
                                    <i class="fas fa-lock" style="font-size:.72em;"></i> Private
                                @endif
                            </div>
                            @if(isset($group->last_message) && $group->last_message)
                                <div class="grp-last-msg">
                                    <span class="grp-last-msg-text">
                                        <strong>{{ Str::limit($group->last_message['sender'], 12) }}:</strong>
                                        {{ Str::limit($group->last_message['text'], 40) }}
                                    </span>
                                    <span class="grp-last-msg-time">{{ $group->last_message['time'] }}</span>
                                </div>
                            @endif
                        </a>
                    </div>

                    <div class="grp-card-actions">
                        <button class="grp-btn grp-btn-grey" disabled>
                            <i class="fas fa-check"></i> Joined
                        </button>
                        <button class="grp-btn grp-btn-red" onclick="leaveGroup({{ $group->id }})">
                            <i class="fas fa-sign-out-alt"></i> Leave
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
        @endif

        {{-- Discover Groups --}}
        <div class="grp-section">
            <div class="grp-section-label">
                <span class="lbl-icon"><i class="fas fa-compass"></i></span>
                Discover Groups
                <span class="lbl-count">{{ $otherGroups->count() }}</span>
            </div>

            @forelse($otherGroups as $group)
                @if($loop->first)<div class="grp-discover-grid">@endif

                <div class="grp-discover-card" data-name="{{ strtolower($group->name) }}" data-desc="{{ strtolower($group->description ?? '') }}">
                    <div class="grp-discover-banner">
                        @if($group->group_image)
                            <img src="{{ $group->group_image }}" alt="{{ $group->name }}">
                        @else
                            {{ strtoupper(substr($group->name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="grp-discover-body">
                        <div class="grp-discover-name" title="{{ $group->name }}">{{ $group->name }}</div>
                        <div class="grp-discover-desc">{{ $group->description ?: 'No description provided.' }}</div>
                        <div class="grp-discover-footer">
                            <span class="grp-discover-members">
                                <i class="fas fa-users"></i> {{ $group->members_count }}
                            </span>
                            <div style="display:flex;gap:6px;">
                                <button class="grp-btn grp-btn-teal" style="font-size:.72em;" onclick="openInfoModal({{ json_encode($group->name) }}, {{ json_encode($group->description) }}, {{ $group->members_count }}, {{ json_encode($group->privacy) }}, {{ json_encode($group->creator->name) }})">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                                @if($group->has_pending_request)
                                    <button class="grp-btn grp-btn-yellow" disabled>
                                        <i class="fas fa-clock"></i> Pending
                                    </button>
                                @else
                                    <button class="grp-btn grp-btn-green" onclick="joinGroup({{ $group->id }}, this)">
                                        <i class="fas fa-user-plus"></i> Join
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if($loop->last)</div>@endif
            @empty
                <div class="grp-empty">
                    <i class="fas fa-search"></i>
                    <p>No groups to discover right now.</p>
                </div>
            @endforelse
        </div>

    </div><!-- /grp-page -->

    {{-- Add Members Modal --}}
    <div class="grp-modal-overlay" id="addMembersModal">
        <div class="grp-modal">
            <div class="grp-modal-head">
                <h4><i class="fas fa-user-plus" style="color:#667eea;margin-right:8px;"></i> Add to <span id="addMembersGroupName"></span></h4>
                <button class="grp-modal-close" onclick="closeAddMembersModal()"><i class="fas fa-times"></i></button>
            </div>
            <input type="hidden" id="addMembersGroupId">
            <div class="grp-field">
                <label>Search Friends</label>
                <input type="text" id="friendSearch" placeholder="Type a name..." onkeyup="searchFriends()">
            </div>
            <div id="friendsList" style="max-height:340px;overflow-y:auto;">
                <p style="text-align:center;color:#aaa;padding:20px 0;">Loading...</p>
            </div>
            <button class="grp-submit" onclick="submitAddMembers()" style="margin-top:16px;">
                <i class="fas fa-user-plus"></i> Add Selected
            </button>
        </div>
    </div>

    {{-- Create / Edit Modal --}}
    <div class="grp-modal-overlay" id="groupModal">
        <div class="grp-modal">
            <div class="grp-modal-head">
                <h4 id="modalTitle"><i class="fas fa-layer-group" style="color:#667eea;margin-right:8px;"></i> Create Group</h4>
                <button class="grp-modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
            </div>
            <form id="groupForm">
                @csrf
                <input type="hidden" id="groupId" name="group_id">
                <div class="grp-field">
                    <label>Group Name *</label>
                    <input type="text" id="groupName" name="name" required maxlength="100" placeholder="Enter group name">
                </div>
                <div class="grp-field">
                    <label>Description</label>
                    <textarea id="groupDescription" name="description" rows="3" maxlength="500" placeholder="What's this group about?"></textarea>
                </div>
                <div class="grp-field">
                    <label>Privacy *</label>
                    <select id="groupPrivacy" name="privacy" required>
                        <option value="public">Public – Anyone can join</option>
                        <option value="private">Private – Requires approval</option>
                    </select>
                </div>
                <button type="submit" class="grp-submit" id="submitBtn">
                    <i class="fas fa-check"></i> <span id="submitText">Create Group</span>
                </button>
            </form>
        </div>
    </div>

    {{-- Info Modal --}}
    <div class="grp-modal-overlay" id="infoModal">
        <div class="grp-modal">
            <div class="grp-modal-head">
                <h4 id="infoGroupName" style="word-break:break-word;"></h4>
                <button class="grp-modal-close" onclick="closeInfoModal()"><i class="fas fa-times"></i></button>
            </div>
            <div class="grp-info-row">
                <div class="info-lbl">Description</div>
                <div class="info-val" id="infoDescription"></div>
            </div>
            <div class="grp-info-row">
                <div class="info-lbl">Members</div>
                <div class="info-val" id="infoMembers"></div>
            </div>
            <div class="grp-info-row">
                <div class="info-lbl">Privacy</div>
                <div class="info-val" id="infoPrivacy"></div>
            </div>
            <div class="grp-info-row">
                <div class="info-lbl">Created By</div>
                <div class="info-val" id="infoCreator"></div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('myjs/bar.js') }}"></script>
    <script src="{{ asset('myjs/allpost.js') }}"></script>
    <script src="{{ asset('myjs/mobilenavbar.js') }}"></script>
    <script src="{{ asset('myjs/searchuser.js') }}"></script>

    <script>
    const csrfToken = '{{ csrf_token() }}';

    document.addEventListener('DOMContentLoaded', function () {
        let isEdit = false;
        let selectedFriendIds = [];

        function el(id) { return document.getElementById(id); }

        /* ── Create modal ── */
        window.openCreateModal = function () {
            isEdit = false;
            el('modalTitle').innerHTML = '<i class="fas fa-layer-group" style="color:#667eea;margin-right:8px;"></i> Create Group';
            el('submitText').textContent = 'Create Group';
            el('groupForm').reset();
            el('groupId').value = '';
            el('groupModal').classList.add('show');
        };

        /* ── Edit modal ── */
        window.openEditModal = function (id, name, description, privacy) {
            isEdit = true;
            el('modalTitle').innerHTML = '<i class="fas fa-edit" style="color:#667eea;margin-right:8px;"></i> Edit Group';
            el('submitText').textContent = 'Save Changes';
            el('groupId').value = id;
            el('groupName').value = name;
            el('groupDescription').value = description || '';
            el('groupPrivacy').value = privacy;
            el('groupModal').classList.add('show');
        };

        window.closeModal = function () { el('groupModal').classList.remove('show'); };

        /* ── Info modal ── */
        window.openInfoModal = function (name, description, members, privacy, creator) {
            el('infoGroupName').textContent = name;
            el('infoDescription').textContent = description || 'No description.';
            el('infoMembers').textContent = members + ' member' + (members != 1 ? 's' : '');
            el('infoPrivacy').textContent = privacy === 'public' ? '🌐 Public' : '🔒 Private';
            el('infoCreator').textContent = creator;
            el('infoModal').classList.add('show');
        };
        window.closeInfoModal = function () { el('infoModal').classList.remove('show'); };

        /* ── Join ── */
        window.joinGroup = async function (groupId, button) {
            const orig = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            try {
                const res  = await fetch(`/groups/${groupId}/join`, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json' } });
                const data = await res.json();
                if (data.success) {
                    if (data.status === 'joined') {
                        button.className = 'grp-btn grp-btn-grey';
                        button.innerHTML = '<i class="fas fa-check"></i> Joined';
                    } else {
                        button.className = 'grp-btn grp-btn-yellow';
                        button.innerHTML = '<i class="fas fa-clock"></i> Pending';
                    }
                    alert(data.message);
                } else { alert(data.error || 'Failed'); button.disabled = false; button.innerHTML = orig; }
            } catch (e) { alert('Network error'); button.disabled = false; button.innerHTML = orig; }
        };

        /* ── Add members modal ── */
        window.openAddMembersModal = async function (groupId, groupName) {
            el('addMembersGroupId').value = groupId;
            el('addMembersGroupName').textContent = groupName;
            el('addMembersModal').classList.add('show');
            selectedFriendIds = [];
            el('friendsList').innerHTML = '<p style="text-align:center;color:#aaa;padding:20px 0;">Loading...</p>';
            try {
                const res  = await fetch('/messages/friends');
                const data = await res.json();
                if (data.friends && data.friends.length > 0) {
                    el('friendsList').innerHTML = data.friends.map(f => `
                        <div class="friend-item" onclick="toggleFriend(${f.id})">
                            <input type="checkbox" id="fr_${f.id}" value="${f.id}" style="width:17px;height:17px;cursor:pointer;" onclick="event.stopPropagation();toggleFriend(${f.id})">
                            <img src="${f.profileimg || '{{ asset('images/best3.png') }}'}" alt="">
                            <label for="fr_${f.id}">${f.name}<small>@${f.username}</small></label>
                        </div>`).join('');
                } else {
                    el('friendsList').innerHTML = '<p style="text-align:center;color:#aaa;padding:20px 0;">No friends found</p>';
                }
            } catch (e) { el('friendsList').innerHTML = '<p style="color:red;text-align:center;">Failed to load</p>'; }
        };
        window.closeAddMembersModal = function () { el('addMembersModal').classList.remove('show'); };

        window.toggleFriend = function (id) {
            const cb  = document.getElementById('fr_' + id);
            const idx = selectedFriendIds.indexOf(id);
            if (idx > -1) { selectedFriendIds.splice(idx, 1); if (cb) cb.checked = false; }
            else           { selectedFriendIds.push(id);      if (cb) cb.checked = true; }
        };

        window.searchFriends = function () {
            const q = el('friendSearch').value.toLowerCase();
            document.querySelectorAll('.friend-item').forEach(item => {
                item.style.display = item.textContent.toLowerCase().includes(q) ? 'flex' : 'none';
            });
        };

        window.submitAddMembers = async function () {
            if (!selectedFriendIds.length) { alert('Select at least one friend'); return; }
            const gid = el('addMembersGroupId').value;
            try {
                const res  = await fetch(`/groups/${gid}/members`, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json' }, body: JSON.stringify({ user_ids: selectedFriendIds }) });
                const data = await res.json();
                if (data.success) { alert(data.message); location.reload(); }
                else alert(data.error || 'Failed');
            } catch (e) { alert('Network error'); }
        };

        /* ── Delete ── */
        window.deleteGroup = async function (groupId) {
            if (!confirm('Delete this group? This cannot be undone.')) return;
            try {
                const res  = await fetch(`/groups/${groupId}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json' } });
                const data = await res.json();
                if (data.success) { alert(data.message); location.reload(); }
                else alert(data.error || 'Failed');
            } catch (e) { alert('Network error'); }
        };

        /* ── Leave ── */
        window.leaveGroup = async function (groupId) {
            if (!confirm('Leave this group?')) return;
            const userId = {{ $user->id ?? 'null' }};
            try {
                const res  = await fetch(`/groups/${groupId}/members/${userId}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json' } });
                const data = await res.json();
                if (data.success) { alert(data.message); location.reload(); }
                else alert(data.error || 'Failed');
            } catch (e) { alert('Network error'); }
        };

        /* ── Form submit ── */
        el('groupForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const btn  = el('submitBtn');
            const txt  = el('submitText');
            const orig = txt.textContent;
            btn.disabled = true;
            txt.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            const fd  = new FormData(this);
            const gid = el('groupId').value;
            try {
                const res  = await fetch(isEdit ? `/groups/${gid}` : '/groups', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'X-HTTP-Method-Override': isEdit ? 'PUT' : 'POST' },
                    body: JSON.stringify({ name: fd.get('name'), description: fd.get('description'), privacy: fd.get('privacy') })
                });
                const data = await res.json();
                if (data.success) { alert(data.message); location.reload(); }
                else alert(data.error || 'Something went wrong');
            } catch (e) { alert('Network error'); }
            finally { btn.disabled = false; txt.textContent = orig; }
        });

        /* ── Group search ── */
        window.handleGroupSearch = function (query) {
            const q = query.trim().toLowerCase();
            const clearBtn  = document.getElementById('grpSearchClear');
            const noResult  = document.getElementById('grpNoResult');
            const noTermEl  = document.getElementById('grpNoResultTerm');

            clearBtn.classList.toggle('visible', q.length > 0);

            const allCards = document.querySelectorAll('.grp-card, .grp-discover-card');
            let totalVisible = 0;

            allCards.forEach(card => {
                const name = card.dataset.name || '';
                const desc = card.dataset.desc || '';
                const match = !q || name.includes(q) || desc.includes(q);
                card.style.display = match ? '' : 'none';
                if (match) totalVisible++;
            });

            // Show/hide whole sections if all their cards are hidden
            document.querySelectorAll('.grp-section').forEach(section => {
                const visible = [...section.querySelectorAll('.grp-card, .grp-discover-card')]
                    .some(c => c.style.display !== 'none');
                section.style.display = (q && !visible) ? 'none' : '';
            });

            // Show "no result" banner
            if (q && totalVisible === 0) {
                noTermEl.textContent = query;
                noResult.style.display = 'block';
            } else {
                noResult.style.display = 'none';
            }
        };

        window.clearGroupSearch = function () {
            const input = document.getElementById('grpSearchInput');
            input.value = '';
            handleGroupSearch('');
            input.focus();
        };

        /* ── Close on backdrop click ── */
        document.querySelectorAll('.grp-modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', function (e) {
                if (e.target === this) this.classList.remove('show');
            });
        });
    });
    </script>

</body>
</html>
