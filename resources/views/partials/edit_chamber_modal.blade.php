<div class="modal fade" id="editChamber-{{ $chamber->id }}" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-edit mr-2"></i> চেম্বারের তথ্য আপডেট করুন</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            
            <form action="{{ route('doctor.chambers.update', $chamber->id) }}" method="POST">
                @csrf @method('PUT')
                
                <div class="modal-body p-4">
                    <div class="alert alert-secondary border-0 mb-4">
                        <small class="d-block text-uppercase font-weight-bold opacity-50">হাসপাতাল:</small>
                        <h5 class="mb-0 font-weight-bold">{{ $chamber->hospital->name }}</h5>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="font-weight-bold small text-muted">চেম্বার এড্রেস/রুম নম্বর</label>
                            <input type="text" name="address_or_room" class="form-control" value="{{ $chamber->address_or_room }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold small text-muted">সিরিয়াল ফোন নম্বর *</label>
                            <input type="text" name="serial_phone" class="form-control" value="{{ $chamber->serial_phone }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold small text-muted">অনলাইন সিরিয়াল</label>
                            <select name="onlineserial" class="form-control">
                                <option value="1" {{ $chamber->onlineserial == 1 ? 'selected' : '' }}>সক্রিয় ✅</option>
                                <option value="0" {{ $chamber->onlineserial == 0 ? 'selected' : '' }}>বন্ধ ❌</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="font-weight-bold small text-muted">সাপ্তাহিক সময়সূচী *</label>
                            <textarea name="weekdays" class="form-control" rows="2" required>{{ $chamber->weekdays }}</textarea>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="font-weight-bold small text-muted">যেদিন যেদিন চেম্বারে বসবেন (অতিরিক্ত তারিখসমূহ)</label>
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

                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">আপডেট করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>