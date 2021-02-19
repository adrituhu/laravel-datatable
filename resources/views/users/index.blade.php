@extends('layouts.app')

@push('styles')
  <style>

    .dataTables_filter{ 
      display: none;
    }
  </style>
@endpush

@push('styles')
  <style>
    @import "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.css";
  </style>
@endpush

@section('content')
    <div class="container-fluid">
        {{$dataTable->table([], true)}}
    </div>
@endsection

@push('scripts')
    {{$dataTable->scripts()}}
@endpush
