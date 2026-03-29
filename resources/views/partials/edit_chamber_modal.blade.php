<div class="modal fade" id="editChamber-{{ $chamber->id }}" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">


    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-edit mr-2"></i> চেম্বারের তথ্য আপডেট করুন
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form action="{{ route('dashboard.doctors.chambers.update', $chamber->id) }}" method="POST">
                @csrf
                
                <div class="modal-body p-4">
                    <div class="alert alert-secondary border-0 mb-4">
                        <small class="d-block text-uppercase font-weight-bold opacity-10">হাসপাতাল:</small>
                        <h5 class="mb-0 font-weight-bold text-white">{{ $chamber->hospital->name }}</h5>
                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold small text-muted">অনলাইন সিরিয়াল স্ট্যাটাস</label>
                            <select name="onlineserial" class="form-control custom-select" required>
                                <option value="1" {{ $chamber->onlineserial == 1 ? 'selected' : '' }}>সক্রিয় ✅ (অনলাইনে সিরিয়াল দেওয়া যাবে)</option>
                                <option value="0" {{ $chamber->onlineserial == 0 ? 'selected' : '' }}>বন্ধ ❌ (শুধুমাত্র অফলাইন)</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold small text-muted">চেম্বার এড্রেস/রুম নম্বর *</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-right-0"><i class="fas fa-door-open text-primary"></i></span>
                                </div>
                                <input type="text" name="address_or_room" class="form-control border-left-0" 
                                       value="{{ $chamber->address_or_room }}" placeholder="যেমন: ৩১০ নম্বর রুম" required>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold small text-muted">সিরিয়াল ফোন নম্বর *</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-right-0"><i class="fas fa-phone-alt text-primary"></i></span>
                                </div>
                                <input type="text" name="serial_phone" class="form-control border-left-0" 
                                       value="{{ $chamber->serial_phone }}" placeholder="০১৭XXXXXXXX" required>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="font-weight-bold small text-muted">সাপ্তাহিক সময়সূচী (বিস্তারিত) *</label>
                            <textarea name="weekdays" class="form-control" rows="3" 
                                      placeholder="উদা: শনি-বুধ (বিকাল ৫টা - রাত ৯টা)" required>{{ $chamber->weekdays }}</textarea>
                            <small class="form-text text-muted">আপনি সপ্তাহে যে দিন এবং সময়ে এই চেম্বারে বসেন তা পরিষ্কারভাবে লিখুন।</small>
                        </div>


                        <div class="col-md-12 mb-3">
                            <label class="font-weight-bold small">যেদিন যেদিন চেম্বারে বসবেন (অতিরিক্ত তারিখসমূহ)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                                </div>
                                <input type="text" name="ondays" class="form-control ondays-datepicker" 
                                       placeholder="তারিখগুলো নির্বাচন করুন" 
                                       value="{{ $chamber->ondays ? implode(',', json_decode($chamber->ondays)) : '' }}" readonly>
                            </div>
                            <small class="text-muted">একাধিক তারিখ সিলেক্ট করা যাবে।</small>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 p-4 bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                        <i class="fas fa-sync-alt mr-1"></i> তথ্য আপডেট করুন
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>