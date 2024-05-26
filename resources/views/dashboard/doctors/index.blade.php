@extends('layouts.app')
@section('title') ড্যাশবোর্ড | ডাক্তার তালিকা @endsection

@section('third_party_stylesheets')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2-bootstrap4.min.css') }}" rel="stylesheet" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{ asset('js/select2.full.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
    <style type="text/css">
      .select2-selection__choice{
          background-color: rgba(0, 123, 255) !important;
      }
    </style>
@endsection

@section('content')
  @section('page-header') ডাক্তার তালিকা (মোট {{ bangla($doctorscount) }} জন) @endsection
    <div class="container-fluid">
    <div class="card">
          <div class="card-header">
            <h3 class="card-title">ডাক্তার তালিকা</h3>

            <div class="card-tools">
              <form class="form-inline form-group-lg" action="">
                <div class="form-group">
                  <input type="search-param" class="form-control form-control-sm" placeholder="ডাক্তার খুঁজুন" id="search-param" required>
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
              <thead>
                <tr>
                  <th>নাম</th>
                  <th>বিভাগ</th>
                  <th>লক্ষণ</th>
                  <th align="right">কার্যক্রম</th>
                </tr>
              </thead>
              <tbody>
                @foreach($doctors as $doctor)
                  <tr>
                    <td>
                      {{ $doctor->name }}
                      <small class="text-black-50"><i class="fas fa-phone"></i> {{ $doctor->serial }}</small>
                      <small class="text-black-50"><i class="fas fa-mobile"></i> {{ $doctor->helpline }}</small><br/>
                      <span class="badge bg-success">{{ $doctor->degree }}</span>
                    </td>
                    <td>
                      {{ $doctor->upazilla->name_bangla }}, {{ $doctor->district->name_bangla }}
                    </td>
                    <td align="right" width="40%">
                      {{-- <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#notifModal{{ $doctor->id }}">
                        <i class="fas fa-bell"></i>
                      </button> --}}
                      <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editUserModal{{ $doctor->id }}">
                        <i class="fas fa-edit"></i>
                      </button>
                      {{-- Edit User Modal Code --}}
                      {{-- Edit User Modal Code --}}
                      <!-- Modal -->
                      <div class="modal fade" id="editUserModal{{ $doctor->id }}" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true" data-backdrop="static">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header bg-primary">
                              <h5 class="modal-title" id="editUserModalLabel">ডাক্তার তথ্য হালনাগাদ</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <form method="post" action="{{ route('dashboard.hospitals.update', $doctor->id) }}">
                              <div class="modal-body">
                                
                                    @csrf

                                    <div class="input-group mb-3">
                                      <select name="district_id" id="district" class="form-control district" required>
                                          <option selected="" disabled="" value="">জেলা নির্বাচন করুন</option>
                                          {{-- @foreach($districts as $district)
                                            <option value="{{ $district->id }}" @if($district->id == $doctor->district_id) selected @endif>{{ $district->name_bangla }}</option>
                                          @endforeach --}}
                                      </select>
                                      <div class="input-group-append">
                                          <div class="input-group-text"><span class="fas fa-map"></span></div>
                                      </div>
                                    </div>
                                    <div class="input-group mb-3">
                                      <select name="upazilla_id" id="upazilla" class="form-control upazilla" required>
                                          <option selected="" value="{{ $doctor->upazilla_id }}">{{ $doctor->upazilla->name_bangla }}</option>
                                      </select>
                                      <div class="input-group-append">
                                          <div class="input-group-text"><span class="fas fa-map-marked-alt"></span></div>
                                      </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <input type="text"
                                               name="name"
                                               class="form-control"
                                               value="{{ $doctor->name }}"
                                               placeholder="ডাক্তারের নাম" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-hospital"></span></div>
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                      <select name="hospital_type" class="form-control" required>
                                          <option selected="" disabled="" value="">ডাক্তারের ধরন</option>
                                          <option value="1" @if($doctor->hospital_type == 1) selected @endif>মেডিকেল কলেজ ও ডাক্তার</option>
                                          <option value="2" @if($doctor->hospital_type == 2) selected @endif>প্রাইভেট ডাক্তার</option>
                                          <option value="3" @if($doctor->hospital_type == 3) selected @endif>স্বাস্থ্য কমপ্লেক্স</option>
                                      </select>
                                      <div class="input-group-append">
                                          <div class="input-group-text"><span class="fas fa-star-half-alt"></span></div>
                                      </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <input type="number"
                                               name="telephone"
                                               class="form-control"
                                               value="{{ $doctor->telephone }}"
                                               placeholder="টেলিফোন নং" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-phone"></span></div>
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <input type="number"
                                               name="mobile"
                                               value="{{ $doctor->mobile }}"
                                               class="form-control"
                                               placeholder="মোবাইল নম্বর" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-mobile"></span></div>
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <input type="text"
                                               name="location"
                                               value="{{ $doctor->location }}"
                                               class="form-control"
                                               placeholder="গুগল ম্যাপ লোকেশন লিংক" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-map-marker-alt"></span></div>
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

                      <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteUserModal{{ $doctor->id }}">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </td>
                        {{-- Delete User Modal Code --}}
                        {{-- Delete User Modal Code --}}
                        <!-- Modal -->
                        <div class="modal fade" id="deleteUserModal{{ $doctor->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true" data-backdrop="static">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header bg-danger">
                                <h5 class="modal-title" id="deleteUserModalLabel">ডাক্তার ডিলেট</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                আপনি কি নিশ্চিতভাবে এই ডাক্তারকে ডিলেট করতে চান?<br/>
                                <center>
                                    <big><b>{{ $doctor->name }}</b></big><br/>
                                    <small><i class="fas fa-phone"></i> {{ $doctor->mobile }}</small>
                                </center>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                                <a href="{{ route('dashboard.doctors.delete', $doctor->id) }}" class="btn btn-danger">ডিলেট করুন</a>
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
                    $("#packageexpirydate{{ $doctor->id }}").datepicker({
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
        {{ $doctors->links() }}
    </div>

    {{-- Add User Modal Code --}}
    {{-- Add User Modal Code --}}
    <!-- Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-success">
            <h5 class="modal-title" id="addUserModalLabel">নতুন ডাক্তার যোগ</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" action="{{ route('dashboard.doctors.store') }}">
            <div class="modal-body">
              
                  @csrf

                  <div class="row">
                    <div class="col-md-6">
                      <div class="input-group mb-3">
                        <select name="district_id" id="district" class="form-control district" required>
                            <option selected="" disabled="" value="">জেলা নির্বাচন করুন</option>
                            @foreach($districts as $district)
                              <option value="{{ $district->id }}">{{ $district->name_bangla }}</option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-map"></span></div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="input-group mb-3">
                        <select name="upazilla_id" id="upazilla" class="form-control upazilla" required>
                            <option selected="" disabled="" value="">উপজেলা নির্বাচন করুন</option>
                        </select>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-map-marked-alt"></span></div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="input-group mb-3">
                          <input type="text"
                                 name="name"
                                 class="form-control"
                                 value="{{ old('name') }}"
                                 placeholder="ডাক্তারের নাম" required>
                          <div class="input-group-append">
                              <div class="input-group-text"><span class="fas fa-user-md"></span></div>
                          </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="input-group mb-3">
                          <input type="text"
                                 name="degree"
                                 value="{{ old('degree') }}"
                                 
                                 class="form-control"
                                 placeholder="ডাক্তারের ডিগ্রি/ ডিগ্রিসমূহ (যেমন: MBBS, FCPS, MD)" required>
                          <div class="input-group-append">
                              <div class="input-group-text"><span class="fas fa-certificate"></span></div>
                          </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="input-group mb-3">
                          <input type="number"
                                 name="serial"
                                 class="form-control"
                                 value="{{ old('serial') }}"
                                 placeholder="সিরিয়াল নেওয়ার ফোন নং" required>
                          <div class="input-group-append">
                              <div class="input-group-text"><span class="fas fa-phone"></span></div>
                          </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="input-group mb-3">
                          <input type="number"
                                 name="helpline"
                                 value="{{ old('helpline') }}"
                                 
                                 class="form-control"
                                 placeholder="হেল্পলাইন নম্বর (যদি থাকে)">
                          <div class="input-group-append">
                              <div class="input-group-text"><span class="fas fa-mobile"></span></div>
                          </div>
                      </div>
                    </div>
                  </div>
                  
                  <div style="margin-bottom: 15px;">
                    <select name="medicaldepartments[]" class="form-control multiple-select" multiple="multiple" data-placeholder="বিভাগ (প্রয়োজনে একাধিক সিলেক্ট করা যাবে)" required>
                        
                        @foreach($medicaldepartments as $medicaldepartment)
                          <option value="{{ $medicaldepartment->id }}">{{ $medicaldepartment->name }}</option>
                        @endforeach
                    </select>
                  </div> 
                  
                  <div style="margin-bottom: 15px;">
                    <select name="medicalsymptoms[]" class="form-control multiple-select" multiple="multiple" data-placeholder="লক্ষণ (প্রয়োজনে একাধিক সিলেক্ট করা যাবে)" required>
                        
                        @foreach($medicalsymptoms as $medicalsymptom)
                          <option value="{{ $medicalsymptom->id }}">{{ $medicalsymptom->name }}</option>
                        @endforeach
                    </select>
                  </div>
                  
                  <div style="margin-bottom: 15px;">
                    <select name="hospitals[]" class="form-control multiple-select" multiple="multiple" data-placeholder="ডাক্তার যে হাসপাতালের সাথে সম্পৃক্ত (প্রয়োজনে একাধিক সিলেক্ট করা যাবে)" required>
                        @foreach($hospitals as $hospital)
                          <option value="{{ $hospital->id }}">{{ $hospital->name }} - ({{ $hospital->upazilla->name_bangla }}, {{ $hospital->district->name_bangla }})</option>
                        @endforeach
                    </select>
                  </div>

                  <div class="form-group ">
                      <label for="image">ছবি/ ভিজিটিং কার্ড/ ব্যানার (প্রয়োজনে, ২ মেগাবাইটের মধ্যে)</label>
                      <input type="file" id="image" name="image" accept="image/*">
                  </div>
                  <center>
                      <img src="{{ asset('images/placeholder.png')}}" id='img-upload' style="width: 250px; height: auto;" class="img-responsive" />
                  </center>
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
    <script type="text/javascript">
        $('.multiple-select').select2({
          // theme: 'bootstrap4',
        });

        $('.district').on('change', function() {
          $('.upazilla').prop('disabled', true);
          $('.upazilla').append('<option value="" selected disabled>উপজেলা লোড হচ্ছে...</option>');

          $.ajax({
            url: "/api/getupazillas/{{ env('SOFT_TOKEN') }}/" +$(this).val(), 
            type: "GET",
            success: function(result){
              $('.upazilla')
                  .find('option')
                  .remove()
                  .end()
                  .prop('disabled', false)
                  .append('<option value="" selected disabled>উপজেলা নির্ধারণ করুন</option>')
              ;
              for(var countupazilla = 0; countupazilla < result.length; countupazilla++) {
                console.log(result[countupazilla]);
                $('.upazilla').append('<option value="'+result[countupazilla]['id']+'">'+result[countupazilla]['name_bangla']+'</option>')
              }
            }
          });
        });

        $(document).on('click', '#search-button', function() {
          if($('#search-param').val() != '') {
            var urltocall = '{{ route('dashboard.doctors') }}' +  '/' + $('#search-param').val();
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
              var urltocall = '{{ route('dashboard.doctors') }}' +  '/' + $('#search-param').val();
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