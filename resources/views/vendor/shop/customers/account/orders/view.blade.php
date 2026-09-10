@extends('shop::layouts.account')

@section('body')
  {!! view_render_event('bagisto.shop.customers.account.orders.view.before', ['order' => $order]) !!}
  <visual:section name="visual-debut::customer-order-details" :order="$order" />
  {!! view_render_event('bagisto.shop.customers.account.orders.view.after', ['order' => $order]) !!}
@endsection
