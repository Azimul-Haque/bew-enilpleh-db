@extends('layouts.app')
@section('title') ড্যাশবোর্ড | সাংবাদিকবৃন্দের তালিকা @endsection

@section('third_party_stylesheets')
   {{--  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/icheck-bootstrap@3.0.1/icheck-bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-datepicker.min.css') }}"> --}}
@endsection

@section('content')
  @section('page-header') সাংবাদিক তালিকা / {{ $district->name_bangla }} জেলা (মোট {{ bangla($journalistscount) }} টি) @endsection
    <div class="container-fluid">
    <div class="card">
          <div class="card-header">
            <h3 class="card-title">সাংবাদিক তালিকা</h3>

            <div class="card-tools">
              <form class="form-inline form-group-lg" action="">
                <div class="form-group">
                  <input type="search-param" class="form-control form-control-sm" placeholder="সাংবাদিক খুঁজুন" id="search-param" required>
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
                  <th>হোয়াটসঅ্যাপ নম্বর</th>
                  <th>কোন পত্রিকায় কাজ করে</th>
                  <th align="right">কার্যক্রম</th>
                </tr>
              </thead>
              <tbody>
                @foreach($journalists as $journalist)
                  <tr>
                    <td>
                      {{ $journalist->name }}
                    </td>
                    <td>{{ $journalist->mobile }}</td>
                    <td>
                      {{ $journalist->affiliation }}
                    </td>
                    <td align="right">
                      {{-- <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#notifModal{{ $journalist->id }}">
                        <i class="fas fa-bell"></i>
                      </button> --}}
                      <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editUserModal{{ $journalist->id }}">
                        <i class="fas fa-edit"></i>
                      </button>
                      {{-- Edit User Modal Code --}}
                      {{-- Edit User Modal Code --}}
                      <!-- Modal -->
                      <div class="modal fade" id="editUserModal{{ $journalist->id }}" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true" data-backdrop="static">
                        <div class="modal-dialog modal-lg" role="document">
                          <div class="modal-content">
                            <div class="modal-header bg-primary">
                              <h5 class="modal-title" id="editUserModalLabel">সাংবাদিক তথ্য হালনাগাদ</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <form method="post" action="{{ route('dashboard.journalists.update', [$district->id, $journalist->id]) }}" enctype="multipart/form-data">
                              <div class="modal-body">
                                
                                    @csrf

                                    <div class="input-group mb-3">
                                        <input type="text"
                                               name="name"
                                               class="form-control"
                                               value="{{ $journalist->name }}"
                                               placeholder="সাংবাদিকের নাম" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-user-tie"></span></div>
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <input type="text"
                                               name="affiliation"
                                               value="{{ $journalist->affiliation }}"
                                               class="form-control"
                                               placeholder="কোন পত্রিকায় কাজ করে" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-university"></span></div>
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <input type="number"
                                               name="mobile"
                                               value="{{ $journalist->mobile }}"
                                               class="form-control"
                                               placeholder="সাংবাদিকের হোয়াটসঅ্যাপ নম্বর" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-mobile"></span></div>
                                        </div>
                                    </div>

                                    @php
                                        $topLinks = old('top_news_links', $journalist->top_news_links ?? []);
                                    @endphp

                                    @for($i = 0; $i < 5; $i++)
                                        <div class="input-group mb-2">
                                            <input type="url"
                                                   name="top_news_links[]"
                                                   class="form-control"
                                                   placeholder="শীর্ষ প্রকাশিত সংবাদের লিংক -  {{ $i + 1 }}"
                                                   value="{{ $topLinks[$i] ?? '' }}">
                                            <div class="input-group-append">
                                                <div class="input-group-text"><span class="fas fa-link"></span></div>
                                            </div>
                                        </div>
                                    @endfor
                                     
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

                      <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteUserModal{{ $journalist->id }}">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </td>
                        {{-- Delete User Modal Code --}}
                        {{-- Delete User Modal Code --}}
                        <!-- Modal -->
                        <div class="modal fade" id="deleteUserModal{{ $journalist->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true" data-backdrop="static">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header bg-danger">
                                <h5 class="modal-title" id="deleteUserModalLabel">সাংবাদিক ডিলেট</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                আপনি কি নিশ্চিতভাবে এই সাংবাদিককে ডিলেট করতে চান?<br/>
                                <center>
                                    <big><b>{{ $journalist->name }}</b></big><br/>
                                    <small><i class="fas fa-phone"></i> {{ $journalist->mobile }}</small>
                                </center>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                                <a href="{{ route('dashboard.journalists.delete', [$district->id, $journalist->id]) }}" class="btn btn-danger">ডিলেট করুন</a>
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
        {{ $journalists->links() }}
    </div>

    {{-- Add User Modal Code --}}
    {{-- Add User Modal Code --}}
    <!-- Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-success">
            <h5 class="modal-title" id="addUserModalLabel">নতুন সাংবাদিক যোগ (জেলা: <strong>{{ $district->name_bangla }}</strong>)</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" action="{{ route('dashboard.journalists.store', $district->id) }}" enctype='multipart/form-data'>
            <div class="modal-body">
              
                  @csrf
                  
                  <div class="input-group mb-3">
                      <input type="text"
                             name="name"
                             class="form-control"
                             value="{{ old('name') }}"
                             placeholder="সাংবাদিকের নাম" required>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-user-tie"></span></div>
                      </div>
                  </div>

                  <div class="input-group mb-3">
                      <input type="text"
                             name="affiliation"
                             class="form-control"
                             value="{{ old('affiliation') }}"
                             placeholder="কোন পত্রিকায় কাজ করে" required>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-university"></span></div>
                      </div>
                  </div>
                  
                  <div class="input-group mb-3">
                      <input type="number"
                             name="mobile"
                             value="{{ old('mobile') }}"
                             class="form-control"
                             placeholder="সাংবাদিকের হোয়াটসঅ্যাপ নাম্বার নম্বর" required>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-mobile"></span></div>
                      </div>
                  </div>

                  @for($i = 0; $i < 5; $i++)
                      <div class="input-group mb-2">
                          <input type="url"
                                 name="top_news_links[]"
                                 class="form-control"
                                 placeholder="শীর্ষ প্রকাশিত সংবাদের লিংক -  {{ $i + 1 }}" >
                          <div class="input-group-append">
                              <div class="input-group-text"><span class="fas fa-link"></span></div>
                          </div>
                      </div>
                  @endfor
                  
                  
                  
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
            var urltocall = '{{ route('dashboard.journalists.districtwise', $district->id) }}' +  '/' + $('#search-param').val();
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
              var urltocall = '{{ route('dashboard.journalists.districtwise', $district->id) }}' +  '/' + $('#search-param').val();
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