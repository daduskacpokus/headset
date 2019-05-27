<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author x64386
 */
interface adapter {
    
    /**
     * Что-то берёт
     * @param type $row
     * @param type $column
     */
    public function _push($row, $column);

    public function _rows($tabname);

    public function _columns();
}
