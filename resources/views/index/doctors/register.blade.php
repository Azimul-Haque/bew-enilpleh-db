<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Registration | SmartBD</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;600;700&display=swap');
        body { font-family: 'Hind Siliguri', sans-serif; background-color: #f0f2f5; color: #333; }
        .navbar-brand { font-weight: 800; color: #1c9288 !important; letter-spacing: -1px; font-size: 1.8rem; }
        .smart-card { border: none; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.08); background: #fff; }
        .section-title { border-left: 5px solid #1c9288; padding-left: 15px; margin: 30px 0 20px; font-weight: 700; color: #1c9288; }
        .btn-submit { background: linear-gradient(135deg, #1c9288 0%, #157a71 100%); border: none; padding: 12px 40px; border-radius: 50px; font-weight: 700; transition: transform 0.2s; }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(28, 146, 136, 0.3); color: white; }
        .form-label { font-size: 0.95rem; font-weight: 600; margin-bottom: 8px; }
        .select2-container--bootstrap-5 .select2-selection { border-radius: 10px; padding: 0.4rem; }
        .captcha-box { background: #f8f9fa; border: 2px dashed #1c9288; border-radius: 15px; }
    </style>
</head>
<body>

<nav class="navbar navbar-light bg-white shadow-sm mb-4">
    <div class="container justify-content-center">
        <a class="navbar-brand" href="https://smartbd.bdhelpline.info"><i class="fas fa-bolt me-2"></i>SmartBD</a>
    </div>
</nav>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="smart-card p-4 p-md-5">
                <div class="text-center mb-5">
                    <i class="fas fa-user-md fa-3x text-success mb-3"></i>
                    <h2 class="fw-bold">ডাক্তার রেজিস্ট্রেশন ফরম</h2>
                    <p class="text-muted">আপনার তথ্য প্রদান করে আমাদের নেটওয়ার্কে যুক্ত হোন</p>
                </div>

                <form method="post" action="{{ route('index.register.doctor.store') }}" enctype="multipart/form-data" id="mainRegForm">
                    @csrf
                    <input type="hidden" name="role" value="doctor">

                    <h5 class="section-title">১. এলাকা ও প্রাথমিক তথ্য</h5>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">জেলা নির্বাচন করুন *</label>
                            <select name="district_id" id="district" class="form-select select2-init" required>
                                <option selected disabled value="">জেলা নির্বাচন করুন</option>
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}">{{ $district->name_bangla }} - {{ $district->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">উপজেলা নির্বাচন করুন *</label>
                            <select name="upazilla_id" id="upazilla" class="form-select select2-init" required disabled>
                                <option selected disabled value="">আগে জেলা নির্বাচন করুন</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">ডাক্তারের নাম *</label>
                            <input type="text" name="name" class="form-control rounded-3" value="{{ old('name') }}" placeholder="নাম লিখুন" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">BM&DC রেজিস্ট্রেশন নম্বর *</label>
                            <input type="text" name="bmdc_number" class="form-control rounded-3" value="{{ old('bmdc_number') }}" placeholder="যেমন: A-54321" required>
                        </div>
                    </div>

                    <h5 class="section-title">২. একাউন্ট ও নিরাপত্তা (Login)</h5>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">মোবাইল নম্বর (এটি আপনার ইউজারনেম হবে) *</label>
                            <input type="number" name="mobile" class="form-control rounded-3" placeholder="01XXXXXXXXX" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">পাসওয়ার্ড সেট করুন *</label>
                            <input type="password" name="password" class="form-control rounded-3" placeholder="কমপক্ষে ৬ ডিজিট" required>
                        </div>
                    </div>

                    <h5 class="section-title">৩. পেশাগত বিবরণ ও দক্ষতা</h5>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">বিশেষজ্ঞ (Specialization) *</label>
                            <input type="text" name="specialization" class="form-control rounded-3" placeholder="যেমন: হৃদরোগ বিশেষজ্ঞ" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">ডিগ্রি সমূহ (একাধিক লাইন ব্যবহার করা যাবে) *</label>
                            <textarea name="degree" class="form-control rounded-3" rows="3" placeholder="যেমন: MBBS, FCPS, MD (একাধিক ডিগ্রি লিখুন)" required></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label">বিভাগ (প্রয়োজনে একাধিক সিলেক্ট করুন) *</label>
                            <select name="medicaldepartments[]" class="form-select multiple-select" multiple="multiple" required>
                                @foreach($medicaldepartments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">লক্ষণ (প্রয়োজনে একাধিক সিলেক্ট করুন) *</label>
                            <select name="medicalsymptoms[]" class="form-select multiple-select" multiple="multiple" required>
                                @foreach($medicalsymptoms as $symptom)
                                    <option value="{{ $symptom->id }}">{{ $symptom->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">সম্পৃক্ত হাসপাতাল (ঐচ্ছিক)</label>
                            <select name="hospitals[]" class="form-select multiple-select" multiple="multiple">
                                @foreach($hospitals as $hospital)
                                    <option value="{{ $hospital->id }}">{{ $hospital->name }} - ({{ $hospital->district->name_bangla }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- <h5 class="section-title">৪. চেম্বার ও সময়সূচী</h5>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">সিরিয়াল নেওয়ার ফোন নং *</label>
                            <input type="number" name="serial" class="form-control rounded-3" placeholder="সিরিয়াল ফোন নম্বর" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">চেম্বারের ঠিকানা *</label>
                            <input type="text" name="address" class="form-control rounded-3" placeholder="যেমন: পপুলার ডায়াগনস্টিক সেন্টার" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">রোগী দেখার সময়সূচী (বিস্তারিত লিখুন) *</label>
                            <textarea name="weekdays" class="form-control rounded-3" rows="3" placeholder="উদাহরণ: শনি-বুধ (বিকাল ৫টা - রাত ৯টা)" required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">রোগী দেখার দিনগুলো সিলেক্ট করুন (ঐচ্ছিক)</label>
                            <input type="text" name="selected_offdays" class="form-control rounded-3" placeholder="শনি, রবি, মঙ্গল...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">অনলাইনে সিরিয়াল দেওয়া যাবে? *</label>
                            <select name="onlineserial" class="form-select rounded-3" required>
                                <option value="" disabled selected>নির্বাচন করুন</option>
                                <option value="1">অনলাইনে সিরিয়াল দেওয়া যাবে ✅</option>
                                <option value="0">অনলাইনে সিরিয়াল দেওয়া যাবে না ❌</option>
                            </select>
                        </div>
                    </div> -->

                    <div class="captcha-box p-4 mt-5">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <h6 class="fw-bold mb-1"><i class="fas fa-shield-alt me-2 text-success"></i>আপনি রোবট নন তো?</h6>
                                <p class="small text-muted mb-0">স্প্যাম প্রতিরোধে নিচের ছোট অংকটি সমাধান করুন।</p>
                            </div>
                            <div class="col-md-5">
                                <div class="input-group">
                                    <span class="input-group-text fw-bold" id="math-label"></span>
                                    <input type="number" id="math-answer" class="form-control" placeholder="ফলাফল লিখুন" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-5">
                        <button type="submit" class="btn btn-submit text-white shadow-lg">রেজিস্ট্রেশন দাখিল করুন</button><br/><br/>
                        <a href="{{ route('login') }}" class="">লগইন করুন</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Initialize Select2 with Bootstrap 5 Theme
    $('.multiple-select, .select2-init').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: $(this).data('placeholder'),
    });

    // 1. Dynamic Upazilla Loading via API
    $('#district').on('change', function() {
        var districtID = $(this).val();
        if(districtID) {
            $('#upazilla').prop('disabled', true).html('<option>অপেক্ষা করুন...</option>');
            $.ajax({
                url: '/api/getupazillas/{{ env('SOFT_TOKEN') }}/' + districtID, 
                type: "GET",
                dataType: "json",
                success:function(data) {
                    $('#upazilla').empty().append('<option selected disabled value="">উপজেলা নির্বাচন করুন</option>');
                    $.each(data, function(key, value) {
                        $('#upazilla').append('<option value="'+ value.id +'">'+ value.name_bangla +'-'+ value.name_bangla +'</option>');
                    });
                    $('#upazilla').prop('disabled', false);
                }
            });
        }
    });

    // 2. Math Captcha Setup
    var n1 = Math.floor(Math.random() * 9) + 1;
    var n2 = Math.floor(Math.random() * 9) + 1;
    var result = n1 + n2;
    $('#math-label').text(n1 + ' + ' + n2 + ' =');

    $('#mainRegForm').on('submit', function(e) {
        if(parseInt($('#math-answer').val()) !== result) {
            e.preventDefault();
            // alert('দুঃখিত! যোগফল সঠিক হয়নি। আবার চেষ্টা করুন।');
            Swal.fire({
                icon: 'error',
                title: 'ভুল উত্তর!',
                text: 'দয়া করে সঠিক যোগফলটি প্রদান করুন।',
                confirmButtonColor: '#1c9288',
            });
            // Reset for another try
            n1 = Math.floor(Math.random() * 9) + 1;
            n2 = Math.floor(Math.random() * 9) + 1;
            result = n1 + n2;
            $('#math-label').text(n1 + ' + ' + n2 + ' =');
            $('#math-answer').val('');
        }
    });
});
</script>

<script>
    $(document).ready(function() {
        const Toast = Swal.mixin({
            toast: false,
            position: 'center', // <--- This moves it to the middle center
            showConfirmButton: true,
            timer: 3000,
            timerProgressBar: false,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Show Success Message
        @if(session('success'))
            Toast.fire({ icon: 'success', title: "{{ session('success') }}" });
        @endif

        // Show Generic Error Message
        @if(session('error'))
            Toast.fire({ icon: 'error', title: "{{ session('error') }}" });
        @endif
        
        // Show Generic Error Message
        @if(session('warning'))
            Toast.fire({ icon: 'warning', title: "{{ session('warning') }}" });
        @endif

        // Show Backend Validation Errors
        @if($errors->any())
            @foreach($errors->all() as $error)
                Toast.fire({ icon: 'error', title: "{{ $error }}" });
            @endforeach
        @endif
    });
</script>

</body>
</html>