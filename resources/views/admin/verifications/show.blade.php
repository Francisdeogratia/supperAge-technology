@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <h2 class="mb-4">Verification Details</h2>

    <table class="details-table">
        <tbody>
            <tr>
                <th>Full Name</th>
                <td>{{ $verification->full_name }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ ucfirst($verification->status) }}</td>
            </tr>
            <tr>
                <th>Notes</th>
                <td>{{ $verification->notes ?? 'None' }}</td>
            </tr>
            <tr>
                <th>Government ID</th>
                <td>
                    @if($verification->gov_id_path)
                        <a href="{{ Storage::disk('public')->url($verification->gov_id_path) }}" target="_blank">
                            <img src="{{ Storage::disk('public')->url($verification->gov_id_path) }}"
                                 alt="Government ID"
                                 class="gov-id-img">
                        </a>
                    @else
                        <span class="no-file">No file uploaded</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Profile Picture</th>
                <td>
                    @if($verification->profile_pic_path)
                        <img src="{{ Storage::disk('public')->url($verification->profile_pic_path) }}"
                             alt="Profile Picture"
                             class="profile-img">
                    @else
                        <span class="no-file">No file uploaded</span>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    <a href="{{ route('admin.verifications.index') }}" class="btn btn-secondary mt-3">Back to list</a>

</div>

<style>
    .details-table {
        width: 100%;
        border-collapse: collapse;
        font-family: Arial, sans-serif;
        font-size: 0.95rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    .details-table th {
        background-color: #009879;
        color: #fff;
        padding: 12px;
        text-align: left;
        width: 200px;
    }
    .details-table td {
        padding: 12px;
        border: 1px solid #ddd;
        vertical-align: top;
    }
    .details-table tr:nth-child(even) td {
        background-color: #f9f9f9;
    }
    .details-table tr:hover td {
        background-color: #f1fdf8;
    }
    .gov-id-img {
        max-width: 300px;
        border: 1px solid #ccc;
        padding: 4px;
        background-color: #fff;
    }
    .profile-img {
        max-width: 150px;
        border-radius: 50%;
        border: 1px solid #ccc;
        padding: 2px;
        background-color: #fff;
    }
    .no-file {
        color: #888;
        font-style: italic;
    }
</style>
@endsection
