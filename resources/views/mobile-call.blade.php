<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
<title>SupperAge Call</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
html,body{width:100%;height:100%;background:#000;overflow:hidden}
#remote{position:fixed;top:0;left:0;width:100%;height:100%;background:#1a1a2e;display:flex;align-items:center;justify-content:center}
#local{position:fixed;bottom:130px;right:12px;width:100px;height:140px;border-radius:12px;overflow:hidden;border:2px solid rgba(255,255,255,0.75);background:#333;z-index:10}
#status{color:rgba(255,255,255,0.7);font-size:15px;font-family:sans-serif;text-align:center;padding:10px}
</style>
</head>
<body>
<div id="remote"><div id="status">Connecting...</div></div>
<div id="local"></div>

<script src="https://download.agora.io/sdk/release/AgoraRTC_N-4.22.0.js"></script>
<script>
var p       = new URLSearchParams(location.search);
var APP_ID  = p.get('appId')  || '';
var TOKEN   = p.get('token')  || '';
var CHANNEL = p.get('channel')|| '';
var UID     = parseInt(p.get('uid') || '0');
var CTYPE   = p.get('callType') || 'audio';

var client = null, audioTrack = null, videoTrack = null;

function post(data) {
  if (window.ReactNativeWebView) {
    window.ReactNativeWebView.postMessage(JSON.stringify(data));
  }
}

function setStatus(msg) {
  var el = document.getElementById('status');
  if (el) el.textContent = msg;
}

async function join() {
  try {
    client = AgoraRTC.createClient({ mode: 'rtc', codec: 'vp8' });

    client.on('user-published', async function(user, mediaType) {
      await client.subscribe(user, mediaType);
      if (mediaType === 'video' && user.videoTrack) {
        document.getElementById('status').style.display = 'none';
        user.videoTrack.play('remote');
      }
      if (mediaType === 'audio' && user.audioTrack) {
        user.audioTrack.play();
      }
      post({ type: 'connected', uid: user.uid });
    });

    client.on('user-left', function() {
      post({ type: 'user-left' });
    });

    client.on('token-privilege-will-expire', function() {
      post({ type: 'token-expiring' });
    });

    setStatus('Joining channel...');
    await client.join(APP_ID, CHANNEL, TOKEN, UID);

    setStatus('Setting up media...');
    if (CTYPE === 'video') {
      audioTrack = await AgoraRTC.createMicrophoneAudioTrack();
      videoTrack = await AgoraRTC.createCameraVideoTrack();
      videoTrack.play('local');
      await client.publish([audioTrack, videoTrack]);
    } else {
      audioTrack = await AgoraRTC.createMicrophoneAudioTrack();
      await client.publish([audioTrack]);
    }

    setStatus('Waiting for other party...');
    post({ type: 'joined' });

  } catch(e) {
    setStatus('Error: ' + (e.message || e));
    post({ type: 'error', message: e.message || String(e) });
  }
}

/* ── Commands from React Native ── */
function handleRNMessage(event) {
  var d;
  try { d = JSON.parse(event.data); } catch { return; }

  if (d.action === 'end') {
    if (audioTrack) { try { audioTrack.stop(); audioTrack.close(); } catch{} }
    if (videoTrack) { try { videoTrack.stop(); videoTrack.close(); } catch{} }
    if (client)     { try { client.leave(); } catch{} }
    post({ type: 'ended' });
  }
  if (d.action === 'mute' && audioTrack) {
    try { audioTrack.setEnabled(!d.muted); } catch{}
  }
  if (d.action === 'toggleVideo' && videoTrack) {
    try { videoTrack.setEnabled(d.enabled); } catch{}
  }
}

window.addEventListener('message', handleRNMessage);
document.addEventListener('message', handleRNMessage); /* Android */

join();
</script>
</body>
</html>
