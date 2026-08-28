@extends('remix::layouts.refund-account')

@section('page_title', 'Ajukan Refund')

@section('content')
    <livewire:remix-refund-request-wizard :order-id="$orderId" />
@endsection
