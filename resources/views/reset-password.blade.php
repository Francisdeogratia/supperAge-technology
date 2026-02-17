@extends('layouts.app')

@section('content')




<style>
    body {
        background: #f5f8fa;
    }
#loader {
    display: none;
}

    .reset-wrapper {
        max-width: 450px;
        margin: 60px auto;
        padding: 35px;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        text-align: center;
    }

    .reset-wrapper h3 {
        color: #007BFF;
        margin-bottom: 25px;
        font-weight: bold;
    }

    .form-group label {
        font-weight: 600;
        margin-bottom: 5px;
    }

    .form-control {
        border-radius: 6px;
        height: 45px;
        box-shadow: none;
        border: 1px solid #ced4da;
    }

    .btn-primary {
        background-color: #007BFF;
        border-color: #007BFF;
        font-weight: 700;
        font-size: 18px;
        border-radius: 6px;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    /* .alert-success {
        text-align: center;
        font-weight: bold;
    } */
/* error msg */
    .alert-danger {
    background-color: #f8d7da;
    color: #842029;
    border-radius: 6px;
    padding: 10px;
}

</style>

<div class="reset-wrapper">
    <div>
        <!-- loader -->
    <i class="fa fa-spinner fa-spin m-2" id="loader" width="80" height="80" style="color:blue;"></i> 
 </div>
    <h3 class="text-center">🔐 Reset Your Password</h3>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="email" value="{{ request()->query('email') }}">
        <input type="hidden" name="token" value="{{ request()->query('token') }}">

        <div class="form-group">
            <label for="new_password">New Password</label><br>
            <input type="password" name="new_password" class="form-control" required minlength="6" autocomplete="new-password">
        </div>

        <div class="form-group">
            <label for="new_password_confirmation">Confirm Password</label><br>
            <input type="password" name="new_password_confirmation" class="form-control" required minlength="6" autocomplete="new-password">
        </div>
        

        <!-- error msg -->
        @if ($errors->any())
    <div class="alert alert-danger text-left mt-3">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

      <br><br>
        <button type="submit" class="btn btn-primary btn-block mt-3">Update Password</button>
    </form>
    @if(session('status'))
    <script>
        setTimeout(function () {
            document.getElementById('loader').style.display = 'none';
        }, 1000); // hide loader after 1 second

        setTimeout(function () {
            window.location.href = "/account";
        }, 5000); // redirect after 5 seconds
    </script>
@endif
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector("form");
        const loader = document.getElementById("loader");

        form.addEventListener("submit", function () {
            loader.style.display = "inline-block";
        });
    });
</script>

</div>
@endsection

