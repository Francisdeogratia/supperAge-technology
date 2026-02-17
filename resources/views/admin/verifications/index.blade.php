@extends('layouts.app')

@section('content')
<h1>Badge Verifications</h1>

<table class="styled-table">
    <thead>
        <tr>
            <th>Full Name</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($verifications as $verify)
        <tr>
            <td>{{ $verify->full_name }}</td>
            <td>{{ ucfirst($verify->status) }}</td>
            <td>
                <a href="{{ route('admin.verifications.show', $verify->id) }}" class="view-link">View</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<style>
    .styled-table {
        border-collapse: collapse;
        margin: 20px 0;
        font-size: 1rem;
        font-family: sans-serif;
        min-width: 600px;
        width: 100%;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    .styled-table thead tr {
        background-color: #009879;
        color: #ffffff;
        text-align: left;
    }
    .styled-table th,
    .styled-table td {
        padding: 12px 15px;
        border: 1px solid #ddd;
    }
    .styled-table tbody tr {
        border-bottom: 1px solid #ddd;
    }
    .styled-table tbody tr:nth-of-type(even) {
        background-color: #f3f3f3;
    }
    .styled-table tbody tr:hover {
        background-color: #eaf6f3;
        cursor: pointer;
    }
    .view-link {
        color: #009879;
        text-decoration: none;
        font-weight: bold;
    }
    .view-link:hover {
        text-decoration: underline;
    }
</style>
@endsection
