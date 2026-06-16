@extends('layouts.app')
@section('title') ড্যাশবোর্ড | ডাক্তার প্রোফাইল @endsection

@section('third_party_stylesheets')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2-bootstrap4.min.css') }}" rel="stylesheet" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script src="{{ asset('js/select2.full.min.js') }}"></script>
    <style type="text/css">
      .select2-selection__choice{
          background-color: rgba(0, 123, 255) !important;
      }
    </style> 
@endsection

@section('content')
  @section('page-header') ডাক্তার প্রোফাইল @endsection
  <div class="container-fluid">
      <div class="row">
          <div class="col-10">
              <div class="card card-outline card-success shadow-sm">
                  <div class="card-header">
                      <h3 class="card-title fw-bold"><i class="fas fa-hospital-alt mr-2 text-success"></i> ডাক্তার: <strong>{{ $doctor->name }}</strong></h3>
                  </div>
                  
                  <div class="card-body">
                      
                  </div>
              </div>
          </div>
      </div>
  </div>

  <div class="modal fade" id="addChamberModal" role="dialog" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content rounded-4 border-0">
              <div class="modal-header bg-success text-white border-0">
                  <h5 class="modal-title font-weight-bold"><i class="fas fa-plus-circle mr-2"></i>নতুন চেম্বার যুক্ত করুন</h5>
                  <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <form action="{{ route('dashboard.doctors.chambers.store') }}" method="POST">
                  @csrf

                  <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
                  <div class="modal-body p-4">
                      <div class="row">
                          <div class="col-md-12 mb-3">
                              <label class="font-weight-bold small">হাসপাতাল নির্বাচন করুন *</label>
                              <select name="hospital_id" class="form-control select21" style="width: 100%;" required>
                                  <option value="" selected disabled>তালিকা থেকে হাসপাতাল খুঁজুন...</option>
                                  @foreach($hospitals as $hosp)
                                      @if(!$doctor->doctorhospitals->contains('hospital_id', $hosp->id))
                                          <option value="{{ $hosp->id }}">{{ $hosp->name }} - ({{ $hosp->upazilla->name_bangla }})</option>
                                      @endif
                                  @endforeach
                              </select>
                          </div>

                          <div class="col-md-6 mb-3">
                              <label class="font-weight-bold small">অনলাইন সিরিয়াল</label>
                              <select name="onlineserial" class="form-control" required>
                                  <option value="1">অনলাইনে সিরিয়াল দেওয়া যাবে ✅</option>
                                  <option value="0" selected>না, অফলাইন ❌</option>
                              </select>
                          </div>
                          <div class="col-md-6">
                          </div>

                          <div class="col-md-6 mb-3">
                              <label class="font-weight-bold small">রুম নম্বর/বিভাগ *</label>
                              <input type="text" name="address_or_room" class="form-control" placeholder="উদা: ৩১০ নম্বর রুম" required>
                          </div>
                          <div class="col-md-6 mb-3">
                              <label class="font-weight-bold small">সিরিয়াল ফোন নম্বর *</label>
                              <input type="text" name="serial_phone" class="form-control" placeholder="০১৭XXXXXXXX" required>
                          </div>
                          <div class="col-md-12 mb-3">
                              <label class="font-weight-bold small">সাপ্তাহিক সময়সূচী *</label>
                              <textarea name="weekdays" class="form-control" rows="3" placeholder="উদা: শনি-বুধ (বিকাল ৫টা - রাত ৯টা)" required></textarea>
                          </div>

                          <div class="col-md-12 mb-3">
                              <label class="font-weight-bold small">যেদিন যেদিন চেম্বারে বসবেন (অতিরিক্ত তারিখসমূহ)</label>
                              <div class="input-group">
                                  <div class="input-group-prepend">
                                      <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                                  </div>
                                  <input type="text" name="ondays" class="form-control ondays-datepicker" 
                                         placeholder="তারিখগুলো নির্বাচন করুন" 
                                         value="" readonly>
                              </div>
                              <small class="text-muted">একাধিক তারিখ সিলেক্ট করা যাবে, কোন তারিখ সিলেক্ট না করলে অ্যাপ ব্যবহারকারী বর্তমান তারিখ হতে প্রতিদিন সিরিয়াল দিতে পারবে।</small>
                          </div>
                      </div>
                  </div>
                  <div class="modal-footer border-0 p-4">
                      <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">ফিরে যান</button>
                      <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">সংরক্ষণ করুন</button>
                  </div>
              </form>
          </div>
      </div>
  </div>
    

@endsection

@section('third_party_scripts')
    <script type="text/javascript">
      $('.select21').select2({
        theme: 'bootstrap4',
      });
    </script>

    <script type="text/javascript" src="{{ asset('js/jquery-for-dp.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>

    <script type="text/javascript">
      $(".ondays-datepicker").datepicker({
        format: 'yyyy-mm-dd',
        startDate: new Date(),
        todayHighlight: true,
        autoclose: false,
        multidate: true,
      })
      // Close Button Functionality
      // $("#closePicker").click(function() {
      //   $("#selected_offdays").datepicker('hide'); // Close the picker
      //   $('body').click();
      // })
    </script>
@endsection