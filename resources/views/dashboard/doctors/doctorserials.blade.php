@extends('layouts.app')
@section('title') ড্যাশবোর্ড | ডাক্তারের অ্যাপয়েন্টমেন্ট তালিকা @endsection

@section('third_party_stylesheets')
    
@endsection

@section('content')
  @section('page-header') {{ $doctorserials ?? $doctorserials->first->doctor->name }} অ্যাপয়েন্টমেন্ট তালিকা @endsection
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-9">
          <div class="card">
                <div class="card-header">
                  <h3 class="card-title">ডাক্তারের অ্যাপয়েন্টমেন্ট তালিকা (তারিখ: {{ $todaydate }})</h3>

                  <div class="card-tools">
                    <form class="form-inline form-group-lg" action="">
                      <div class="form-group">
                        <input type="search-param" class="form-control form-control-sm" placeholder="ডাক্তার খুঁজুন" id="search-param" required>
                      </div>
                      <button type="button" id="search-button" class="btn btn-default btn-sm" style="margin-left: 5px;">
                        <i class="fas fa-search"></i> খুঁজুন
                      </button>
                      <button type="button" class="btn btn-success btn-sm"  data-toggle="modal" data-target="#addUserModal" style="margin-left: 5px;">
                        <i class="fas fa-user-plus"></i> নতুন
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
                        <th>বিভাগ/ লক্ষণ</th>
                        <th>হাসপাতাল/ ঠিকানা</th>
                        <th align="right" width="15%">কার্যক্রম</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($doctorserials as $doctorserial)
                        <tr>
                          <td>
                            {{ $doctorserial->name }}<br/>
                            <span style="font-size: 12px;">{{ $doctorserial->degree }}</span>
                            <span class="badge bg-primary">{{ $doctorserial->specialization }}</span>
                            <br/>
                            <small class="text-black-50"><i class="fas fa-phone"></i> {{ $doctorserial->serial }}</small>
                            <small class="text-black-50"><i class="fas fa-mobile"></i> {{ $doctorserial->helpline }}</small>
                            
                          </td>
                          <td>
                            
                          </td>
                          <td>
                            
                          </td>
                          <td align="right">
                           
                          </td>
                        </tr>
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
    <script type="text/javascript">
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
    </script>
@endsection