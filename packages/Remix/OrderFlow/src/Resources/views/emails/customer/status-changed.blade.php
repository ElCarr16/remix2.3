@component('shop::emails.layouts.master')

<div>
    <div style="text-align: center;">
        <a href="{{ config('app.url') }}">
            @include ('shop::emails.layouts.logo')
        </a>
    </div>

    <div style="padding: 30px;">
        <div style="font-size: 20px; color: #242424; line-height: 30px; margin-bottom: 34px;">
            <p style="font-weight: bold; font-size: 24px; color: #0041FF; line-height: 24px; margin-bottom: 24px;">
                Update Status Pesanan
            </p>

            <p style="font-size: 16px; color: #5E5E5E; line-height: 24px;">
                Halo {{ $order->customer_first_name }},
            </p>

            <p style="font-size: 16px; color: #5E5E5E; line-height: 24px;">
                Pesanan Anda dengan nomor <strong>#{{ $order->increment_id }}</strong> telah berubah status menjadi:
            </p>

            <p style="font-weight: bold; font-size: 20px; color: #242424; line-height: 24px; margin: 24px 0;">
                {{ $status->label() }}
            </p>

            @if($note)
            <p style="font-size: 16px; color: #5E5E5E; line-height: 24px;">
                <strong>Catatan:</strong> {{ $note }}
            </p>
            @endif

            @if($status === \Remix\OrderFlow\Enums\FulfillmentStatus::SHIPPED)
            <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 4px;">
                <p style="margin: 0;"><strong>Kurir:</strong> {{ $order->courier_name }}</p>
                <p style="margin: 5px 0 0;"><strong>No. Resi:</strong> {{ $order->courier_tracking_number }}</p>
            </div>
            @endif
        </div>
    </div>
</div>

@endcomponent
