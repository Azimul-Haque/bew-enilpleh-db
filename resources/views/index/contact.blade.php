@extends('layouts.index')
@section('title') Contact | Infoline - BD Smart Seba | প্রতিটি জেলার সকল প্রকার তথ্য এবার এক প্ল্যাটফর্ম @endsection

@section('third_party_stylesheets')

@endsection

@section('content')
<!-- ========================= contact-section start ========================= -->
<section id="contact" class="contact-section" style="padding-top: 150px; padding-bottom: 50px; background-color: var(--light-3);">
  <div class="container">
    <div class="row">
      <div class="col-xl-4">
        <div class="contact-item-wrapper">
          <div class="row">
            <div class="col-12 col-md-6 col-xl-12">
              <div class="contact-item">
                <div class="contact-icon">
                  <i class="lni lni-phone"></i>
                </div>
                <div class="contact-content">
                  <h4>Contact</h4>
                  <p>+8801722924995</p>
                  <p>unisoft360@gmail.com</p>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-6 col-xl-12">
              <div class="contact-item">
                <div class="contact-icon">
                  <i class="lni lni-map-marker"></i>
                </div>
                <div class="contact-content">
                  <h4>Address</h4>
                  <p>Unisoft 360</p>
                  <p>Registripara, Tangail 1900</p>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-6 col-xl-12">
              <div class="contact-item">
                <div class="contact-icon">
                  <i class="lni lni-alarm-clock"></i>
                </div>
                <div class="contact-content">
                  <h4>Schedule</h4>
                  <p>24 Hours / 7 Days Open</p>
                  <p>Office time: 10 AM - 5:30 PM</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-8">
        <div class="contact-form-wrapper">
          <div class="row">
            <div class="col-xl-10 col-lg-8 mx-auto">
              <div class="section-title text-center">
                <span> Get in Touch </span>
                <h2>
                  Ready to Get Started
                </h2>
                <p>
                  Send us messages.
                </p>
              </div>
            </div>
          </div>
          <form action="#" class="contact-form">
            <div class="row">
              <div class="col-md-6">
                <input type="text" name="name" id="name" placeholder="Name" required />
              </div>
              <div class="col-md-6">
                <input type="email" name="email" id="email" placeholder="Email" required />
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <input type="text" name="phone" id="phone" placeholder="Phone" required />
              </div>
              <div class="col-md-6">
                <input type="text" name="subject" id="email" placeholder="Subject" required />
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <textarea name="message" id="message" placeholder="Type Message" rows="5"></textarea>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="button text-center rounded-buttons">
                  <button type="submit" class="btn primary-btn rounded-full">
                    Send Message
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- ========================= contact-section end ========================= -->

<!-- ========================= map-section end ========================= -->
<section class="map-section map-style-9">
  <div class="map-container">
    <object style="border:0; height: 500px; width: 100%;"
      data="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14606.067929552457!2d90.35101528681905!3d23.76459799164188!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c09f9ba3d447%3A0x1babce9f1c6c95a3!2sMohammadpur%2C%20Dhaka!5e0!3m2!1sen!2sbd!4v1655394465359!5m2!1sen!2sbd"></object>
  </div>
  </div>
</section>
<!-- ========================= map-section end ========================= -->
@endsection

@section('third_party_scripts')

@endsection