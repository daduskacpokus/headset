<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of model_matches_cell
 *
 * @author x64386
 */
class matches_cell {

    private $row;
    private $column;
   private $array;
    private $tabname;

    function __construct($tabname) {
        $this->tabname = $tabname;
    }

    function __destruct() {
        // session_destroy();
       // echo '__destruct_cell ' . session_id() . '<br>';
        // if ($this->row == count($this->array)) {
        //     unset($_SESSION[$this->tabname . '_' . $this->row]);
        // }
        // echo "$this->tabname _ $this->row => " . count($_SESSION[$this->tabname . '_' . $this->row]) . '<br>';
    }

    public function _set_col($col) {
        $this->column = $col;
//        echo '$cell->column ' . $this->column . '<br>';
    }
    
    public function _set_row($row) {
        $this->row = $row;
    }
    
    public function _value() {
        $tab_row = $this->tabname . '_' . $this->row;
       // echo $this->tabname . '_row' . $this->row . ' ';
        $session_arr = $_SESSION[$tab_row];
        // var_dump($session_arr) . '<br>';
        // foreach ($this->array as $key => $value) {
        foreach ($session_arr as $key => $value) {
            if ($this->tabname == 'writeoff') {//записи "правой части" across
                if ($key == $this->column) {
                    switch ($key) {
                        case 0:
                            return $session_arr['row_id'];
                        case 1:
                            return $session_arr['row_date'];
                        case 2:
                            return $session_arr['decrement_id'];
                        case 3:
                            return $session_arr['decrement_label'];
                        case 4:
                            return $session_arr['decrement_condition'];
                        case 5:
                            return $session_arr['decrement_storage'];
                    }
                }                
            }elseif($this->tabname == 'rotation'){

                if ($key == $this->column) {
                    switch ($key) {
                        case 0:
                            return $session_arr['row_id'];
                        case 1:
                            return $session_arr['row_date'];
                        case 2:
                            return $session_arr['increment_label'];
                        case 3:
                            return $session_arr['increment_id'];
                        case 4:
                            return $session_arr['increment_storage'];
                        case 5:
                            return $session_arr['decrement_label'];
                        case 6:
                            return $session_arr['decrement_id'];
                        case 7:
                            return $session_arr['decrement_storage'];
                    }
                }
            }else{//записи "левой части" across
                if ($key == $this->column) {
                    switch ($key) {
                        case 0:
                            return $session_arr['row_id'];
                        case 1:
                            return $session_arr['row_date'];
                        case 2:
                            return $session_arr['increment_id'];
                        case 3:
                            return $session_arr['increment_label'];
                        case 4:
                            return $session_arr['increment_condition'];
                        case 5:
                            return $session_arr['increment_storage'];
                    }
                }
                // if (strlen($key) == 1) {
                //     if ($key == $this->column) {
                //         return $value;
                //     }
                // }
            }
        }
    }

    /**
     * 
     * @param type $array
     */
    public function _put($array) {
       $this->array = $array;
        $tab_row = $this->tabname . '_' . $this->row;
        if(!isset($_SESSION[$tab_row])){
            $_SESSION[$tab_row] = $array;
        }
        // var_dump($_SESSION[$tab_row]) . '<br>';
    }

}
