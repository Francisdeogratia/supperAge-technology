@extends('layouts.app')

@section('seo_title', 'Login - SupperAge')
@section('seo_description', 'Log in to your SupperAge account. Access your feed, messages, marketplace, and community.')

@section('content')
<div class="container mt-5" style="max-width: 400px;">
    <h3 class="text-center mb-4">Login</h3>

    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger text-center">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input id="username" type="text" class="form-control" name="username" required autofocus>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" class="form-control" name="password" required>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input" id="rem" name="rem">
            <label class="form-check-label" for="rem">Remember me</label>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Login</button>
        </div>
    </form>
</div>
@endsection
