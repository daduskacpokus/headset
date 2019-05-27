<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of supply
 *
 * @author ua638
 */
class supply implements adapter {

    private $rows;
    private $push_query;
    private $dbhandler;

    function __construct() {
        $this->dbhandler = new database_handler('headset');
        $this->push_query = "select * from `across` where `decrement_id` is null and `row_date` >= '".$_COOKIE['start_date']."' and `row_date` <= '".$_COOKIE['end_date']."' and `rotate` = FALSE ORDER BY `row_id` DESC;";
        $this->push_result = $this->dbhandler->_response($this->push_query);
        $this->rows = mysqli_num_rows($this->push_result);
        session_start();
        $array_num = 0;
        while ($arr = mysqli_fetch_array($this->push_result)) {
            $_SESSION['supply_' . $array_num] = $arr;
            $array_num++;
        }
    }

    function __destruct() {
        session_destroy();
    }

    /**
     * 
     * @param type $row
     * @param type $column
     * @return type
     */
    public function _push($row, $column) {
        switch ($column) {
            case 0:
                return $_SESSION['supply_' . $row][0];
            case 1:
                return $_SESSION['supply_' . $row][1];
            case 2:
                return $_SESSION['supply_' . $row][2];
            case 3:
                return $_SESSION['supply_' . $row][3];
            case 4:
                return $_SESSION['supply_' . $row][4];
            case 5:
                return $_SESSION['supply_' . $row][5];
            case 6:
                return $_SESSION['supply_' . $row][10];
        }
    }

    public function _columns() {
        return 6;
    }

    public function _rows($tabname = NULL) {
        return $this->rows;
    }

}
