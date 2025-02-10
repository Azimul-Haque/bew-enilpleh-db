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
                           <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#sendSMS{{ $doctorserial->id }}">
                             <i class="fas fa-edit"></i>
                           </button>
                          </td>
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