@extends('layouts.app')
@section('title') ড্যাশবোর্ড | শিক্ষা প্রতিষ্ঠান তালিকা @endsection

@section('third_party_stylesheets')
   {{--  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/icheck-bootstrap@3.0.1/icheck-bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-datepicker.min.css') }}"> --}}
@endsection

@section('content')
  @section('page-header') শিক্ষা প্রতিষ্ঠান তালিকা (সরকারি/বেসরকারি/কোচিং) / {{ $district->name_bangla }} জেলা (মোট {{ bangla($coachingscount) }} টি) @endsection
    <div class="container-fluid">
    <div class="card">
          <div class="card-header">
            <h3 class="card-title">শিক্ষা প্রতিষ্ঠান তালিকা (সরকারি/বেসরকারি/কোচিং)</h3>

            <div class="card-tools">
              <form class="form-inline form-group-lg" action="">
                <div class="form-group">
                  <input type="search-param" class="form-control form-control-sm" placeholder="শিক্ষা প্রতিষ্ঠান খুঁজুন" id="search-param" required>
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
                  <th>শিক্ষা প্রতিষ্ঠানের ধরন</th>
                  <th>মোবাইল নম্বর</th>
                  <th>ঠিকানা</th>
                  <th align="right">কার্যক্রম</th>
                </tr>
              </thead>
              <tbody>
                @foreach($coachings as $coaching)
                  <tr>
                    <td>
                      {{ $coaching->name }}
                    </td>
                    <td><span class="badge badge-pill badge-{{ edu_inst_badge($coaching->type) }}">{{ edu_inst_type($coaching->type) }}</span></td>
                    <td>{{ $coaching->mobile }}</td>
                    <td>{{ $coaching->address }}</td>
                    <td align="right">
                      {{-- <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#notifModal{{ $coaching->id }}">
                        <i class="fas fa-bell"></i>
                      </button> --}}
                      <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editUserModal{{ $coaching->id }}">
                        <i class="fas fa-edit"></i>
                      </button>
                      <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteUserModal{{ $coaching->id }}">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </td>

                    {{-- Edit User Modal Code --}}
                    {{-- Edit User Modal Code --}}
                    <!-- Modal -->
                    <div class="modal fade" id="editUserModal{{ $coaching->id }}" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true" data-backdrop="static">
                      <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                          <div class="modal-header bg-primary">
                            <h5 class="modal-title" id="editUserModalLabel">শিক্ষা প্রতিষ্ঠান তথ্য হালনাগাদ</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <form method="post" action="{{ route('dashboard.coachings.update', [$district->id, $coaching->id]) }}" enctype="multipart/form-data">
                            <div class="modal-body">
                              
                                  @csrf

                                  <div class="input-group mb-3">
                                      <input type="text"
                                             name="name"
                                             class="form-control"
                                             value="{{ $coaching->name }}"
                                             placeholder="শিক্ষা প্রতিষ্ঠানের নাম" required>
                                      <div class="input-group-append">
                                          <div class="input-group-text"><span class="fas fa-user-tie"></span></div>
                                      </div>
                                  </div>

                                  <div class="input-group mb-3">
                                    <select name="type" class="form-control" required>
                                        <option selected="" disabled="" value="">শিক্ষা প্রতিষ্ঠানের ধরন</option>
                                        <option value="1" @if($coaching->type == 1) selected @endif>সরকারি শিক্ষা প্রতিষ্ঠান</option>
                                        <option value="2" @if($coaching->type == 2) selected @endif>বেসরকারি শিক্ষা প্রতিষ্ঠান</option>
                                        <option value="3" @if($coaching->type == 3) selected @endif>কোচিং সেন্টার</option>
                                    </select>
                                    <div class="input-group-append">
                                        <div class="input-group-text"><span class="fas fa-star-half-alt"></span></div>
                                    </div>
                                  </div>

                                  <div class="input-group mb-3">
                                      <input type="number"
                                             name="mobile"
                                             value="{{ $coaching->mobile }}"
                                             class="form-control"
                                             placeholder="শিক্ষা প্রতিষ্ঠানের মোবাইল নম্বর" required>
                                      <div class="input-group-append">
                                          <div class="input-group-text"><span class="fas fa-mobile"></span></div>
                                      </div>
                                  </div>
                                  <div class="input-group mb-3">
                                      <input type="text"
                                             name="address"
                                             class="form-control"
                                             value="{{ $coaching->address }}"
                                             placeholder="শিক্ষা প্রতিষ্ঠানের ঠিকানা" required>
                                      <div class="input-group-append">
                                          <div class="input-group-text"><span class="fas fa-map-marked-alt"></span></div>
                                      </div>
                                  </div>
                                  <textarea class="form-control" name="description" placeholder="বক্স এর জন্য বার্তা লিখুন (Optional)">{{ $coaching->description }}</textarea>    

                                  <div class="row">
                                    <div class="col-md-6">
                                      <div class="form-group" style="margin-top: 15px;">
                                          <label for="image">ব্যানার-১ (Optional, Max 1 MB)</label>
                                          <input type="file" name="image1" accept="image/*">
                                      </div>

                                      <div class="form-group" style="margin-top: 15px;">
                                          <label for="image">ব্যানার-২ (Optional, Max 1 MB)</label>
                                          <input type="file" name="image2" accept="image/*">
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group" style="margin-top: 15px;">
                                          <label for="image">ব্যানার-৩ (Optional, Max 1 MB)</label>
                                          <input type="file" name="image3" accept="image/*">
                                      </div>

                                      <div class="form-group" style="margin-top: 15px;">
                                          <label for="image">ব্যানার-৪ (Optional, Max 1 MB)</label>
                                          <input type="file" name="image4" accept="image/*">
                                      </div>
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
                        <div class="modal fade" id="deleteUserModal{{ $coaching->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true" data-backdrop="static">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header bg-danger">
                                <h5 class="modal-title" id="deleteUserModalLabel">শিক্ষা প্রতিষ্ঠান ডিলেট</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                আপনি কি নিশ্চিতভাবে এই শিক্ষা প্রতিষ্ঠানকে ডিলেট করতে চান?<br/>
                                <center>
                                    <big><b>{{ $coaching->name }}</b></big><br/>
                                    <small><i class="fas fa-phone"></i> {{ $coaching->mobile }}</small>
                                </center>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                                <a href="{{ route('dashboard.coachings.delete', [$district->id, $coaching->id]) }}" class="btn btn-danger">ডিলেট করুন</a>
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
        {{ $coachings->links() }}
    </div>

    {{-- Add User Modal Code --}}
    {{-- Add User Modal Code --}}
    <!-- Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-success">
            <h5 class="modal-title" id="addUserModalLabel">নতুন শিক্ষা প্রতিষ্ঠান যোগ (জেলা: <strong>{{ $district->name_bangla }}</strong>)</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" action="{{ route('dashboard.coachings.store', $district->id) }}" enctype='multipart/form-data'>
            <div class="modal-body">
              
                  @csrf
                  
                  <div class="input-group mb-3">
                      <input type="text"
                             name="name"
                             class="form-control"
                             value="{{ old('name') }}"
                             placeholder="শিক্ষা প্রতিষ্ঠানের নাম" required>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-user-tie"></span></div>
                      </div>
                  </div>
                  <div class="input-group mb-3">
                    <select name="type" class="form-control" required>
                        <option selected="" disabled="" value="">শিক্ষা প্রতিষ্ঠানের ধরন</option>
                        <option value="1">সরকারি শিক্ষা প্রতিষ্ঠান</option>
                        <option value="2">বেসরকারি শিক্ষা প্রতিষ্ঠান</option>
                        <option value="3">কোচিং সেন্টার</option>
                    </select>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-star-half-alt"></span></div>
                    </div>
                  </div>
                  <div class="input-group mb-3">
                      <input type="number"
                             name="mobile"
                             value="{{ old('mobile') }}"
                             class="form-control"
                             placeholder="শিক্ষা প্রতিষ্ঠানের মোবাইল নম্বর" required>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-mobile"></span></div>
                      </div>
                  </div>
                  <div class="input-group mb-3">
                      <input type="text"
                             name="address"
                             class="form-control"
                             value="{{ old('address') }}"
                             placeholder="শিক্ষা প্রতিষ্ঠানের ঠিকানা" required>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-map-marked-alt"></span></div>
                      </div>
                  </div>
                  <textarea class="form-control" name="description" placeholder="বক্স এর জন্য বার্তা লিখুন (Optional)">{{ old('description') }}</textarea>   
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group" style="margin-top: 15px;">
                          <label for="image">ব্যানার-১ (Optional, Max 1 MB)</label>
                          <input type="file" name="image1" accept="image/*">
                      </div>

                      <div class="form-group" style="margin-top: 15px;">
                          <label for="image">ব্যানার-২ (Optional, Max 1 MB)</label>
                          <input type="file" name="image2" accept="image/*">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group" style="margin-top: 15px;">
                          <label for="image">ব্যানার-৩ (Optional, Max 1 MB)</label>
                          <input type="file" name="image3" accept="image/*">
                      </div>

                      <div class="form-group" style="margin-top: 15px;">
                          <label for="image">ব্যানার-৪ (Optional, Max 1 MB)</label>
                          <input type="file" name="image4" accept="image/*">
                      </div>
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
            var urltocall = '{{ route('dashboard.coachings.districtwise', $district->id) }}' +  '/' + $('#search-param').val();
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
              var urltocall = '{{ route('dashboard.coachings.districtwise', $district->id) }}' +  '/' + $('#search-param').val();
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