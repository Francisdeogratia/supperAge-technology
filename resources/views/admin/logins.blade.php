<style>
    .recent-logins-container {
        max-width: 960px;
        margin: 40px auto 20px;
        font-family: 'Segoe UI', sans-serif;
        padding: 1rem;
    }

    .recent-logins-container h2 {
        font-size: 24px;
        color: #34495e;
        margin-bottom: 15px;
        border-left: 5px solid #3498db;
        padding-left: 10px;
    }

    .recent-logins-table {
        width: 100%;
        border-collapse: collapse;
        box-shadow: 0 2px 4px rgba(0,0,0,0.06);
    }

    .recent-logins-table th {
        background-color: #2c3e50;
        color: #fff;
        padding: 10px;
        text-align: left;
    }

    .recent-logins-table td {
        padding: 10px;
        border-bottom: 1px solid #eaeaea;
        font-size: 14px;
        vertical-align: middle;
    }

    .recent-logins-table tr:hover {
        background-color: #f5f5f5;
    }
</style>

<div class="recent-logins-container">
    <h2>Recent Logins</h2>
    <table class="recent-logins-table">
        <tr>
            <th>Username</th>
            <th>IP Address</th>
            <th>Time</th>
        </tr>
        @foreach($logins as $login)
        <tr>
            <td>{{ $login->username }}</td>
            <td>{{ $login->ip_address }}</td>
            <td>{{ $login->created_at }}</td>
        </tr>
        @endforeach
    </table>
</div>
