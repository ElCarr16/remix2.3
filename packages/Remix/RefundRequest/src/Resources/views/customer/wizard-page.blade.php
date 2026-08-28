<x-shop::layouts.account>
    <x-slot:title>
        Ajukan Refund
    </x-slot>

    <livewire:remix-refund-request-wizard :order-id="$orderId" />
</x-shop::layouts.account>
