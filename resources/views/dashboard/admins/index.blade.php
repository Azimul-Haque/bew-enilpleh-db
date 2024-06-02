@extends('layouts.app')
@section('title') ড্যাশবোর্ড | প্রশাসন কর্মকর্তাগণ @endsection

@section('third_party_stylesheets')
   {{--  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/icheck-bootstrap@3.0.1/icheck-bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-datepicker.min.css') }}"> --}}
@endsection

@section('content')
  @section('page-header') জেলা তালিকা  @endsection
    <div class="container-fluid">
    <div class="card">
          <div class="card-header">
            <h3 class="card-title">জেলা তালিকা তালিকা</h3>

            <div class="card-tools"></div>
          </div>
          <!-- /.card-header -->
          <div class="card-body p-0">
            <table class="table">
              <thead>
                <tr>
                  <th>জেলার নাম</th>
                  <th align="right">কার্যক্রম</th>
                </tr>
              </thead>
              <tbody>
                @foreach($districts as $esheba)
                  <tr>
                    <td>
                      {{ $esheba->name }}<br/>
                    </td>
                    <td>
                      <a href="{{ $esheba->url }}" target="_blank">{{ $esheba->name }} (ক্লিক করুন)</a>
                    </td>
                  </tr>
                  
                @endforeach
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
    </div>

@endsection

@section('third_party_scripts')
    
@endsection