@extends('shop::layouts.default')

@section('page_title')
  Error {{ $errorCode ?? 500 }}
@stop

@section('body')
  <div class="container max-w-[1000px] mx-auto px-[15px] pt-10">
    <h1 class="text-3xl font-bold text-center">Error {{ $errorCode ?? 500 }}</h1>
    <p class="text-center mt-4">@isset($exception) {{ $exception->getMessage() }} @else An error occurred @endisset</p>
  </div>
@stop
