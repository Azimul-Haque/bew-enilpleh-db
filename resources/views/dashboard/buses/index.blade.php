@extends('layouts.app')
@section('title') ড্যাশবোর্ড | বাস @endsection

@section('third_party_stylesheets')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
@endsection

@section('content')
  @section('page-header') জেলা তালিকা  @endsection
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-8">
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

                      @foreach ($districts->chunk(5) as $chunk)
                          <tr>
                              @foreach ($chunk as $district)
                                  <td>
                                    <a href="{{ route('dashboard.buses.districtwise', $district->id) }}" rel="tooltip" title="" data-original-title="{{ $district->name_bangla }}-এর বাস তালিকা দেখতে ক্লিক করুন">{{ $district->name_bangla }} <small>({{ bangla($district->buses->count()) }} টি বাস)</small></a>
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
        <div class="col-md-4">
          <div class="card">
              <div class="card-header">
                <h3 class="card-title">কাউন্টার ইনপুট</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-warning btn-sm"  data-toggle="modal" data-target="#addModal" style="margin-left: 5px;">
                    <i class="fas fa-user-plus"></i> নতুন
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <table class="table">
                  <thead>
                    <tr>
                      <th>বিভাগ</th>
                      <th align="right" width="20%">কার্যক্রম</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($medicaldepartments as $medicaldepartment)
                      <tr>
                        <td>
                          {{ $medicaldepartment->name }}
                        </td>
                        <td align="right">
                          {{-- <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#notifModal{{ $medicaldepartment->id }}">
                            <i class="fas fa-bell"></i>
                          </button> --}}
                          <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editDeptModal{{ $medicaldepartment->id }}">
                            <i class="fas fa-edit"></i>
                          </button>
                          {{-- Edit Dept Modal Code --}}
                          {{-- Edit Dept Modal Code --}}
                          <!-- Modal -->
                          <div class="modal fade" id="editDeptModal{{ $medicaldepartment->id }}" tabindex="-1" role="dialog" aria-labelledby="editDeptModalLabel" aria-hidden="true" data-backdrop="static">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                <div class="modal-header bg-warning">
                                  <h5 class="modal-title" id="editDeptModalLabel">বিভাগ তথ্য হালনাগাদ</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <form method="post" action="{{ route('dashboard.doctorsdept.update', $medicaldepartment->id) }}">
                                  <div class="modal-body">
                                    
                                        @csrf
                                        <div class="input-group mb-3">
                                            <input type="text"
                                                   name="name"
                                                   class="form-control"
                                                   value="{{ $medicaldepartment->name }}"
                                                   placeholder="বিভাগের নাম" required>
                                            <div class="input-group-append">
                                                <div class="input-group-text"><span class="fas fa-user-md"></span></div>
                                            </div>
                                        </div>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                                    <button type="submit" class="btn btn-warning">দাখিল করুন</button>
                                  </div>
                                </form>
                              </div>
                            </div>
                          </div>
                          {{-- Edit Dept Modal Code --}}
                          {{-- Edit Dept Modal Code --}}
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
        </div>
      </div>
    </div>


    {{-- Add Dept Modal Code --}}
    {{-- Add Dept Modal Code --}}
    <!-- Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-warning">
            <h5 class="modal-title" id="addModalLabel">নতুন বিভাগ যোগ</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" action="{{ route('dashboard.doctorsdept.store') }}">
            <div class="modal-body">
              
                  @csrf
                  <div class="input-group mb-3">
                      <input type="text"
                             name="name"
                             class="form-control"
                             value="{{ old('name') }}"
                             placeholder="বিভাগের নাম" required>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-user-md"></span></div>
                      </div>
                  </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
              <button type="submit" class="btn btn-warning">দাখিল করুন</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    {{-- Add Dept Modal Code --}}
    {{-- Add Dept Modal Code --}}

@endsection

@section('third_party_scripts')
    
@endsection