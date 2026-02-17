<div class="row">
    <div class="col-md-6">
        <h6 class="text-primary">User Information</h6>
        <table class="table table-sm">
            <tr>
                <td><strong>Name:</strong></td>
                <td>{{ $app->name }}</td>
            </tr>
            <tr>
                <td><strong>Username:</strong></td>
                <td>{{'@'. $app->username }}</td>
            </tr>
            <tr>
                <td><strong>Email:</strong></td>
                <td>{{ $app->email }}</td>
            </tr>
            <tr>
                <td><strong>Phone:</strong></td>
                <td>{{ $app->phone ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>
    
    <div class="col-md-6">
        <h6 class="text-primary">Payment Details</h6>
        <table class="table table-sm">
            <tr>
                <td><strong>Method:</strong></td>
                <td>{{ ucfirst(str_replace('_', ' ', $app->payment_method)) }}</td>
            </tr>
            <tr>
                <td><strong>Amount Requested:</strong></td>
                <td class="text-danger">{{ $app->currency }} {{ number_format($app->amount_requested, 2) }}</td>
            </tr>
            <tr>
                <td><strong>User Receives (70%):</strong></td>
                <td class="text-success">{{ $app->currency }} {{ number_format($app->amount_to_receive ?? ($app->amount_requested * 0.70), 2) }}</td>
            </tr>
            <tr>
                <td><strong>Platform Fee (30%):</strong></td>
                <td class="text-info">{{ $app->currency }} {{ number_format($app->platform_fee ?? ($app->amount_requested * 0.30), 2) }}</td>
            </tr>
        </table>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <h6 class="text-primary">Bank/Payment Information</h6>
        @if($app->payment_method == 'bank_transfer')
            <table class="table table-sm table-bordered">
                <tr>
                    <td><strong>Bank Name:</strong></td>
                    <td>{{ $app->bank_name }}</td>
                </tr>
                <tr>
                    <td><strong>Account Number:</strong></td>
                    <td>{{ $app->account_number }}</td>
                </tr>
                <tr>
                    <td><strong>Account Name:</strong></td>
                    <td>{{ $app->account_name }}</td>
                </tr>
            </table>
        @elseif($app->payment_method == 'paypal')
            <table class="table table-sm table-bordered">
                <tr>
                    <td><strong>PayPal Email:</strong></td>
                    <td>{{ $app->paypal_email }}</td>
                </tr>
            </table>
        @else
            <p class="text-muted">Flutterwave payment method</p>
        @endif
    </div>
</div>

@if($app->reason)
<div class="row mt-3">
    <div class="col-12">
        <h6 class="text-primary">Reason</h6>
        <p class="border p-2 bg-light">{{ $app->reason }}</p>
    </div>
</div>
@endif

<div class="row mt-3">
    <div class="col-12">
        <h6 class="text-primary">Status Information</h6>
        <table class="table table-sm">
            <tr>
                <td><strong>Status:</strong></td>
                <td>
                    @if($app->status == 'pending')
                        <span class="badge bg-warning">Pending</span>
                    @elseif($app->status == 'approved')
                        <span class="badge bg-info">Approved</span>
                    @elseif($app->status == 'paid')
                        <span class="badge bg-success">Paid</span>
                    @else
                        <span class="badge bg-danger">Rejected</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td><strong>Applied On:</strong></td>
                <td>{{ \Carbon\Carbon::parse($app->created_at)->format('M d, Y H:i A') }}</td>
            </tr>
            @if($app->paid_at)
            <tr>
                <td><strong>Paid On:</strong></td>
                <td>{{ \Carbon\Carbon::parse($app->paid_at)->format('M d, Y H:i A') }}</td>
            </tr>
            @endif
            @if($app->admin_note)
            <tr>
                <td><strong>Admin Note:</strong></td>
                <td>{{ $app->admin_note }}</td>
            </tr>
            @endif
        </table>
    </div>
</div>