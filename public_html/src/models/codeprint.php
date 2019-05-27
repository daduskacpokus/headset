<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of code_generator
 *
 * @author x64386
 */
class codeprint {

    private $dbhandler;

    public function __construct() {
        // include './src/barcode.php';
        include './src/controllers/database_handler.php';
        $this->dbhandler = new database_handler('headset');
    }

    /**
     * 
     * @return array
     */
    public function _code_arr() {
//        1 сгенерировать новые 
        $arr = array();
        $code_length = 5;
        for ($x = 0; count($arr) < 248; $x++) {
            $code = '';
            for ($i = 0; $i < $code_length; $i++) {
                $code .= $this->_get_ansi_char(rand(0, 1));
            }
//        2 выдать созданные (минус уже имеющиеся)
            $query = "select * from `barcode` where `code`='" . $code . "';";
            $db_result = $this->dbhandler->_response($query);
            if (mysqli_num_rows($db_result) == 0) {
                $arr[$x] = $code;
                $query = "insert into `barcode` values('','" . date('c') . "','" . $code . "');";
                $this->dbhandler->_response($query);
            }
        }
        return $arr;
    }

    /**
     * Случайный символ ANSI
     * @param $range номер диапазона. 0 - числа, 1 - заглавные, 2 - строчные
     */
    private function _get_ansi_char($range) {
        $ansi_int = array(48, 57);
        $ansi_upper = array(65, 90);
//        $ansi_lower = array(97, 122); не используется в code39
        switch ($range) {
            case 0:
                $from = $ansi_int[0];
                $to = $ansi_int[1];
                break;
            case 1:
                $from = $ansi_upper[0];
                $to = $ansi_upper[1];
                break;
//            case 2:
//                $from = $ansi_lower[0];
//                $to = $ansi_lower[1];
//                break;
        }
        $char_code = rand($from, $to);
        return chr($char_code);
    }

}
