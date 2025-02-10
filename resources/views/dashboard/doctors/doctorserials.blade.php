@extends('layouts.app')
@section('title') ড্যাশবোর্ড | ডাক্তারের অ্যাপয়েন্টমেন্ট তালিকা @endsection

@section('third_party_stylesheets')
  <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-datepicker.min.css') }}">
  <style type="text/css">
    .datepicker_wrapper, .datepicker_wrapper2{
      position:relative;
    }
  </style>
@endsection

@section('content')
  @section('page-header') <a href="{{ route('dashboard.doctors') }}">ডাক্তার তালিকা</a> / {{ $doctor->name }}-এর অ্যাপয়েন্টমেন্ট তালিকা @endsection
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-9">
          <div class="card">
                <div class="card-header">
                  <h3 class="card-title">{{ $doctor->name }}-এর অ্যাপয়েন্টমেন্ট তালিকা (তারিখ: {{ $todaydate }})</h3>

                  <div class="card-tools">
                    <form class="form-inline form-group-lg" action="">
                      <div class="form-group">
                        <input type="text" id="selectdate" class="form-control form-control-sm" placeholder="তারিখ পরিবর্তন" required>
                      </div>
                      <button type="button" id="search-button" class="btn btn-default btn-sm" style="margin-left: 5px;">
                        <i class="fas fa-search"></i> Search
                      </button>
                      <a href="{{ route('dashboard.getdoctorserialpdf', [$doctor->id, $todaydate]) }}" class="btn btn-success btn-sm"  style="margin-left: 5px;" target="_blank">
                        <i class="fas fa-print"></i> প্রিন্ট করুন
                      </a>
                    </form>
                    
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>সিরিয়াল</th>
                        <th>নাম</th>
                        <th>মোবাইল</th>
                        <th>তারিখ</th>
                        <th align="right" width="15%">কার্যক্রম</th>
                      </tr>
                    </thead>
                    <tbody>
                      @php $iteratior = 1; @endphp
                      @foreach($doctorserials as $doctorserial)
                        <tr>
                          <td>{{ bangla($iteratior) }}</td>
                          <td>{{ $doctorserial->name }}</td>
                          <td>{{ $doctorserial->mobile }}</td>
                          <td>{{ date('F d, Y', strtotime($doctorserial->serialdate)) }}</td>

                          <td align="right">
                           <button type="button" class="btn btn-warning btn-sm" title="ক্যান্সেল মেসেজ পাঠান" data-toggle="modal" data-target="#sendCancelSMSModal{{ $doctorserial->id }}">
                             <i class="fas fa-envelope"></i>
                           </button>
                          </td>

                          {{-- Send Single SMS Code --}}
                          {{-- Send Single SMS Code --}}
                          <!-- Modal -->
                          <div class="modal fade" id="sendCancelSMSModal{{ $doctorserial->id }}" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true" data-backdrop="static">
                            <div class="modal-dialog modal-lg" role="document">
                              <div class="modal-content">
                                <div class="modal-header bg-warning">
                                  <h5 class="modal-title" id="editUserModalLabel">ক্যান্সেল মেসেজ পাঠান</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <form method="post" action="{{ route('dashboard.doctorserialcancelsingle', [$todaydate, $doctorserial->doctor_id]) }}" enctype="multipart/form-data">
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
                          {{-- Send Single SMS Code --}}
                          {{-- Send Single SMS Code --}}
                        </tr>
                        @php $iteratior++; @endphp
                      @endforeach
                    </tbody>
                  </table>
                </div>
                <!-- /.card-body -->
              </div>
              {{ $doctorserials->links() }}
        </div>
      </div>
    </div>

    

@endsection

@section('third_party_scripts')
  <script type="text/javascript" src="{{ asset('js/jquery-for-dp.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
  {{-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script> --}}
  <script type="text/javascript">
    $("#selectdate").datepicker({
      format: 'yyyy-mm-dd',
      todayHighlight: true,
      autoclose: true,
    });

    $(document).on('click', '#search-button', function() {
      if($('#selectdate').val() != '') {
        var urltocall = '{{ route('dashboard.doctors') }}' +  '/{{ $doctor->id }}/appoinments/list/' + $('#selectdate').val();
        location.href= urltocall;
      } else {
        $('#selectdate').css({ "border": '#FF0000 2px solid'});
        Toast.fire({
            icon: 'warning',
            title: 'তারিখ সিলেক্ট করুন!'
        })
      }
    });
    $("#selectdate").keyup(function(e) {
      if(e.which == 13) {
        if($('#selectdate').val() != '') {
          var urltocall = '{{ route('dashboard.doctors') }}' +  '/{{ $doctor->id }}/appoinments/list/' + $('#selectdate').val();
          location.href= urltocall;
        } else {
          $('#selectdate').css({ "border": '#FF0000 2px solid'});
          Toast.fire({
              icon: 'warning',
              title: 'তারিখ সিলেক্ট করুন!'
          })
        }
      }
    });
  </script>
@endsection