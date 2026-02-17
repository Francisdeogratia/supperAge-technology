document.addEventListener('DOMContentLoaded', () => {
  const video = document.getElementById('customVideo');
  const playPauseBtn = document.getElementById('playPauseBtn');
  const spinner = document.getElementById('videoSpinner');

  // Hide spinner when video is ready
  if (video) {
  // Hide spinner when video is ready
  if (video.readyState >= 2) {
    spinner.classList.add('hidden');
  } else {
    video.addEventListener('loadeddata', () => {
      spinner.classList.add('hidden');
    });
  }

  // Toggle play/pause
  playPauseBtn.addEventListener('click', () => {
    if (video.paused) {
      video.play();
      playPauseBtn.textContent = '⏸';
    } else {
      video.pause();
      playPauseBtn.textContent = '▶';
    }
  });

  // Update icon if user uses native controls or autoplay
  video.addEventListener('play', () => {
    playPauseBtn.textContent = '⏸';
  });

  video.addEventListener('pause', () => {
    playPauseBtn.textContent = '▶';
  });

  // Show play/pause briefly on video click
  video.addEventListener('click', () => {
    playPauseBtn.style.opacity = '1';
    setTimeout(() => {
      playPauseBtn.style.opacity = '0';
    }, 2000);
  });
}

// submit comments
  
  // const taleId = {{ $tale->tales_id }};

  $('#sendCommentBtn').on('click', function () {
    const comment = $('#commentInput').val().trim();
    const endpoint = `/tales/${taleId}/comment`;

    if (!comment) {
      alert('Comment cannot be empty!');
      return;
    }

    $.ajax({
      url: endpoint,
      type: 'POST',
      data: {
        comment: comment
      },
      success: function (response) {
         const newComment = `<div class="comment-item"><strong>${currentUsername}</strong>: ${comment}</div>`;
        $('#commentsList').prepend(newComment);
        $('#commentInput').val('');
        $('#errorMsg').hide();
         // ✅ Update comment count
      const currentCount = parseInt($('#commentCount').text());
      $('#commentCount').text(currentCount + 1);
      },
      error: function (xhr) {
        if (xhr.status === 422 && xhr.responseJSON?.error) {
          $('#errorMsg').text(xhr.responseJSON.error).show();
        } else {
          console.error('Comment failed:', xhr);
          alert('Something went wrong. Try again.');
        }
      }
    });
  });






});
