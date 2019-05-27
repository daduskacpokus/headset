<?php

/**
 * 
 */
class search implements adapter {

    private $dbhandler;
    private $cortege;
    private $haystack;
    private $tabnames;

    function __construct() {
        $this->dbhandler = new database_handler('headset');
        $this->cortege = new model_matches_cortege();
        $this->tabnames = array('rotation', 'supply', 'writeoff');
    }

    public function _set_haystack($tabname) {
        $this->haystack = $tabname;
    }

    public function _get_haystack() {
        return $this->haystack;
    }

    public function _columns() {
        switch ($this->haystack) {
            case $this->tabnames[0]:
                return 8;
            case $this->tabnames[1]:
                return 6;
            case $this->tabnames[2]:
                return 6;
        }
    }

    /**
     * 
     * @param type $row
     * @param type $column
     */
    public function _push($row, $column) {
        $emergency_brake = 0;
        // echo "$column<br>";
        while ($this->cortege->_has_next()) {//только чётные?
            $matches_tab = $this->cortege->_next();
            $emergency_brake++;
            if($emergency_brake>100){
                // echo "Ограничение на число итерраций = 100<br>";
                break;
            }
            // var_dump($matches_tab);
            // var_dump($this->cortege);
            // exit();
            if ($matches_tab->_get_tabname() == $this->haystack) {
                while ($matches_tab->_has_next()) {
                    $cell = $matches_tab->_next();
                    $cell->_set_row($row);
                    $cell->_set_col($column);
                    $value = $cell->_value();
                    // echo "$value <br>";
                    return $value;
                }
            }
        }

    }

    public function _rows($tabname) {
        $matches_tab = $this->cortege->_by_name($tabname);
        if ($matches_tab instanceof model_matches_tab) {
            $rows = count($matches_tab->_get_array());
        } else {
            $rows = 0;
        }
        return $rows;
    }

    /**
     * Сквозной поиск.
     * @param type $needle
     */
    public function _search_through($needle) {
        $this->needle = $needle;
        $match = 0;
        $request_rotation = "select * from `across` where (`increment_id` like '%" .
                $needle . "%' or `decrement_id` like '%" . $needle .
                "%') and (`increment_id` is not null and `decrement_id` is not null) " .
                " and `rotate` = true and `reverse_date` is null ORDER BY `row_id` DESC;";
        // echo "$request_rotation" . '<br>';
        $request_supply = "select * from `across` where `increment_id` like '%" .
                $needle . "%' and `decrement_id` is null and `rotate` = false and `reverse_date` is null ORDER BY `row_id` DESC;";
        // echo "$request_supply" . '<br>';
        $request_writeoff = "select * from `across` where `decrement_id` like '%" .
                $needle . "%' and `increment_id` is null and `rotate` = false and `reverse_date` is null ORDER BY `row_id` DESC;";
        $requests = array('rotation' => $request_rotation, 'supply' => $request_supply,
            'writeoff' => $request_writeoff);
        // echo "$request_writeoff" . '<br>';
        foreach ($requests as $key => $value) {
            $mysqli_result = $this->dbhandler->_response($value);
            if (mysqli_num_rows($mysqli_result) > 0) {
                $match += mysqli_num_rows($mysqli_result);
                $this->cortege->_add_tab($key);
                while ($row = mysqli_fetch_array($mysqli_result)) {
                    $this->cortege->_fill_tab($key, $row);
                }
            }
        }
        return $match;
    }

}
