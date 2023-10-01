<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Lib_date
 * This class created for helping take date data from mysql
 *
 * @author Dichi Al Faridi
 * Update : 01 Sep 2010 (By agusnur)
 *
 */
class Lib_date {

    private $lang;

    public function __construct() {
        $this->CI = & get_instance();

        if ($this->CI->session->userdata('lang') === 'en') {
            $this->lang = "en";
        } else {
            $this->lang = "id";
        }
    }

    private function get_date() {
        $this->year = substr($this->mysql_date, 0, 4);
        $this->month = substr($this->mysql_date, 5, 2);
        $this->date = substr($this->mysql_date, 8, 2);
    }

    public function get_day($date = NULL) {

        $hari = array(
            'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
        );
        list($yr, $mn, $dt) = explode('-', $date);
        $now = getdate(mktime(0, 0, 0, $mn, $dt, $yr));
        $i = $now['wday'];
        return $hari[$i];
    }

    public function get_datetime_now() {
        $date_now = date('Y-m-d G:i:s');
        return $date_now;
    }

    public function get_date_now() {
        $date_now = date('Y-m-d G:i:s');
        return $date_now;
    }

    public function set_date($date, $length = NULL) {
        if ($length === NULL)
            $length = 0;
        $day = 86400 * $length;
        $timestamp = strtotime($date);
        $date_value = date('Y-m-d', $timestamp + $day);
        return $date_value;
    }

    public function set_month_roman($month = NULL) {
        switch ($month) {
            case '1' :
                $month_text = 'I';
                break;
            case '2' :
                $month_text = 'II';
                break;
            case '3' :
                $month_text = 'III';
                break;
            case '4' :
                $month_text = 'IV';
                break;
            case '5' :
                $month_text = 'V';
                break;
            case '6' :
                $month_text = 'VI';
                break;
            case '7' :
                $month_text = 'VII';
                break;
            case '8' :
                $month_text = 'VIII';
                break;
            case '9' :
                $month_text = 'IX';
                break;
            case '10' :
                $month_text = 'X';
                break;
            case '11' :
                $month_text = 'XI';
                break;
            case '12' :
            default :
                $month_text = 'XII';
                break;
        }

        return $month_text;
    }

    public function set_month_name($month = NULL, $lang = NULL) {
        if ($lang === 'en') {
            switch ($month) {
                case '1' :
                    $month_text = 'January';
                    break;
                case '2' :
                    $month_text = 'February';
                    break;
                case '3' :
                    $month_text = 'March';
                    break;
                case '4' :
                    $month_text = 'April';
                    break;
                case '5' :
                    $month_text = 'May';
                    break;
                case '6' :
                    $month_text = 'June';
                    break;
                case '7' :
                    $month_text = 'July';
                    break;
                case '8' :
                    $month_text = 'August';
                    break;
                case '9' :
                    $month_text = 'September';
                    break;
                case '10' :
                    $month_text = 'October';
                    break;
                case '11' :
                    $month_text = 'November';
                    break;
                case '12' :
                default :
                    $month_text = 'December';
                    break;
            }
        } else if ($lang === "id") {
            switch ($month) {
                case '1' :
                    $month_text = 'Januari';
                    break;
                case '2' :
                    $month_text = 'Februari';
                    break;
                case '3' :
                    $month_text = 'Maret';
                    break;
                case '4' :
                    $month_text = 'April';
                    break;
                case '5' :
                    $month_text = 'Mei';
                    break;
                case '6' :
                    $month_text = 'Juni';
                    break;
                case '7' :
                    $month_text = 'Juli';
                    break;
                case '8' :
                    $month_text = 'Agustus';
                    break;
                case '9' :
                    $month_text = 'September';
                    break;
                case '10' :
                    $month_text = 'Oktober';
                    break;
                case '11' :
                    $month_text = 'November';
                    break;
                case '12' :
                default :
                    $month_text = 'Desember';
                    break;
            }
        }

        return $month_text;
    }

    public function date_range($date_begin = NULL, $date_end = NULL) {

        if ($this->lang === 'en') {
            $to = " to ";
        } else {
            $to = " sampai ";
        }

        $this->mysql_date = $date_begin;
        $this->get_date();

        $year_begin = $this->year;
        $month_begin = $this->month;
        $date_begin = $this->date;

        $this->mysql_date = $date_end;
        $this->get_date();

        $year_end = $this->year;
        $month_end = $this->month;
        $date_end = $this->date;

        if ($date_begin === $date_end && $month_begin === $month_end &&
                $year_begin === $year_end) {
            $date_range = $date_begin . ' ' . $this->set_month_name($month_begin, $this->lang) . ' '
                    . $year_begin;
        } else if ($date_begin !== $date_end && $month_begin === $month_end &&
                $year_begin === $year_end) {
            $date_range = $date_begin . $to . $date_end . ' ' .
                    $this->set_month_name($month_begin, $this->lang) . ' '
                    . $year_begin;
        } else if ($date_begin !== $date_end && $month_begin !== $month_end &&
                $year_begin === $year_end) {
            $date_range = $date_begin .
                    ' ' . $this->set_month_name($month_begin, $this->lang) .
                    $to . $date_end . ' ' .
                    $this->set_month_name($month_end, $this->lang) . ' '
                    . $year_begin;
        } else {
            $date_range = $date_begin .
                    ' ' . $this->set_month_name($month_begin, $this->lang) . ' ' .
                    $year_begin . $to . $date_end . ' ' .
                    $this->set_month_name($month_end, $this->lang) . ' '
                    . $year_end;
        }

        return $date_range;
    }

    public function mysql_to_human($mysql_date = NULL, $format = NULL, $lang = 'id') {
        $this->mysql_date = $mysql_date;
        $this->get_date();
        if ($this->mysql_date === NULL || $this->mysql_date === '0000-00-00') {
            return "Tanggal belum diset.";
        } else if ($format === NULL) {
            return $this->date . " " . $this->set_month_name($this->month, $lang) . " " . $this->year;
        } else {
            return $this->date . "-" . $this->month . "-" . $this->year;
        }
    }

    public function mysql_get_date($mysql_date = NULL, $type = NULL) {
        $this->mysql_date = $mysql_date;
        $this->get_date();
        if ($type === 'date') {
            return $this->date;
        } else if ($type === 'month') {
            return $this->set_month_name($this->month, $this->lang);
        } else {
            return $this->year;
        }
    }

    public function mysql_get_tanggal($mysql_date = NULL, $type = NULL) {
        $this->mysql_date = $mysql_date;
        $this->get_date();
        $type === 'date';
        return $this->date;
    }

    public function mysql_get_bulan($mysql_date = NULL, $type = NULL) {
        $this->mysql_date = $mysql_date;
        $this->get_date();
        $type === 'month';
        return $this->set_month_name($this->month, $this->lang);
    }

    public function set_date_format($date, $length = NULL) {
        if ($length === NULL)
            $length = 1;
        $day = 86400 * $length;
        $timestamp = strtotime($date);
        $date_value = date('d/m/Y', $timestamp);
        return $date_value;
    }

    public function set_tanggal($date, $length = NULL) {
        if ($length === NULL)
            $length = 1;
        $day = 86400 * $length;
        $timestamp = strtotime($date);
        $date_value = date('Y-m-d', $timestamp);
        return $date_value;
    }

    public function reversing_date($date) {
        $time = explode("-", $date);
        krsort($time);
        return implode("-", $time);
    }

    public function get_all_month() {
        foreach(range(1, 12) as $number) {
            $month[$this->set_month_name($number, 'id')] = $this->set_month_name($number, 'id');
        }

        return $month;
    }

    public function get_all_month_value_num() {
        foreach(range(1, 12) as $number) {
            $month[$number] = $this->set_month_name($number, 'id');
        }

        return $month;
    }

    public function set_month($month) {
        switch ($month) {
            case 1:
                $text = "Januari";
                break;
            
            case 2:
                $text = "Februari";
                break;

            case 3:
                $text = "Maret";
                break;

            case 4:
                $text = "April";
                break;

            case 5:
                $text = "Mei";
                break;

            case 6:
                $text = "Juni";
                break;

            case 7:
                $text = "Juli";
                break;
            
            case 8:
                $text = "Agustus";
                break;
            
            case 9:
                $text = "September";
                break;
            
            case 10:
                $text = "Oktober";
                break;
            
            case 11:
                $text = "November";
                break;
            
            case 12:
                $text = "Desember";
                break;
        }

        return $text;
    }

    function convert_hari_indo($day){
     
        switch($day){
            case 'Sunday':
                $hari = "Minggu";
            break;
     
            case 'Monday':			
                $hari = "Senin";
            break;
     
            case 'Tuesday':
                $hari = "Selasa";
            break;
     
            case 'Wednesday':
                $hari = "Rabu";
            break;
     
            case 'Thursday':
                $hari = "Kamis";
            break;
     
            case 'Friday':
                $hari = "Jumat";
            break;
     
            case 'Saturday':
                $hari = "Sabtu";
            break;
            
            default:
                $hari = "Hari Tidak diketahui";		
            break;
        }
     
        return $hari;
     
    }

}
