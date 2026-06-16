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