@extends('layouts.app')

@push('styles')
  <style>
    @import "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.css";
  </style>
@endpush

@section('content')

    <div class="container">
      <div class="row">

        <form action="/users" method="GET" class="form-inline">

          <div class="form-group mr-2">
            <div class="mr-2"> Email </div>
            <input type="text" class="form-control" name="email" value="{{\Request::get('email')}}">
          </div>


          <div class="form-group mr-2">
            <div class="mr-2"> Jumlah Post </div>
            <select class="form-control" name="operator">
              <option value=">="> &gt;= </option>
              <option value=">"> &gt; </option>
              <option value="<"> &lt; </option>
              <option value="="> = </option>
            </select>
            <input type="text" class="form-control" name="jumlah_post" value="{{\Request::get('jumlah_post')}}">
          </div>

          <button type="submit" class="btn btn-primary"> Cari </button>
        </form>

      </div>
    </div>

    <hr/>

    <div class="container-fluid">
        {{$dataTable->table([], true)}}
    </div>
@endsection

@push('scripts')
    {{$dataTable->scripts()}}
@endpush
