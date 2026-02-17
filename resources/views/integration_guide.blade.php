<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />
    <title>ingeration guide</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Your Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/post.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/finebtn.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobilenavbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/searchuser.css') }}">

     <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
    </head>
<body>
   
@extends('layouts.app')

@section('content')
<div class="container py-5" style="background: #f4f7f6; min-height: 100vh;">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <div class="text-center mb-5">
                <h1 class="display-4" style="font-weight: 700; color: #333;">🎯 Conversion Tracking Setup Guide</h1>
                <p class="lead text-muted">Track when people who clicked your ad take actions on your website. You only pay when someone actually converts!</p>
            </div>

            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <h3 class="card-title text-primary"><i class="fas fa-bolt"></i> ⚡ Quick Setup (2 Steps)</h3>
                    
                    <h5 class="mt-4">Step 1: Install Tracking Script (One Time)</h5>
                    <p>Copy and paste this code into your website's HTML (before the closing <code>&lt;/head&gt;</code> tag):</p>
                    
                    <div style="background: #282c34; padding: 15px; border-radius: 8px; border-left: 5px solid skyblue;">
                        <pre style="margin-bottom:0;"><code style="color: #abb2bf;">&lt;!-- Supperage Conversion Tracking --&gt;
&lt;script src="https://supperage.com/js/supperage-tracking.js"&gt;&lt;/script&gt;
&lt;script&gt;
    SupperageTracking.init('YOUR_AD_ID_HERE');
&lt;/script&gt;</code></pre>
                    </div>
                    <p class="small text-muted mt-2">Replace <code>YOUR_AD_ID_HERE</code> with your actual ad ID found in your campaign dashboard.</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <h3 class="card-title text-success"><i class="fas fa-file-code"></i> Full Website Example</h3>
                    <p>Here is how a complete HTML page looks with tracking integrated for downloads, signups, and purchases.</p>
                    
                    <div style="background: #1e1e1e; padding: 20px; border-radius: 10px; max-height: 500px; overflow-y: auto;">
                        <pre style="margin-bottom:0;"><code style="color: #dcdcaa;">&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;title&gt;My Online Course Website&lt;/title&gt;
    
    &lt;!-- STEP 1: Install Tracking --&gt;
    &lt;script src="https://supperage.com/js/supperage-tracking.js"&gt;&lt;/script&gt;
    &lt;script&gt;
        SupperageTracking.init('456'); 
    &lt;/script&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;h1&gt;Welcome to My Online Course!&lt;/h1&gt;
    
    &lt;button id="downloadBtn"&gt;Download Free Ebook&lt;/button&gt;
    &lt;button id="signupBtn"&gt;Sign Up&lt;/button&gt;
    &lt;button id="buyBtn"&gt;Buy Course - ₦5,000&lt;/button&gt;
    
    &lt;script&gt;
        // STEP 2: Track conversions
        
        document.getElementById('downloadBtn').addEventListener('click', function() {
            window.location.href = '/ebook.pdf';
            SupperageTracking.trackDownload();
        });
        
        document.getElementById('signupBtn').addEventListener('click', function() {
            // Your signup logic...
            SupperageTracking.trackSignup();
        });
        
        document.getElementById('buyBtn').addEventListener('click', function() {
            // After payment confirmation...
            SupperageTracking.trackPurchase(5000);
        });
    &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;</code></pre>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <h3>📊 Available Action Types</h3>
                    <table class="table table-hover mt-3">
                        <thead class="thead-light">
                            <tr>
                                <th>Action Type</th>
                                <th>When to Use</th>
                                <th>Code to Use</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td><strong>Signup</strong></td><td>User creates account</td><td><code>trackSignup()</code></td></tr>
                            <tr><td><strong>Purchase</strong></td><td>User buys something</td><td><code>trackPurchase(amount)</code></td></tr>
                            <tr><td><strong>Download</strong></td><td>User downloads file</td><td><code>trackDownload()</code></td></tr>
                            <tr><td><strong>Lead</strong></td><td>User becomes a lead</td><td><code>trackLead()</code></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="alert alert-info p-4" style="border-radius: 15px; border: none;">
                <h4><i class="fas fa-life-ring"></i> ✅ How to Test It Works</h4>
                <ol>
                    <li>Open your website and press <strong>F12</strong> to open the Developer Console.</li>
                    <li>Perform a test action (like clicking your signup button).</li>
                    <li>Look for: <span class="text-success">"✅ Supperage conversion tracked"</span> in the console.</li>
                </ol>
            </div>

        </div>
    </div>
</div>

<!-- Load jQuery first -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="{{ asset('myjs/bar.js') }}"></script> <!-- this should come last -->
<script src="{{ asset('myjs/allpost.js') }}"></script>
<script src="{{ asset('myjs/mobilenavbar.js') }}"></script>
    <script src="{{ asset('myjs/searchuser.js') }}"></script>
@endsection

</body>

</html>