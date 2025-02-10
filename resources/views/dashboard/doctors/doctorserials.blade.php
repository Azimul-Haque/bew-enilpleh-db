@extends('layouts.app')
@section('title') ড্যাশবোর্ড | ডাক্তারের অ্যাপয়েন্টমেন্ট তালিকা @endsection

@section('third_party_stylesheets')
  <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-datepicker.min.css') }}">
  <style type="text/css">
    .datepicker_wrapper, .datepicker_wrapper2{
      position:relative;
    }
    textarea {
      resize: none;
    }
  </style>
@endsection

@section('content')
  @section('page-header') <a href="{{ route('dashboard.doctors') }}">ডাক্তার তালিকা</a> / {{ $doctor->name }}-এর অ্যাপয়েন্টমেন্ট তালিকা @endsection
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-10">
          <div class="card">
                <div class="card-header">
                  <h3 class="card-title">{{ $doctor->name }}-এর অ্যাপয়েন্টমেন্ট তালিকা (তারিখ: <big><strong>{{ date('d-m-Y', strtotime($todaydate)) }}</strong></big>)</h3>

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
                      <button type="button" class="btn btn-warning btn-sm" style="margin-left: 5px;" title="সকলকে ক্যানসেল মেসেজ পাঠান" data-toggle="modal" data-target="#sendCancelSMSALLModal">
                       <i class="fas fa-envelope"></i> ক্যানসেল মেসেজ
                     </button>
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
                          <div class="modal fade" id="sendCancelSMSModal{{ $doctorserial->id }}" role="dialog" aria-labelledby="sendCancelSMSModal{{ $doctorserial->id }}" aria-hidden="true" data-backdrop="static">
                            <div class="modal-dialog modal-md" role="document">
                              <div class="modal-content">
                                <div class="modal-header bg-warning">
                                  <h5 class="modal-title" id="sendCancelSMSModal{{ $doctorserial->id }}"><i class="fas fa-envelope"></i> ক্যান্সেল মেসেজ পাঠান</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <form method="post" action="{{ route('dashboard.doctorserialcancelsingle', [$doctorserial->doctor_id, $todaydate]) }}" enctype="multipart/form-data">
                                  <div class="modal-body">
                              
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $doctorserial->id }}"/>
                                    <input type="hidden" name="doctor_id" value="{{ $doctorserial->doctor_id }}"/>
                                      <textarea name="message" class="form-control" style="min-height: 250px;" placeholder="মেসেজ লিখুন" readonly="">Appointment Cancelled!&#10;&#10;Dear {{ $doctorserial->name }}, we are sorry to inform you that, your appointment with {{ $doctorserial->doctor->name }} on {{ date('d-m-Y', strtotime($doctorserial->serialdate)) }} has been cancelled unfortunately.&#10;&#10;Infoline - BD Smart Seba</textarea>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                                    <button type="submit" class="btn btn-primary">মেসেজ পাঠান</button>
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

    {{-- Send SMS ALL Code --}}
    {{-- Send SMS ALL Code --}}
    <!-- Modal -->
    <div class="modal fade" id="sendCancelSMSALLModal" role="dialog" aria-labelledby="sendCancelSMSALLModal" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header bg-warning">
            <h5 class="modal-title" id="sendCancelSMSALLModal"><i class="fas fa-envelope"></i> ক্যান্সেল মেসেজ পাঠান</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" action="{{ route('dashboard.doctorserialcancelall', [$doctorserial->doctor_id, $todaydate]) }}" enctype="multipart/form-data">
            <div class="modal-body">
        
              @csrf
              <input type="hidden" name="id" value="{{ $doctorserial->id }}"/>
              <input type="hidden" name="doctor_id" value="{{ $doctorserial->doctor_id }}"/>
                <textarea name="message" class="form-control" style="min-height: 250px;" placeholder="মেসেজ লিখুন" readonly="">Appointment Cancelled!&#10;&#10;Dear {{ $doctorserial->name }}, we are sorry to inform you that, your appointment with {{ $doctorserial->doctor->name }} on {{ date('d-m-Y', strtotime($doctorserial->serialdate)) }} has been cancelled unfortunately.&#10;&#10;Infoline - BD Smart Seba</textarea>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
              <button type="submit" class="btn btn-primary">মেসেজ পাঠান</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    {{-- Send SMS ALL Code --}}
    {{-- Send SMS ALL Code --}}

    

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