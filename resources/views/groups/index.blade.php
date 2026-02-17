<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Groups - Supperage</title>
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Your Custom CSS -->

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <link rel="stylesheet" href="{{ asset('css/post.css') }}">

    <link rel="stylesheet" href="{{ asset('css/bar.css') }}">

    <link rel="stylesheet" href="{{ asset('css/finebtn.css') }}">

    <link rel="stylesheet" href="{{ asset('css/mobilenavbar.css') }}">

    <link rel="stylesheet" href="{{ asset('css/searchuser.css') }}">

<!-- google ads -->
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2892124627300694"
     crossorigin="anonymous"></script>


    
    <style>
        <style>
        .groups-container {
            max-width: 1200px;
            margin: 80px auto 20px;
            padding: 0 15px;
        }
        
        .groups-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .create-group-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .create-group-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }
        
        .groups-section {
            margin-bottom: 40px;
        }
        
        .section-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #333;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
        }
        
        .group-card {
            position: relative;
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        
        .group-card:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }
        
        .group-card-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .group-image {
            width: 60px;
            height: 60px;
            min-width: 60px;
            border-radius: 12px;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }
        
        .group-info {
            flex: 1;
            min-width: 0; /* ✅ CRITICAL for text-overflow to work */
        }
        
        .group-name {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
            /* ✅ FIX: Truncate long names */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .group-members {
            font-size: 14px;
            color: #666;
        }
        
        .group-actions {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .btn-group-action {
            padding: 8px 16px;
            border-radius: 20px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
            white-space: nowrap;
        }
        
        .btn-edit {
            background: #667eea;
            color: white;
        }
        
        .btn-info {
            background: #17a2b8;
            color: white;
        }
        
        .btn-join {
            background: #28a745;
            color: white;
        }
        
        .btn-joined {
            background: #6c757d;
            color: white;
            cursor: not-allowed;
        }
        
        .btn-pending {
            background: #ffc107;
            color: white;
            cursor: not-allowed;
        }
        
        .group-description {
            color: #555;
            font-size: 14px;
            margin-top: 10px;
            line-height: 1.5;
            /* ✅ FIX: Prevent description overflow */
            word-break: break-word;
        }
        
        /* ✅ NEW: Unread Badge */
        .unread-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            min-width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            padding: 0 6px;
        }
        
        /* ✅ NEW: Last Message Preview */
        .last-message {
            color: #666;
            font-size: 13px;
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 5px;
            /* ✅ CRITICAL: Prevent overflow */
            min-width: 0;
        }
        
        .last-message-text {
            flex: 1;
            /* ✅ FIX: Truncate long messages */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            min-width: 0;
        }
        
        .message-time {
            font-size: 11px;
            color: #999;
            white-space: nowrap;
            flex-shrink: 0;
        }
        
        .spinner-border-sm {
            width: 16px;
            height: 16px;
            border-width: 2px;
        }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            animation: fadeIn 0.3s;
        }
        
        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 15px;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideUp 0.3s;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .modal-title {
            font-size: 22px;
            font-weight: 700;
            color: #333;
        }
        
        .close-modal {
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: #999;
            line-height: 1;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }
        
        .form-control {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .form-select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
        }
        
        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
        
        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }
        
        .empty-state i {
            font-size: 60px;
            margin-bottom: 20px;
            opacity: 0.5;
        }
        
        @media (max-width: 768px) {
            .groups-container {
                margin-top: 60px;
            }
            
            .groups-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            
            .group-card-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .group-actions {
                width: 100%;
                justify-content: flex-start;
            }
            
            .unread-badge {
                top: 10px;
                right: 10px;
            }
        }
    </style>
</head>
<body>
    @extends('layouts.app')
    @section('seo_title', 'Groups - SupperAge | Join Communities')
    @section('seo_description', 'Join or create groups on SupperAge. Find communities that share your interests and connect with like-minded people.')




    <!-- Your groups  navbar content  -->
    @include('layouts.navbar')







@section('content')
    <div class="groups-container">
        {{-- Header --}}
        <div class="groups-header">
            <h1 style="font-size:32px; font-weight:700; color:#333;">
                <i class="fa fa-users"></i> Groups
            </h1>
            <button class="create-group-btn" onclick="openCreateModal()">
                <i class="fa fa-plus"></i> Create Group
            </button>
        </div>
        
        {{-- My Groups with Unread Count --}}
        <div class="groups-section">
            <div class="section-title">
                <i class="fa fa-user-circle"></i> My Groups ({{ $myGroups->count() }})
            </div>
            
            @forelse($myGroups as $group)
                <div class="group-card">
                    {{-- Unread Badge --}}
                    @if($group->unread_count > 0)
                        <div class="unread-badge">{{ $group->unread_count > 99 ? '99+' : $group->unread_count }}</div>
                    @endif
                    
                    <div class="group-card-header">
                        <a href="{{ route('groups.show', $group->id) }}" style="text-decoration:none; display:flex; align-items:center; gap:15px; flex:1; min-width:0;">
                            <div class="group-image">
                                @if($group->group_image)
                                    <img src="{{ $group->group_image }}" alt="{{ $group->name }}" style="width:100%; height:100%; border-radius:12px; object-fit:cover;">
                                @else
                                    {{ strtoupper(substr($group->name, 0, 1)) }}
                                @endif
                            </div>
                            <div class="group-info">
                                <div class="group-name" title="{{ $group->name }}">{{ $group->name }}</div>
                                <div class="group-members">
                                    <i class="fa fa-users"></i> {{ $group->members_count }} {{ $group->members_count == 1 ? 'member' : 'members' }}
                                </div>
                                
                                {{-- Last Message Preview --}}
                                @if(isset($group->last_message) && $group->last_message)
                                    <div class="last-message">
                                        <span class="last-message-text" title="{{ $group->last_message['sender'] }}: {{ $group->last_message['text'] }}">
                                            <strong>{{ Str::limit($group->last_message['sender'], 15) }}:</strong> 
                                            {{ $group->last_message['text'] }}
                                            <br>
                                        <span class="message-time">{{ $group->last_message['time'] }}</span>
                                        </span>
                                        
                                    </div>
                                @endif
                            </div>
                        </a>
                        <div class="group-actions">
                            <button class="btn-group-action" style="background:#17a2b8; color:white;" onclick="openAddMembersModal({{ $group->id }}, '{{ addslashes($group->name) }}')">
                                <i class="fa fa-user-plus"></i> Add
                            </button>
                            <button class="btn-group-action btn-edit" onclick="openEditModal({{ $group->id }}, '{{ addslashes($group->name) }}', '{{ addslashes($group->description ?? '') }}', '{{ $group->privacy }}')">
                                <i class="fa fa-edit"></i> Edit
                            </button>
                            <button class="btn-group-action" style="background:#dc3545; color:white;" onclick="deleteGroup({{ $group->id }})">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    @if($group->description)
                        <div class="group-description">{{ $group->description }}</div>
                    @endif
                </div>
            @empty
                <div class="empty-state">
                    <i class="fa fa-folder-open"></i>
                    <p>You haven't created any groups yet.</p>
                </div>
            @endforelse
        </div>
        
        {{-- Groups I'm In with Unread Count --}}
        @if($memberGroups->count() > 0)
        <div class="groups-section">
            <div class="section-title">
                <i class="fa fa-check-circle"></i> Groups I'm In ({{ $memberGroups->count() }})
            </div>
            
            @foreach($memberGroups as $group)
                <div class="group-card">
                    {{-- Unread Badge --}}
                    @if($group->unread_count > 0)
                        <div class="unread-badge">{{ $group->unread_count > 99 ? '99+' : $group->unread_count }}</div>
                    @endif
                    
                    <div class="group-card-header">
                        <a href="{{ route('groups.show', $group->id) }}" style="text-decoration:none; display:flex; align-items:center; gap:15px; flex:1; min-width:0;">
                            <div class="group-image">
                                @if($group->group_image)
                                    <img src="{{ $group->group_image }}" alt="{{ $group->name }}" style="width:100%; height:100%; border-radius:12px; object-fit:cover;">
                                @else
                                    {{ strtoupper(substr($group->name, 0, 1)) }}
                                @endif
                            </div>
                            <div class="group-info">
                                <div class="group-name" title="{{ $group->name }}">{{ $group->name }}</div>
                                <div class="group-members">
                                    <i class="fa fa-users"></i> {{ $group->members_count }} {{ $group->members_count == 1 ? 'member' : 'members' }}
                                </div>
                                
                                {{-- Last Message Preview --}}
                                @if(isset($group->last_message) && $group->last_message)
                                    <div class="last-message">
                                        <span class="last-message-text" title="{{ $group->last_message['sender'] }}: {{ $group->last_message['text'] }}">
                                            <strong>{{ Str::limit($group->last_message['sender'], 15) }}:</strong> 
                                            {{ $group->last_message['text'] }}<br>
                                            <span class="message-time">{{ $group->last_message['time'] }}</span>
                                        </span>
                                        
                                    </div>
                                @endif
                            </div>
                        </a>
                        <div class="group-actions">
                            <button class="btn-group-action btn-joined" disabled>
                                <i class="fa fa-check"></i> Joined
                            </button>
                            <button class="btn-group-action" style="background:#dc3545; color:white;" onclick="leaveGroup({{ $group->id }})">
                                <i class="fa fa-sign-out-alt"></i> Leave
                            </button>
                        </div>
                    </div>
                    @if($group->description)
                        <div class="group-description">{{ $group->description }}</div>
                    @endif
                </div>
            @endforeach
        </div>
        @endif
        
        {{-- Other Groups --}}
        <div class="groups-section">
            <div class="section-title">
                <i class="fa fa-globe"></i> Discover Groups ({{ $otherGroups->count() }})
            </div>
            
            @forelse($otherGroups as $group)
                <div class="group-card">
                    <div class="group-card-header">
                        <div class="group-image">
                            @if($group->group_image)
                                <img src="{{ $group->group_image }}" alt="{{ $group->name }}" style="width:100%; height:100%; border-radius:12px; object-fit:cover;">
                            @else
                                {{ strtoupper(substr($group->name, 0, 1)) }}
                            @endif
                        </div>
                        <div class="group-info">
                            <div class="group-name" title="{{ $group->name }}">{{ $group->name }}</div>
                            <div class="group-members">
                                <i class="fa fa-users"></i> {{ $group->members_count }} {{ $group->members_count == 1 ? 'member' : 'members' }}
                            </div>
                        </div>
                        <div class="group-actions">
                            <button class="btn-group-action btn-info" onclick="openInfoModal({{ json_encode($group->name) }}, {{ json_encode($group->description) }}, {{ $group->members_count }}, {{ json_encode($group->privacy) }}, {{ json_encode($group->creator->name) }})">
                                <i class="fa fa-info-circle"></i> Info
                            </button>
                            
                            @if($group->has_pending_request)
                                <button class="btn-group-action btn-pending" disabled>
                                    <i class="fa fa-clock"></i> Pending
                                </button>
                            @else
                                <button class="btn-group-action btn-join" onclick="joinGroup({{ $group->id }}, this)">
                                    <i class="fa fa-user-plus"></i> Join
                                </button>
                            @endif
                        </div>
                    </div>
                    @if($group->description)
                        <div class="group-description">{{ Str::limit($group->description, 150) }}</div>
                    @endif
                </div>
            @empty
                <div class="empty-state">
                    <i class="fa fa-search"></i>
                    <p>No groups to discover right now.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Add Members Modal --}}
    <div class="modal" id="addMembersModal">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add People to <span id="addMembersGroupName"></span></h4>
                <button class="close-modal" onclick="closeAddMembersModal()">&times;</button>
            </div>
            <div style="padding:20px 0;">
                <input type="hidden" id="addMembersGroupId">
                <div class="form-group">
                    <label class="form-label">Search Friends</label>
                    <input type="text" class="form-control" id="friendSearch" placeholder="Search friends..." onkeyup="searchFriends()">
                </div>
                <div id="friendsList" style="max-height:400px; overflow-y:auto; margin-top:20px;">
                    <p style="text-align:center; color:#999;">Loading friends...</p>
                </div>
                <button class="btn-submit" onclick="submitAddMembers()" style="margin-top:20px;">
                    <i class="fa fa-user-plus"></i> Add Selected Members
                </button>
            </div>
        </div>
    </div>

    {{-- Create/Edit Group Modal --}}
    <div class="modal" id="groupModal">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalTitle">Create New Group</h4>
                <button class="close-modal" onclick="closeModal()">&times;</button>
            </div>
            <form id="groupForm">
                @csrf
                <input type="hidden" id="groupId" name="group_id">
                
                <div class="form-group">
                    <label class="form-label">Group Name *</label>
                    <input type="text" class="form-control" id="groupName" name="name" required maxlength="100" placeholder="Enter group name">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" id="groupDescription" name="description" rows="4" maxlength="500" placeholder="Tell people what your group is about..."></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Privacy *</label>
                    <select class="form-select" id="groupPrivacy" name="privacy" required>
                        <option value="public">Public - Anyone can join</option>
                        <option value="private">Private - Requires approval</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fa fa-check"></i> <span id="submitText">Create Group</span>
                </button>
            </form>
        </div>
    </div>

    {{-- Info Modal --}}
    <div class="modal" id="infoModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="infoGroupName">Group Info</h2>
                <button class="close-modal" onclick="closeInfoModal()">&times;</button>
            </div>
            <div style="padding:20px 0;">
                <div style="margin-bottom:15px;">
                    <strong style="color:#667eea;">Description:</strong>
                    <p id="infoDescription" style="margin-top:8px; color:#555; line-height:1.6;"></p>
                </div>
                <div style="margin-bottom:15px;">
                    <strong style="color:#667eea;">Members:</strong>
                    <p id="infoMembers" style="margin-top:8px; color:#555;"></p>
                </div>
                <div style="margin-bottom:15px;">
                    <strong style="color:#667eea;">Privacy:</strong>
                    <p id="infoPrivacy" style="margin-top:8px; color:#555;"></p>
                </div>
                <div>
                    <strong style="color:#667eea;">Created By:</strong>
                    <p id="infoCreator" style="margin-top:8px; color:#555;"></p>
                </div>
            </div>
        </div>
    </div>


<!-- Load jQuery first -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="{{ asset('myjs/bar.js') }}"></script> <!-- this should come last -->

<script src="{{ asset('myjs/more_lesstext.js') }}"></script>
<script src="{{ asset('myjs/menu_pop_up_post.js') }}"></script>

<script src="{{ asset('myjs/allpost.js') }}"></script>
<script src="{{ asset('myjs/tales.js') }}"></script>
<script src="{{ asset('myjs/mobilenavbar.js') }}"></script>
<script src="{{ asset('myjs/searchuser.js') }}"></script>

<script>

    // Define the global CSRF token variable for JavaScript usage
    const csrfToken = '{{ csrf_token() }}';
    // ✅ FIX: Wait for DOM to load before accessing elements
        document.addEventListener('DOMContentLoaded', function() {
            console.log('✅ DOM fully loaded');
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            let isEdit = false;
            let selectedFriendIds = [];
            
            // ✅ SAFE: Check if element exists before accessing
            function safeGetElement(id) {
                const element = document.getElementById(id);
                if (!element) {
                    console.warn(`⚠️ Element with id "${id}" not found`);
                }
                return element;
            }
            
            // Make functions global
            window.openCreateModal = function() {
                isEdit = false;
                const modalTitle = safeGetElement('modalTitle');
                const submitText = safeGetElement('submitText');
                const groupForm = safeGetElement('groupForm');
                const groupId = safeGetElement('groupId');
                const groupModal = safeGetElement('groupModal');
                
                if (modalTitle) modalTitle.textContent = 'Create New Group';
                if (submitText) submitText.textContent = 'Create Group';
                if (groupForm) groupForm.reset();
                if (groupId) groupId.value = '';
                if (groupModal) groupModal.classList.add('show');
            };
            
            window.openEditModal = function(id, name, description, privacy) {
                isEdit = true;
                const modalTitle = safeGetElement('modalTitle');
                const submitText = safeGetElement('submitText');
                const groupId = safeGetElement('groupId');
                const groupName = safeGetElement('groupName');
                const groupDescription = safeGetElement('groupDescription');
                const groupPrivacy = safeGetElement('groupPrivacy');
                const groupModal = safeGetElement('groupModal');
                
                if (modalTitle) modalTitle.textContent = 'Edit Group';
                if (submitText) submitText.textContent = 'Update Group';
                if (groupId) groupId.value = id;
                if (groupName) groupName.value = name;
                if (groupDescription) groupDescription.value = description || '';
                if (groupPrivacy) groupPrivacy.value = privacy;
                if (groupModal) groupModal.classList.add('show');
            };
            
            window.closeModal = function() {
                const groupModal = safeGetElement('groupModal');
                if (groupModal) groupModal.classList.remove('show');
            };
            
            window.openInfoModal = function(name, description, members, privacy, creator) {
                const modal = safeGetElement('infoModal');
                if (modal) {
                    const infoGroupName = safeGetElement('infoGroupName');
                    const infoDescription = safeGetElement('infoDescription');
                    const infoMembers = safeGetElement('infoMembers');
                    const infoPrivacy = safeGetElement('infoPrivacy');
                    const infoCreator = safeGetElement('infoCreator');
                    
                    if (infoGroupName) infoGroupName.textContent = name;
                    if (infoDescription) infoDescription.textContent = description || 'No description';
                    if (infoMembers) infoMembers.textContent = `${members} member${members != 1 ? 's' : ''}`;
                    if (infoPrivacy) infoPrivacy.textContent = privacy === 'public' ? 'Public' : 'Private';
                    if (infoCreator) infoCreator.textContent = creator;
                    
                    modal.classList.add('show');
                }
            };
            
            window.closeInfoModal = function() {
                const modal = safeGetElement('infoModal');
                if (modal) modal.classList.remove('show');
            };
            
            window.joinGroup = async function(groupId, button) {
                const originalHTML = button.innerHTML;
                button.disabled = true;
                button.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Joining...';
                
                try {
                    const response = await fetch(`/groups/${groupId}/join`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        if (data.status === 'joined') {
                            button.className = 'btn-group-action btn-joined';
                            button.innerHTML = '<i class="fa fa-check"></i> Joined';
                            alert(data.message);
                        } else if (data.status === 'pending') {
                            button.className = 'btn-group-action btn-pending';
                            button.innerHTML = '<i class="fa fa-clock"></i> Pending';
                            alert(data.message);
                        }
                    } else {
                        alert(data.error || 'Failed to join');
                        button.disabled = false;
                        button.innerHTML = originalHTML;
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Failed to join group');
                    button.disabled = false;
                    button.innerHTML = originalHTML;
                }
            };
            
            window.openAddMembersModal = async function(groupId, groupName) {
                const modal = safeGetElement('addMembersModal');
                const groupIdInput = safeGetElement('addMembersGroupId');
                const groupNameSpan = safeGetElement('addMembersGroupName');
                
                if (groupIdInput) groupIdInput.value = groupId;
                if (groupNameSpan) groupNameSpan.textContent = groupName;
                if (modal) modal.classList.add('show');
                
                selectedFriendIds = [];
                
                try {
                    const response = await fetch('/messages/friends');
                    const data = await response.json();
                    
                    const friendsList = safeGetElement('friendsList');
                    if (friendsList && data.friends && data.friends.length > 0) {
                        friendsList.innerHTML = data.friends.map(friend => `
                            <div class="friend-select-item" style="padding:12px; border-bottom:1px solid #f0f0f0; display:flex; align-items:center; gap:12px;">
                                <input type="checkbox" id="friend_${friend.id}" value="${friend.id}" onchange="toggleFriendSelection(${friend.id})" style="width:18px; height:18px; cursor:pointer;">
                                <img src="${friend.profileimg || '{{ asset('images/best3.png') }}'}" style="width:40px; height:40px; border-radius:50%; object-fit:cover;">
                                <label for="friend_${friend.id}" style="cursor:pointer; flex:1;">
                                    ${friend.name}
                                    <small style="display:block; color:#666; font-size:12px;">@${friend.username}</small>
                                </label>
                            </div>
                        `).join('');
                    } else if (friendsList) {
                        friendsList.innerHTML = '<p style="text-align:center; color:#999; padding:40px 0;">No friends</p>';
                    }
                } catch (error) {
                    console.error('Error:', error);
                    const friendsList = safeGetElement('friendsList');
                    if (friendsList) {
                        friendsList.innerHTML = '<p style="text-align:center; color:#ff0000;">Failed to load</p>';
                    }
                }
            };
            
            window.closeAddMembersModal = function() {
                const modal = safeGetElement('addMembersModal');
                if (modal) modal.classList.remove('show');
            };
            
            window.toggleFriendSelection = function(friendId) {
                const index = selectedFriendIds.indexOf(friendId);
                if (index > -1) {
                    selectedFriendIds.splice(index, 1);
                } else {
                    selectedFriendIds.push(friendId);
                }
            };
            
            window.searchFriends = function() {
                const searchInput = safeGetElement('friendSearch');
                const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
                const items = document.querySelectorAll('.friend-select-item');
                
                items.forEach(item => {
                    const text = item.textContent.toLowerCase();
                    item.style.display = text.includes(searchTerm) ? 'flex' : 'none';
                });
            };
            
            window.submitAddMembers = async function() {
                if (selectedFriendIds.length === 0) {
                    alert('Please select at least one friend');
                    return;
                }
                
                const groupIdInput = safeGetElement('addMembersGroupId');
                const groupId = groupIdInput ? groupIdInput.value : null;
                
                if (!groupId) {
                    alert('Group ID not found');
                    return;
                }
                
                try {
                    const response = await fetch(`/groups/${groupId}/members`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ user_ids: selectedFriendIds })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert(data.error || 'Failed');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Failed to add members');
                }
            };
            
            window.deleteGroup = async function(groupId) {
                if (!confirm('Delete this group? This cannot be undone.')) return;
                
                try {
                    const response = await fetch(`/groups/${groupId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert(data.error || 'Failed');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Failed to delete');
                }
            };
            
            window.leaveGroup = async function(groupId) {
                if (!confirm('Leave this group?')) return;
                
                const userId = {{ $user->id ?? 'null' }};
                if (!userId) {
                    alert('User ID not found');
                    return;
                }
                
                try {
                    const response = await fetch(`/groups/${groupId}/members/${userId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert(data.error || 'Failed');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Failed to leave');
                }
            };
            
            // Form submission
            const groupForm = safeGetElement('groupForm');
            if (groupForm) {
                groupForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const submitBtn = safeGetElement('submitBtn');
                    const submitText = safeGetElement('submitText');
                    const originalText = submitText ? submitText.textContent : 'Create Group';
                    
                    if (submitBtn) submitBtn.disabled = true;
                    if (submitText) submitText.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Processing...';
                    
                    const formData = new FormData(this);
                    const groupIdInput = safeGetElement('groupId');
                    const groupId = groupIdInput ? groupIdInput.value : '';
                    
                    const url = isEdit ? `/groups/${groupId}` : '/groups';
                    const method = isEdit ? 'PUT' : 'POST';
                    
                    try {
                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Content-Type': 'application/json',
                                'X-HTTP-Method-Override': method
                            },
                            body: JSON.stringify({
                                name: formData.get('name'),
                                description: formData.get('description'),
                                privacy: formData.get('privacy')
                            })
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            alert(data.message);
                            location.reload();
                        } else {
                            alert(data.error || 'Something went wrong');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Failed to save');
                    } finally {
                        if (submitBtn) submitBtn.disabled = false;
                        if (submitText) submitText.textContent = originalText;
                    }
                });
            }
            
            // Close modals on outside click
            window.onclick = function(event) {
                if (event.target.classList && event.target.classList.contains('modal')) {
                    event.target.classList.remove('show');
                }
            };
            
            console.log('✅ All event listeners attached successfully');
        });
</script>
@endsection

</body>
</html>