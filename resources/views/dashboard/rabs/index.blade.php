@extends('layouts.app')
@section('title') ড্যাশবোর্ড | র‍্যাব @endsection

@section('third_party_stylesheets')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
@endsection

@section('content')
  @section('page-header') জেলা তালিকা  @endsection
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-9">
          <div class="card">
                <div class="card-header">
                  <h3 class="card-title">জেলা তালিকা</h3>

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

                      @foreach ($districts->chunk(3) as $chunk)
                          <tr>
                              @foreach ($chunk as $district)
                                  <td>
                                    <a href="{{ route('dashboard.coachings.districtwise', $district->id) }}" rel="tooltip" title="" data-original-title="{{ $district->name_bangla }} জেলার কোচিং সেন্টারের তালিকা দেখতে ক্লিক করুন">{{ $district->name_bangla }} <small>({{ bangla($district->coachings->count()) }} টি কোচিং সেন্টার)</small></a>
                                  </td>
                                  <td>
                                    বাটন
                                  </td>
                              @endforeach
                              @if ($chunk->count() < 3)
                                  @for ($i = 0; $i < 3 - $chunk->count(); $i++)
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
        <div class="col-md-4"></div>
      </div>
    </div>

@endsection

@section('third_party_scripts')
    
@endsection