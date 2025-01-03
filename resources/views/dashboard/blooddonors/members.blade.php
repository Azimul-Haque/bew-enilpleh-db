@extends('layouts.app')
@section('title') ড্যাশবোর্ড | রক্তদাতা প্রতিষ্ঠান ও সদস্য @endsection

@section('third_party_stylesheets')
   {{--  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/icheck-bootstrap@3.0.1/icheck-bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-datepicker.min.css') }}"> --}}
@endsection

@section('content')
  @section('page-header')  <a href="">রক্তদাতা তালিকা</a> / {{ $blooddonor->name }} (মোট সদস্য - {{ bangla($blooddonormemberscount) }} জন) @endsection
    <div class="container-fluid">
    <div class="card">
          <div class="card-header">
            <h3 class="card-title">রক্তদাতা সদস্য</h3>

            <div class="card-tools">
              <form class="form-inline form-group-lg" action="">
                <div class="form-group">
                  <input type="search-param" class="form-control form-control-sm" placeholder="রক্তদাতা খুঁজুন" id="search-param" required>
                </div>
                <button type="button" id="search-button" class="btn btn-default btn-sm" style="margin-left: 5px;">
                  <i class="fas fa-search"></i> খুঁজুন
                </button>
                {{-- <button type="button" class="btn btn-info btn-sm"  data-toggle="modal" data-target="#addBulkDate" style="margin-left: 5px;">
                  <i class="fas fa-calendar-alt"></i> বাল্ক মেয়াদ বাড়ান
                </button> --}}
                <button type="button" class="btn btn-success btn-sm"  data-toggle="modal" data-target="#addUserModal" style="margin-left: 5px;">
                  <i class="fas fa-plus-square"></i> নতুন
                </button>
              </form>
              
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body p-0">
            <table class="table">
              <thead>
                <tr>
                  <th>নাম</th>
                  <th>পদবি</th>
                  <th>রক্তের গ্রুপ</th>
                  <th>ঠিকানা ও যোগাযোগ</th>
                  <th align="right">কার্যক্রম</th>
                </tr>
              </thead>
              <tbody>
                @foreach($blooddonormembers as $blooddonormember)
                  <tr>
                    <td>
                      {{ $blooddonormember->name }}
                      <small class="text-black-50"><i class="fas fa-phone"></i> {{ $blooddonormember->contact }}</small>
                    </td>
                    <td>
                      {{ $blooddonormember->designation }}
                    </td>
                    <td>
                      {{ $blooddonormember->blood_group }}
                    </td>
                    <td>
                      {{ $blooddonormember->address }}
                    </td>
                    <td align="right">
                      {{-- <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#notifModal{{ $blooddonormember->id }}">
                        <i class="fas fa-bell"></i>
                      </button> --}}
                      <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editUserModal{{ $blooddonormember->id }}">
                        <i class="fas fa-edit"></i>
                      </button>
                      {{-- Edit User Modal Code --}}
                      {{-- Edit User Modal Code --}}
                      <!-- Modal -->
                      <div class="modal fade" id="editUserModal{{ $blooddonormember->id }}" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true" data-backdrop="static">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header bg-primary">
                              <h5 class="modal-title" id="editUserModalLabel">রক্তদাতা তথ্য হালনাগাদ</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <form method="post" action="{{ route('dashboard.blooddonormembers.update', $blooddonormember->id) }}">
                              <div class="modal-body">
                                
                                    @csrf

                                    <input type="hidden" name="blooddonor_id" value="{{ $blooddonor->id }}">

                                    <div class="input-group mb-3">
                                        <input type="text"
                                               name="name"
                                               class="form-control"
                                               value="{{ $blooddonormember->name }}"
                                               placeholder="রক্তদাতার নাম" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-hospital"></span></div>
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <input type="text"
                                               name="designation"
                                               class="form-control"
                                               value="{{ $blooddonormember->designation }}"
                                               placeholder="রক্তদাতার পদবি" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-tag"></span></div>
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                      <select name="blood_group" class="form-control" required>
                                          <option selected="" disabled="" value="">রক্তের গ্রুপ</option>
                                          <option value="A+ (এ পজিটিভ)" @if($blooddonormember->blood_group == 'A+ (এ পজিটিভ)') selected @endif>A+ (এ পজিটিভ)</option>
                                          <option value="B+ (বি পজিটিভ)" @if($blooddonormember->blood_group == 'B+ (বি পজিটিভ)') selected @endif>B+ (বি পজিটিভ)</option>
                                          <option value="AB+ (এবি পজিটিভ)" @if($blooddonormember->blood_group == 'AB+ (এবি পজিটিভ)') selected @endif>AB+ (এবি পজিটিভ)</option>
                                          <option value="O+ (ও পজিটিভ)" @if($blooddonormember->blood_group == 'O+ (ও পজিটিভ)') selected @endif>O+ (ও পজিটিভ)</option>
                                          <option value="A- (এ নেগেটিভ)" @if($blooddonormember->blood_group == 'A- (এ নেগেটিভ)') selected @endif>A- (এ নেগেটিভ)</option>
                                          <option value="B- (বি নেগেটিভ)" @if($blooddonormember->blood_group == 'B- (বি নেগেটিভ)') selected @endif>B- (বি নেগেটিভ)</option>
                                          <option value="AB- (এবি নেগেটিভ)" @if($blooddonormember->blood_group == 'AB- (এবি নেগেটিভ)') selected @endif>AB- (এবি নেগেটিভ)</option>
                                          <option value="O- (ও নেগেটিভ)" @if($blooddonormember->blood_group == 'O- (ও নেগেটিভ)') selected @endif>O- (ও নেগেটিভ)</option>
                                      </select>
                                      <div class="input-group-append">
                                          <div class="input-group-text"><span class="fas fa-tint"></span></div>
                                      </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <input type="text"
                                               name="address"
                                               class="form-control"
                                               value="{{ $blooddonormember->address }}"
                                               placeholder="রক্তদাতার ঠিকানা" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-map-marked-alt"></span></div>
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <input type="number"
                                               name="contact"
                                               value="{{ $blooddonormember->contact }}"
                                               autocomplete="off"
                                               class="form-control"
                                               placeholder="মোবাইল নম্বর" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-mobile"></span></div>
                                        </div>
                                    </div>          
                                
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                                <button type="submit" class="btn btn-primary">দাখিল করুন</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                      {{-- Edit User Modal Code --}}
                      {{-- Edit User Modal Code --}}

                      <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteUserModal{{ $blooddonormember->id }}">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </td>
                        {{-- Delete User Modal Code --}}
                        {{-- Delete User Modal Code --}}
                        <!-- Modal -->
                        <div class="modal fade" id="deleteUserModal{{ $blooddonormember->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true" data-backdrop="static">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header bg-danger">
                                <h5 class="modal-title" id="deleteUserModalLabel">রক্তদাতা ডিলেট</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                আপনি কি নিশ্চিতভাবে এই রক্তদাতাকে ডিলেট করতে চান?<br/>
                                <center>
                                    <big><b>{{ $blooddonormember->name }}</b></big><br/>
                                    <small><i class="fas fa-phone"></i> {{ $blooddonormember->contact }}</small>
                                </center>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                                <a href="{{ route('dashboard.blooddonormembers.delete', [$blooddonor->id, $blooddonormember->id]) }}" class="btn btn-danger">ডিলেট করুন</a>
                              </div>
                            </div>
                          </div>
                        </div>
                        {{-- Delete User Modal Code --}}
                        {{-- Delete User Modal Code --}}
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        {{ $blooddonormembers->links() }}
    </div>

    {{-- Add User Modal Code --}}
    {{-- Add User Modal Code --}}
    <!-- Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-success">
            <h5 class="modal-title" id="addUserModalLabel">নতুন রক্তদাতা যোগ (প্রতিষ্ঠান: <b>{{ $blooddonor->name }}</b>)</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" action="{{ route('dashboard.blooddonormembers.store') }}">
            <div class="modal-body">
              
                  @csrf

                  <input type="hidden" name="blooddonor_id" value="{{ $blooddonor->id }}">

                  <div class="input-group mb-3">
                      <input type="text"
                             name="name"
                             class="form-control"
                             value="{{ old('name') }}"
                             placeholder="রক্তদাতার নাম" required>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-hospital"></span></div>
                      </div>
                  </div>
                  <div class="input-group mb-3">
                      <input type="text"
                             name="designation"
                             class="form-control"
                             value="{{ old('designation') }}"
                             placeholder="রক্তদাতার পদবি" required>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-tag"></span></div>
                      </div>
                  </div>
                  <div class="input-group mb-3">
                    <select name="blood_group" class="form-control" required>
                        <option selected="" disabled="" value="">রক্তের গ্রুপ</option>
                        <option value="A+ (এ পজিটিভ)">A+ (এ পজিটিভ)</option>
                        <option value="B+ (বি পজিটিভ)">B+ (বি পজিটিভ)</option>
                        <option value="AB+ (এবি পজিটিভ)">AB+ (এবি পজিটিভ)</option>
                        <option value="O+ (ও পজিটিভ)">O+ (ও পজিটিভ)</option>
                        <option value="A- (এ নেগেটিভ)">A- (এ নেগেটিভ)</option>
                        <option value="B- (বি নেগেটিভ)">B- (বি নেগেটিভ)</option>
                        <option value="AB- (এবি নেগেটিভ)">AB- (এবি নেগেটিভ)</option>
                        <option value="O- (ও নেগেটিভ)">O- (ও নেগেটিভ)</option>
                    </select>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-tint"></span></div>
                    </div>
                  </div>
                  <div class="input-group mb-3">
                      <input type="text"
                             name="address"
                             class="form-control"
                             value="{{ old('address') }}"
                             placeholder="রক্তদাতার ঠিকানা" required>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-map-marked-alt"></span></div>
                      </div>
                  </div>
                  <div class="input-group mb-3">
                      <input type="number"
                             name="contact"
                             value="{{ old('contact') }}"
                             autocomplete="off"
                             class="form-control"
                             placeholder="মোবাইল নম্বর" required>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-mobile"></span></div>
                      </div>
                  </div>         
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
              <button type="submit" class="btn btn-success">দাখিল করুন</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    {{-- Add User Modal Code --}}
    {{-- Add User Modal Code --}}

@endsection

@section('third_party_scripts')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).on('click', '#search-button', function() {
          if($('#search-param').val() != '') {
            var urltocall = '{{ route('dashboard.blooddonormembers', $blooddonor->id) }}' +  '/' + $('#search-param').val();
            location.href= urltocall;
          } else {
            $('#search-param').css({ "border": '#FF0000 2px solid'});
            Toast.fire({
                icon: 'warning',
                title: 'কিছু লিখে খুঁজুন!'
            })
          }
        });
        $("#search-param").keyup(function(e) {
          if(e.which == 13) {
            if($('#search-param').val() != '') {
              var urltocall = '{{ route('dashboard.blooddonormembers', $blooddonor->id) }}' +  '/' + $('#search-param').val();
              location.href= urltocall;
            } else {
              $('#search-param').css({ "border": '#FF0000 2px solid'});
              Toast.fire({
                  icon: 'warning',
                  title: 'কিছু লিখে খুঁজুন!'
              })
            }
          }
        });
    </script>
@endsection