<form method="post" action="{{ route('dashboard.doctors.update', $doctor->id) }}" enctype="multipart/form-data">
                                  <div class="modal-body">
                                    
                                        @csrf


                                        <div class="row">
                                          <div class="col-md-6">
                                            <div class="input-group mb-3">
                                                <input type="text"
                                                       name="name"
                                                       class="form-control"
                                                       value="{{ $doctor->name }}"
                                                       placeholder="ডাক্তারের নাম" required>
                                                <div class="input-group-append">
                                                    <div class="input-group-text"><span class="fas fa-user-md"></span></div>
                                                </div>
                                            </div>
                                            <div class="input-group mb-3">
                                                <input type="text"
                                                       name="specialization"
                                                       value="{{ $doctor->specialization }}"
                                                       
                                                       class="form-control"
                                                       placeholder="ডাক্তার কী বিশেষজ্ঞ (যেমন: হৃদরোগ বিশেষজ্ঞ)">
                                                <div class="input-group-append">
                                                    <div class="input-group-text"><span class="fas fa-certificate"></span></div>
                                                </div>
                                            </div>
                                          </div>
                                          <div class="col-md-6">
                                            <div class="input-group mb-3">
                                              <textarea name="degree" class="form-control" style="min-height: 90px;" placeholder="ডাক্তারের ডিগ্রি/ ডিগ্রিসমূহ (যেমন: MBBS, FCPS, MD, বিসিএস (স্বাস্থ্য) ইত্যাদি) [একাধিক লাইন এড করা যাবে]">{{ str_replace('<br />', "", $doctor->degree) }}</textarea>
                                            </div>
                                          </div>
                                        </div>
                                    
                                        
                                        <div style="margin-bottom: 15px;">
                                          <select name="medicaldepartments[]" class="form-control multiple-select" multiple="multiple" data-placeholder="বিভাগ (প্রয়োজনে একাধিক সিলেক্ট করা যাবে)" required>
                                              
                                              @foreach($medicaldepartments as $medicaldepartment)
                                                <option value="{{ $medicaldepartment->id }}" @if(in_array($medicaldepartment->id, $doctor->doctormedicaldepartments->pluck('medicaldepartment_id')->toArray())) selected @endif>{{ $medicaldepartment->name }}</option>
                                              @endforeach
                                          </select>
                                        </div> 
                                        
                                        <div style="margin-bottom: 15px;">
                                          <select name="medicalsymptoms[]" class="form-control multiple-select" multiple="multiple" data-placeholder="লক্ষণ (প্রয়োজনে একাধিক সিলেক্ট করা যাবে)" required>
                                              
                                              @foreach($medicalsymptoms as $medicalsymptom)
                                                <option value="{{ $medicalsymptom->id }}" @if(in_array($medicalsymptom->id, $doctor->doctormedicalsymptoms->pluck('medicalsymptom_id')->toArray())) selected @endif>{{ $medicalsymptom->name }}</option>
                                              @endforeach
                                          </select>
                                        </div>
                                        
                                        <div style="margin-bottom: 15px;">
                                          <select name="hospitals[]" class="form-control multiple-select" multiple="multiple" data-placeholder="ডাক্তার যে হাসপাতালের সাথে সম্পৃক্ত (প্রয়োজনে একাধিক সিলেক্ট করা যাবে) [Optional]">
                                              @foreach($hospitals as $hospital)
                                                <option value="{{ $hospital->id }}" @if(in_array($hospital->id, $doctor->doctorhospitals->pluck('hospital_id')->toArray())) selected @endif>{{ $hospital->name }} - ({{ $hospital->upazilla->name_bangla }}, {{ $hospital->district->name_bangla }})</option>
                                              @endforeach
                                          </select>
                                        </div>
                                    
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                                    <button type="submit" class="btn btn-primary">দাখিল করুন</button>
                                  </div>
                                </form>