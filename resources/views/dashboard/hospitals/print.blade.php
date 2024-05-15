@extends('layouts.app')
@section('title') জেলা তালিকা @endsection

@section('third_party_stylesheets')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/icheck-bootstrap@3.0.1/icheck-bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-datepicker.min.css') }}">
@endsection

@section('content')
  @section('page-header') জেলা @endsection
    <div class="container-fluid">
    <div class="card">
          <div class="card-header">
            <h3 class="card-title">জেলা</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body p-0">
            {{-- {{ $districts }} --}}
          </div>
          <!-- /.card-body -->
        </div>
    </div>

    

@endsection

@section('third_party_scripts')
    
@endsection