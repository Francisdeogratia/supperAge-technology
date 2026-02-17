<style>
  .comments-scroll::-webkit-scrollbar {
    width: 6px;
  }
  .comments-scroll::-webkit-scrollbar-thumb {
    background-color: #ccc;
    border-radius: 3px;
  }
</style>


<div class="comments mt-3">
  <h6>💬 Comments ({{ $post->comments->count() }})</h6>

  <div class="comments-scroll" style="
    max-height: {{ $post->comments->count() >= 10 ? '400px' : 'auto' }};
    overflow-y: {{ $post->comments->count() >= 10 ? 'scroll' : 'visible' }};
    padding-right: 10px; 
  ">
      @foreach($post->comments as $comment)
        <div class="d-flex mb-2">
            <a href="{{ route('profile.show', $comment->user->id) }}" style="text-decoration: none;">
          <img src="{{ $comment->user->profileimg ?? asset('images/default.png') }}" 
               class="rounded-circle me-2" style="width:35px;height:35px;">
               </a>
          <div style="position:relative;">
            <strong class="ml-2">{{ $comment->user->name ?? 'Unknown' }}</strong>
            @if($comment->user->badge_status)
        <img src="{{ asset($comment->user->badge_status) }}" 
             alt="Verified" 
             title="Verified User" 
             style="width:20px;height:20px;position:absolute;">
    @endif
    <br>
            <span class="text-muted">{{ '@'.($comment->user->username ?? 'deleted') }}</span><br>
            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
            <p class="mb-1">{{ $comment->comment }}</p>

            {{-- Reply form --}}
            <form method="POST" action="{{ route('posts.comment', $post->id) }}" 
      class="comment-form d-flex" 
      data-post-id="{{ $post->id }}" 
      data-parent-id="{{ $comment->id }}">
  @csrf
  <input type="text" name="comment" placeholder="Reply..." 
         class="form-control form-control-sm me-1" required>
  <button type="submit" class="btn btn-sm btn-outline-secondary">↩</button>
</form>


            {{-- Replies --}}
            @foreach($comment->replies as $reply)
              <div class="d-flex ms-4 mt-2">
                <a href="{{ route('profile.show', $reply->user->id) }}" style="text-decoration: none;">
                <img src="{{ $reply->user->profileimg ?? asset('images/default.png') }}" 
                     class="rounded-circle me-2" style="width:30px;height:30px;">
                     </a>
                <div>
                  <strong class="ml-2">{{ $reply->user->name ?? 'Unknown' }}</strong>
                  @if($reply->user->badge_status)
        <img src="{{ asset($reply->user->badge_status) }}" 
             alt="Verified" 
             title="Verified User" 
             style="width:20px;height:20px;position:absolute;">
    @endif
    <br>
                  <span class="text-muted" style="font-size:12px;">{{ '@'.($reply->user->username ?? 'deleted') }}</span><br>
                  <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                  <p class="mb-1">{{ $reply->comment }}</p>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      @endforeach
      </div>
    </div>
  