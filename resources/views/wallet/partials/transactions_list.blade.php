@foreach($transactions as $txn)
    @php
        $isCredit = $txn->amount > 0;
    @endphp
    <tr style="background-color: {{ $isCredit ? '#e6ffed' : '#ffe6e6' }};">
        {{-- Date --}}
        <td data-label="Date">{{ $txn->created_at->format('d M Y, h:i A') }}</td>

        {{-- Transaction ID --}}
        <td data-label="Transaction ID">{{ $txn->transaction_id }}</td>

        {{-- Reference --}}
        <td data-label="Reference">{{ $txn->tx_ref }}</td>

        {{-- Amount --}}
        <td data-label="Amount" style="color: {{ $isCredit ? 'green' : 'red' }}; font-weight: bold;">
            {{ $isCredit ? '+' : '-' }}{{ number_format(abs($txn->amount), 2) }} {{ $txn->currency ?? '' }}
        </td>

        {{-- Credit --}}
        <td data-label="Credit" class="text-success fw-bold">
            @if($isCredit)
                +{{ number_format($txn->amount, 2) }} {{ $txn->currency ?? '' }}
            @endif
        </td>

        {{-- Debit --}}
        <td data-label="Debit" class="text-danger fw-bold">
            @if(!$isCredit)
                -{{ number_format(abs($txn->amount), 2) }} {{ $txn->currency ?? '' }}
            @endif
        </td>

        {{-- Payer/Receiver --}}
        {{-- Payer --}}
{{-- Payer --}}
<td data-label="Payer">
    @if($txn->payer)
        <img src="{{ $txn->payer->profileimg ?? asset('images/default-avatar.png') }}" 
             alt="{{ $txn->payer->name }}" width="30" height="30" style="border-radius:50%;">
        {{ $txn->payer->name }}
    @else
        -
    @endif
</td>

{{-- Receiver --}}
{{-- Receiver --}}
<td data-label="Receiver">
    @if($txn->wallet_owner_id)
        @php $receiver = \App\Models\UserRecord::find($txn->wallet_owner_id); @endphp
        @if($receiver)
            <img src="{{ $receiver->profileimg ?? asset('images/default-avatar.png') }}" 
                 alt="{{ $receiver->name }}" width="30" height="30" style="border-radius:50%;">
            {{ $receiver->name }}
        @else
            -
        @endif
    @else
        -
    @endif
</td>

{{-- ✅ Description --}}
{{-- ✅ Description --}}
<td data-label="Description">
    @if(Str::startsWith($txn->transaction_id, 'wallet_badge'))
        {{-- Badge verification --}}
        Badge verification ({{ $txn->currency ?? '' }})
    @elseif($txn->payer_id == $txn->wallet_owner_id && Str::startsWith($txn->transaction_id, 'wallet_'))
        {{-- Wallet funding --}}
        Wallet funding ({{ $txn->currency ?? '' }})
    @elseif(!$txn->payer_id || !$txn->wallet_owner_id)
        {{-- System transaction --}}
        System transaction ({{ $txn->currency ?? '' }})
    @elseif($isCredit)
        {{-- Incoming transfer --}}
        Transfer from 
        @if($txn->payer)
            <img src="{{ $txn->payer->profileimg ?? asset('images/default-avatar.png') }}" 
                 alt="{{ $txn->payer->name }}" width="25" height="25" style="border-radius:50%;">
            <strong>{{ $txn->payer->name }}</strong>
        @else
            Unknown User
        @endif
        ({{ $txn->currency ?? '' }})
    @else
        {{-- Outgoing transfer --}}
        Transfer to 
        @php
            $receiver = \App\Models\UserRecord::find($txn->wallet_owner_id);
        @endphp
        @if($receiver)
            <img src="{{ $receiver->profileimg ?? asset('images/default-avatar.png') }}" 
                 alt="{{ $receiver->name }}" width="25" height="25" style="border-radius:50%;">
            <strong>{{ $receiver->name }}</strong>
        @else
            Unknown User
        @endif
        ({{ $txn->currency ?? '' }})
    @endif
</td>



    </tr>
@endforeach








