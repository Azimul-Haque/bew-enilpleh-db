				  {{-- Edit User Modal Code --}}
                  {{-- Edit User Modal Code --}}
                  <!-- Modal -->
                  <div class="modal fade" id="editUserModal{{ $bus->id }}" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true" data-backdrop="static">
                    <div class="modal-dialog modal-lg" role="document">
                      
                        <div class="modal-content">
                          <div class="modal-header bg-primary">
                            <h5 class="modal-title" id="editUserModalLabel">বাস তথ্য হালনাগাদ</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <form method="post" action="{{ route('dashboard.buses.update', [$district->id, $bus->id]) }}">
                            <div class="modal-body">
                                @csrf
                                <h5>হতে জেলা: {{ $district->name_bangla }}</h5>
                                <div class="" style="margin-bottom: 15px;">
                                  <select name="to_district" class="form-control " required>
                                      <option selected="" disabled="" value="">গন্তব্য জেলা নির্বাচন করুন</option>
                                      @foreach($districts as $todistrict)
                                        <option value="{{ $todistrict->id }}" @if($todistrict->id == $bus->toDistrict->id) selected @endif >{{ $todistrict->name_bangla }} - {{ $todistrict->name }}</option>
                                      @endforeach
                                  </select>
                                  {{-- <div class="input-group-append">
                                      <div class="input-group-text"><span class="fas fa-map"></span></div>
                                  </div> --}}
                                </div>

                                <div class="input-group mb-3">
                                    <input type="text"
                                           name="bus_name"
                                           class="form-control"
                                           value="{{ $bus->bus_name }}"
                                           placeholder="বাসের নাম" required>
                                    <div class="input-group-append">
                                        <div class="input-group-text"><span class="fas fa-bus"></span></div>
                                    </div>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="text"
                                           name="route_info"
                                           class="form-control"
                                           value="{{ $bus->route_info }}"
                                           placeholder="রুটের তথ্য (যেমন: টাঙ্গাইল হতে দর্শনা ভায়া সিরাজগঞ্জ, উল্লাপাড়া, শাহজাদপুর, কাশিনাথপুর, পাবনা, কুষ্টিয়া, ঝিনাইদহ, চুয়াডাঙ্গা)" required>
                                    <div class="input-group-append">
                                        <div class="input-group-text"><span class="fas fa-bus"></span></div>
                                    </div>
                                </div>
                                <div class="row">
                                  <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <input type="text"
                                               name="bus_type"
                                               class="form-control"
                                               value="{{ $bus->bus_type }}"
                                               placeholder="AC/ Non-AC/ Volvo/ স্ক্যানিয়া/ ডাবল ডেকার ইত্যাদি" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-bus"></span></div>
                                        </div>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <input type="text"
                                               name="fare"
                                               class="form-control"
                                               value="{{ $bus->fare }}"
                                               placeholder="বাস ভাড়া" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-bus"></span></div>
                                        </div>
                                    </div>
                                  </div>
                                </div>


                                <div class="row">
                                  <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <input type="text"
                                               name="starting_time"
                                               class="form-control"
                                               value="{{ $bus->starting_time }}"
                                               placeholder="ছাড়ার সময়/ সময়সমূহ (একাধিক হলে কমা দিয়ে লিখুন)" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-bus"></span></div>
                                        </div>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    {{-- <div class="input-group mb-3">
                                        <input type="text"
                                               name="counter_address"
                                               class="form-control"
                                               value="{{ $bus->counter_address }}"
                                               placeholder="কাউন্টার/ কাউন্টারসমূহ (একাধিক হলে কমা দিয়ে লিখুন)" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-bus"></span></div>
                                        </div>
                                    </div> --}}
                                    <div class="input-group mb-3">
                                        <input type="number"
                                               name="contact"
                                               value="{{ $bus->contact }}"
                                               class="form-control"
                                               placeholder="যোগাযোগ" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-mobile"></span></div>
                                        </div>
                                    </div>
                                  </div>
                                </div>
                                

                                <hr>
                                <h5>কাউন্টারসমূহ</h5>
                                <div id="counterFieldsEdit{{ $bus->id }}">
                                  
                                  @if($bus->buscounterdatas)
                                    @foreach($bus->buscounterdatas as $key => $counterdata)
                                      <div class="row mb-2 counter-group">
                                          <div class="col-md-4">
                                              <select name="counterdata[{{ $key }}][buscounter_id]" class="form-control" required>
                                                  <option value="">কাউন্টার নির্বাচন করুন</option>
                                                  @foreach($buscounters as $buscounter)
                                                      <option value="{{ $buscounter->id }}" {{ $buscounter->id == $counterdata->buscounter_id ? 'selected' : '' }}>{{ $buscounter->name }}</option>
                                                  @endforeach
                                              </select>
                                          </div>
                                          <div class="col-md-4">
                                              <input type="text" name="counterdata[{{ $key }}][address]" class="form-control" placeholder="ঠিকানা" value="{{ $counterdata->address }}" required>
                                          </div>
                                          <div class="col-md-3">
                                              <input type="text" name="counterdata[{{ $key }}][mobile]" class="form-control" placeholder="মোবাইল" value="{{ $counterdata->mobile }}" required>
                                          </div>
                                          <div class="col-md-1 d-flex align-items-center">
                                              <button type="button" class="btn btn-danger btn-sm removeCounter"><i class="fas fa-times"></i></button>
                                          </div>
                                      </div>
                                    @endforeach
                                  @endif
                                </div>

                                <button type="button" class="btn btn-sm btn-primary mt-2" id="addCounterEdit{{ $bus->id }}"><i class="fas fa-plus-circle"></i> কাউন্টার যোগ করুন</button>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                              <button type="submit" class="btn btn-primary">দাখিল করুন</button>
                            </div>
                          </form>
                        </div>
                    </div>
                  </div>

                  <script>
                  $(document).ready(function () {
                      let counterEditIndex = {{ $bus->buscounterdatas->count() ?? 0 }};

                      $('#addCounterEdit{{ $bus->id }}').click(function () {
                          let existingOptions = [];
                          $('#counterFieldsEdit{{ $bus->id }} select[name*="[buscounter_id]"]').each(function () {
                              existingOptions.push($(this).val());
                          });

                          let options = `<option value="">কাউন্টার নির্বাচন করুন</option>`;
                          @foreach($buscounters as $counter)
                              if (!existingOptions.includes("{{ $counter->id }}")) {
                                  options += `<option value="{{ $counter->id }}">{{ $counter->name }}</option>`;
                              }
                          @endforeach

                          let field = `
                              <div class="row mb-2 counter-group">
                                  <div class="col-md-4">
                                      <select name="counterdata[${counterEditIndex}][buscounter_id]" class="form-control" required>
                                          ${options}
                                      </select>
                                  </div>
                                  <div class="col-md-4">
                                      <input type="text" name="counterdata[${counterEditIndex}][address]" class="form-control" placeholder="ঠিকানা" required>
                                  </div>
                                  <div class="col-md-3">
                                      <input type="text" name="counterdata[${counterEditIndex}][mobile]" class="form-control" placeholder="মোবাইল" required>
                                  </div>
                                  <div class="col-md-1 d-flex align-items-center">
                                      <button type="button" class="btn btn-danger btn-sm removeCounter"><i class="fas fa-times"></i></button>
                                  </div>
                              </div>`;

                          $('#counterFieldsEdit{{ $bus->id }}').append(field);
                          counterEditIndex++;
                      });

                      $(document).on('click', '.removeCounter', function () {
                          $(this).closest('.counter-group').remove();
                      });
                  });
                  </script>
                  
                  {{-- Edit User Modal Code --}}
                  {{-- Edit User Modal Code --}}