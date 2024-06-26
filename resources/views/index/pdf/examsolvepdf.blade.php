<html>
<head>
  <title>Exam | Solve Sheet Download | PDF</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <style>
  body {
    font-family: 'kalpurush', sans-serif;
  }

  /*table {
      border-collapse: collapse;
      width: 100%;
  }
  table, td, th {
      border: 1px solid black;
  }
  th, td{
    padding: 4px;
    font-family: 'kalpurush', sans-serif;
    font-size: 13px;
  }*/
  @page {
    header: page-header;
    footer: page-footer;
    background-image: url({{ public_path('images/logo-background.png') }});
    background-size: cover;              
    background-repeat: no-repeat;
    background-position: center center;
    margin: 90px 60px 90px 60px;
  }
  .page-header,
  .page-header-space {
    height: 250px;
  }
  .graybackground {
    background: rgba(192,192,192, 0.7);
  }
  </style>
</head>
<body>
  <htmlpageheader name="page-header">
    <img src="{{ public_path('images/logo.png') }}" style="height: 50px; width: auto;">
    <div style="border-top: 1px solid green;"></div>
  </htmlpageheader>
  {{-- <h2 align="center">
    Test    
  </h2> --}}
  <p align="center" style="padding-top: 0px;">
    <span style="font-size: 20px;"><strong>{{ $exam['name'] }}</strong></span><br/>
  </p>
  <div style="padding-top: -30px; text-align: center;">
    <p style="position: relative;">পূর্ণমান - {{ bangla($exam['examquestions']->count() * $exam['qsweight']) }}, কাটমার্ক - {{ bangla($exam['cutmark']) }}, সময় - {{ bangla($exam['duration']) }} মিনিট
    </p> 
  </div>
  
  @php
    $counter = 1;
  @endphp
  @foreach($exam['examquestions'] as $question)
    <div style="margin-bottom: 15px;">
      <h4><b>{{ bangla($counter) }}. {!! $question->question->question !!}</b></h4>
      <table cellspacing="0" cellpadding="0">
        <tr>
          <td style="padding-right: 20px;">(ক) {{ $question->question->option1 }}</td>
          <td>(খ) {{ $question->question->option2 }}</td>
          <td></td>
        </tr>
        <tr>
          <td style="padding-right: 40px;">(গ) {{ $question->question->option3 }}</td>
          <td style="padding-right: 40px;">(ঘ) {{ $question->question->option4 }}</td>
          <td>
            @if($question->question->answer == 1)
              <b>উত্তর: {{ $question->question->option1 }}</b>
            @elseif($question->question->answer == 2)
              <b>উত্তর: {{ $question->question->option2 }}</b>
            @elseif($question->question->answer == 3)
              <b>উত্তর: {{ $question->question->option3 }}</b>
            @elseif($question->question->answer == 4)
              <b>উত্তর: {{ $question->question->option4 }}</b>
            @endif
          </td>
        </tr>
      </table>
      <div style="background: #E8FFF3; padding: 10px;">
        @if($question->question->questionexplanation)
          <p><b>ব্যাখ্যা:</b> {{ $question->question->questionexplanation->explanation }}</p>
        @endif
        @if($question->question->questionimage)
        <img class="img-responsive" src="{{ asset('/images/questions/' . $question->question->questionimage->image) }}">
        @endif
      </div>
    </div>

    @php
      $counter++;
    @endphp
  @endforeach
 
  <htmlpagefooter name="page-footer">
    <small>ডাউনলোডের সময়কালঃ <span style="font-family: Calibri;">{{ date('F d, Y, h:i A') }}</span></small><br/>
    <small style="font-family: Calibri; color: #4472C4;">Generated by: https://bcsexamaid.com | Download Android App: BCS Exam Aid – </small>
    <small style="color: #4472C4;">বিসিএস এক্সাম</small>
  </htmlpagefooter>
</body>
</html>