@extends('layouts.app')

@section('content')
<div class="reset-wrapper">
    <div id="loader">
        <i class="fa fa-spinner fa-spin m-2" style="color:blue;width:70px;height:70px;"></i>
    </div>

    @if(session('status'))
        <div class="alert alert-success mt-3">
            {{ session('status') }}
        </div>

        <script>
            setTimeout(function () {
                window.location.href = "/account"; // or "/login"
            }, 4000);
        </script>
    @else
        <div class="alert alert-warning mt-3">
            No confirmation message found. Please retry your action.
        </div>
    @endif
</div>

<style>
    #loader {
        display: block;
        text-align: center;
    }
    .alert-success {
        background-color: #d1e7dd;
        color: #0f5132;
        border-radius: 6px;
        padding: 12px;
        font-weight: bold;
        text-align: center;
    }
</style>
@endsection
