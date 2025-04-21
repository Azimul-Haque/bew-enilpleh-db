@extends('layouts.app')
@section('title') ড্যাশবোর্ড | হাসপাতাল তালিকা @endsection

@section('third_party_stylesheets')
   <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
   <link href="{{ asset('css/select2-bootstrap4.min.css') }}" rel="stylesheet" />
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
  @section('page-header') হাসপাতাল তালিকা (মোট {{ bangla($hospitalscount) }} টি) @endsection
    <div class="container-fluid">
    <div class="card">
          <div class="card-header">
            <h3 class="card-title">হাসপাতাল তালিকা</h3>

            <div class="card-tools">
              @if(Auth::user()->role == 'admin')
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
              @endif
              
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body p-0">
            <table class="table">
              <thead>
                <tr>
                  <th>নাম</th>
                  <th>ঠিকানা</th>
                  <th align="right">কার্যক্রম</th>
                </tr>
              </thead>
              <tbody>
                @foreach($hospitals as $hospital)
                  <tr>
                    <td>
                      {{ $hospital->name }}
                      <small class="text-black-50"><i class="fas fa-phone"></i> {{ $hospital->telephone }}</small>
                      <small class="text-black-50"><i class="fas fa-mobile"></i> {{ $hospital->mobile }}</small><br/>
                      <span class="badge bg-success">{{ hospital_type($hospital->hospital_type) }}</span>
                    </td>
                    <td>
                      {{ $hospital->address }}<br/>
                      {{ $hospital->upazilla->name_bangla }}, {{ $hospital->district->name_bangla }}
                    </td>
                    <td align="right">
                      {{-- <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#notifModal{{ $hospital->id }}">
                        <i class="fas fa-bell"></i>
                      </button> --}}
                      <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editUserModal{{ $hospital->id }}">
                        <i class="fas fa-edit"></i>
                      </button>
                      @if(Auth::user()->role == 'admin')
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteUserModal{{ $hospital->id }}">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      @endif
                    </td>
                  </tr>
                  {{-- Edit User Modal Code --}}
                  {{-- Edit User Modal Code --}}
                  <!-- Modal -->
                  <div class="modal fade" id="editUserModal{{ $hospital->id }}" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true" data-backdrop="static">
                    <div class="modal-dialog modal-lg" role="document">
                      <div class="modal-content">
                        <div class="modal-header bg-primary">
                          <h5 class="modal-title" id="editUserModalLabel">হাসপাতাল তথ্য হালনাগাদ</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <form method="post" action="{{ route('dashboard.hospitals.update', $hospital->id) }}" enctype="multipart/form-data">
                          <div class="modal-body">
                            
                                @csrf

                                <div class="row">
                                  <div class="col-md-6">
                                    <select name="district_id" id="district" class="form-control district select21" data-placeholder="জেলা নির্বাচন করুন" required>
                                        <option selected="" disabled="" value="">জেলা নির্বাচন করুন</option>
                                        @foreach($districts as $district)
                                          <option value="{{ $district->id }}" @if($district->id == $hospital->district_id) selected @endif>{{ $district->name_bangla }}-{{ $district->name }}</option>
                                        @endforeach
                                    </select>
                                  </div>
                                  <div class="col-md-6">
                                    <select name="upazilla_id" id="upazilla" class="form-control upazilla select21" data-placeholder="উপজেলা সিলেক্ট করুন" required>
                                        <option selected="" value="{{ $hospital->upazilla_id }}">{{ $hospital->upazilla->name_bangla }}-{{ $hospital->upazilla->name }}</option>
                                    </select>
                                  </div>
                                  <br/><br/>
                                  <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <input type="text"
                                               name="name"
                                               class="form-control"
                                               value="{{ $hospital->name }}"
                                               placeholder="হাসপাতালের নাম" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-hospital"></span></div>
                                        </div>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class="input-group mb-3">
                                      <select name="hospital_type" class="form-control" required>
                                          <option selected="" disabled="" value="">হাসপাতালের ধরন</option>
                                          <option value="1" @if($hospital->hospital_type == 1) selected @endif>সরকারি হাসপাতাল</option>
                                          <option value="2" @if($hospital->hospital_type == 2) selected @endif>প্রাইভেট ক্লিনিক ও হাসপাতাল</option>
                                          <option value="3" @if($hospital->hospital_type == 3) selected @endif>ফিজিওথেরাপি সেন্টার</option>
                                          <option value="4" @if($hospital->hospital_type == 4) selected @endif>কিডনি ডায়ালাইসিস</option>
                                      </select>
                                      <div class="input-group-append">
                                          <div class="input-group-text"><span class="fas fa-star-half-alt"></span></div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <input type="number"
                                               name="telephone"
                                               class="form-control"
                                               value="{{ $hospital->telephone }}"
                                               placeholder="টেলিফোন নং" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-phone"></span></div>
                                        </div>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <input type="number"
                                               name="mobile"
                                               value="{{ $hospital->mobile }}"
                                               autocomplete="off"
                                               class="form-control"
                                               placeholder="মোবাইল নম্বর (যদি থাকে)">
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-mobile"></span></div>
                                        </div>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="input-group mb-3">
                                        <input type="text"
                                               name="address"
                                               value="{{ $hospital->address }}"
                                               autocomplete="off"
                                               class="form-control"
                                               placeholder="হাসপাতাল/ক্লিনিক/প্রতিষ্ঠানের ঠিকানা লিখুন" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-map-marked-alt"></span></div>
                                        </div>
                                    </div>
                                  </div>                                  
                                  {{-- <div class="col-md-12">
                                    <div style="margin-bottom: 15px;">
                                      <select name="doctor_ids[]" class="form-control multiple-select" multiple="multiple" data-placeholder="যে ডাক্তার হাসপাতালের এর সাথে সম্পৃক্ত (প্রয়োজনে একাধিক সিলেক্ট করা যাবে)">
                                          @foreach($alldoctors as $doctor)
                                            <option value="{{ $doctor->id }}" @if(in_array($doctor->id, $hospital->doctorhospitals->pluck('doctor_id')->toArray())) selected @endif>{{ $doctor->name }}, {{ $doctor->degree }}, {{ $doctor->specialization }} ({{ $doctor->district->name }})</option>
                                          @endforeach
                                      </select>
                                    </div>
                                  </div> --}}
                                  <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <input type="text"
                                               name="location"
                                               value="{{ $hospital->location }}"
                                               autocomplete="off"
                                               class="form-control"
                                               placeholder="(Optional) গুগল ম্যাপ লোকেশন লিংক">
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-map-marker-alt"></span></div>
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <input type="text"
                                               name="website"
                                               value="{{ $hospital->website }}"
                                               autocomplete="off"
                                               class="form-control"
                                               placeholder="(Optional) ডাক্তার তালিকার ওয়েবপেজ লিংক">
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-globe"></span></div>
                                        </div>
                                    </div>
                                    <div style="margin-bottom: 15px;">
                                      <select name="branch_ids[]" class="form-control multiple-select" multiple="multiple" data-placeholder="শাখা হাসপাতাল (প্রয়োজনে একাধিক সিলেক্ট করা যাবে)">
                                          
                                          @foreach($allhospitals->except($hospital->id) as $brhospital)
                                            <option value="{{ $brhospital->id }}" @if(in_array($brhospital->id, $hospital->branches->pluck('id')->toArray())) selected @endif>{{ $brhospital->name }}</option>
                                          @endforeach
                                      </select>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class="input-group mb-3">
                                      <textarea name="investigation_data" class="form-control" style="min-height: 150px;" placeholder="টেস্ট ইনভেস্টিগেশন তালিকা (OPTIONAL)">{{ str_replace('<br />', "", $hospital->investigation_data) }}</textarea>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="input-group mb-3">
                                        <input type="text"
                                               name="description"
                                               value="{{ $hospital->description }}"
                                               autocomplete="off"
                                               class="form-control"
                                               placeholder="(Optional) বক্স এর জন্য বার্তা লিখুন">
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-edit"></span></div>
                                        </div>
                                    </div>
                                  </div>

                                  <div class="col-md-12">
                                    <h4>
                                      বিস্তারিত (OPTIONAL)
                                    </h4>
                                    <input type="text" name="webaddress" class="form-control" placeholder="ওয়েব এড্রেস" style="margin-bottom: 5px;">
                                    <h5>
                                      অথবা (ওয়েব এড্রেস দিলে নিচের ব্যানার প্রদর্শন করবে না)
                                    </h5>
                                  </div>
                                  <div class="col-md-6">
                                    <div class="form-group" style="margin-top: 15px;">
                                        <label for="image">ব্যানার-১ (Optional, Max 1 MB)</label>
                                        <input type="text" name="image1caption" class="form-control" placeholder="ব্যানার-১ এর ক্যাপশন লিখুন (OPTIONAL)" style="margin-bottom: 5px;">
                                        <input type="file" name="image1" accept="image/*">
                                    </div>

                                    <div class="form-group" style="margin-top: 15px;">
                                        <label for="image">ব্যানার-২ (Optional, Max 1 MB)</label>
                                        <input type="text" name="image2caption" class="form-control" placeholder="ব্যানার-২ এর ক্যাপশন লিখুন (OPTIONAL)" style="margin-bottom: 5px;">
                                        <input type="file" name="image2" accept="image/*">
                                    </div>
                                  </div>

                                  <div class="col-md-6">
                                    <div class="form-group" style="margin-top: 15px;">
                                        <label for="image">ব্যানার-৩ (Optional, Max 1 MB)</label>
                                        <input type="text" name="image3caption" class="form-control" placeholder="ব্যানার-৩ এর ক্যাপশন লিখুন (OPTIONAL)" style="margin-bottom: 5px;">
                                        <input type="file" name="image3" accept="image/*">
                                    </div>
                                    <div class="form-group" style="margin-top: 15px;">
                                        <label for="image">ব্যানার-৪ (Optional, Max 1 MB)</label>
                                        <input type="text" name="image4caption" class="form-control" placeholder="ব্যানার-৪ এর ক্যাপশন লিখুন (OPTIONAL)" style="margin-bottom: 5px;">
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
                          <a href="{{ route('dashboard.hospitals.delete', $hospital->id) }}" class="btn btn-danger">ডিলেট করুন</a>
                        </div>
                      </div>
                    </div>
                  </div>
                  {{-- Delete User Modal Code --}}
                  {{-- Delete User Modal Code --}}
                  
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
    <div class="modal fade" id="addUserModal" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-success">
            <h5 class="modal-title" id="addUserModalLabel">নতুন হাসপাতাল যোগ</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" action="{{ route('dashboard.hospitals.store') }}" enctype="multipart/form-data">
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
                      {{-- <div class="input-group mb-3">
                        
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-map"></span></div>
                        </div>
                      </div> --}}
                    </div>
                    <div class="col-md-6">
                      <select name="upazilla_id" id="upazilla" class="form-control upazilla select21" data-placeholder="উপজেলা নির্বাচন করুন" required>
                          <option selected="" disabled="" value="">উপজেলা নির্বাচন করুন</option>
                      </select>
                      {{-- <div class="input-group mb-3">
                        
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-map-marked-alt"></span></div>
                        </div>
                      </div> --}}
                    </div>
                    <br/><br/>
                    <div class="col-md-6">
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
                    </div>
                    <div class="col-md-6">
                      <div class="input-group mb-3">
                        <select name="hospital_type" class="form-control" required>
                            <option selected="" disabled="" value="">হাসপাতালের ধরন</option>
                            <option value="1">সরকারি হাসপাতাল</option>
                            <option value="2">প্রাইভেট ক্লিনিক ও হাসপাতাল</option>
                            <option value="3">ফিজিওথেরাপি সেন্টার</option>
                            <option value="4">কিডনি ডায়ালাইসিস</option>
                        </select>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-star-half-alt"></span></div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
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
                    </div>
                    <div class="col-md-6">
                      <div class="input-group mb-3">
                          <input type="number"
                                 name="mobile"
                                 value="{{ old('mobile') }}"
                                 autocomplete="off"
                                 class="form-control"
                                 placeholder="মোবাইল নম্বর (যদি থাকে)">
                          <div class="input-group-append">
                              <div class="input-group-text"><span class="fas fa-mobile"></span></div>
                          </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="input-group mb-3">
                          <input type="text"
                                 name="address"
                                 value="{{ old('address') }}"
                                 autocomplete="off"
                                 class="form-control"
                                 placeholder="হাসপাতাল/ক্লিনিক/প্রতিষ্ঠানের ঠিকানা লিখুন" required>
                          <div class="input-group-append">
                              <div class="input-group-text"><span class="fas fa-map-marked-alt"></span></div>
                          </div>
                      </div>
                    </div>                    
                    {{-- <div class="col-md-12">
                      <div style="margin-bottom: 15px;">
                        <select name="doctor_ids[]" class="form-control multiple-select" multiple="multiple" data-placeholder="যে ডাক্তার হাসপাতালের এর সাথে সম্পৃক্ত (প্রয়োজনে একাধিক সিলেক্ট করা যাবে)">
                            @foreach($alldoctors as $doctor)
                              <option value="{{ $doctor->id }}">{{ $doctor->name }}, {{ $doctor->degree }}, {{ $doctor->specialization }} ({{ $doctor->district->name }})</option>
                            @endforeach
                        </select>
                      </div>
                    </div> --}}
                    <div class="col-md-6">
                      <div class="input-group mb-3">
                          <input type="text"
                                 name="location"
                                 value="{{ old('location') }}"
                                 autocomplete="off"
                                 class="form-control"
                                 placeholder="(OPTIONAL) গুগল ম্যাপ লোকেশন লিংক">
                          <div class="input-group-append">
                              <div class="input-group-text"><span class="fas fa-map-marker-alt"></span></div>
                          </div>
                      </div>
                      <div class="input-group mb-3">
                          <input type="text"
                                 name="website"
                                 value="{{ old('website') }}"
                                 autocomplete="off"
                                 class="form-control"
                                 placeholder="(OPTIONAL) ডাক্তার তালিকার ওয়েবপেজ লিংক">
                          <div class="input-group-append">
                              <div class="input-group-text"><span class="fas fa-globe"></span></div>
                          </div>
                      </div>                      

                      <div style="margin-bottom: 15px;">
                        <select name="branch_ids[]" class="form-control multiple-select" multiple="multiple" data-placeholder="শাখা হাসপাতাল (প্রয়োজনে একাধিক সিলেক্ট করা যাবে)">
                            @foreach($allhospitals as $hospital)
                              <option value="{{ $hospital->id }}">{{ $hospital->name }}</option>
                            @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="input-group mb-3">
                        <textarea name="investigation_data" class="form-control" style="min-height: 150px;" placeholder="টেস্ট ইনভেস্টিগেশন তালিকা (OPTIONAL)"></textarea>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <div class="input-group mb-3">
                          <input type="text"
                                 name="description"
                                 value="{{ old('description') }}"
                                 autocomplete="off"
                                 class="form-control"
                                 placeholder="বক্স এর জন্য বার্তা লিখুন (Optional)">
                          <div class="input-group-append">
                              <div class="input-group-text"><span class="fas fa-edit"></span></div>
                          </div>
                      </div>
                      
                    </div>
                    <div class="col-md-12">
                      <h4>
                        বিস্তারিত (OPTIONAL)
                      </h4>
                      <input type="text" name="webaddress" class="form-control" placeholder="ওয়েব এড্রেস" style="margin-bottom: 5px;">
                      <h5>
                        অথবা (ওয়েব এড্রেস দিলে নিচের ব্যানার প্রদর্শন করবে না)
                      </h5>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group" style="margin-top: 15px;">
                          <label for="image">ব্যানার-১ (Optional, Max 1 MB)</label>
                          <input type="text" name="image1caption" class="form-control" placeholder="ব্যানার-১ এর ক্যাপশন লিখুন (OPTIONAL)" style="margin-bottom: 5px;">
                          <input type="file" name="image1" accept="image/*">
                      </div>

                      <div class="form-group" style="margin-top: 15px;">
                          <label for="image">ব্যানার-২ (Optional, Max 1 MB)</label>
                          <input type="text" name="image2caption" class="form-control" placeholder="ব্যানার-২ এর ক্যাপশন লিখুন (OPTIONAL)" style="margin-bottom: 5px;">
                          <input type="file" name="image2" accept="image/*">
                      </div>
                    </div>
                    <div class="col-md-6">                    
                      <div class="form-group" style="margin-top: 15px;">
                          <label for="image">ব্যানার-৩ (Optional, Max 1 MB)</label>
                          <input type="text" name="image3caption" class="form-control" placeholder="ব্যানার-৩ এর ক্যাপশন লিখুন (OPTIONAL)" style="margin-bottom: 5px;">
                          <input type="file" name="image3" accept="image/*">
                      </div>
                      <div class="form-group" style="margin-top: 15px;">
                          <label for="image">ব্যানার-৪ (Optional, Max 1 MB)</label>
                          <input type="text" name="image4caption" class="form-control" placeholder="ব্যানার-৪ এর ক্যাপশন লিখুন (OPTIONAL)" style="margin-bottom: 5px;">
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
    {{-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}
    <script type="text/javascript">
      $('.multiple-select').select2({
        // theme: 'bootstrap4',
      });
      $('.select21').select2({
        // theme: 'bootstrap4',
        dropdownParent: $('.modal')
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
              var urltocall = '{{ route('dashboard.hospitals') }}' +  '/' + $('#search-param').val();
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