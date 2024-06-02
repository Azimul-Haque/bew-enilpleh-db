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
            <table class="table table-condensed">
              {{-- <thead>
                <tr>
                  <th>জেলার নাম</th>
                </tr>
              </thead> --}}
              <tbody>
                {{-- @foreach($districts as $district)
                  <tr>
                    <td style="font-size: 14px;">
                      {{ $district->name }}
                    </td>
                  </tr>
                @endforeach --}}

                @foreach ($districts->chunk(5) as $chunk)
                    <tr>
                        @foreach ($chunk as $district)
                            <td>
                              <a href="{{ route('dashboard.admins.districtwise', $district->id) }}">{{ $district->name_bangla }}</a>
                            </td>
                        @endforeach
                        @if ($chunk->count() < 5)
                            @for ($i = 0; $i < 5 - $chunk->count(); $i++)
                                <td></td>
                            @endfor
                        @endif
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