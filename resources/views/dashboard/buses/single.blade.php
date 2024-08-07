@extends('layouts.app')
@section('title') ড্যাশবোর্ড | বাস তালিকা @endsection

@section('third_party_stylesheets')
  <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('css/select2-bootstrap4.min.css') }}" rel="stylesheet" />
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{ asset('js/select2.full.min.js') }}"></script>
    <style type="text/css">
      .select2-selection__choice{
          background-color: rgba(0, 123, 255) !important;
      }
      .select2-container--default .select2-selection--single {
          background-color: #fff;
          border: 1px solid #aaa;
          border-radius: 4px;
          height: 38px;
      }
    </style>
@endsection

@section('content')
  @section('page-header') বাস তালিকা / {{ $district->name_bangla }} জেলা (মোট {{ bangla($busescount) }} টি) @endsection
    <div class="container-fluid">
    <div class="card">
          <div class="card-header">
            <h3 class="card-title">বাস তালিকা</h3>

            <div class="card-tools">
              <form class="form-inline form-group-lg" action="">
                <div class="form-group">
                  <input type="search-param" class="form-control form-control-sm" placeholder="বাস খুঁজুন" id="search-param" required>
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
                  <th>হতে</th>
                  <th>পর্যন্ত</th>
                  <th>ছাড়ার সময়</th>
                  <th>রুটের তথ্য</th>
                  <th>অন্যান্য তথ্য</th>
                  <th align="right">কার্যক্রম</th>
                </tr>
              </thead>
              <tbody>
                @foreach($buses as $bus)
                  <tr>
                    <td>
                      {{ $bus->bus_name }}<br/>
                      <span class="badge bg-success">{{ $bus->bus_type }}</span>
                      <span class="badge bg-warning">{{ $bus->contact }}</span>
                    </td>
                    <td>{{ $bus->district->name_bangla }}</td>
                    <td>{{ $bus->toDistrict->name_bangla }}</td>
                    <td>{{ $bus->starting_time }}</td>
                    <td width="15%"><small>{{ $bus->route_info }}</small></td>
                    <td>
                      <span class="badge bg-primary">ভাড়া: ৳ {{ $bus->fare }}/-</span><br/>
                      <small>{{ $bus->counter_address }}</small>
                    </td>
                    <td align="right">
                      {{-- <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#notifModal{{ $bus->id }}">
                        <i class="fas fa-bell"></i>
                      </button> --}}
                      <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editUserModal{{ $bus->id }}">
                        <i class="fas fa-edit"></i>
                      </button>
                      <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteUserModal{{ $bus->id }}">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </td>
                    {{-- Edit User Modal Code --}}
                    {{-- Edit User Modal Code --}}
                    <!-- Modal -->
                    <div class="modal fade" id="editUserModal{{ $bus->id }}" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true" data-backdrop="static">
                      <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                          <div class="modal-header bg-primary">
                            <h5 class="modal-title" id="editUserModalLabel">বাস তথ্য হালনাগাদ</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <form method="post" action="{{ route('dashboard.buses.update', [$district->id, $bus->id]) }}" enctype="multipart/form-data">
                            <div class="modal-body">
                                @csrf
                                <h5>হতে জেলা: {{ $district->name_bangla }}</h5>
                                <div class="" style="margin-bottom: 15px;">
                                  <select name="to_district" class="form-control " required>
                                      <option selected="" disabled="" value="">গন্তব্য জেলা নির্বাচন করুন</option>
                                      @foreach($districts as $todistrict)
                                        <option value="{{ $todistrict->id }}" @if($todistrict->id == $bus->toDistrict->id) selected @endif >{{ $todistrict->name_bangla }} - {{ $todistrict->name }}</option>
                                      @endforeach
                                  </select>
                                  {{-- <div class="input-group-append">
                                      <div class="input-group-text"><span class="fas fa-map"></span></div>
                                  </div> --}}
                                </div>
                                
                                <div class="input-group mb-3">
                                    <input type="text"
                                           name="bus_name"
                                           class="form-control"
                                           value="{{ $bus->bus_name }}"
                                           placeholder="বাসের নাম" required>
                                    <div class="input-group-append">
                                        <div class="input-group-text"><span class="fas fa-bus"></span></div>
                                    </div>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="text"
                                           name="route_info"
                                           class="form-control"
                                           value="{{ $bus->route_info }}"
                                           placeholder="রুটের তথ্য (যেমন: টাঙ্গাইল হতে দর্শনা ভায়া সিরাজগঞ্জ, উল্লাপাড়া, শাহজাদপুর, কাশিনাথপুর, পাবনা, কুষ্টিয়া, ঝিনাইদহ, চুয়াডাঙ্গা)" required>
                                    <div class="input-group-append">
                                        <div class="input-group-text"><span class="fas fa-bus"></span></div>
                                    </div>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="text"
                                           name="bus_type"
                                           class="form-control"
                                           value="{{ $bus->bus_type }}"
                                           placeholder="AC/ Non-AC/ Volvo/ স্ক্যানিয়া/ ডাবল ডেকার ইত্যাদি" required>
                                    <div class="input-group-append">
                                        <div class="input-group-text"><span class="fas fa-bus"></span></div>
                                    </div>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="text"
                                           name="fare"
                                           class="form-control"
                                           value="{{ $bus->fare }}"
                                           placeholder="বাস ভাড়া" required>
                                    <div class="input-group-append">
                                        <div class="input-group-text"><span class="fas fa-bus"></span></div>
                                    </div>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="text"
                                           name="starting_time"
                                           class="form-control"
                                           value="{{ $bus->starting_time }}"
                                           placeholder="ছাড়ার সময়/ সময়সমূহ (একাধিক হলে কমা দিয়ে লিখুন)" required>
                                    <div class="input-group-append">
                                        <div class="input-group-text"><span class="fas fa-bus"></span></div>
                                    </div>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="text"
                                           name="counter_address"
                                           class="form-control"
                                           value="{{ $bus->counter_address }}"
                                           placeholder="কাউন্টার/ কাউন্টারসমূহ (একাধিক হলে কমা দিয়ে লিখুন)" required>
                                    <div class="input-group-append">
                                        <div class="input-group-text"><span class="fas fa-bus"></span></div>
                                    </div>
                                </div>                                
                                <div class="input-group mb-3">
                                    <input type="number"
                                           name="contact"
                                           value="{{ $bus->contact }}"
                                           class="form-control"
                                           placeholder="যোগাযোগ" required>
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
                        {{-- Delete User Modal Code --}}
                        {{-- Delete User Modal Code --}}
                        <!-- Modal -->
                        <div class="modal fade" id="deleteUserModal{{ $bus->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true" data-backdrop="static">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header bg-danger">
                                <h5 class="modal-title" id="deleteUserModalLabel">বাস ডিলেট</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                আপনি কি নিশ্চিতভাবে এই বাসকে ডিলেট করতে চান?<br/>
                                <center>
                                    <big><b>{{ $bus->name }}</b></big><br/>
                                    <small><i class="fas fa-phone"></i> {{ $bus->mobile }}</small>
                                </center>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                                <a href="{{ route('dashboard.buses.delete', [$district->id, $bus->id]) }}" class="btn btn-danger">ডিলেট করুন</a>
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
        {{ $buses->links() }}
    </div>

    {{-- Add User Modal Code --}}
    {{-- Add User Modal Code --}}
    <!-- Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-success">
            <h5 class="modal-title" id="addUserModalLabel">নতুন বাস যোগ (জেলা: <strong>{{ $district->name_bangla }}</strong>)</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" action="{{ route('dashboard.buses.store', $district->id) }}" enctype='multipart/form-data'>
            <div class="modal-body">
              
                  @csrf
                  <h5>হতে জেলা: {{ $district->name_bangla }}</h5>
                  <div class="" style="margin-bottom: 15px;">
                    <select name="to_district" class="form-control select2" required>
                        <option selected="" disabled="" value="">গন্তব্য জেলা নির্বাচন করুন</option>
                        @foreach($districts as $todistrict)
                          <option value="{{ $todistrict->id }}">{{ $todistrict->name_bangla }} - {{ $todistrict->name }}</option>
                        @endforeach
                    </select>
                    {{-- <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-map"></span></div>
                    </div> --}}
                  </div>
                  
                  <div class="input-group mb-3">
                      <input type="text"
                             name="bus_name"
                             class="form-control"
                             value="{{ old('bus_name') }}"
                             placeholder="বাসের নাম" required>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-bus"></span></div>
                      </div>
                  </div>
                  <div class="input-group mb-3">
                      <input type="text"
                             name="route_info"
                             class="form-control"
                             value="{{ old('route_info') }}"
                             placeholder="রুটের তথ্য (যেমন: টাঙ্গাইল হতে দর্শনা ভায়া সিরাজগঞ্জ, উল্লাপাড়া, শাহজাদপুর, কাশিনাথপুর, পাবনা, কুষ্টিয়া, ঝিনাইদহ, চুয়াডাঙ্গা)" required>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-bus"></span></div>
                      </div>
                  </div>
                  <div class="input-group mb-3">
                      <input type="text"
                             name="bus_type"
                             class="form-control"
                             value="{{ old('bus_type') }}"
                             placeholder="AC/ Non-AC/ Volvo/ স্ক্যানিয়া/ ডাবল ডেকার ইত্যাদি" required>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-bus"></span></div>
                      </div>
                  </div>
                  <div class="input-group mb-3">
                      <input type="text"
                             name="fare"
                             class="form-control"
                             value="{{ old('fare') }}"
                             placeholder="বাস ভাড়া" required>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-bus"></span></div>
                      </div>
                  </div>
                  <div class="input-group mb-3">
                      <input type="text"
                             name="starting_time"
                             class="form-control"
                             value="{{ old('starting_time') }}"
                             placeholder="ছাড়ার সময়/ সময়সমূহ (একাধিক হলে কমা দিয়ে লিখুন)" required>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-bus"></span></div>
                      </div>
                  </div>
                  <div class="input-group mb-3">
                      <input type="text"
                             name="counter_address"
                             class="form-control"
                             value="{{ old('counter_address') }}"
                             placeholder="কাউন্টার/ কাউন্টারসমূহ (একাধিক হলে কমা দিয়ে লিখুন)" required>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-bus"></span></div>
                      </div>
                  </div>
                  <div class="input-group mb-3">
                      <input type="number"
                             name="contact"
                             value="{{ old('contact') }}"
                             class="form-control"
                             placeholder="মোবাইল নং (১১ ডিজিট, ইংরেজিতে)" required>
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
    {{-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}
    <script type="text/javascript">
        $('.select2').select2({
          // theme: 'bootstrap4',
          dropdownParent: $('.modal')
        });
        

        $(document).on('click', '#search-button', function() {
          if($('#search-param').val() != '') {
            var urltocall = '{{ route('dashboard.buses.districtwise', $district->id) }}' +  '/' + $('#search-param').val();
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
              var urltocall = '{{ route('dashboard.buses.districtwise', $district->id) }}' +  '/' + $('#search-param').val();
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


        $(document).on('change', '.btn-file :file', function() {
          var input = $(this),
              label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
          input.trigger('fileselect', [label]);
        });

        $('.btn-file :file').on('fileselect', function(event, label) {
            var input = $(this).parents('.input-group').find(':text'),
                log = label;
            if( input.length ) {
                input.val(log);
            } else {
                if( log ) alert(log);
            }
        });
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#img-upload').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#image").change(function(){
            readURL(this);
            var filesize = parseInt((this.files[0].size)/1024);
            if(filesize > 2000) {
              $("#image").val('');
              // toastr.warning('File size is: '+filesize+' Kb. try uploading less than 300Kb', 'WARNING').css('width', '400px;');
              Toast.fire({
                  icon: 'warning',
                  title: 'File size is: '+filesize+' Kb. try uploading less than 2MB'
              })
              setTimeout(function() {
              $("#img-upload").attr('src', '{{ asset('images/placeholder.png') }}');
              }, 1000);
            }
        });
    </script>
@endsection