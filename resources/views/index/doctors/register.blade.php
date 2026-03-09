<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ডাক্তার রেজিস্ট্রেশন | SmartBD Helpline</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Hind Siliguri', sans-serif; background-color: #f4f7f6; }
        .card { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .btn-success { background-color: #1c9288; border: none; padding: 10px 25px; font-weight: 600; }
        .btn-success:hover { background-color: #157a71; }
        .form-label { font-weight: 600; color: #444; }
        .input-group-text { background-color: #e9ecef; border-right: none; }
        .form-control:focus { border-color: #1c9288; box-shadow: 0 0 0 0.2rem rgba(28, 146, 136, 0.25); }
        .math-captcha { background: #eee; padding: 10px; border-radius: 5px; font-weight: bold; text-align: center; }
    </style>
</head>
<body>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card p-4 p-md-5">
                <div class="text-center mb-4">
                    <i class="fas fa-user-md fa-3x text-success mb-3"></i>
                    <h2 class="fw-bold">ডাক্তার রেজিস্ট্রেশন ফরম</h2>
                    <p class="text-muted">আপনার তথ্য প্রদান করে আমাদের নেটওয়ার্কে যুক্ত হোন</p>
                </div>

                <form method="post" action="{{ route('index.register.doctor.store') }}" enctype="multipart/form-data" id="registrationForm">
                    @csrf
                    <input type="hidden" name="role" value="doctor">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">জেলা নির্বাচন করুন *</label>
                            <select name="district_id" id="district" class="form-select" required>
                                <option selected disabled value="">জেলা নির্বাচন করুন</option>
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}">{{ $district->name_bangla }} ({{ $district->name }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">উপজেলা নির্বাচন করুন *</label>
                            <select name="upazilla_id" id="upazilla" class="form-select" required disabled>
                                <option selected disabled value="">আগে জেলা সিলেক্ট করুন</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">ডাক্তারের নাম *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user-md"></i></span>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="যেমন: ডাঃ মোঃ রহিম" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">BM&DC রেজিস্ট্রেশন নম্বর *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                <input type="text" name="bmdc_number" class="form-control" value="{{ old('bmdc_number') }}" placeholder="যেমন: A-12345" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">মোবাইল নম্বর (লগইন এর জন্য) *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input type="number" name="mobile" class="form-control" value="{{ old('mobile') }}" placeholder="017XXXXXXXX" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">পাসওয়ার্ড *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="কমপক্ষে ৬ ডিজিট" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">বিশেষজ্ঞ (Specialization) *</label>
                            <input type="text" name="specialization" class="form-control" value="{{ old('specialization') }}" placeholder="যেমন: হৃদরোগ বিশেষজ্ঞ" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">ডিগ্রি সমূহ *</label>
                            <textarea name="degree" class="form-control" rows="2" placeholder="MBBS, FCPS, MD (একাধিক ডিগ্রি কমা দিয়ে লিখুন)" required>{{ old('degree') }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">সিরিয়াল নেওয়ার ফোন নং *</label>
                            <input type="number" name="serial" class="form-control" value="{{ old('serial') }}" placeholder="রোগী সিরিয়ালের নম্বর" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">চেম্বারের ঠিকানা *</label>
                            <input type="text" name="address" class="form-control" value="{{ old('address') }}" placeholder="যেমন: পপুলার ডায়াগনস্টিক, ভবন-১" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">সাপ্তাহিক সময়সূচি *</label>
                            <textarea name="weekdays" class="form-control" rows="2" placeholder="যেমন: শনি-বুধ (বিকাল ৫টা - রাত ৯টা)" required>{{ old('weekdays') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">অনলাইনে সিরিয়াল দেওয়া যাবে কি? *</label>
                            <select name="onlineserial" class="form-select" required>
                                <option value="1">হ্যাঁ, অনলাইনে সিরিয়াল দেওয়া যাবে</option>
                                <option value="0" selected>না, অফলাইনে</option>
                            </select>
                        </div>

                        <div class="col-md-6 offset-md-3 mt-4">
                            <div class="captcha-box p-3 border rounded bg-light">
                                <label class="form-label d-block text-center">স্প্যাম প্রতিরোধে উত্তর দিন: *</label>
                                <div class="d-flex align-items-center justify-content-center">
                                    <span id="math-question" class="fw-bold me-2"></span>
                                    <input type="number" id="captcha-answer" class="form-control w-50" placeholder="ফলাফল লিখুন" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-5">
                        <button type="submit" class="btn btn-success px-5 py-3 rounded-pill shadow">রেজিস্ট্রেশন সম্পন্ন করুন</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        // 1. Dynamic Upazilla Loading
        $('#district').on('change', function() {
            var districtID = $(this).val();
            if(districtID) {
                $.ajax({
                    url: '/api/getupazillas/{{ env('SOFT_TOKEN') }}/' + districtID, // Adjust this path to your real API
                    type: "GET",
                    dataType: "json",
                    success:function(data) {
                        $('#upazilla').empty().append('<option selected disabled value="">উপজেলা নির্বাচন করুন</option>');
                        $.each(data, function(key, value) {
                            $('#upazilla').append('<option value="'+ value.id +'">'+ value.name_bangla +'-' + value.name + '</option>');
                        });
                        $('#upazilla').prop('disabled', false);
                    }
                });
            }
        });

        // 2. Math Captcha Logic
        var num1 = Math.floor(Math.random() * 10) + 1;
        var num2 = Math.floor(Math.random() * 10) + 1;
        var correctAnswer = num1 + num2;
        $('#math-question').text(num1 + ' + ' + num2 + ' = ');

        $('#registrationForm').on('submit', function(e) {
            var userAnswer = $('#captcha-answer').val();
            if (parseInt(userAnswer) !== correctAnswer) {
                e.preventDefault();
                alert('ভুল উত্তর! দয়া করে সঠিক যোগফলটি প্রদান করুন।');
                // Refresh captcha
                num1 = Math.floor(Math.random() * 10) + 1;
                num2 = Math.floor(Math.random() * 10) + 1;
                correctAnswer = num1 + num2;
                $('#math-question').text(num1 + ' + ' + num2 + ' = ');
                $('#captcha-answer').val('');
            }
        });
    });
</script>

</body>
</html>