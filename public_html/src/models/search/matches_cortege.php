<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of model_matches
 *
 * @author x64386
 */
class model_matches_cortege implements iterable {

    private $matches_tabs;
    private $add;
    private $cursor;

    function __construct() {
        $this->matches_tabs = array();
        $this->add = 0;
        $this->cursor = 0;
    }

    public function _fill_tab($tabname, $array_row) {
        $tab = $this->_by_name($tabname);
        $tab instanceof model_matches_tab;
        $tab->_add_row($array_row);
    }

    public function _add_tab($key) {
        $tab = new model_matches_tab();
        $tab->_set_name($key);
        $this->matches_tabs[$this->add] = $tab;
        $this->add++;
    }

    /**
     * Для записи
     * @return \model_matches_tab
     */
    public function _current() {
        $result = $this->matches_tabs[$this->add];
        $result instanceof model_matches_tab;
        $this->add++;
        return $result;
    }

    /**
     * 
     * @param type $name
     * @return \model_matches_tab
     */
    public function _by_name($name) {
        foreach ($this->matches_tabs as $value) {
            $value instanceof model_matches_tab;
            if ($value->_get_tabname() == $name) {
                return $value;
            }
        }
    }

    public function _has_next() {
        if ($this->cursor < count($this->matches_tabs)) {
            $has_next = 'TRUE';
        } else {
            $has_next = 'FALSE';
            $this->cursor = 0;//для следующих таблиц результатов
        }
        // echo "matches_cortege $has_next<br>";
        return $has_next;
    }

    /**
     * 
     * @return \model_matches_tab
     */
    public function _next() {
        $matches_tab = $this->matches_tabs[$this->cursor];
        $matches_tab instanceof model_matches_tab;
        $this->cursor++;
        return $matches_tab;
    }

}
