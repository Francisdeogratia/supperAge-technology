<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-P7ZNRWKS7Z"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-P7ZNRWKS7Z');
    </script>

    <meta charset="utf-8">
    <meta name="author" content="omoha Ekenedilichukwu Francis">
    <meta name="description" content="SupperAge Child Safety & Protection Policy - We are committed to protecting children and preventing child sexual abuse and exploitation.">
    <meta name="keywords" content="SupperAge, child safety, child protection, CSAE policy">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />

    <meta http-equiv="X-UA-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Child Safety Policy - SupperAge</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">

    <!-- Your Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/finebtn.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobilenavbar.css') }}">

    <!-- google ads -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2892124627300694"
         crossorigin="anonymous"></script>
</head>

<body>

<div class="settings-policy-container">
    <div class="page-header">
        <a href="{{ url()->previous() }}" class="btn btn-sm btn-outline-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <h1>🛡️ Child Safety & Protection Policy</h1>
        <p>Our commitment to protecting children on SupperAge</p>
    </div>

    <div class="policy-content">
        <div class="policy-section" style="display:block;">

            <h2>🛡️ Child Safety & Protection Policy</h2>

            <p>
                <strong>SupperAge</strong> is committed to protecting children and preventing
                child sexual abuse and exploitation (CSAE).
            </p>

            <h3>1. Age Restriction</h3>
            <ul>
                <li>SupperAge is not intended for children under 13 years old</li>
                <li>Some features (wallet, marketplace, live streaming) are restricted to 18+ users</li>
                <li>We actively remove accounts found to belong to underage users</li>
            </ul>

            <h3>2. Zero-Tolerance Policy for CSAE</h3>
            <p>SupperAge has zero tolerance for:</p>
            <ul>
                <li>Child sexual abuse material (CSAM)</li>
                <li>Sexual exploitation of minors</li>
                <li>Grooming or solicitation of minors</li>
                <li>Any sexual content involving a minor</li>
            </ul>
            <p><strong>Any such content results in immediate account termination.</strong></p>

            <h3>3. Content Moderation</h3>
            <ul>
                <li>Automated content monitoring</li>
                <li>User reporting tools</li>
                <li>Manual review by moderators</li>
                <li>Immediate removal of violating content</li>
            </ul>

            <h3>4. Reporting & Blocking</h3>
            <ul>
                <li>Report accounts, posts, chats, or media</li>
                <li>Block other users instantly</li>
                <li>Report suspected child exploitation directly to our team</li>
            </ul>

            <h3>5. Law Enforcement Cooperation</h3>
            <p>We cooperate fully with:</p>
            <ul>
                <li>Local and international law enforcement</li>
                <li>Regulatory authorities</li>
                <li>Child protection organizations</li>
            </ul>
            <p>We report confirmed CSAE cases as required by law.</p>

            <h3>6. Contact for Child Safety</h3>
            <p>
                📧 <strong><a href="mailto:info@supperage.com">info@supperage.com</a></strong>
            </p>

        </div>
    </div>
</div>

<style>
.settings-policy-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 40px 20px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.page-header {
    text-align: center;
    margin-bottom: 40px;
}

.page-header h1 {
    font-size: 2.5em;
    color: #2c3e50;
    margin-bottom: 10px;
}

.page-header p {
    font-size: 1.1em;
    color: #666;
}

.policy-content {
    background: white;
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
}

.policy-section h2 {
    font-size: 2em;
    color: #2c3e50;
    margin-bottom: 10px;
    border-bottom: 3px solid #1DA1F2;
    padding-bottom: 10px;
}

.policy-section h3 {
    font-size: 1.5em;
    color: #2c3e50;
    margin-top: 25px;
    margin-bottom: 15px;
}

.policy-section p {
    font-size: 1.05em;
    line-height: 1.8;
    color: #333;
    margin-bottom: 15px;
}

.policy-section ul {
    margin: 15px 0;
    padding-left: 30px;
}

.policy-section ul li {
    font-size: 1.05em;
    line-height: 1.8;
    color: #333;
    margin-bottom: 8px;
}

.policy-section a {
    color: #1DA1F2;
    text-decoration: none;
    font-weight: 600;
}

.policy-section a:hover {
    text-decoration: underline;
}

@media (max-width: 768px) {
    .settings-policy-container {
        padding: 20px 10px;
    }

    .page-header h1 {
        font-size: 1.8em;
    }

    .policy-content {
        padding: 20px;
    }

    .policy-section h2 {
        font-size: 1.5em;
    }
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('myjs/bar.js') }}"></script>
<script src="{{ asset('myjs/mobilenavbar.js') }}"></script>

</body>
</html>
