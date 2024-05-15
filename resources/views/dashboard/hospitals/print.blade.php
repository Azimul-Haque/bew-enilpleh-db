@extends('layouts.app')
@section('title') জেলা তালিকা @endsection

@section('third_party_stylesheets')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/icheck-bootstrap@3.0.1/icheck-bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-datepicker.min.css') }}">
@endsection

@section('content')
  @section('page-header') জেলা @endsection
    <div class="container-fluid">
    <div class="card">
          <div class="card-header">
            <h3 class="card-title">জেলা</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body p-0">
            
          </div>
          <!-- /.card-body -->
        </div>
        {{ $hospitals->links() }}
    </div>

    {{-- Add User Modal Code --}}
    {{-- Add User Modal Code --}}
    <!-- Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-success">
            <h5 class="modal-title" id="addUserModalLabel">নতুন হাসপাতাল যোগ</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" action="{{ route('dashboard.hospitals.store') }}">
            <div class="modal-body">
              
                  @csrf

                  <div class="input-group mb-3">
                    <select name="district_id" id="district" class="form-control district" required>
                        <option selected="" disabled="" value="">জেলা নির্বাচন করুন</option>
                        @foreach($districts as $district)
                          <option value="{{ $district->id }}">{{ $district->name_bangla }}</option>
                        @endforeach
                    </select>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-map"></span></div>
                    </div>
                  </div>
                  <div class="input-group mb-3">
                    <select name="upazilla_id" id="upazilla" class="form-control upazilla" required>
                        <option selected="" disabled="" value="">উপজেলা নির্বাচন করুন</option>
                    </select>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-map-marked-alt"></span></div>
                    </div>
                  </div>
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
                  <div class="input-group mb-3">
                    <select name="hospital_type" class="form-control" required>
                        <option selected="" disabled="" value="">হাসপাতালের ধরন</option>
                        <option value="1">মেডিকেল কলেজ ও হাসপাতাল</option>
                        <option value="2">প্রাইভেট হাসপাতাল</option>
                        <option value="3">স্বাস্থ্য কমপ্লেক্স</option>
                    </select>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-star-half-alt"></span></div>
                    </div>
                  </div>
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
                  <div class="input-group mb-3">
                      <input type="number"
                             name="mobile"
                             value="{{ old('mobile') }}"
                             autocomplete="off"
                             class="form-control"
                             placeholder="মোবাইল নম্বর" required>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-mobile"></span></div>
                      </div>
                  </div>
                  <div class="input-group mb-3">
                      <input type="text"
                             name="location"
                             value="{{ old('location') }}"
                             autocomplete="off"
                             class="form-control"
                             placeholder="গুগল ম্যাপ লোকেশন লিংক" required>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-map-marker-alt"></span></div>
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
                $('.upazilla').append('<option value="'+result[countupazilla]['id']+'">'+result[countupazilla]['name_bangla']+'</option>')
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