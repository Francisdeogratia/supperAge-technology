
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
<title>premuim page </title>
<style>
    .premium-container {
        max-width: 500px;
        margin: auto;
        padding: 20px;
        font-family: sans-serif;
        /* width: 300px; */
    }
    .premium-title {
        text-align: center;
        font-size: 2em;
        font-weight: bold;
    }
    .premium-sub {
        text-align: center;
        color: #777;
        margin-bottom: 20px;
    }
    .progress-bar-container {
        background: #eee;
        border-radius: 25px;
        overflow: hidden;
    }
    .progress-bar {
        height: 12px;
        background: linear-gradient(90deg, #ff9800, #ffca28);
        transition: width 0.4s;
    }
    .task-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .task-item {
    background: #fff;
    padding: 15px;
    margin-bottom: 12px;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    display: flex;
    flex-wrap: wrap; /* allows icon/text to wrap on small screens */
    align-items: center;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
    word-break: break-word; /* prevents long text overflow */
}
    .task-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    }
    .task-icon {
        font-size: 1.5em;
        margin-right: 12px;
    }
    .upgrade-btn {
    background: linear-gradient(90deg, #ff9800, #ffca28);
    color: white;
    padding: 12px 20px; /* reduced for mobile */
    border-radius: 30px;
    font-weight: bold;
    text-decoration: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transition: transform 0.2s;
    display: inline-block;
    max-width: 100%; /* prevents cut-off */
    text-align: center;
}
    .upgrade-btn:hover {
        transform: scale(1.05);
    }
    /* Mobile tweaks */
    @media (max-width: 600px) {
    .premium-title {
        font-size: 1.5em;
        flex-wrap: wrap; /* allows arrow + text to wrap */
    }
    .premium-container {
        padding: 15px;
    }
    .task-item {
        flex-direction: row;
        justify-content: flex-start;
    }
    .upgrade-btn {
        width: 250px;
    }
}
</style>

</head>
<body>
<div class="premium-container">
    <h1 class="premium-title">
        <a href="{{ url('update') }}" style="margin-right: 10px; font-size: 20px; color: red; border-radius:50%;background:green;padding:5px;">
    <i class="fa-solid fa-arrow-left"></i>
</a>
     🌟 Premium Features
    </h1>

    <p class="premium-sub">Unlock your full potential with these exclusive benefits</p>

    <div style="margin-bottom: 25px;">
        <label style="font-weight:bold; color:#555;">Your Progress</label>
        <div class="progress-bar-container">
            <div class="progress-bar" style="width: {{ $progress }}%;"></div>
        </div>
        <small style="color:#888;">{{ $completedCount }} of {{ $totalCount }} tasks completed</small>
    </div>

    <ul class="task-list">
    <li class="task-item">
        <span class="task-icon">✅</span>
        <a href="{{ url('/blue-badge') }}" style="flex-grow:1; text-decoration:none; color:inherit;">
            Blue Badge Verification
        </a>
    </li>
    <li class="task-item">
        <span class="task-icon">🚀</span> Priority Profile Boost
    </li>
    <li class="task-item">
        <span class="task-icon">🛡</span> Enhanced Security & Privacy
    </li>
    <li class="task-item">
        <span class="task-icon">📈</span> Advanced Analytics
    </li>
    <li class="task-item">
        <span class="task-icon">💬</span> Premium Support 24/7
    </li>
</ul>


    <div style="text-align:center; margin-top:30px;">
        <!-- <a href="{{ url('/subscribe-premium') }}" class="upgrade-btn">Upgrade Now 🚀</a> -->
    </div>
</div>
</body>
</html>
