<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of db_handler
 *
 * @author ua638
 */
class database_handler {

    private $mysqli;

    public function __construct($db_name) {
        include ('/usr/local/etc/auth.php');
        $this->mysqli = new mysqli("mysql", $usr, $pwd, $db_name);
        if (!$this->mysqli) {
            printf("Невозможно подключиться к базе данных. Код ошибки: %s\n", mysqli_connect_error());
            exit;
        } else {
            $this->mysqli->query('SET NAMES utf8;');
        }
    }
    
    public function __destruct() {
        $this->mysqli->close();
    }
    
    public function _response($request) {
        $result = $this->mysqli->query($request);
        return $result;
    }

    public function _err_code() {
        return $this->mysqli->errno;
    }

    public function _err_mess() {
        return $this->mysqli->error;
    }

}
