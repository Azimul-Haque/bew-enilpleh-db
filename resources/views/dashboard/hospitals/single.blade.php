@extends('layouts.app')
@section('title') ড্যাশবোর্ড | হাসপাতাল - ডাক্তার তালিকা তালিকা @endsection

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
  @section('page-header') হাসপাতাল - ডাক্তার তালিকা ({{ $hospital->name }}) @endsection
    <div class="container-fluid">
    <div class="card">
          <div class="card-header">
            <h3 class="card-title">হাসপাতাল - ডাক্তার তালিকা তালিকা</h3>

            <div class="card-tools">              
              <form class="form-inline form-group-lg" action="">
                @if(Auth::user()->role == 'admin')
                <div class="form-group">
                  <input type="search-param" class="form-control form-control-sm" placeholder="হাসপাতাল খুঁজুন" id="search-param" required>
                </div>
                <button type="button" id="search-button" class="btn btn-default btn-sm" style="margin-left: 5px;">
                  <i class="fas fa-search"></i> খুঁজুন
                </button>
                @endif
                {{-- <button type="button" class="btn btn-info btn-sm"  data-toggle="modal" data-target="#addBulkDate" style="margin-left: 5px;">
                  <i class="fas fa-calendar-alt"></i> বাল্ক মেয়াদ বাড়ান
                </button> --}}
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
                  <th>ধরন</th>
                  <th>ঠিকানা</th>
                  <th align="right">কার্যক্রম</th>
                </tr>
              </thead>
              <tbody>
                @foreach($hospital->doctorhospitals as $doctorhospital)
                <tr>
                    <td>
                      {{ $doctorhospital->doctor->name }}<br/>
                      <span style="font-size: 12px;">{{ $doctorhospital->doctor->degree }}</span>
                      <br/>
                      <small class="text-black-50"><i class="fas fa-phone"></i> {{ $doctorhospital->doctor->serial }}</small>
                      <small class="text-black-50"><i class="fas fa-mobile"></i> {{ $doctorhospital->doctor->helpline }}</small>
                      
                    </td>
                    <td>
                      @foreach($doctorhospital->doctor->doctormedicaldepartments as $medicaldepartment)
                        <span class="">{{ $medicaldepartment->medicaldepartment->name }}</span>
                      @endforeach <br/>
                      @foreach($doctorhospital->doctor->doctormedicalsymptoms as $medicalsymptom)
                        <span class="">{{ $medicalsymptom->medicalsymptom->name }}</span>
                      @endforeach
                    </td>

                    <td>
                      @foreach($doctorhospital->doctor->doctorhospitals as $hospital)
                        <small class="badge badge-pill badge-success">{{ $hospital->hospital->name }}</small>
                      @endforeach <br/>
                      <small class="">{{ $doctorhospital->doctor->upazilla->name_bangla }}, {{ $doctorhospital->doctor->district->name_bangla }}</small>
                    </td>
                    
                    <td align="right">
                      {{-- <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editUserModal{{ $doctorhospital->doctor->id }}">
                        <i class="fas fa-edit"></i>
                      </button>
                      <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteUserModal{{ $doctorhospital->doctor->id }}" >
                        <i class="fas fa-trash-alt"></i>
                      </button><br/> --}}
                       <a href="{{ route('dashboard.doctorserialindex', [$doctorhospital->doctor->id, date('Y-m-d')]) }}" style="margin-top: 5px;" class="btn btn-warning btn-sm">
                        <i class="fas fa-calendar-alt"></i> <b>অ্যাপয়েন্টমেন্ট তালিকা</b>
                      </a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        {{-- {{ $hospitals->links() }} --}}
    </div>


@endsection

@section('third_party_scripts')

@endsection