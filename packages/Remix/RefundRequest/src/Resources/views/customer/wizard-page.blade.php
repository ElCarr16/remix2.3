@extends('shop::layouts.account')

@section('page_title', 'Ajukan Refund')

@section('content')
    <livewire:remix-refund-request-wizard :order-id="$orderId" />
@endsection
