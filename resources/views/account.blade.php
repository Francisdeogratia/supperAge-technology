<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>create account</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />


  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- SEO -->
  <meta name="author" content="omoha Ekenedilichukwu Francis">
  <meta name="description" content="Join SupperAge, the social-financial app where you can chat, share, earn, shop, create stores, fund wallets, and withdraw money.">
  <meta name="keywords" content="SupperAge, social financial app, earn money online, chat and earn, online marketplace, digital wallet, social networking, e-commerce platform">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- Assets -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- <link rel="stylesheet" href="{{ asset('css/style.css') }}"> -->

  <!-- Favicons -->
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
<link rel="stylesheet" href="{{ asset('css/allbtns.css') }}">

{{-- //Supperage Conversion Tracking 
<script src="http://127.0.0.1:8000/js/supperage-tracking.js"></script>
<script>
    SupperageTracking.init('20');
</script>--}}



  <style>
    #alert, #register-box, #forgot-box,#loader {
      display: none;
    }
    .sk {
      color: skyblue;
    }
  </style>









 

</head>

<body class="" style="background-color:#212121;">
  <div class="container mt-4">
    <!-- Modal -->
    <div class="modal" id="myModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" style="color:skyblue;">SupperAge</h4>
            <button type="button" class="close close-modal-btn" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <strong id="result2"></strong>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger close-modal-btn" data-dismiss="modal">Close</button>
            <div class="text-center">
              <!-- loader -->
                <i class="fa fa-spinner fa-spin m-2" id="loader" width="100" height="80" style="color:blue;"></i>
                <span class="spinner" style=""></span> 
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Alert -->
    <div class="row">
      <div class="col-lg-4 offset-lg-4" id="alert">
          @if(session('status'))
  <div class="alert alert-success text-center">
    {{ session('status') }}
    <strong id="result"></strong>
  </div>
@endif

@if(session('status'))
    <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
        {{ session('status') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

          
          
        
      </div>
    </div>

    <!-- Logo -->
    <!-- <div class="row">
      <div class="col-lg-4 offset-lg-4 text-center"> -->
        <!-- <strong style="font-size:60px; font-weight:700; color:skyblue;">SupperAge</strong> -->
      <!-- </div>
    </div> -->

    <!-- Login Box -->
    <div class="row">
      <div class="col-lg-4 offset-lg-4  rounded" id="login-box">
        <h2 class="text-center sk">Login</h2>
           @if(session('message'))
            <div class="alert alert-info">{{ session('message') }}</div>
          @endif

        <form method="POST" action="{{ url('/login') }}" class="p-2" id="login-frm" autocomplete="off">
         @csrf
         <div class="input-group2 mb-3">
  <input required="" type="text" name="username"  required minlength="3" autocomplete="username" value="{{ request()->cookie('username') }}" class="input" id="login1">
  <label class="user-label cv">User Name</label>
</div>
          <!-- <div class="form-group position-relative">
          <input type="text" name="username" id="login1" class="form-control" placeholder="Username" required minlength="3" autocomplete="username" value="{{ request()->cookie('username') }}">
            <div class="position-absolute" style="top:6px; right:9px;">
              <span><i class="fa fa-microphone sk" id="mic1" style="font-size:17px;"> start </i></span>
            </div>
          </div> -->

          <div class="input-group2 mb-3">
          <input type="password" name="password" class="input pws" required minlength="6" autocomplete="current-password" id="passwordInput">
          <label class="user-label cv">Password</label>
          <span class="toggle-password">
            <i class="fa fa-eye eye sk" style="font-size:17px;"></i>
          </span>
        </div>

          <div class="form-group">
            <div class="custom-control custom-checkbox">
              <input type="checkbox" name="rem" class="custom-control-input" id="customCheck"
              @if(cookie('username')) checked @endif>
              <label for="customCheck" class="custom-control-label sk text-white">Remember Me</label>
              <a href="#" id="forgot-btn" class="float-right sk text-white">Forgot Password</a><br />
              <!-- <a href="{{ url('register_with_face.php') }}" class="float-right sk">Login with face</a> -->
            </div>
          </div>
            
          <!-- LOGIN FORM BUTTON - Remove data-toggle and data-target -->
<div class="wrap">
  <button type="submit" name="login" id="login" class="button text-white">Login</button>
</div>

          <div class="form-group text-center mt-3">
            <p><span style="color:#666;">New User?</span> <a href="#" id="register-btn" class="sk" style="font-weight:700; font-size:20px;">Create new account</a></p>
            <p class="sk"><a href="{{ url('account') }}">Create TV Channels</a> for Celebrity, Brand, Business, and Live Broadcasting Events</p>
          </div>
        </form>
      </div>
    </div>

  <!-- Registration Form -->
<div class="row">
  <div class="col-lg-6 offset-lg-3  rounded" id="register-box">
    <h2 class="text-center mt-2" style="color:skyblue;">Create Account</h2>

    <form method="POST" action="{{ url('/register') }}" id="register-frm" role="form" class="p-2">
  @csrf
      <span id="instructions2" style="color:green;"></span>

      <!-- Full Name -->
      <div class="form-group position-relative item">
        <input type="text" name="name" id="inside2" class="input" autocomplete="Your Full Name or brand Name" required minlength="3">
        <label class="user-label cv">Full Name</label>
      </div>

      <!-- Username -->
      <div class="form-group position-relative item">
        <input type="text" name="username" id="inside3" class="input" autocomplete="Username" required minlength="4">
        <label class="user-label cv">User Name</label>
      </div>

      <!-- Email -->
      <div class="form-group position-relative item">
        <input type="email" name="email" id="inside6" class="input" autocomplete="E-mail" required>
        <label class="user-label cv">Email</label>
      </div>

      <!-- Phone -->
     
      <div class="form-group position-relative item">
        <input type="tel" name="phone" id="inside4" class="input" autocomplete="Phone Number with country code" required maxlength="15">
        <label class="user-label cv">Phone Number</label>
      </div>

      <!-- Gender & DOB -->
      <div class="row item">
        <div class="form-group col-lg-6 position-relative">
          @foreach (['male', 'female', 'other'] as $gender)
          <div class="form-check rounded mb-2 ">
            <input type="radio" name="gender" id="{{ $gender }}" class="form-check-input" value="{{ $gender }}" required>
            <label for="{{ $gender }}" style="font-weight:500" class="text-white">{{ ucfirst($gender) }}</label>
          </div>
          @endforeach
        </div>
        <div class="form-group col-lg-6">
          <input type="date" name="dob" id="dob" class="input" required>
          <label for="dob" style="font-weight:500;color:white" class="user-label cv">Date...of</label>
        </div>
      </div>
              <div class="wrap">
                <button id="nextBtn" class="button text-white">Next</button>
                <button id="prevBtn" style="display:none;" class="button text-white mb-3">Prev</button>
              </div>
      <!-- Location Dropdowns -->
      <div class="row item hidden">
        <div class="col-lg-12">
          <div class="form-group position-relative">
            
            <select class="input bg-dark" name="continent" id="continent" required>
              <option value="">Select Continent</option>
              <option value="Africa">Africa</option>
              <option value="Asia">Asia</option>
              <option value="Europe">Europe</option>
              <option value="North America">North America</option>
              <option value="South America">South America</option>
              <option value="Australia/Oceania">Australia/Oceania</option>
              <option value="Antarctica">Antarctica</option>
            </select>
           <label class="user-label cv">Select Continent</label>
          </div>
          <div class="form-group position-relative">
            <select class="input bg-dark" name="country" id="country" required>
              <option value="" class="text-white">Select Country</option>
            </select>
            <label class="user-label cv">Select Country</label>
          </div>
        </div>
        <!-- <div class="col-lg-6">
          <div class="form-group">
            <select class="form-control" name="state" id="state" required>
              <option value="">Select State</option>
            </select>
          </div>
          <div class="form-group">
            <select class="form-control" name="city" id="city">
              <option value="">Select City</option>
            </select>
          </div>
        </div> -->
      </div>

      <!-- Password Fields -->
      <div class="form-group position-relative item hidden">
        <input type="password" name="password" id="pass" class="input pws" placeholder="Password" required minlength="6">
        <label class="user-label cv">Password</label>
        <div class="position-absolute" style="top:10px; right:9px;">
          <span><i class="fa fa-eye eye" style="color:skyblue;"></i></span>
        </div>
      </div>

      <div class="form-group position-relative item hidden">
        <input type="password" name="password_confirmation" id="cpass" class="input pws" placeholder="Confirm Password" required minlength="6">
        <label class="user-label cv">password_confirmation</label>
        <div class="position-absolute" style="top:9px; right:9px;">
          <span><i class="fa fa-eye eye" style="color:skyblue;"></i></span>
        </div>
      </div>

      <!-- Terms -->
      <div class="form-group item hidden">
        <div class="custom-control custom-checkbox">
          <input type="checkbox" name="rem" class="custom-control-input" id="customCheck2">
          <label for="customCheck2" class="custom-control-label text-white">
            I agree to the <a href="{{ url('termsandcondition.php') }}" style="color:skyblue;">terms and conditions</a>
          </label>
        </div>
      </div>

      <!-- Submit
      !-- REGISTRATION FORM BUTTON - Remove data-toggle and data-target -->
<div class="wrap item hidden">
  <button type="submit" name="register" id="register" class="button text-white">
    Create account
  </button>
</div>

      <!-- Switch to Login -->
      <div class="form-group text-center mt-3">
        <p class="text-white">Already Registered? <a href="#" id="login-btn" style="color:skyblue; font-weight:700; font-size:20px;">Login Here</a></p>
      </div>
    </form>
  </div>
</div>
<!-- Forgot Password -->
<div class="row">
  <div class="col-lg-4 offset-lg-4 bg-light rounded" id="forgot-box">
    <h2 class="text-center" style="color:skyblue;">Reset Password</h2>
    <form method="POST" action="" class="p-2" id="forgot-frm">
      <div class="form-group">
        <small class="text-muted">
          To reset your password, enter the email address and we will send reset instructions. <br>
          Reset links expire after 5 minutes.
        </small>
      </div>

      <span id="show5" style="color:green;"></span>
      <div class="form-group position-relative">
        <input type="email" name="femail" id="forgot1" class="form-control" placeholder="E-mail" required>
        <div class="position-absolute" style="top:6px; right:9px;">
          <span><i class="fa fa-microphone" id="mic5" style="color:skyblue;"> start</i></span>
        </div>
      </div>

      <!-- FORGOT PASSWORD BUTTON - Remove data-toggle and data-target -->
<div class="form-group">
  <input type="submit" name="forgot" id="forgot" value="Reset" 
         class="btn btn-block text-white"
         style="background: skyblue; font-weight:700; font-size:20px;">
</div>

      <div class="form-group text-center">
        <a href="#" id="back-btn" style="color:skyblue; font-weight:700; font-size:25px;">Back</a>
      </div>
    </form>
  </div>
</div>

<!-- Credits -->
<!-- <div class="row mt-4">
  <div class="col-lg-4 offset-lg-4 text-center text-muted">
    <strong>Powered and sponsored by SupperAge Technologist</strong>
  </div>
</div> -->

<!-- Footer Links -->
<div class="row mt-4">
  <div class="col-lg-4 offset-lg-4 text-center" style="font-size:11px;">
    <strong><a href="{{ url('about.php') }}" style="color:skyblue;">About Us</a></strong> |
    <strong><a href="#" style="color:skyblue;">Contact Us</a></strong> |
    <strong><a href="{{ url('settings2.php') }}" style="color:skyblue;">Settings</a></strong>
  </div>
</div>

<!-- Final Footer -->
<div style="font-size:12px; color:#666; text-align:center; padding:10px;">
  <strong>SupperAge &copy;2025-{{ date('Y') }}. All Rights Reserved.</strong>
</div>
</div>

  <!-- Scripts -->
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js"></script>
<!-- <script src="{{ asset('myjquery/switcher.js') }}"></script> optional if used -->

<script>

  // Or use the basic track method
// SupperageTracking.track('signup');


//   document.getElementById('register-btn').onclick = function() {
//   document.getElementById('login-box').style.display = 'none';
//   document.getElementById('register-box').style.display = 'block';
// };

// document.getElementById('forgot-btn').onclick = function() {
//   document.getElementById('login-box').style.display = 'none';
//   document.getElementById('forgot-box').style.display = 'block';
// };

// document.getElementById('back-btn').onclick = function() {
//   document.getElementById('forgot-box').style.display = 'none';
//   document.getElementById('register-box').style.display = 'none';
//   document.getElementById('login-box').style.display = 'block';
// };

// first script
  $(document).ready(function () {
    const continentCountry = {
      "Africa": [
            "Algeria", "Angola", "Benin", "Botswana", "Burkina Faso", "Burundi", "Cabo Verde", "Cameroon",
            "Central African Republic", "Chad", "Comoros", "Congo", "Djibouti", "Egypt", "Equatorial Guinea",
            "Eritrea", "Eswatini", "Ethiopia", "Gabon", "Gambia", "Ghana", "Guinea", "Guinea-Bissau",
            "Ivory Coast", "Kenya", "Lesotho", "Liberia", "Libya", "Madagascar", "Malawi", "Mali", "Mauritania",
            "Mauritius", "Morocco", "Mozambique", "Namibia", "Niger", "Nigeria", "Rwanda", "Sao Tome and Principe",
            "Senegal", "Seychelles", "Sierra Leone", "Somalia", "South africa", "South Sudan", "Sudan", "Tanzania",
            "Togo", "Tunisia", "Uganda", "Zambia", "Zimbabwe"
        ],
      "Asia": [
            "Afghanistan", "Armenia", "Azerbaijan", "Bahrain", "Bangladesh", "Bhutan", "Brunei", "Cambodia",
            "China", "Cyprus", "Georgia", "India", "Indonesia", "Iran", "Iraq", "Israel", "Japan", "Jordan",
            "Kazakhstan", "Kuwait", "Kyrgyzstan", "Laos", "Lebanon", "Malaysia", "Maldives", "Mongolia",
            "Myanmar", "Nepal", "North Korea", "Oman", "Pakistan", "Palestine", "Philippines", "Qatar",
            "Saudi Arabia", "Singapore", "South Korea", "Sri Lanka", "Syria", "Tajikistan", "Thailand",
            "Timor-Leste", "Turkey", "Turkmenistan", "United Arab Emirates", "Uzbekistan", "Vietnam", "Yemen"
        ],
        "Europe": [
            "Albania", "Andorra", "Austria", "Belarus", "Belgium", "Bosnia and Herzegovina", "Bulgaria",
            "Croatia", "Czech Republic", "Denmark", "Estonia", "Finland", "France", "Germany", "Greece",
            "Hungary", "Iceland", "Ireland", "Italy", "Kosovo", "Latvia", "Liechtenstein", "Lithuania",
            "Luxembourg", "Malta", "Moldova", "Monaco", "Montenegro", "Netherlands", "North Macedonia",
            "Norway", "Poland", "Portugal", "Romania", "Russia", "San Marino", "Serbia", "Slovakia",
            "Slovenia", "Spain", "Sweden", "Switzerland", "Ukraine", "United Kingdom", "Vatican City"
        ],
        "North America": [
            "Antigua and Barbuda", "Bahamas", "Barbados", "Belize", "Canada", "Costa Rica", "Cuba", "Dominica",
            "Dominican Republic", "El Salvador", "Grenada", "Guatemala", "Haiti", "Honduras", "Jamaica",
            "Mexico", "Nicaragua", "Panama", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines",
            "Trinidad and Tobago", "United States"
        ],
        "South America": [
            "Argentina", "Bolivia", "Brazil", "Chile", "Colombia", "Ecuador", "Guyana", "Paraguay", "Peru",
            "Suriname", "Uruguay", "Venezuela"
        ],
        "Australia/Oceania": [
            "Australia", "Fiji", "Kiribati", "Marshall Islands", "Micronesia", "Nauru", "New Zealand",
            "Palau", "Papua New Guinea", "Samoa", "Solomon Islands", "Tonga", "Tuvalu", "Vanuatu"
        ],
        "Antarctica": ["Research Station"]
    };

    $("#continent").change(function () {
      const continent = $(this).val();
      const countries = continentCountry[continent] || [];
      let countryOptions = '<option value="">Select Country</option>';
      countries.forEach(function (country) {
        countryOptions += `<option value="${country}">${country}</option>`;
      });
      $("#country").html(countryOptions);
    });
  
  $.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});


// just second script
  // 👁️ Toggle password visibility (individual fields)
  $(".eye").click(function () {
    const input = $(this).closest(".form-group").find(".pws");
    $(this).toggleClass("fa-eye-slash");
    input.attr("type", input.attr("type") === "password" ? "text" : "password");
  });


// eye toggle for login 
$(document).ready(function() {
  $('.toggle-password').on('click', function() {
    const input = $('#passwordInput');
    const icon = $(this).find('i');

    if (input.attr('type') === 'password') {
      input.attr('type', 'text');
      icon.removeClass('fa-eye').addClass('fa-eye-slash');
    } else {
      input.attr('type', 'password');
      icon.removeClass('fa-eye-slash').addClass('fa-eye');
    }
  });
});



  // 🎙️ Toggle mic icons
  ["#mic1", "#mic2", "#mic3", "#mic4", "#mic5", "#mic6"].forEach(id => {
    $(id).click(function () {
      $(this).toggleClass("fa-microphone-slash");
    });
  });

  // 📦 Form toggling
  $("#forgot-btn").click(function () {
    $("#login-box").hide();
    $("#forgot-box").show();
  });

  $("#back-btn").click(function () {
    $("#forgot-box").hide();
    $("#login-box").show();
  });

  $("#register-btn").click(function () {
    $("#login-box").hide();
    $("#register-box").show();
  });

  $("#login-btn").click(function () {
    $("#register-box").hide();
    $("#login-box").show();
  });

  // ✅ Form validation
  $("#login-frm").validate();
  $("#forgot-frm").validate();
  $("#register-frm").validate({
    rules: {
      cpass: {
        equalTo: "#pass"
      }
    }
  });

  // 📤 Registration form submit with AJAX
// Replace your registration AJAX handler with this

let registrationSuccessful = false;

$('#register-frm').on('submit', function(e) {
  e.preventDefault();

  let $btn = $('#register');
  let originalText = $btn.html();

  // Make sure modal is hidden initially
  $('#myModal').modal('hide');

  // Replace button text with spinner
  $btn.html('<i class="fa fa-spinner fa-spin"></i> Creating...').prop('disabled', true);

  $.ajax({
    url: $(this).attr('action'),
    method: 'POST',
    data: $(this).serialize(),
    success: function(response) {
      registrationSuccessful = true;

      // Change button to success state
      $btn.html('✅ Account Created').prop('disabled', false);

      // Play success sound
      let audio = new Audio('/sounds/mixkit-fantasy-game-success-notification-270.wav');
      audio.play().catch(() => {}); // Ignore audio errors

      let message = response.message || '✅ Registration successful!';
      $('#result, #result2').html(`<span style="color:green;">${message}</span>`);
      
      // NOW show the modal for success
      $('#myModal').modal('show');
    },
    error: function(xhr) {
      registrationSuccessful = false;

      // Restore original button text
      $btn.html(originalText).prop('disabled', false);

      let message = 'Something went wrong';
      try {
        let json = JSON.parse(xhr.responseText);
        if (json.errors) {
          message = Object.values(json.errors).flat().join('<br>');
        } else if (json.message) {
          message = json.message;
        }
      } catch (e) {
        message = xhr.responseText;
      }

      console.error('Registration failed:', xhr.status, message);
      $('#result2').html(`<span style="color:red;">❌ ${message}</span>`);
      
      // NOW show the modal for error
      $('#myModal').modal('show');
    }
  });
});

// Listen on Close button
$('.close-modal-btn').on('click', function() {
  if (registrationSuccessful) {
    location.reload();
  }
});


// 📥 Login form submit with AJAX + spinner in button
// Replace your existing login AJAX handler with this improved version

$('#login-frm').on('submit', function(e) {
  e.preventDefault();

  let $btn = $('#login');
  let originalText = $btn.html();

  // Make sure modal is hidden initially
  $('#myModal').modal('hide');

  // Show spinner in button
  $btn.html('<i class="fa fa-spinner fa-spin"></i>').prop('disabled', true);

  const formObj = $(this).serializeArray();
  const data = {};
  formObj.forEach(field => {
    data[field.name] = field.value;
  });

  $.ajax({
    url: '/login',
    method: 'POST',
    data: data,
    success: function(response) {
      if (response.status === 'success') {
        // Success state
        $btn.html('✅ Logged In').prop('disabled', false);
        
        // Play success sound
        let audio = new Audio('/sounds/mixkit-fantasy-game-success-notification-270.wav');
        audio.play().catch(() => {}); // Ignore audio errors

        setTimeout(() => {
          window.location.href = response.redirect;
        }, 800);
      } else {
        // Handle unsuccessful login
        $btn.html(originalText).prop('disabled', false);
        
        $('#result2').html(`<span style="color:red;">${response.message || 'Login failed'}</span>`);
        $('#myModal').modal('show');
      }
    },
    error: function(xhr) {
      // Restore button
      $btn.html(originalText).prop('disabled', false);

      let errorMessage = 'Login failed. Please try again.';
      
      // Parse error response
      if (xhr.responseJSON && xhr.responseJSON.message) {
        errorMessage = xhr.responseJSON.message;
      } else if (xhr.responseText) {
        try {
          let json = JSON.parse(xhr.responseText);
          errorMessage = json.message || xhr.responseText;
        } catch (e) {
          errorMessage = xhr.responseText;
        }
      }

      // Handle specific HTTP status codes
      if (xhr.status === 403) {
        errorMessage = '🚫 ' + errorMessage;
      } else if (xhr.status === 429) {
        errorMessage = '⏳ ' + errorMessage;
      } else if (xhr.status === 400) {
        errorMessage = '❌ ' + errorMessage;
      }

      console.error('Login error:', xhr.status, errorMessage);
      
      // Show error in modal
      $('#result2').html(`<span style="color:red;">${errorMessage}</span>`);
      $('#myModal').modal('show');
    }
  });
});


// forgot password

// Replace your forgot password handler with this

$('#forgot').click(function(e) {
  if (document.getElementById('forgot-frm').checkValidity()) {
    e.preventDefault();
    
    // Hide modal initially
    $('#myModal').modal('hide');
    
    // Show inline loader
    $('#loader').show();

    $.ajax({
      url: '/forgot',
      method: 'POST',
      data: $('#forgot-frm').serialize(),
      success: function(response) {
        $('#loader').hide();
        $('#result, #result2').html(response);
        
        // Show modal with result
        $('#myModal').modal('show');
      },
      error: function(xhr) {
        $('#loader').hide();
        
        const msg = xhr.responseText || "Something went wrong";
        $('#result, #result2').html(`<span style="color:red;">❌ ${msg}</span>`);
        
        // Show modal with error
        $('#myModal').modal('show');
      }
    });
  }
});

    });

// for hidding form

$(document).ready(function() {
    // Show first 3 items, hide last 3
    $('.item').slice(5).hide();

    $('#nextBtn').click(function() {
        $('.item').slice(0, 5).hide();
        $('.item').slice(5).show();
        $('#nextBtn').hide();
        $('#prevBtn').show();
    });

    $('#prevBtn').click(function() {
        $('.item').slice(5).hide();
        $('.item').slice(0, 5).show();
        $('#prevBtn').hide();
        $('#nextBtn').show();
    });
});



// ============================================
// STEP 6: JavaScript Tracking Code for Advertisers
// ============================================

// Give this code to advertisers to add to their website:



// <!-- ✅ Supperage Ad Conversion Tracking -->
// (function() {
//     // Configuration
//     const SUPPERAGE_AD_ID = '18'; // Replace with actual ad ID
//     const SUPPERAGE_TRACKING_URL = 'https://127.0.0.1:8000//advertising/' + SUPPERAGE_AD_ID + '/action';
    
//     /**
//      * Track a conversion action
//      * @param {string} actionType - Type of action (signup, purchase, download, etc.)
//      * @param {number} value - Optional value of the conversion
//      * @param {object} metaData - Optional additional data
//      */
//     window.trackSupperageConversion = function(actionType, value, metaData) {
//         fetch(SUPPERAGE_TRACKING_URL, {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json',
//             },
//             body: JSON.stringify({
//                 action_type: actionType || 'other',
//                 value: value || 0,
//                 meta_data: metaData || {},
//                 country: getUserCountry(), // Get from user's session or IP
//                 _token: getCsrfToken() // If using CSRF protection
//             })
//         })
//         .then(response => response.json())
//         .then(data => {
//             console.log('Supperage conversion tracked:', data);
//         })
//         .catch(error => {
//             console.error('Supperage tracking error:', error);
//         });
//     };
    
//     // Helper function to get CSRF token
//     function getCsrfToken() {
//         const token = document.querySelector('meta[name="csrf-token"]');
//         return token ? token.getAttribute('content') : '';
//     }
    
    // Helper function to get user country (implement based on your needs)
//     function getUserCountry() {
//         // You can use IP geolocation API or server-side detection
//         return 'NG'; // Default or from session
//     }
// })();

// After signup
// SupperageTracking.trackSignup();

// After purchase
// SupperageTracking.trackPurchase(5000);

// After download
// SupperageTracking.trackDownload();


</script>

// <!-- Add to website header -->
<!-- <script src="https://yourdomain.com/js/supperage-tracking.js"></script>
<script>
    SupperageTracking.init('123'); // Your ad ID
</script> -->

<!-- Track signup -->
<!-- <script>
document.getElementById('signupForm').addEventListener('submit', function() {
    SupperageTracking.track('signup');
});
</script> -->

<!-- Track purchase -->
<!-- <script>
function onCheckoutComplete(total) {
    SupperageTracking.track('purchase', total);
} -->


 <!-- What Advertisers Do:
They paste 2 simple pieces of code on their website:
Installation (in HTML head): -->



</body>
</html>
