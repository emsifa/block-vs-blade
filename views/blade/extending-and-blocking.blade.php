@extends('layout-with-block')

@section('content')
  @parent
  <p>
    {{ $message }}
  </p>
@stop