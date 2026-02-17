{{-- Reusable "People you may want to follow" section --}}
<div class="suggestions-section">
    <div class="suggestions-section-header">
        <i class="fas fa-user-plus"></i>
        <h5>People you may want to follow</h5>
    </div>

    @forelse($otherUsers as $person)
        @php
            $loginSession = $person->lastLoginSession;

            if ($loginSession) {
                $isOnline = is_null($loginSession->logout_at)
                    && $loginSession->updated_at
                    && \Carbon\Carbon::parse($loginSession->updated_at)->isAfter(now()->subMinutes(30));

                if ($isOnline) {
                    $lastSeen = null;
                } else {
                    if ($loginSession->logout_at) {
                        $lastSeen = \Carbon\Carbon::parse($loginSession->logout_at)->diffForHumans();
                    } else {
                        $lastSeen = $loginSession->updated_at
                            ? \Carbon\Carbon::parse($loginSession->updated_at)->diffForHumans()
                            : 'recently';
                    }
                }
            } else {
                $isOnline = false;
                $lastSeen = 'never logged in';
            }
        @endphp

        <div class="sg-card" id="sg-card-{{ $person->id }}">
            <a href="{{ route('profile.show', $person->id) }}" class="sg-avatar">
                @if($person->profileimg)
                    <img src="{{ $person->profileimg }}" alt="{{ $person->name }}" class="sg-avatar-img">
                @else
                    <div class="sg-placeholder">
                        <i class="fa fa-user"></i>
                    </div>
                @endif
                @if($person->badge_status)
                    <img src="{{ asset($person->badge_status) }}" alt="Verified" title="Verified User" class="sg-badge-icon">
                @endif
                @if($isOnline)
                    <span class="sg-online-dot" title="Online now"></span>
                @endif
            </a>

            <div class="sg-info">
                <p class="sg-name">
                    <a href="{{ route('profile.show', $person->id) }}">{{ $person->name }}</a>
                </p>
                <p class="sg-meta">
                    <span class="sg-follower-count" id="sg-count-{{ $person->id }}">{{ $person->followers_count }}</span>
                    {{ Str::plural('follower', $person->followers_count) }}
                    @if(!$isOnline)
                        <span class="sg-last-seen">{{ $lastSeen }}</span>
                    @else
                        <span class="sg-last-seen" style="color: #31a24c;">Online now</span>
                    @endif
                </p>
            </div>

            <button class="sg-follow-btn"
                    data-user-id="{{ $person->id }}"
                    data-url="{{ route('follow', $person->id) }}"
                    data-person-name="{{ $person->name }}"
                    onclick="sgFollowUser(this)">
                Follow
            </button>
        </div>
    @empty
        <div class="sg-empty">
            <i class="fas fa-users"></i>
            <p>No suggestions right now.</p>
        </div>
    @endforelse

    @if($otherUsers->count() >= 5)
        <div class="sg-view-all">
            <a href="{{ route('people.suggestions') }}">View All Suggestions</a>
        </div>
    @endif
</div>
