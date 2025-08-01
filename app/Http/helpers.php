<?php 


	function limit_text($text, $limit) {
      // $pos=strpos($text, ' ', $limit);
      // $text = substr($text,0,$pos ); 

      if (str_word_count($text, 0) > $limit) {
          $words = str_word_count($text, 2);
          $pos = array_keys($words);
          $text = substr($text, 0, $pos[$limit]) . '...';
      }
      return $text;
  }

  function bangla($str){
        $en = array(1,2,3,4,5,6,7,8,9,0);
        $bn = array('১','২','৩','৪','৫','৬','৭','৮','৯','০');
        $str = str_replace($en, $bn, $str);
        $en = array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' );
        $en_short = array( 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' );
        $bn = array( 'জানুয়ারী', 'ফেব্রুয়ারী', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'অগাস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর' );
        $str = str_replace( $en, $bn, $str );
        $str = str_replace( $en_short, $bn, $str );
        $en = array('Saturday','Sunday','Monday','Tuesday','Wednesday','Thursday','Friday');
        $en_short = array('Sat','Sun','Mon','Tue','Wed','Thu','Fri');
        $bn_short = array('শনি', 'রবি','সোম','মঙ্গল','বুধ','বৃহঃ','শুক্র');
        $bn = array('শনিবার','রবিবার','সোমবার','মঙ্গলবার','বুধবার','বৃহস্পতিবার','শুক্রবার');
        $str = str_replace( $en, $bn, $str );
        $str = str_replace( $en_short, $bn_short, $str );
        $en = array( 'am', 'pm' );
        $bn = array( 'পূর্বাহ্ন', 'অপরাহ্ন' );
        $str = str_replace( $en, $bn, $str );
        return $str;
  }
  
  function random_string($length){
        $pool = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $random_string = substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
        return $random_string;
  }

  function ordinal($number) {
      $ends = array('th','st','nd','rd','th','th','th','th','th','th');
      if ((($number % 100) >= 11) && (($number%100) <= 13))
          return $number. 'th';
      else
          return $number. $ends[$number % 10];
  }

  function hospital_type($hospital_type) {
      if ($hospital_type == 1) {
        $text = "সরকারি হাসপাতাল";
      } elseif($hospital_type == 2) {
        $text = "প্রাইভেট ক্লিনিক ও হাসপাতাল";
      } elseif($hospital_type == 3) {
        $text = "ফিজিওথেরাপি সেন্টার";
      } elseif($hospital_type == 4) {
        $text = "কিডনি ডায়ালাইসিস";
      } else {
        $text = $hospital_type;
      }
      return $text;
  }

  function hospital_badge($hospital_type) {
      if ($hospital_type == 1) {
        $text = "success";
      } elseif($hospital_type == 2) {
        $text = "primary";
      } elseif($hospital_type == 3) {
        $text = "warning";
      } elseif($hospital_type == 4) {
        $text = "info";
      } else {
        $text = $hospital_type;
      }
      return $text;
  }

  function blooddonor_category($category) {
      if ($category == 1) {
        $text = "ব্লাড ব্যাংক";
      } elseif($category == 2) {
        $text = "রক্তদাতা সংগঠন";
      }
      return $text;
  }

  function station_type($station_type) {
      if ($station_type == 1) {
        $text = "পুলিশ সুপারের (SP) কার্যালয়";
      } elseif($station_type == 2) {
        $text = "পুলিশ সার্কেল (ASP) কার্যালয়";
      } elseif($station_type == 3) {
        $text = "ভারপ্রাপ্ত কর্মকর্তার (OC) কার্যালয়";
      }
      return $text;
  }

  function court_type($court_type) {
      if ($court_type == 1) {
        $text = "ফৌজদারি";
      } elseif($court_type == 2) {
        $text = "দেওয়ানি";
      } elseif($court_type == 3) {
        $text = "ফৌজদারি ও দেওয়ানি";
      }
      return $text;
  }

  function edu_inst_type($type) {
      $text = '';
      if ($type == 1) {
        $text = "সরকারি শিক্ষা প্রতিষ্ঠান";
      } elseif($type == 2) {
        $text = "বেসরকারি শিক্ষা প্রতিষ্ঠান";
      } elseif($type == 3) {
        $text = "কোচিং সেন্টার";
      }
      return $text;
  }

  function edu_inst_badge($type) {
      $text = '';
      if ($type == 1) {
        $text = "success";
      } elseif($type == 2) {
        $text = "primary";
      } elseif($type == 3) {
        $text = "warning";
      }
      return $text;
  }

  function role_bangla($role) {
      $text = '';
      if ($role == 'admin') {
        $text = "এডমিন";
      } elseif($role == 'editor') {
        $text = "জেলা এডমিন";
      } elseif($role == 'manager') {
        $text = "প্রতিষ্ঠান ম্যানেজার";
      }
      return $text;
  }

  function local_currency($num) {
    $explrestunits = "" ;
    if(strlen($num)>3) {
        $lastthree = substr($num, strlen($num)-3, strlen($num));
        $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
        $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
        $expunit = str_split($restunits, 2);
        for($i=0; $i<sizeof($expunit); $i++) {
            // creates each of the 2's group and adds a comma to the end
            if($i==0) {
                $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
            } else {
                $explrestunits .= $expunit[$i].",";
            }
        }
        $thecash = $explrestunits.$lastthree;
    } else {
        $thecash = $num;
    }
    return $thecash; // writes the final format where $currency is the currency symbol.
}