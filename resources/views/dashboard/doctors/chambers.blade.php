@extends('layouts.app')
@section('title') ড্যাশবোর্ড | ডাক্তার তালিকা @endsection

@section('third_party_stylesheets')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2-bootstrap4.min.css') }}" rel="stylesheet" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    {{-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> --}}
  {{-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> --}}

    <script src="{{ asset('js/select2.full.min.js') }}"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script> --}}
    <style type="text/css">
      .select2-selection__choice{
          background-color: rgba(0, 123, 255) !important;
      }
    </style>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-datepicker.min.css') }}">
    <style type="text/css">
      .datepicker_wrapper, .datepicker_wrapper2{
        position:relative;
      }
      /*textarea {
        resize: none;
      }*/

      .datepicker-footer {
          padding: 5px;
          background: #f8f9fa;
          border-top: 1px solid #ddd;
      }
    </style>

@endsection

@section('content')
  @section('page-header') চেম্বার তালিকা @endsection

    

@endsection

@section('third_party_scripts')
    

    <script type="text/javascript" src="{{ asset('js/jquery-for-dp.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>

    <script type="text/javascript">
      $(".selected_offdays").datepicker({
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