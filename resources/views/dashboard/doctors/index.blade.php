@extends('layouts.app')
@section('title') ড্যাশবোর্ড | ডাক্তার তালিকা @endsection

@section('third_party_stylesheets')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2-bootstrap4.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{ asset('js/select2.full.min.js') }}"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script> --}}
    <style type="text/css">
      .select2-selection__choice{
          background-color: rgba(0, 123, 255) !important;
      }
    </style>
@endsection

@section('content')
  @section('page-header') ডাক্তার তালিকা (মোট {{ bangla($doctorscount) }} জন) @endsection

  @php
      use Carbon\Carbon;

      $startDate = Carbon::today();
      $endDate = $startDate->copy()->addMonths(3);
      $optiondates = [];

      while ($startDate->lte($endDate)) {
          $optiondates[] = $startDate->copy();
          $startDate->addDay();
      }
  @endphp
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-9">
          <div class="card">
                <div class="card-header">
                  <h3 class="card-title">ডাক্তার তালিকা</h3>

                  <div class="card-tools">
                    @if(Auth::user()->role == 'admin')
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
                    @else
                    <button type="button" class="btn btn-success btn-sm"  data-toggle="modal" data-target="#addUserModal" style="margin-left: 5px;">
                      <i class="fas fa-user-plus"></i> নতুন
                    </button>
                    @endif
                    
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>নাম</th>
                        <th>বিভাগ/ লক্ষণ</th>
                        <th>হাসপাতাল/ ঠিকানা</th>
                        <th align="right" width="15%">কার্যক্রম</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($doctors as $doctor)
                        <tr>
                          <td>
                            {{ $doctor->name }}<br/>
                            <span style="font-size: 12px;">{{ $doctor->degree }}</span><br/>
                            {{-- <span class="">{{ $doctor->specialization }}</span> --}}
                            <br/>
                            <small class="text-black-50"><i class="fas fa-phone"></i> {{ $doctor->serial }}</small>
                            <small class="text-black-50"><i class="fas fa-mobile"></i> {{ $doctor->helpline }}</small>
                            
                          </td>
                          <td>
                            @foreach($doctor->doctormedicaldepartments as $medicaldepartment)
                              <span class="">{{ $medicaldepartment->medicaldepartment->name }}</span>
                            @endforeach <br/>
                            @foreach($doctor->doctormedicalsymptoms as $medicalsymptom)
                              <span class="">{{ $medicalsymptom->medicalsymptom->name }}</span>
                            @endforeach
                          </td>
                          <td>
                            @foreach($doctor->doctorhospitals as $hospital)
                              <small class="">{{ $hospital->hospital->name }}</small>
                            @endforeach <br/>
                            <small class="">{{ $doctor->upazilla->name_bangla }}, {{ $doctor->district->name_bangla }}</small>
                          </td>
                          <td align="right">
                            {{-- <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#notifModal{{ $doctor->id }}">
                              <i class="fas fa-bell"></i>
                            </button> --}}
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editUserModal{{ $doctor->id }}">
                              <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteUserModal{{ $doctor->id }}" disabled>
                              <i class="fas fa-trash-alt"></i>
                            </button><br/>
                             <a href="{{ route('dashboard.doctorserialindex', [$doctor->id, date('Y-m-d')]) }}" style="margin-top: 5px;" class="btn btn-warning btn-sm">
                              <i class="fas fa-calendar-alt"></i> <b>অ্যাপয়েন্টমেন্ট তালিকা</b>
                            </a>
                          </td>

                          {{-- Edit User Modal Code --}}
                          {{-- Edit User Modal Code --}}
                          <!-- Modal -->
                          <div class="modal fade" id="editUserModal{{ $doctor->id }}" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true" data-backdrop="static">
                            <div class="modal-dialog modal-lg" role="document">
                              <div class="modal-content">
                                <div class="modal-header bg-success">
                                  <h5 class="modal-title" id="editUserModalLabel">ডাক্তার তথ্য হালনাগাদ</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <form method="post" action="{{ route('dashboard.doctors.update', $doctor->id) }}" enctype="multipart/form-data">
                                  <div class="modal-body">
                                    
                                        @csrf

                                        <div class="row">
                                          <div class="col-md-6">
                                            <div class="input-group mb-3">
                                              <select name="district_id" id="district" class="form-control district select21" required>
                                                  <option selected="" disabled="" value="">জেলা নির্বাচন করুন</option>
                                                  @foreach($districts as $district)
                                                    <option value="{{ $district->id }}" @if($doctor->district->id == $district->id) selected @endif>{{ $district->name_bangla }}-{{ $district->name }}</option>
                                                  @endforeach
                                              </select>
                                              <div class="input-group-append">
                                                  <div class="input-group-text"><span class="fas fa-map"></span></div>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="col-md-6">
                                            <div class="input-group mb-3">
                                              <select name="upazilla_id" id="upazilla" class="form-control upazilla select21" required>
                                                  <option selected="" value="{{ $doctor->upazilla_id }}">{{ $doctor->upazilla->name_bangla }}-{{ $doctor->upazilla->name }}</option>
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
                                                       value="{{ $doctor->name }}"
                                                       placeholder="ডাক্তারের নাম" required>
                                                <div class="input-group-append">
                                                    <div class="input-group-text"><span class="fas fa-user-md"></span></div>
                                                </div>
                                            </div>
                                            <div class="input-group mb-3">
                                                <input type="text"
                                                       name="specialization"
                                                       value="{{ $doctor->specialization }}"
                                                       
                                                       class="form-control"
                                                       placeholder="ডাক্তার কী বিশেষজ্ঞ (যেমন: হৃদরোগ বিশেষজ্ঞ)">
                                                <div class="input-group-append">
                                                    <div class="input-group-text"><span class="fas fa-certificate"></span></div>
                                                </div>
                                            </div>
                                          </div>
                                          <div class="col-md-6">
                                            <div class="input-group mb-3">
                                              <textarea name="degree" class="form-control" style="min-height: 90px;" placeholder="ডাক্তারের ডিগ্রি/ ডিগ্রিসমূহ (যেমন: MBBS, FCPS, MD, বিসিএস (স্বাস্থ্য) ইত্যাদি) [একাধিক লাইন এড করা যাবে]">{{ str_replace('<br />', "", $doctor->degree) }}</textarea>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="row">
                                          <div class="col-md-6">
                                            <div class="input-group mb-3">
                                                <input type="number"
                                                       name="serial"
                                                       class="form-control"
                                                       value="{{ $doctor->serial }}"
                                                       placeholder="সিরিয়াল নেওয়ার ফোন নং" required>
                                                <div class="input-group-append">
                                                    <div class="input-group-text"><span class="fas fa-phone"></span></div>
                                                </div>
                                            </div>
                                          </div>
                                          <div class="col-md-6">
                                            {{-- <div class="input-group mb-3">
                                                <input type="number"
                                                       name="helpline"
                                                       value="{{ $doctor->helpline }}"
                                                       
                                                       class="form-control"
                                                       placeholder="হেল্পলাইন নম্বর (যদি থাকে)">
                                                <div class="input-group-append">
                                                    <div class="input-group-text"><span class="fas fa-mobile"></span></div>
                                                </div>
                                            </div> --}}
                                            <div class="input-group mb-3">
                                                <input type="text"
                                                       name="address"
                                                       value="{{ $doctor->address }}"
                                                       class="form-control"
                                                       placeholder="চেম্বারের ঠিকানা" required>
                                            </div>
                                          </div>
                                        </div>
                                        
                                        <div style="margin-bottom: 15px;">
                                          <select name="medicaldepartments[]" class="form-control multiple-select" multiple="multiple" data-placeholder="বিভাগ (প্রয়োজনে একাধিক সিলেক্ট করা যাবে)" required>
                                              
                                              @foreach($medicaldepartments as $medicaldepartment)
                                                <option value="{{ $medicaldepartment->id }}" @if(in_array($medicaldepartment->id, $doctor->doctormedicaldepartments->pluck('medicaldepartment_id')->toArray())) selected @endif>{{ $medicaldepartment->name }}</option>
                                              @endforeach
                                          </select>
                                        </div> 
                                        
                                        <div style="margin-bottom: 15px;">
                                          <select name="medicalsymptoms[]" class="form-control multiple-select" multiple="multiple" data-placeholder="লক্ষণ (প্রয়োজনে একাধিক সিলেক্ট করা যাবে)" required>
                                              
                                              @foreach($medicalsymptoms as $medicalsymptom)
                                                <option value="{{ $medicalsymptom->id }}" @if(in_array($medicalsymptom->id, $doctor->doctormedicalsymptoms->pluck('medicalsymptom_id')->toArray())) selected @endif>{{ $medicalsymptom->name }}</option>
                                              @endforeach
                                          </select>
                                        </div>
                                        
                                        <div style="margin-bottom: 15px;">
                                          <select name="hospitals[]" class="form-control multiple-select" multiple="multiple" data-placeholder="ডাক্তার যে হাসপাতালের সাথে সম্পৃক্ত (প্রয়োজনে একাধিক সিলেক্ট করা যাবে) [Optional]">
                                              @foreach($hospitals as $hospital)
                                                <option value="{{ $hospital->id }}" @if(in_array($hospital->id, $doctor->doctorhospitals->pluck('hospital_id')->toArray())) selected @endif>{{ $hospital->name }} - ({{ $hospital->upazilla->name_bangla }}, {{ $hospital->district->name_bangla }})</option>
                                              @endforeach
                                          </select>
                                        </div>

                                        <div class="row">
                                          <div class="col-md-6">
                                            <div>
                                              সপ্তাহে যে যে দিন রোগী দেখেন<br/>
                                              <textarea name="weekdays" class="form-control" style="min-height: 90px;" placeholder="উদাহরণ: শুক্রবার সকাল ৯টা থেকে দুপুর ১২টা, শনিবার সন্ধ্যা ৬টা থেকে রাত ১০টা ইত্যাদি (কোন সপ্তাহে ডাক্তার না বসলে সেটা লিখে দিন)">{{ str_replace('<br />', "", $doctor->weekdays) }}</textarea>
                                            </div>
                                          </div>
                                          <div class="col-md-6">
                                            অনলাইনে সিরিয়াল দেওয়া যাবে কি না<br/>
                                            <select name="onlineserial" class="form-control" required>
                                                <option selected="" disabled="" value="">অনলাইনে সিরিয়াল দেওয়া যাবে কি না</option>
                                                <option value="1" @if($doctor->onlineserial == 1) selected @endif>অনলাইনে সিরিয়াল দেওয়া যাবে ✅</option>
                                                <option value="0" @if($doctor->onlineserial == 0) selected @endif>অনলাইনে সিরিয়াল দেওয়া যাবে না ❌</option>
                                            </select>
                                          </div>
                                        </div>

                                        <div style="margin-top: 15px;">

                                          <select name="offdays[]" class="form-control multiple-select" multiple="multiple" data-placeholder="যেদিন যেদিন রোগী দেখবেন না (প্রয়োজনে একাধিক সিলেক্ট করা যাবে) [Optional]">
                                              @php
                                                $decodedoffdays = json_decode($doctor->offdays, true);
                                                // print_r($decodedoffdays);
                                              @endphp
                                              @foreach($optiondates as $date)
                                                  <option value="{{ $date->format('Y-m-d') }}" @if(!empty($decodedoffdays) && in_array($date->format('Y-m-d'), $decodedoffdays, true)) selected @endif>
                                                      {{ bangla($date->format('d-m-Y l')) }}
                                                  </option>
                                              @endforeach
                                          </select>
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
                      @endforeach
                    </tbody>
                  </table>
                </div>
                <!-- /.card-body -->
              </div>
              {{ $doctors->links() }}
        </div>
        <div class="col-md-3">
          @if(Auth::user()->role == 'admin')
            <div class="card">
                <div class="card-header">
                  <h3 class="card-title">বিভাগ তালিকা</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-warning btn-sm"  data-toggle="modal" data-target="#addDeptModal" style="margin-left: 5px;">
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

              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">লক্ষণ তালিকা</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-warning btn-sm"  data-toggle="modal" data-target="#addSymptomModal" style="margin-left: 5px;">
                      <i class="fas fa-user-plus"></i> নতুন
                    </button>
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>লক্ষণ</th>
                        <th align="right" width="20%">কার্যক্রম</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($medicalsymptoms as $medicalsymptom)
                        <tr>
                          <td>
                            {{ $medicalsymptom->name }}
                          </td>
                          <td align="right">
                            {{-- <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#notifModal{{ $medicalsymptom->id }}">
                              <i class="fas fa-bell"></i>
                            </button> --}}
                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editSymptomModal{{ $medicalsymptom->id }}">
                              <i class="fas fa-edit"></i>
                            </button>
                            {{-- Edit Dept Modal Code --}}
                            {{-- Edit Dept Modal Code --}}
                            <!-- Modal -->
                            <div class="modal fade" id="editSymptomModal{{ $medicalsymptom->id }}" tabindex="-1" role="dialog" aria-labelledby="editSymptomModalLabel" aria-hidden="true" data-backdrop="static">
                              <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                  <div class="modal-header bg-warning">
                                    <h5 class="modal-title" id="editSymptomModalLabel">লক্ষণ তথ্য হালনাগাদ</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <form method="post" action="{{ route('dashboard.doctorssymp.update', $medicalsymptom->id) }}">
                                    <div class="modal-body">
                                      
                                          @csrf
                                          <div class="input-group mb-3">
                                              <input type="text"
                                                     name="name"
                                                     class="form-control"
                                                     value="{{ $medicalsymptom->name }}"
                                                     placeholder="লক্ষণের নাম" required>
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
          @endif
        </div>
      </div>
    </div>

    {{-- Add User Modal Code --}}
    {{-- Add User Modal Code --}}
    <!-- Modal -->
    <div class="modal fade" id="addUserModal" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-success">
            <h5 class="modal-title" id="addUserModalLabel">নতুন ডাক্তার যোগ</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" action="{{ route('dashboard.doctors.store') }}" enctype="multipart/form-data">
            <div class="modal-body">
              
                  @csrf

                  <div class="row">
                    <div class="col-md-6">
                      <select name="district_id" id="district" class="form-control district select21" data-placeholder="জেলা নির্বাচন করুন" required>
                          <option selected="" disabled="" value="">জেলা নির্বাচন করুন</option>
                          @foreach($districts as $district)
                            <option value="{{ $district->id }}">{{ $district->name_bangla }}-{{ $district->name }}</option>
                          @endforeach
                      </select>
                    </div>
                    <div class="col-md-6">
                      <select name="upazilla_id" id="upazilla" class="form-control upazilla select21" data-placeholder="উপজেলা নির্বাচন করুন" required>
                          <option selected="" disabled="" value="">উপজেলা নির্বাচন করুন</option>
                      </select>
                    </div>
                  </div>
                  <br/>

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
                      <div class="input-group mb-3">
                          <input type="text"
                                 name="specialization"
                                 value="{{ old('specialization') }}"
                                 
                                 class="form-control"
                                 placeholder="ডাক্তার কী বিশেষজ্ঞ (যেমন: হৃদরোগ বিশেষজ্ঞ)">
                          <div class="input-group-append">
                              <div class="input-group-text"><span class="fas fa-certificate"></span></div>
                          </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="input-group mb-3">
                        <textarea name="degree" class="form-control" style="min-height: 90px;" placeholder="ডাক্তারের ডিগ্রি/ ডিগ্রিসমূহ (যেমন: MBBS, FCPS, MD, বিসিএস (স্বাস্থ্য) ইত্যাদি) [একাধিক লাইন এড করা যাবে]">{{ str_replace('<br />', "", old('degree')) }}</textarea>
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
                      {{-- <div class="input-group mb-3">
                          <input type="number"
                                 name="helpline"
                                 value="{{ old('helpline') }}"
                                 class="form-control"
                                 placeholder="হেল্পলাইন নম্বর (যদি থাকে)">
                          <div class="input-group-append">
                              <div class="input-group-text"><span class="fas fa-mobile"></span></div>
                          </div>
                      </div> --}}
                      <div class="input-group mb-3">
                          <input type="text"
                                 name="address"
                                 value="{{ old('address') }}"
                                 class="form-control"
                                 placeholder="চেম্বারের ঠিকানা" required>
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
                    <select name="hospitals[]" class="form-control multiple-select" multiple="multiple" data-placeholder="ডাক্তার যে হাসপাতালের সাথে সম্পৃক্ত (প্রয়োজনে একাধিক সিলেক্ট করা যাবে) [Optional]" >
                        @foreach($hospitals as $hospital)
                          <option value="{{ $hospital->id }}">{{ $hospital->name }} - ({{ $hospital->upazilla->name_bangla }}, {{ $hospital->district->name_bangla }})</option>
                        @endforeach
                    </select>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div>
                        সপ্তাহে যে যে দিন রোগী দেখেন<br/>
                        <textarea name="weekdays" class="form-control" style="min-height: 90px;" placeholder="উদাহরণ: শুক্রবার সকাল ৯টা থেকে দুপুর ১২টা, শনিবার সন্ধ্যা ৬টা থেকে রাত ১০টা ইত্যাদি (কোন সপ্তাহে ডাক্তার না বসলে সেটা লিখে দিন)">{{ str_replace('<br />', "", old('weekdays')) }}</textarea>
                      </div>
                    </div>
                    <div class="col-md-6">
                      অনলাইনে সিরিয়াল দেওয়া যাবে কি না<br/>
                      <select name="onlineserial" class="form-control" required>
                          <option selected="" disabled="" value="">অনলাইনে সিরিয়াল দেওয়া যাবে কি না</option>
                          <option value="1">অনলাইনে সিরিয়াল দেওয়া যাবে ✅</option>
                          <option value="0">অনলাইনে সিরিয়াল দেওয়া যাবে না ❌</option>
                      </select>
                    </div>
                  </div>
                  <div style="margin-top: 15px;">
                    <input type="text" id="selected_offdays" class="form-control" readonly>

                    <div id="datepicker"></div> <!-- Datepicker here -->
                    <select name="offdays[]" class="form-control multiple-select" multiple="multiple" data-placeholder="যেদিন যেদিন রোগী দেখবেন না (প্রয়োজনে একাধিক সিলেক্ট করা যাবে) [Optional]">
                        @foreach($optiondates as $date)
                            <option value="{{ $date->format('Y-m-d') }}">
                                {{ bangla($date->format('d-m-Y l')) }}
                            </option>
                        @endforeach
                    </select>
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


    {{-- Add Dept Modal Code --}}
    {{-- Add Dept Modal Code --}}
    <!-- Modal -->
    <div class="modal fade" id="addDeptModal" tabindex="-1" role="dialog" aria-labelledby="addDeptModalLabel" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-warning">
            <h5 class="modal-title" id="addDeptModalLabel">নতুন বিভাগ যোগ</h5>
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


    {{-- Add Symptom Modal Code --}}
    {{-- Add Symptom Modal Code --}}
    <!-- Modal -->
    <div class="modal fade" id="addSymptomModal" tabindex="-1" role="dialog" aria-labelledby="addSymptomModalLabel" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-warning">
            <h5 class="modal-title" id="addSymptomModalLabel">নতুন লক্ষণ যোগ</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" action="{{ route('dashboard.doctorssymp.store') }}">
            <div class="modal-body">
              
                  @csrf
                  <div class="input-group mb-3">
                      <input type="text"
                             name="name"
                             class="form-control"
                             value="{{ old('name') }}"
                             placeholder="লক্ষণের নাম" required>
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
    {{-- Add Symptom Modal Code --}}
    {{-- Add Symptom Modal Code --}}

@endsection

@section('third_party_scripts')
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript">
      $(document).ready(function () {
          let selectedDates = [];

          $("#datepicker").datepicker({
              dateFormat: "dd-mm-yy",
              beforeShowDay: function (date) {
                  let dateString = $.datepicker.formatDate('dd-mm-yy', date);
                  return [true, selectedDates.includes(dateString) ? "ui-state-highlight" : ""];
              },
              onSelect: function (dateText) {
                  let index = selectedDates.indexOf(dateText);
                  if (index === -1) {
                      selectedDates.push(dateText);
                  } else {
                      selectedDates.splice(index, 1);
                  }
                  $("#selected_dates").val(selectedDates.join(", ")); // Show in text field
                  $(this).datepicker("refresh");
              }
          });
      });

      
        $('.multiple-select').select2({
          // theme: 'bootstrap4',
        });
        $('.select21').select2({
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
                $('.upazilla').append('<option value="'+result[countupazilla]['id']+'">'+result[countupazilla]['name_bangla']+'-'+result[countupazilla]['name']+'</option>')
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