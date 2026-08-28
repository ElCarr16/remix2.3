@extends('shop::layouts.account')

@section('body')
  <visual:section name="visual-debut::customer-order-details" :order="$order" />
@endsection