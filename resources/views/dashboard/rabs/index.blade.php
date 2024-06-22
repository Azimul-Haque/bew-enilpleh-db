@extends('layouts.app')
@section('title') ড্যাশবোর্ড | র‍্যাব @endsection

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

                      @foreach ($districts->chunk(3) as $chunk)
                          <tr>
                              @foreach ($chunk as $district)
                                  <td>
                                    {{ $district->name_bangla }} 
                                    <small>
                                      @if($district->rabs->count() == 0)(যুক্ত করা নেই)@endif
                                      @if($district->rabs->count() > 0)<strong>({{ $district->rabs->first()->rabbattalion->name }})</strong>@endif
                                    </small>
                                  </td>
                                  <td>
                                    <button type="button" class="btn btn-warning btn-sm"  data-toggle="modal" data-target="#updateDistrictRabBattalion{{ $district->id }}" style="margin-left: 5px;" rel="tooltip" title="" data-original-title="র‍্যাব ব্যাটালিয়ন সংযুক্ত করুন">
                                      <i class="fas fa-link"></i> আপডেট
                                    </button>
                                    {{-- Edit Rab Battalion Code --}}
                                    {{-- Edit Rab Battalion Code --}}
                                    <!-- Modal -->
                                    <div class="modal fade" id="updateDistrictRabBattalion{{ $district->id }}" tabindex="-1" role="dialog" aria-labelledby="updateDistrictRabBattalionLabel" aria-hidden="true" data-backdrop="static">
                                      <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header bg-primary">
                                            <h5 class="modal-title" id="updateDistrictRabBattalionLabel">র‍্যাব ব্যাটালিয়নের তথ্য হালনাগাদ (জেলা: <strong>{{ $district->name_bangla }}</strong>)</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                            </button>
                                          </div>
                                          <form method="post" action="{{ route('dashboard.districtrabbattalions.update', $district->id) }}" enctype="multipart/form-data">
                                            <div class="modal-body">
                                              
                                                  @csrf

                                                  <div class="input-group mb-3">
                                                    <select name="rabbattalion_id" class="form-control district" required>
                                                        <option selected="" disabled="" value="">র‍্যাব ব্যাটালিয়ন নির্বাচন করুন</option>
                                                        @foreach($rabbattalions as $rabbattalion)
                                                          <option value="{{ $rabbattalion->id }}">
                                                            {{ $rabbattalion->name }}<br/>
                                                            <div style="color: #9C9FA0; font-size: 12.5px; line-height: 90%!;">
                                                              {{ $rabbattalion->details }}
                                                            </div>
                                                          </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="input-group-append">
                                                        <div class="input-group-text"><span class="fas fa-map"></span></div>
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
                                    {{-- Edit Rab Battalion Code --}}
                                    {{-- Edit Rab Battalion Code --}}
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
        <div class="col-md-4">
          <div class="card">
                <div class="card-header">
                  <h3 class="card-title">র‍্যাব ব্যাটালিয়ন তালিকা</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-success btn-sm"  data-toggle="modal" data-target="#addRabBattalionModal" style="margin-left: 5px;">
                      <i class="fas fa-plus-square"></i> নতুন ব্যাটালিয়ন
                    </button>
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>নাম</th>
                        <th width="30%">কার্যক্রম</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($rabbattalions as $rabbattalion)
                        <tr>
                          <td>
                            <a href="{{ route('dashboard.rabbattalions.details', $rabbattalion->id) }}">
                              {{ $rabbattalion->name }}<br/>
                              <div style="color: #9C9FA0; font-size: 12.5px; line-height: 90%!;">
                                {{ $rabbattalion->details }}
                              </div>
                            </a>
                          </td>
                          <td align="right">
                            {{-- <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#notifModal{{ $rabbattalion->id }}">
                              <i class="fas fa-bell"></i>
                            </button> --}}
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editRabBattalionModal{{ $rabbattalion->id }}">
                              <i class="fas fa-edit"></i>
                            </button>
                            {{-- Edit Rab Battalion Code --}}
                            {{-- Edit Rab Battalion Code --}}
                            <!-- Modal -->
                            <div class="modal fade" id="editRabBattalionModal{{ $rabbattalion->id }}" tabindex="-1" role="dialog" aria-labelledby="editRabBattalionModalLabel" aria-hidden="true" data-backdrop="static">
                              <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                  <div class="modal-header bg-primary">
                                    <h5 class="modal-title" id="editRabBattalionModalLabel">র‍্যাব ব্যাটালিয়নের তথ্য হালনাগাদ</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <form method="post" action="{{ route('dashboard.rabbattalions.update', $rabbattalion->id) }}" enctype="multipart/form-data">
                                    <div class="modal-body">
                                      
                                          @csrf

                                          <div class="input-group mb-3">
                                              <input type="text"
                                                     name="name"
                                                     class="form-control"
                                                     value="{{ $rabbattalion->name }}"
                                                     placeholder="র‍্যাব ব্যাটালিয়নের নাম" required>
                                              <div class="input-group-append">
                                                  <div class="input-group-text"><span class="fas fa-user-shield"></span></div>
                                              </div>
                                          </div>
                                          <div class="input-group mb-3">
                                              <textarea type="text"
                                                     name="details"
                                                     class="form-control"
                                                     style="min-height:100px;" 
                                                     placeholder="ব্যাটালিয়নের বিস্তারিত" required>{{ $rabbattalion->details }}</textarea>
                                              <div class="input-group-append">
                                                  <div class="input-group-text"><span class="fas fa-info-circle"></span></div>
                                              </div>
                                          </div>
                                          
                                          <div class="form-group">
                                              <label for="image{{ $rabbattalion->id }}">ম্যাপ (প্রয়োজনে, ২ মেগাবাইটের মধ্যে)</label>
                                              <input type="file" id="image{{ $rabbattalion->id }}" name="map" accept="image/*">
                                          </div>
                                          <center>
                                            @if($rabbattalion->map != null)
                                              <img src="{{ asset('images/rabbattalions/' . $rabbattalion->map)}}" id='img-upload{{ $rabbattalion->id }}' style="width: 250px; height: auto;" class="img-responsive" />
                                            @else
                                              <img src="{{ asset('images/placeholder.png')}}" id='img-upload{{ $rabbattalion->id }}' style="width: 250px; height: auto;" class="img-responsive" />
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
                            {{-- Edit Rab Battalion Code --}}
                            {{-- Edit Rab Battalion Code --}}

                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteUserModal{{ $rabbattalion->id }}">
                              <i class="fas fa-trash-alt"></i>
                            </button>
                          </td>
                              {{-- Delete User Modal Code --}}
                              {{-- Delete User Modal Code --}}
                              <!-- Modal -->
                              <div class="modal fade" id="deleteUserModal{{ $rabbattalion->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true" data-backdrop="static">
                                <div class="modal-dialog" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header bg-danger">
                                      <h5 class="modal-title" id="deleteUserModalLabel">কোচিং সেন্টার ডিলেট</h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <div class="modal-body">
                                      আপনি কি নিশ্চিতভাবে এই কোচিং সেন্টারকে ডিলেট করতে চান?<br/>
                                      <center>
                                          <big><b>{{ $rabbattalion->name }}</b></big><br/>
                                          <small><i class="fas fa-phone"></i> {{ $rabbattalion->mobile }}</small>
                                      </center>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                                      <a href="{{ route('dashboard.coachings.delete', [$district->id, $rabbattalion->id]) }}" class="btn btn-danger">ডিলেট করুন</a>
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
        </div>
      </div>
    </div>

    {{-- Add Rab Battalion Code --}}
    {{-- Add Rab Battalion Code --}}
    <!-- Modal -->
    <div class="modal fade" id="addRabBattalionModal" tabindex="-1" role="dialog" aria-labelledby="addRabBattalionModalLabel" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-success">
            <h5 class="modal-title" id="addRabBattalionModalLabel">নতুন র‍্যাব ব্যাটালিয়ন যোগ</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" action="{{ route('dashboard.rabbattalions.store') }}" enctype='multipart/form-data'>
            <div class="modal-body">
              
                  @csrf
                  
                  <div class="input-group mb-3">
                      <input type="text"
                             name="name"
                             class="form-control"
                             value="{{ old('name') }}"
                             placeholder="র‍্যাব ব্যাটালিয়নের নাম" required>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-user-shield"></span></div>
                      </div>
                  </div>
                  <div class="input-group mb-3">
                      <textarea type="text"
                             name="details"
                             class="form-control"
                             style="min-height:100px;" 
                             placeholder="ব্যাটালিয়নের বিস্তারিত" required>{{ old('details') }}</textarea>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-info-circle"></span></div>
                      </div>
                  </div>
                  
                  <div class="form-group ">
                      <label for="image">ম্যাপ (২ মেগাবাইটের মধ্যে)</label>
                      <input type="file" id="image" name="map" accept="image/*" required>
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
    {{-- Add Rab Battalion Code --}}
    {{-- Add Rab Battalion Code --}}

@endsection

@section('third_party_scripts')
    <script type="text/javascript">
      

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