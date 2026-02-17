@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: center; align-items: center; height: 60vh; text-align: center; flex-direction: column;">
  <h2 style="font-size: 2em; color: #cc0000;">Tale has been Deleted</h2>
  <p style="font-size: 25px;">The tale has been deleted or doesn't exist.</p>
  <a href="{{ url('update') }}">
    <button style="background:green;color:white;font-size:25px;border-radius:20px;border:none;outline:none;padding:5px;cursor:pointer;">
      Go back home
    </button>
  </a>
</div>
@endsection
