<style>
    .txn-table-container {
        width: 100%;
        overflow-x: auto;
        background: white;
        padding: 0;
        margin: 0;
    }
    
    .txn-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: white;
        min-width: 1000px;
    }
    
    .txn-table thead {
        background: #f8f9fa;
    }
    
    .txn-table th {
        padding: 15px 10px;
        text-align: left;
        border: 2px solid #dee2e6;
        font-weight: 700;
        font-size: 13px;
        color: #495057;
        background: #e9ecef;
    }
    
    .txn-table td {
        padding: 15px 10px;
        border: 1px solid #dee2e6;
        vertical-align: middle;
        background: white;
    }
    
    .txn-table tr:hover td {
        background: #f8f9fa;
    }
    
    .txn-user-cell {
        display: table;
        width: 100%;
    }
    
    .txn-user-avatar {
        display: table-cell;
        width: 40px;
        vertical-align: middle;
        padding-right: 10px;
    }
    
    .txn-user-info {
        display: table-cell;
        vertical-align: middle;
    }
    
    .txn-avatar-img {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: block;
    }
    
    .txn-avatar-initial {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
        font-size: 14px;
    }
    
    .txn-user-name {
        font-weight: 600;
        font-size: 13px;
        display: block;
        margin-bottom: 3px;
    }
    
    .txn-user-username {
        font-size: 11px;
        color: #6c757d;
        display: block;
    }
    
    .txn-amount-positive {
        color: #28a745;
        font-weight: 700;
        font-size: 14px;
    }
    
    .txn-amount-negative {
        color: #dc3545;
        font-weight: 700;
        font-size: 14px;
    }
    
    .txn-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        color: white;
    }
    
    .txn-badge-success { background: #28a745; }
    .txn-badge-danger { background: #dc3545; }
    .txn-badge-info { background: #17a2b8; }
    .txn-badge-secondary { background: #6c757d; }
    
    .txn-id {
        font-family: 'Courier New', monospace;
        font-size: 11px;
        word-break: break-all;
        display: block;
        margin-bottom: 4px;
    }
    
    .txn-date {
        font-size: 13px;
        display: block;
    }
    
    .txn-time {
        font-size: 11px;
        color: #6c757d;
        display: block;
        margin-top: 3px;
    }
    
    .txn-btn {
        padding: 6px 12px;
        border: 1px solid #0d6efd;
        background: white;
        color: #0d6efd;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
    }
    
    .txn-btn:hover {
        background: #0d6efd;
        color: white;
    }
</style>

<div class="txn-table-container">
    <table class="txn-table">
        <thead>
            <tr>
                <th style="width: 50px;">#</th>
                <th style="width: 180px;">Transaction ID</th>
                <th style="width: 180px;">Wallet Owner</th>
                <th style="width: 180px;">Payer</th>
                <th style="width: 120px; text-align: right;">Amount</th>
                <th style="width: 80px;">Currency</th>
                <th style="width: 100px;">Type</th>
                <th style="width: 120px;">Date</th>
                <th style="width: 80px; text-align: center;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
                <tr>
                    <!-- ID -->
                    <td>{{ $transaction->id }}</td>
                    
                    <!-- Transaction ID -->
                    <td>
                        <span class="txn-id">{{ $transaction->transaction_id }}</span>
                        <span class="txn-id" style="color: #6c757d;">{{ $transaction->tx_ref }}</span>
                    </td>
                    
                    <!-- Wallet Owner -->
                    <td>
                        @if($transaction->walletOwner)
                            <div class="txn-user-cell">
                                <div class="txn-user-avatar">
                                    @if($transaction->walletOwner->profilepix)
                                        <img src="{{ asset($transaction->walletOwner->profilepix) }}" 
                                             alt="{{ $transaction->walletOwner->name }}" 
                                             class="txn-avatar-img">
                                    @else
                                        <div class="txn-avatar-initial" style="background: #6c757d;">
                                            {{ strtoupper(substr($transaction->walletOwner->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="txn-user-info">
                                    <span class="txn-user-name">{{ $transaction->walletOwner->name }}</span>
                                    <span class="txn-user-username">{{ '@' . $transaction->walletOwner->username }}</span>
                                </div>
                            </div>
                        @else
                            <span style="color: #6c757d;">N/A</span>
                        @endif
                    </td>
                    
                    <!-- Payer -->
                    <td>
                        @if($transaction->payer)
                            <div class="txn-user-cell">
                                <div class="txn-user-avatar">
                                    @if($transaction->payer->profilepix)
                                        <img src="{{ asset($transaction->payer->profilepix) }}" 
                                             alt="{{ $transaction->payer->name }}" 
                                             class="txn-avatar-img">
                                    @else
                                        <div class="txn-avatar-initial" style="background: #0d6efd;">
                                            {{ strtoupper(substr($transaction->payer->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="txn-user-info">
                                    <span class="txn-user-name">{{ $transaction->payer->name }}</span>
                                    <span class="txn-user-username">{{ '@' . $transaction->payer->username }}</span>
                                </div>
                            </div>
                        @else
                            <span style="color: #6c757d;">N/A</span>
                        @endif
                    </td>
                    
                    <!-- Amount -->
                    <td style="text-align: right;">
                        <span class="{{ $transaction->amount >= 0 ? 'txn-amount-positive' : 'txn-amount-negative' }}">
                            {{ $transaction->amount >= 0 ? '+' : '' }}{{ number_format($transaction->amount, 2) }}
                        </span>
                    </td>
                    
                    <!-- Currency -->
                    <td>
                        <span class="txn-badge txn-badge-secondary">{{ $transaction->currency }}</span>
                    </td>
                    
                    <!-- Type -->
                    <td>
                        @php
                            $badgeClass = match($transaction->type) {
                                'task_reward' => 'txn-badge-success',
                                'debit' => 'txn-badge-danger',
                                'general' => 'txn-badge-info',
                                default => 'txn-badge-secondary'
                            };
                        @endphp
                        <span class="txn-badge {{ $badgeClass }}">
                            {{ ucfirst(str_replace('_', ' ', $transaction->type)) }}
                        </span>
                    </td>
                    
                    <!-- Date -->
                    <td>
                        <span class="txn-date">{{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y') }}</span>
                        <span class="txn-time">{{ \Carbon\Carbon::parse($transaction->created_at)->format('h:i A') }}</span>
                    </td>
                    
                    <!-- Actions -->
                    <td style="text-align: center;">
                        <button class="txn-btn" 
                                data-bs-toggle="modal" 
                                data-bs-target="#detailModal{{ $transaction->id }}">
                            👁 View
                        </button>
                    </td>
                </tr>

                <!-- Modal -->
                <div class="modal fade" id="detailModal{{ $transaction->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Transaction #{{ $transaction->id }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <table style="width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <td style="padding: 10px; font-weight: 600; color: #6c757d; width: 40%;">Transaction ID:</td>
                                        <td style="padding: 10px; font-family: monospace; font-size: 12px;">{{ $transaction->transaction_id }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px; font-weight: 600; color: #6c757d;">Reference:</td>
                                        <td style="padding: 10px; font-family: monospace; font-size: 12px;">{{ $transaction->tx_ref }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px; font-weight: 600; color: #6c757d;">Wallet Owner:</td>
                                        <td style="padding: 10px;">
                                            @if($transaction->walletOwner)
                                                <strong>{{ $transaction->walletOwner->name }}</strong><br>
                                                <small style="color: #6c757d;">{{ '@' . $transaction->walletOwner->username }}</small>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px; font-weight: 600; color: #6c757d;">Payer:</td>
                                        <td style="padding: 10px;">
                                            @if($transaction->payer)
                                                <strong>{{ $transaction->payer->name }}</strong><br>
                                                <small style="color: #6c757d;">{{ '@' . $transaction->payer->username }}</small>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px; font-weight: 600; color: #6c757d;">Amount:</td>
                                        <td style="padding: 10px;">
                                            <strong style="font-size: 20px; color: {{ $transaction->amount >= 0 ? '#28a745' : '#dc3545' }};">
                                                {{ number_format($transaction->amount, 2) }} {{ $transaction->currency }}
                                            </strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px; font-weight: 600; color: #6c757d;">Type:</td>
                                        <td style="padding: 10px;">{{ ucfirst(str_replace('_', ' ', $transaction->type)) }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px; font-weight: 600; color: #6c757d;">Status:</td>
                                        <td style="padding: 10px;"><span class="txn-badge txn-badge-success">{{ ucfirst($transaction->status) }}</span></td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px; font-weight: 600; color: #6c757d;">Description:</td>
                                        <td style="padding: 10px;">{{ $transaction->description }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px; font-weight: 600; color: #6c757d;">Date:</td>
                                        <td style="padding: 10px;">{{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y h:i A') }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <tr>
                    <td colspan="9" style="padding: 60px; text-align: center;">
                        <div style="font-size: 48px; color: #dee2e6;">📭</div>
                        <p style="color: #6c757d; margin-top: 20px;">No transactions found</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>