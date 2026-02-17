@foreach($tasks as $task)
@php
  $isExpired = $expired ?? false;
  $creator = \App\Models\UserRecord::find($task->creator_id);
  $completed = DB::table('task_user')->where('task_id', $task->id)->count();
    $remaining = max(0, $task->target - $completed);
  $remainingBudget = max(0, $remaining * $task->price);

  $labels = [
    'visit_site' => '🌐 Visit Site & earn',
    'install_app' => '📲 Install App & earn',
    'watch_video' => '🎥 Watch Video & earn',
    'learn_more' => '📘 Learn More & earn',
  ];
@endphp

@php
  $fileUrl = null;
  $isVideo = false;

  if ($task->post_id) {
      $post = \App\Models\SamplePost::find($task->post_id);
      $fileArray = json_decode($post->file_path ?? '[]', true);
      $fileUrl = is_array($fileArray) && count($fileArray) ? $fileArray[0] : null;
      $isVideo = $fileUrl && \Illuminate\Support\Str::endsWith($fileUrl, ['.mp4', '.mov', '.webm']);
  } elseif ($task->task_type === 'tales_sharing') {
      $tale = \App\Models\TalesExten::where('specialcode', $task->specialcode)->latest('created_at')->first();
      $fileUrl = $tale ? $tale->files_talesexten : null;
      $isVideo = $fileUrl && \Illuminate\Support\Str::endsWith($fileUrl, ['.mp4', '.mov', '.webm']);
  }
@endphp

@php
  $loginSession = $creator->lastLoginSession ?? null;
  $isOnline = $loginSession && $loginSession->logout_at === null;
  $lastSeen = $loginSession && $loginSession->logout_at
      ? \Carbon\Carbon::parse($loginSession->logout_at)->diffForHumans()
      : 'Online now';
@endphp


<span class="countdown-badge {{ $isExpired ? 'bg-secondary' : 'bg-danger' }}" style="border-radius: 11px;"
      id="countdown-{{ $task->id }}"
      data-expiry="{{ \Carbon\Carbon::parse($task->created_at)->addDays($task->duration)->format('Y-m-d H:i:s') }}">
    ⏳ {{ $isExpired ? 'Task expired' : 'Expires in: calculating...' }}
</span>

<div class="card mb-3 shadow-sm">
  <div class="card-body">
    <div class="d-flex align-items-center mb-2">
      <img src="{{ str_replace('/upload/', '/upload/w_40,h_40,c_fill,r_max,q_70/', $creator->profileimg) }}"
           alt="Creator Image"
           class="rounded-circle mr-2"
           style="width: 40px; height: 40px;">
      <strong>{{ $creator->username }}</strong>
      &nbsp;<small style="font-size: small;font-weight:bold;color:blue;">{{ $isOnline ? 'Online now' : 'Last seen : ' . $lastSeen }}</small>
    </div>

    <strong class="d-block">{{ ucfirst(str_replace('_', ' ', $task->task_type)) }}</strong>
    <p class=" text-info"><strong>Completed by:</strong> {{ $completed }} users</p>
    <small class="d-block text-muted mt-0">{{ $task->task_content }}</small>
    @if($fileUrl)
  <div class="mb-2">
    @if($isVideo)
      <video controls style="max-width: 100%; border-radius: 10px;">
        <source src="{{ $fileUrl }}" type="video/mp4">
        Your browser does not support the video tag.
      </video>
    @else
      <img src="{{ str_replace('/upload/', '/upload/w_400,h_250,c_fill,q_auto,f_auto/', $fileUrl) }}"
           alt="Task Image"
           class="img-fluid rounded mb-2">
    @endif
  </div>
@endif


    <div class="row mt-2">
      <div class="col-12 col-md-8">
        <div>
          <span class="bg-info">💰You Earn {{ $task->price }} {{ $task->currency }}</span>
          <span class="bg-secondary">🎯 Target: {{ $task->target ?? 'N/A' }}</span>
          <span class="bg-dark text-white">💼 Budget: {{ $task->total_budget }} {{ $task->currency }}</span>
          <span class="bg-success">✅ Completed: {{ $completed }} / {{ $task->target }}</span>
          @if(!$task->is_active || $remaining === 0)
    <span class="badge bg-info">🚫 Task Closed</span>
@else
    <span class="bg-warning">⏳ Remaining: {{ $remaining }}</span>
    <span class="bg-info">💸 Remaining Budget: {{ number_format($remainingBudget, 2) }} {{ $task->currency }}</span>
@endif

          <span class="bg-light text-dark">⏳ Duration: {{ $task->duration }} day(s)</span>
        </div>
      </div>

      <div class="col-12 col-md-4 mt-2 mt-md-0">
        @if(in_array($task->id, $completedTaskIds))
  <span class="badge bg-success">✅ You’ve already earned</span>

       @elseif(in_array($task->task_type, array_keys($labels)))
  @if($remaining > 0 && $task->is_active)
    <a href="{{ route('tasks.visit', $task->id) }}"
       target="_blank"
       class="btn btn-sm btn-warning w-100 task-action-btn"
       data-task-id="{{ $task->id }}"
       title="">
       {{ $labels[$task->task_type] }}
    </a>
  @else
    <button class="btn btn-sm btn-secondary w-100" disabled>
      🚫 Task Closed
    </button>
  @endif

        @elseif($task->task_type === 'tales_sharing')
          @php
            $taleId = \App\Models\TalesExten::where('specialcode', $task->specialcode)->latest('created_at')->value('tales_id');
          @endphp
          @if($taleId)
            <a href="{{ route('view.tale', ['id' => $taleId]) }}?task_id={{ $task->id }}"
               class="btn btn-sm btn-primary w-100 task-action-btn"
               data-task-id="{{ $task->id }}"
               title="">
               📖 View Tale & earn
            </a>
          @else
            <span class="bg-danger">No Tale Found</span>
          @endif
        @elseif($task->task_type === 'post_promotion')
          @php
            $postId = DB::table('sample_posts')->where('specialcode', $task->specialcode)->latest('created_at')->value('id');
          @endphp
          @if($postId)
            <a href="{{ route('posts.show', ['id' => $postId]) }}?task_id={{ $task->id }}"
               class="btn btn-sm btn-success w-100 task-action-btn"
               data-task-id="{{ $task->id }}"
               title="">
               📰 Read Post & earn
            </a>
          @else
            <span class="bg-danger">No Post Found</span>
          @endif
        @elseif($task->task_type === 'follow_me')
          <form method="POST" action="{{ route('follow.user', ['id' => $creator->id]) }}" class="follow-form">
            @csrf
            <input type="hidden" name="task_id" value="{{ $task->id }}">
            <button type="submit"
                    class="btn btn-primary btn-sm w-100 follow-btn task-action-btn"
                    data-task-id="{{ $task->id }}"
                    title="">
              Follow {{ $creator->username }} and earn
            </button>
          </form>
          <div class="loading-spinner text-center mt-2" style="display:none;">
        <div class="spinner-border text-success" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
        @elseif($task->task_type === 'engagement_post')
  @php
    $postId = $task->post_id;
  @endphp
  @if($postId)
    <a href="{{ route('tasks.engage', ['id' => $postId, 'task_id' => $task->id]) }}"
       class="btn btn-sm btn-info w-100 task-action-btn"
       data-task-id="{{ $task->id }}"
       title="">
       👍 Engage with Post & earn
    </a>
  @else
    <span class="bg-danger">No Post Found</span>
  @endif
@endif
      </div>
    </div>
  </div>
</div>
@endforeach
