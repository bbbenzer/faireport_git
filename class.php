<?php

class DatetimeTH
{
  public function getTHday($day)
  {
    $TH = '';
    switch ($day) {
      case 'Monday':
        $TH = 'จันทร์';
        break;
      case 'Tuesday':
        $TH = 'อังคาร';
        break;
      case 'Wednesday':
        $TH = 'พุธ';
        break;
      case 'Thursday':
        $TH = 'พฤหัสบดี';
        break;
      case 'Friday':
        $TH = 'ศุกร์';
        break;
      case 'Saturday':
        $TH = 'เสาร์';
        break;
      case 'Sunday':
        $TH = 'อาทิตย์';
        break;
    }

    return $TH;
  }

  public function getTHmonth($month)
  {
    $TH = '';
    switch ($month) {
      case 'January':
        $TH = 'มกราคม';
        break;
      case 'February':
        $TH = 'กุมภาพันธ์';
        break;
      case 'March':
        $TH = 'มีนาคม';
        break;
      case 'April':
        $TH = 'เมษายน';
        break;
      case 'May':
        $TH = 'พฤษภาคม';
        break;
      case 'June':
        $TH = 'มิถุนายน';
        break;
      case 'July':
        $TH = 'กรกฎาคม';
        break;
      case 'August':
        $TH = 'สิงหาคม';
        break;
      case 'September':
        $TH = 'กันยายน';
        break;
      case 'October':
        $TH = 'ตุลาคม';
        break;
      case 'November':
        $TH = 'พฤศจิกายน';
        break;
      case 'December':
        $TH = 'ธันวาคม';
        break;
    }
    return $TH ;
  }

  public function getTHyear($year)
  {
    return($year+543);
  }
}


 ?>
