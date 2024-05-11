@extends('layouts.app')
@section('title') ড্যাশবোর্ড | হাসপাতাল তালিকা @endsection

@section('third_party_stylesheets')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/icheck-bootstrap@3.0.1/icheck-bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-datepicker.min.css') }}">
@endsection

@section('content')
  @section('page-header') হাসপাতাল তালিকা (মোট {{ bangla($hospitalscount) }} টি) @endsection
    <div class="container-fluid">
    <div class="card">
          <div class="card-header">
            <h3 class="card-title">হাসপাতাল তালিকা</h3>

            <div class="card-tools">
              <form class="form-inline form-group-lg" action="">
                <div class="form-group">
                  <input type="search-param" class="form-control form-control-sm" placeholder="হাসপাতাল খুঁজুন" id="search-param" required>
                </div>
                <button type="button" id="search-button" class="btn btn-default btn-sm" style="margin-left: 5px;">
                  <i class="fas fa-search"></i> খুঁজুন
                </button>
                {{-- <button type="button" class="btn btn-info btn-sm"  data-toggle="modal" data-target="#addBulkDate" style="margin-left: 5px;">
                  <i class="fas fa-calendar-alt"></i> বাল্ক মেয়াদ বাড়ান
                </button> --}}
                <button type="button" class="btn btn-success btn-sm"  data-toggle="modal" data-target="#addUserModal" style="margin-left: 5px;">
                  <i class="fas fa-user-plus"></i> নতুন
                </button>
              </form>
              
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body p-0">
            <table class="table">
              <tbody>
                {{-- <tr>
                  <td>1.</td>
                  <td>Update software</td>
                  <td>
                    <div class="progress progress-xs">
                      <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                    </div>
                  </td>
                  <td><span class="badge bg-danger">55%</span></td>
                </tr> --}}
                @foreach($hospitals as $hospital)
                  <tr>
                    <td>
                      <a href="{{ route('dashboard.users.single', $hospital->id) }}">{{ $hospital->name }}</a>
                      <small><b>প্যাকেজ: ({{ bangla($hospital->payments->count()) }} বার)</b>, <b>পরীক্ষা: {{ bangla($hospital->meritlists->count()) }} টি</b></small>
                      <br/>
                            {{-- {{ $hospital->balances2 }} --}}
                      <small class="text-black-50">{{ $hospital->mobile }}</small> 
                      <span class="badge @if($hospital->role == 'admin') bg-success @else bg-info @endif">{{ ucfirst($hospital->role) }}</span>,
                      <small><span>যোগদান: {{ date('d F, Y h:i A', strtotime($hospital->created_at)) }}</span></small>,
                      <small><span>প্যাকেজ: <b>{{ date('d F, Y', strtotime($hospital->package_expiry_date)) }}</b></span></small>
                    </td>
                    <td align="right" width="40%">
                      <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#smsModal{{ $hospital->id }}">
                        <i class="fas fa-envelope"></i>
                      </button>
                      {{-- SMS Modal Code --}}
                      {{-- SMS Modal Code --}}
                      <!-- Modal -->
                      <div class="modal fade" id="smsModal{{ $hospital->id }}" tabindex="-1" role="dialog" aria-labelledby="smsModalLabel" aria-hidden="true" data-backdrop="static">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header bg-info">
                              <h5 class="modal-title" id="smsModalLabel">এসএমএস পাঠান</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <form method="post" action="{{ route('dashboard.users.singlesms', $hospital->id) }}">
                              <div class="modal-body">
                                    @csrf
                                    <textarea class="form-control" placeholder="মেসেজ লিখুন" name="message" style="min-height: 150px; resize: none;" required></textarea>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                                <button type="submit" class="btn btn-info">মেসেজ পাঠান</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                      {{-- SMS Modal Code --}}
                      {{-- SMS Modal Code --}}
                      <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#notifModal{{ $hospital->id }}">
                        <i class="fas fa-bell"></i>
                      </button>
                      {{-- Notif Modal Code --}}
                      {{-- Notif Modal Code --}}
                      <!-- Modal -->
                      <div class="modal fade" id="notifModal{{ $hospital->id }}" tabindex="-1" role="dialog" aria-labelledby="notifModalLabel" aria-hidden="true" data-backdrop="static">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header bg-warning">
                              <h5 class="modal-title" id="notifModalLabel">নোটিফিকেশন পাঠান</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <form method="post" action="{{ route('dashboard.users.singlenotification', $hospital->id) }}">
                              <div class="modal-body">
                                    @csrf
                                    <div class="input-group mb-3">
                                        <input type="text"
                                               name="headings"
                                               class="form-control"
                                               placeholder="হেডিংস" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-file-alt"></span></div>
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <input type="text"
                                               name="message"
                                               class="form-control"
                                               placeholder="মেসেজ" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-spa"></span></div>
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
                      {{-- Notif Modal Code --}}
                      {{-- Notif Modal Code --}}
                      <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editUserModal{{ $hospital->id }}">
                        <i class="fas fa-user-edit"></i>
                      </button>
                      {{-- Edit User Modal Code --}}
                      {{-- Edit User Modal Code --}}
                      <!-- Modal -->
                      <div class="modal fade" id="editUserModal{{ $hospital->id }}" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true" data-backdrop="static">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header bg-primary">
                              <h5 class="modal-title" id="editUserModalLabel">হাসপাতাল তথ্য হালনাগাদ</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <form method="post" action="{{ route('dashboard.users.update', $hospital->id) }}">
                              <div class="modal-body">
                                
                                    @csrf

                                    <div class="input-group mb-3">
                                        <input type="text"
                                               name="name"
                                               class="form-control"
                                               value="{{ $hospital->name }}"
                                               placeholder="নাম" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-user"></span></div>
                                        </div>
                                    </div>

                                    <div class="input-group mb-3">
                                        <input type="text"
                                               name="mobile"
                                               value="{{ $hospital->mobile }}"
                                               autocomplete="off"
                                               class="form-control"
                                               placeholder="মোবাইল নম্বর (১১ ডিজিট)" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-phone"></span></div>
                                        </div>
                                    </div>

                                    <div class="input-group mb-3">
                                        <input type="text"
                                               name="uid"
                                               value="{{ $hospital->uid }}"
                                               autocomplete="off"
                                               class="form-control"
                                               placeholder="Firebase UID">
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-server"></span></div>
                                        </div>
                                    </div>

                                    <div class="input-group mb-3">
                                        <input type="text"
                                               name="onesignal_id"
                                               value="{{ $hospital->onesignal_id }}"
                                               autocomplete="off"
                                               class="form-control"
                                               placeholder="Onesignal Player ID">
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-bell"></span></div>
                                        </div>
                                    </div>

                                    <div class="input-group mb-3">
                                      <select name="role" class="form-control" required>
                                        <option disabled="" value="">ধরন নির্ধারণ করুন</option>
                                        <option value="admin" @if($hospital->role == 'admin') selected="" @endif>এডমিন</option>
                                        <option value="manager" @if($hospital->role == 'manager') selected="" @endif>ম্যানেজার</option>
                                        <option value="volunteer" @if($hospital->role == 'volunteer') selected="" @endif>ভলান্টিয়ার</option>
                                        <option value="user" @if($hospital->role == 'user') selected="" @endif>হাসপাতাল</option>
                                        {{-- <option value="accountant" @if($hospital->role == 'accountant') selected="" @endif>একাউন্টেন্ট</option> --}}
                                      </select>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-user-secret"></span></div>
                                        </div>
                                    </div>

                                    <div class="input-group mb-3">
                                        <input type="text"
                                               name="packageexpirydate"
                                               id="packageexpirydate{{ $hospital->id }}" 
                                               value="{{ date('F d, Y', strtotime($hospital->package_expiry_date)) }}"
                                               autocomplete="off"
                                               class="form-control"
                                               placeholder="প্যাকেজের মেয়াদ বৃদ্ধি" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-calendar-check"></span></div>
                                        </div>
                                    </div>

                                    <div class="input-group mb-3">
                                        <input type="password"
                                               name="password"
                                               class="form-control"
                                               autocomplete="new-password"
                                               placeholder="পাসওয়ার্ড (ঐচ্ছিক)">
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-lock"></span></div>
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

                      <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteUserModal{{ $hospital->id }}">
                        <i class="fas fa-user-minus"></i>
                      </button>
                    </td>
                        {{-- Delete User Modal Code --}}
                        {{-- Delete User Modal Code --}}
                        <!-- Modal -->
                        <div class="modal fade" id="deleteUserModal{{ $hospital->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true" data-backdrop="static">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header bg-danger">
                                <h5 class="modal-title" id="deleteUserModalLabel">হাসপাতাল ডিলেট</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                আপনি কি নিশ্চিতভাবে এই হাসপাতালকে ডিলেট করতে চান?<br/>
                                <center>
                                    <big><b>{{ $hospital->name }}</b></big><br/>
                                    <small><i class="fas fa-phone"></i> {{ $hospital->mobile }}</small>
                                </center>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                                <a href="{{ route('dashboard.users.delete', $hospital->id) }}" class="btn btn-danger">ডিলেট করুন</a>
                              </div>
                            </div>
                          </div>
                        </div>
                        {{-- Delete User Modal Code --}}
                        {{-- Delete User Modal Code --}}
                  </tr>
                  <script type="text/javascript" src="{{ asset('js/jquery-for-dp.min.js') }}"></script>
                  <script type="text/javascript" src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
                  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
                  <script>
                    $("#packageexpirydate{{ $hospital->id }}").datepicker({
                      format: 'MM dd, yyyy',
                      todayHighlight: true,
                      autoclose: true,
                    });
                  </script>
                @endforeach
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        {{ $hospitals->links() }}
    </div>

    {{-- Add User Modal Code --}}
    {{-- Add User Modal Code --}}
    <!-- Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-success">
            <h5 class="modal-title" id="addUserModalLabel">নতুন হাসপাতাল যোগ</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" action="{{ route('dashboard.users.store') }}">
            <div class="modal-body">
              
                  @csrf

                  <div class="input-group mb-3">
                    <select name="district" id="district" class="form-control" required>
                        <option selected="" disabled="" value="">জেলা নির্বাচন করুন</option>
                        @foreach($districts as $district)
                          <option value="{{ $district->id }}">{{ $district->name_bangla }}</option>
                        @endforeach
                    </select>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-map"></span></div>
                    </div>
                  </div>
                  <div class="input-group mb-3">
                    <select name="upazilla" id="upazilla" class="form-control" required>
                        <option selected="" disabled="" value="">উপজেলা নির্বাচন করুন</option>
                    </select>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-map-marked-alt"></span></div>
                    </div>
                  </div>
                  <div class="input-group mb-3">
                      <input type="text"
                             name="name"
                             class="form-control"
                             value="{{ old('name') }}"
                             placeholder="হাসপাতালের নাম" required>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-hospital"></span></div>
                      </div>
                  </div>
                  <div class="input-group mb-3">
                    <select name="hospital_type" class="form-control" required>
                        <option selected="" disabled="" value="">হাসপাতালের ধরন</option>
                        <option value="1">মেডিকেল কলেজ ও হাসপাতাল</option>
                        <option value="2">প্রাইভেট হাসপাতাল</option>
                        <option value="3">স্বাস্থ্য কমপ্লেক্স</option>
                    </select>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-star-half-alt"></span></div>
                    </div>
                  </div>
                  <div class="input-group mb-3">
                      <input type="number"
                             name="telephone"
                             class="form-control"
                             value="{{ old('telephone') }}"
                             placeholder="টেলিফোন নং" required>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-phone"></span></div>
                      </div>
                  </div>
                  <div class="input-group mb-3">
                      <input type="number"
                             name="mobile"
                             value="{{ old('mobile') }}"
                             autocomplete="off"
                             class="form-control"
                             placeholder="মোবাইল নম্বর" required>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-mobile"></span></div>
                      </div>
                  </div>
                  <div class="input-group mb-3">
                      <input type="text"
                             name="location"
                             value="{{ old('location') }}"
                             autocomplete="off"
                             class="form-control"
                             placeholder="গুগল ম্যাপ লোকেশন লিংক" required>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-map-marker-alt"></span></div>
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

    {{-- Add Bulk Date Modal Code --}}
    {{-- Add Bulk Date Modal Code --}}
    <!-- Modal -->
    <div class="modal fade" id="addBulkDate" tabindex="-1" role="dialog" aria-labelledby="addBulkDateLabel" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-info">
            <h5 class="modal-title" id="addBulkDateLabel">নতুন হাসপাতাল যোগ</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" action="{{ route('dashboard.users.bulk.package.update') }}">
            <div class="modal-body">
              
                  @csrf

                  <div class="input-group mb-3">
                      <textarea type="text"
                             name="numbers"
                             class="form-control"
                             placeholder="নাম্বারসমূহ দিন (কমা সেপারেটেড)" required></textarea>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-user"></span></div>
                      </div>
                  </div>

                  <div class="input-group mb-3">
                      <input type="text"
                             name="packageexpirydatebulk"
                             id="packageexpirydatebulk" 
                             autocomplete="off"
                             class="form-control"
                             placeholder="প্যাকেজের মেয়াদ বৃদ্ধি" required>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-calendar-check"></span></div>
                      </div>
                  </div>
              
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
              <button type="submit" class="btn btn-info">দাখিল করুন</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    {{-- Add Bulk Date Modal Code --}}
    {{-- Add Bulk Date Modal Code --}}

    <script>
      $("#packageexpirydatebulk").datepicker({
        format: 'MM dd, yyyy',
        todayHighlight: true,
        autoclose: true,
      });
    </script>
@endsection

@section('third_party_scripts')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">

        $('#district').on('change', function() {
          $('#upazilla').prop('disabled', true);
          $('#upazilla').append('<option value="" selected disabled>উপজেলা লোড হচ্ছে...</option>');

          $.ajax({
            url: "/api/getupazillas/"+$(this).val(), 
            type: "GET",
            success: function(result){
              $('#upazilla')
                  .find('option')
                  .remove()
                  .end()
                  .prop('disabled', false)
                  .append('<option value="" selected disabled>উপজেলা নির্ধারণ করুন</option>')
              ;
              for(var countupazilla = 0; countupazilla < result.length; countupazilla++) {
                console.log(result[countupazilla]);
                $('#upazilla').append('<option value="'+result[countupazilla]['id']+'">'+result[countupazilla]['name_bangla']+'</option>')
              }
            }
          });
        });

        $('#adduserrole').change(function () {
            if($('#adduserrole').val() == 'accountant') {
                $('#ifaccountant').hide();
            } else {
                $('#ifaccountant').show();
            }
        });


        $(document).on('click', '#search-button', function() {
          if($('#search-param').val() != '') {
            var urltocall = '{{ route('dashboard.hospitals') }}' +  '/' + $('#search-param').val();
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
              var urltocall = '{{ route('dashboard.users') }}' +  '/' + $('#search-param').val();
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