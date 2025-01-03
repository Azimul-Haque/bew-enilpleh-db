@extends('layouts.app')
@section('title') ড্যাশবোর্ড @endsection

@section('third_party_stylesheets')

@endsection

@section('content')
	@section('page-header') ড্যাশবোর্ড @endsection
    <div class="container-fluid">
        <div class="row">
          <div class="col-md-3">
            <a href="{{ route('dashboard.hospitals') }}" class="info-box mb-3">
              <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-hospital"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">হাসপাতাল তালিকা</span>
                <small class="info-box-text" style="margin-top: 10px;">ক্লিক করুন</small>
              </div>
            </a>
          </div>

          <div class="col-md-3">
            <a href="{{ route('dashboard.doctors') }}" class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fa-user-md"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">ডাক্তার তালিকা</span>
                <small class="info-box-text" style="margin-top: 10px;">ক্লিক করুন</small>
              </div>
            </a>
          </div>

          <div class="col-md-3">
            <a href="{{ route('dashboard.hospitals') }}" class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-hospital"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">হাসপাতাল তালিকা</span>
                <small class="info-box-text" style="margin-top: 10px;">ক্লিক করুন</small>
              </div>
            </a>
          </div>

          <div class="col-md-3">
            <a href="{{ route('dashboard.hospitals') }}" class="info-box mb-3">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-hospital"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">হাসপাতাল তালিকা</span>
                <small class="info-box-text" style="margin-top: 10px;">ক্লিক করুন</small>
              </div>
            </a>
          </div>
        </div>

        <div class="row">
          {{-- <div class="col-md-6">
            <a href="{{ route('dashboard.deposit.getlist', [date('Y-m-d'), 'All']) }}" class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-coins"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">দৈনিক জমা</span>
                <span class="info-box-number">৳ {{ 0 }}</span>
              </div>
            </a>
          </div>
          <div class="col-md-6">
            <a href="{{ route('dashboard.expenses.getlist', [date('Y-m-d'), 'All']) }}" class="info-box mb-3">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-hand-holding-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">দৈনিক খরচ</span>
                <span class="info-box-number">৳ {{ 0 }}</span>
              </div>
            </a>
          </div> --}}
        </div>
        @if(Auth::user()->role == 'admin')
        <div class="row">
          <div class="col-md-6">
            <button class="btn btn-warning" data-toggle="modal" data-target="#clearQueryCacheModal">
              <i class="fas fa-tools"></i> সকল কোয়েরি ক্যাশ (API) ক্লিয়ার করুন
            </button>
            {{-- Modal Code --}}
            {{-- Modal Code --}}
            <!-- Modal -->
            <div class="modal fade" id="clearQueryCacheModal" tabindex="-1" role="dialog" aria-labelledby="clearQueryCacheModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="clearQueryCacheModalLabel">কোয়েরি ক্যাশ ক্লিয়ার</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                      আপনি কি নিশ্চিতভাবে সকল কোয়েরি ক্যাশ ক্লিয়ার করতে চান?<br/>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                    <a href="{{ route('dashboard.clearquerycache') }}" class="btn btn-warning">ক্যাশ ক্লিয়ার করুন</a>
                    </div>
                </div>
                </div>
            </div>
            {{-- Modal Code --}}
            {{-- Modal Code --}}
            <br/>
            <br/>
          </div>
        </div>
        @endif
        <div class="row">
          {{-- <div class="col-md-6">
            <div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">ব্যবহারকারী যোগদানের হার</h3>
                <div class="card-tools">
                  <small>সর্বশেষ দুই সপ্তাহ</small>
                </div>
              </div>
              <div class="card-body">
              <div class="chart">
                <canvas id="lineChart" style="min-height: 250px; height: 300px; max-height: 400px; max-width: 100%;"></canvas>
              </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">ক্রমবর্ধমান ব্যবহারকারী সংখ্যা</h3>
                <div class="card-tools">
                  <small>সর্বশেষ দুই সপ্তাহ</small>
                </div>
              </div>
              <div class="card-body">
              <div class="chart">
                <canvas id="lineChart2" style="min-height: 250px; height: 300px; max-height: 400px; max-width: 100%;"></canvas>
              </div>
              </div>
            </div>
          </div> --}}
        </div>
    </div>
@endsection

@section('third_party_scripts')
  <script src=" https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js "></script>
  <script type="text/javascript">
    var ctx = document.getElementById('lineChart').getContext('2d');
    var lineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! $daysforchartc !!},
            datasets: [{
                label: 'ব্যবহারকারী সংখ্যা',
                borderColor: "#3e95cd",
                fill: true,
                data: {!! $totalusersforchartc !!},
                borderWidth: 2,
                borderColor: "rgba(0,165,91,1)",
                borderCapStyle: 'butt',
                pointBorderColor: "rgba(0,165,91,1)",
                pointBackgroundColor: "#fff",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "rgba(0,165,91,1)",
                pointHoverBorderColor: "rgba(0,165,91,1)",
                pointHoverBorderWidth: 2,
                pointRadius: 5,
                pointHitRadius: 10,
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            
        }
    });


    var ctx = document.getElementById('lineChart2').getContext('2d');
    var lineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! $daysforchartc !!},
            datasets: [{
                label: 'ক্রমবর্ধমান ব্যবহারকারী সংখ্যা',
                borderColor: "#112E8A",
                fill: true,
                data: {!! $totaluserscumulitiveforchartc !!},
                borderWidth: 2,
                borderColor: "rgba(17,46,138,1)",
                borderCapStyle: 'butt',
                pointBorderColor: "rgba(17,46,138,1)",
                pointBackgroundColor: "#fff",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "rgba(17,46,138,1)",
                pointHoverBorderColor: "rgba(17,46,138,1)",
                pointHoverBorderWidth: 2,
                pointRadius: 5,
                pointHitRadius: 10,
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            
        }
    });
  </script>
@endsection