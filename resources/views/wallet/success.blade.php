<h3>Wallet Funding Successful</h3>
<p>Transaction ID: {{ $transactionId }}</p>
<p>Reference: {{ $txRef }}</p>
<p>Amount Paid: ₦{{ number_format($amount, 2) }}</p>
<p>Wallet Owner: {{ $walletOwner->name }}</p>
<img src="{{ $walletOwner->profileimg ?? asset('images/default-avatar.png') }}" width="80" height="80" style="border-radius:50%;">
