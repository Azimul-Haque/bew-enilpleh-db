<html>
  <head>
    <title>PDF | Doctor Serials</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
    body {
      font-family: 'kalpurush', sans-serif;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }
    th, td{
      padding: 7px;
      font-family: 'kalpurush', sans-serif;
      font-size: 15px;
    }
    .bordertable td, th {
        border: 1px solid #A8A8A8;
    }
    .calibri_normal {
      font-family: Calibri;
      font-weight: normal;
    }
    @page {
      header: page-header;
      footer: page-footer;
      /*background: url({{ public_path('images/background_demo.png') }});
      background-size: cover;              
      background-repeat: no-repeat;
      background-position: center center;*/
    }
    </style>
  </head>
  <body>
    <h3 align="center">
      <img src="{{ public_path('images/logo.png') }}" style="height: 40px; width: auto;"><br/>
      <span class="calibri_normal"><b>Infoline</b> - BD Smart Seba</span><br/>
    </h3>
    <h3 style="color: #397736; border-bottom: 1px solid #397736;" align="center">
        <b>{{ $doctor->name }}-এর সিরিয়ালসমূহ</b>
    </h3>
    <span class="calibri_normal"><b>Date: {{ date('F d, Y', strtotime($serialdate)) }}</b></span>

    <table class="bordertable">
        <thead>
          <tr>
            <th width="40%">রোগীর নাম</th>
            <th>মোবাইল</th>
            <th>তারিখ</th>
            <th width="30%">সময়</th>
          </tr>
        </thead>
        <tbody>
          @foreach($doctorserials as $doctorserials)
          <tr>
            <td>{{ $doctorserial->name }}</td>
            <td align="center" class="calibri_normal">{{ $doctorserial->name }}</td>
            <td align="right" class="calibri_normal">¥ <span class="calibri_normal">{{ $doctorserial->name }}</span></td>
            <td></td>
          </tr>
          @endforeach
          <tr>
            <td colspan="3"></td>
            <td align="right" class="calibri_normal" style="line-height: 1.5em;">
              SUBTOTAL ¥ {{ $order->cart->totalPrice - $order->cart->deliveryCharge + $order->cart->discount }}<br/>
              Delivery Charge ¥ {{ $order->cart->deliveryCharge }}<br/>
              Discount ¥ {{ $order->cart->discount }}<br/>
              <big><strong>TOTAL ¥ {{ $order->cart->totalPrice }}</strong></big>
            </td>
          </tr>
        </tbody>
      </table>
      <br/>

    

    <htmlpagefooter name="page-footer">
      <small><span class="calibri_normal">Downloaded at:  {{ date('F d, Y, h:i A') }}</span></small><br/>
      <!-- <small class="calibri_normal" style="color: #3f51b5;">Powered by: App Lab Bangladesh</small> -->
    </htmlpagefooter>
  </body>
</html>