@extends('layouts.app')

@push('styles')
  <style>
    @import "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.css";
  </style>
@endpush

@section('content')

    <div class="container">
      <form action="/users" method="GET">
      <input type="text" class="form-control" name="email" value="{{\Request::get('email')}}">
      </form>
    </div>

    <hr/>

    <div class="container-fluid">
        {{$dataTable->table([], true)}}
    </div>
@endsection

@push('scripts')
    {{$dataTable->scripts()}}
@endpush
