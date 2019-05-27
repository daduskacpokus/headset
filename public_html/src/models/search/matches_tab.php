<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of model_matches_tab
 *
 * @author x64386
 */
class model_matches_tab implements iterable{
    
    private $tabname;
    private $mysqli_result;
    private $result_array;
    private $cursor;
    public function __construct() {
        $this->cursor = 0;
        $this->mysqli_result = array();
    }

    public function _set_name($tabname) {
        $this->tabname = $tabname;
    }

    public function _add_row($array_result) {
//        $arr = $array_result;
//        $this->result_array = mysqli_fetch_array($arr);
        $this->mysqli_result[] = $array_result;
    }
    public function _get_tabname() {
        return $this->tabname;
    }

    public function _get_array() {
        return $this->mysqli_result;
    }

    public function _current() {
//        return $this->result_array[$this->cursor];
    }

    public function _has_next() {
        if ($this->cursor < count($this->result_array)) {
            $has_next = 'TRUE';
        } else {
            $has_next = 'FALSE';
        }
       // echo 'matches_tab has_next ' . $has_next . '<br>';        
        return $has_next;        
    }
    
    /**
     * 
     * @return \matches_cell
     */
    public function _next() {
        $cell = new matches_cell($this->_get_tabname());      
        $cell->_set_row($this->cursor);
//        $arr = mysqli_fetch_array($this->mysqli_result);
        $cell->_put($this->mysqli_result[$this->cursor]);
        $this->cursor++;
        return $cell;
    }

}
