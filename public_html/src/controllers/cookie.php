<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of controller_cookie
 *
 * @author x64386
 */
class controller_cookie {

    /**
     * Установить нужный кук, удалить ненужные
     * @param type $cook_name нужный кук
     */
    static function _set_cookie($cook_name) {
        $cooks_arr = array('rotate', 'supply', 'writeoff', 'stat', 'search', 'barcode', 'help');
        foreach ($cooks_arr as $value) {
            if ($cook_name == $value) {
                $isset = !isset($_COOKIE[value]);
                if(!$isset){
                    setcookie($value);
                }
            } else {
                setcookie($value, '', time() - 3600);
            }
        }
    }

}
