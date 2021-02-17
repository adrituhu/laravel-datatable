@extends('layouts.app')

@push('styles')
  <style>
    .dataTables_filter{ 
      display: none;
    }
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
