@extends('layouts.app')
@section('title') ড্যাশবোর্ড | অ্যাম্বুলেন্স তালিকা @endsection

@section('third_party_stylesheets')
   {{--  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/icheck-bootstrap@3.0.1/icheck-bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-datepicker.min.css') }}"> --}}
@endsection

@section('content')
  @section('page-header') অ্যাম্বুলেন্স তালিকা (মোট {{ bangla($ambulancescount) }} টি) @endsection
    <div class="container-fluid">
    <div class="card">
          <div class="card-header">
            <h3 class="card-title">অ্যাম্বুলেন্স তালিকা</h3>

            <div class="card-tools">
              <form class="form-inline form-group-lg" action="">
                @if(Auth::user()->role == 'admin')
                <div class="form-group">
                  <input type="search-param" class="form-control form-control-sm" placeholder="অ্যাম্বুলেন্স খুঁজুন" id="search-param" required>
                </div>
                <button type="button" id="search-button" class="btn btn-default btn-sm" style="margin-left: 5px;">
                  <i class="fas fa-search"></i> খুঁজুন
                </button>
                @endif
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
                  <th>লোকেশন</th>
                  <th align="right">কার্যক্রম</th>
                </tr>
              </thead>
              <tbody>
                @foreach($ambulances as $ambulance)
                  <tr>
                    <td>
                      {{ $ambulance->name }}<br/>
                      <span class="badge bg-success"><i class="fas fa-phone"></i> {{ $ambulance->mobile }}</span>
                    </td>
                    <td>
                      {{ $ambulance->upazilla->name_bangla }}, {{ $ambulance->district->name_bangla }}<br>
                      <small style="color: grey;">{{ $ambulance->address }}</small>
                    </td>
                    <td align="right">
                      {{-- <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#notifModal{{ $ambulance->id }}">
                        <i class="fas fa-bell"></i>
                      </button> --}}
                      <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editUserModal{{ $ambulance->id }}">
                        <i class="fas fa-edit"></i>
                      </button>
                      

                      <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteUserModal{{ $ambulance->id }}">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </td>

                    {{-- Edit User Modal Code --}}
                    {{-- Edit User Modal Code --}}
                    <!-- Modal -->
                    <div class="modal fade" id="editUserModal{{ $ambulance->id }}" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true" data-backdrop="static">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header bg-primary">
                            <h5 class="modal-title" id="editUserModalLabel">অ্যাম্বুলেন্স তথ্য হালনাগাদ</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <form method="post" action="{{ route('dashboard.ambulances.update', $ambulance->id) }}" enctype="multipart/form-data">
                            <div class="modal-body">
                              
                                  @csrf

                                  @if(Auth::user()->role == 'admin')
                                  <div class="input-group mb-3">
                                    <select name="district_id" id="district" class="form-control district" required>
                                        <option selected="" disabled="" value="">জেলা নির্বাচন করুন</option>
                                        @foreach($districts as $district)
                                          <option value="{{ $district->id }}" @if($district->id == $ambulance->district_id) selected @endif>{{ $district->name_bangla }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <div class="input-group-text"><span class="fas fa-map"></span></div>
                                    </div>
                                  </div>
                                  <div class="input-group mb-3">
                                    <select name="upazilla_id" id="upazilla" class="form-control upazilla" required>
                                        <option selected="" value="{{ $ambulance->upazilla_id }}">{{ $ambulance->upazilla->name_bangla }}</option>
                                    </select>
                                    <div class="input-group-append">
                                        <div class="input-group-text"><span class="fas fa-map-marked-alt"></span></div>
                                    </div>
                                  </div>
                                  @else
                                  জেলা: {{ Auth::user()->district->name_bangla }}
                                  <input type="hidden" name="district_id" value="{{ Auth::user()->district_id }}">
                                  <div class="input-group mb-3" style="margin-top: 15px;">
                                    <select name="upazilla_id" id="upazilla" class="form-control upazilla" required>
                                        <option selected="" value="{{ $ambulance->upazilla_id }}">{{ $ambulance->upazilla->name_bangla }}</option>
                                    </select>
                                    <div class="input-group-append">
                                        <div class="input-group-text"><span class="fas fa-map-marked-alt"></span></div>
                                    </div>
                                  </div>
                                  @endif


                                  <div class="input-group mb-3">
                                      <input type="text"
                                             name="name"
                                             class="form-control"
                                             value="{{ $ambulance->name }}"
                                             placeholder="অ্যাম্বুলেন্সের নাম" required>
                                      <div class="input-group-append">
                                          <div class="input-group-text"><span class="fas fa-ambulance"></span></div>
                                      </div>
                                  </div>
                                  <div class="input-group mb-3">
                                      <input type="number"
                                             name="mobile"
                                             value="{{ $ambulance->mobile }}"
                                             autocomplete="off"
                                             class="form-control"
                                             placeholder="মোবাইল নম্বর" required>
                                      <div class="input-group-append">
                                          <div class="input-group-text"><span class="fas fa-mobile"></span></div>
                                      </div>
                                  </div>
                                  <div class="input-group mb-3">
                                      <input type="text"
                                             name="address"
                                             value="{{ $ambulance->address }}"
                                             autocomplete="off"
                                             class="form-control"
                                             placeholder="ঠিকানা" required>
                                      <div class="input-group-append">
                                          <div class="input-group-text"><span class="fas fa-map"></span></div>
                                      </div>
                                  </div>
                                  <textarea class="form-control" name="description" placeholder="বক্স এর জন্য বার্তা লিখুন (Optional)">{{ $ambulance->description }}</textarea><br/>    
                                  <div class="form-group">
                                      <label for="image">ছবি (প্রয়োজনে, ৩০০ x ৩০০ সাইজের, ২ মেগাবাইটের মধ্যে)</label>
                                      <input type="file" name="image" accept="image/*">
                                  </div>
                                  <center>
                                    @if($ambulance->ambulanceimage != null)
                                      <img src="{{ asset('images/ambulances/' . $ambulance->ambulanceimage->image)}}" style="width: 250px; height: auto;" class="img-responsive" />
                                    @else
                                      <img src="{{ asset('images/placeholder.png')}}" style="width: 250px; height: auto;" class="img-responsive" />
                                    @endif
                                  </center>            
                              
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
                        <div class="modal fade" id="deleteUserModal{{ $ambulance->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true" data-backdrop="static">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header bg-danger">
                                <h5 class="modal-title" id="deleteUserModalLabel">অ্যাম্বুলেন্স ডিলেট</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                আপনি কি নিশ্চিতভাবে এই অ্যাম্বুলেন্সকে ডিলেট করতে চান?<br/>
                                <center>
                                    <big><b>{{ $ambulance->name }}</b></big><br/>
                                    <small><i class="fas fa-phone"></i> {{ $ambulance->mobile }}</small>
                                </center>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                                <a href="{{ route('dashboard.ambulances.delete', $ambulance->id) }}" class="btn btn-danger">ডিলেট করুন</a>
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
        {{ $ambulances->links() }}
    </div>

    {{-- Add User Modal Code --}}
    {{-- Add User Modal Code --}}
    <!-- Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-success">
            <h5 class="modal-title" id="addUserModalLabel">নতুন অ্যাম্বুলেন্স যোগ</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" action="{{ route('dashboard.ambulances.store') }}" enctype='multipart/form-data'>
            <div class="modal-body">
              
                  @csrf

                  @if(Auth::user()->role == 'admin')
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
                  <div class="input-group mb-3">
                    <select name="upazilla_id" id="upazilla" class="form-control upazilla" required>
                        <option selected="" disabled="" value="">উপজেলা নির্বাচন করুন</option>
                    </select>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-map-marked-alt"></span></div>
                    </div>
                  </div>
                  @else
                  জেলা: {{ Auth::user()->district->name_bangla }}
                  <input type="hidden" name="district_id" value="{{ Auth::user()->district_id }}">
                  <div class="input-group mb-3" style="margin-top: 15px;">
                    <select name="upazilla_id" id="upazilla" class="form-control upazilla" required>
                        <option selected="" disabled="" value="">উপজেলা নির্বাচন করুন</option>
                    </select>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-map-marked-alt"></span></div>
                    </div>
                  </div>
                  @endif

                  <div class="input-group mb-3">
                      <input type="text"
                             name="name"
                             class="form-control"
                             value="{{ old('name') }}"
                             placeholder="অ্যাম্বুলেন্সের নাম" required>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-ambulance"></span></div>
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
                             name="address"
                             value="{{ old('address') }}"
                             autocomplete="off"
                             class="form-control"
                             placeholder="ঠিকানা" required>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-map"></span></div>
                      </div>
                  </div>
                  <textarea class="form-control" name="description" placeholder="বক্স এর জন্য বার্তা লিখুন (Optional)">{{ old('description') }}</textarea> <br/>
                  <div class="form-group ">
                      <label for="image">ছবি (প্রয়োজনে, ৩০০ x ৩০০ সাইজের, ২ মেগাবাইটের মধ্যে)</label>
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
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">

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

        @if(Auth::user()->role == 'editor' || Auth::user()->role == 'manager')
          $('.upazilla').prop('disabled', true);
          $('.upazilla').append('<option value="" selected disabled>উপজেলা লোড হচ্ছে...</option>');

          $.ajax({
            url: "/api/getupazillas/{{ env('SOFT_TOKEN') }}/" +{{ Auth::user()->district_id }}, 
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
                $('.upazilla').append('<option value="'+result[countupazilla]['id']+'">'+result[countupazilla]['name_bangla']+'-'+result[countupazilla]['name']+'</option>')
              }
            }
          });
        @endif

        $(document).on('click', '#search-button', function() {
          if($('#search-param').val() != '') {
            var urltocall = '{{ route('dashboard.ambulances') }}' +  '/' + $('#search-param').val();
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
              var urltocall = '{{ route('dashboard.ambulances') }}' +  '/' + $('#search-param').val();
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