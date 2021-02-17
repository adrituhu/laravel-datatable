@extends('layouts.app')

<style>
  .dataTables_filter{ 
    display: none;
  }
</style>

@section('content')
    <div class="container-fluid">
        {{$dataTable->table([], true)}}
    </div>
@endsection

@push('scripts')
    {{$dataTable->scripts()}}
@endpush
