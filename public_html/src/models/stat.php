<?php

class stat implements adapter {

    private $tabname;
    private $dbhandler;
    private $storages;
    private $sum;

    function __construct() {
        $this->dbhandler = new database_handler('headset');
    }

    public function _get_tabname($a = null, $b = null) {
        return $this->tabname;
    }

    public function _set_tabname($tabname) {
        $this->tabname = $tabname;
    }

    public function _push($row, $column) {
        if($column == 0){
            $this->sum = 0;
        }
        $request = $this->_request_construct($row, $column);
        // echo "$request<br>";
        if($column > 0 & $this->_get_tabname() == 'move'){
            $value = mysqli_num_rows($this->dbhandler->_response($request));
        }elseif($column > 0 & $this->_get_tabname() == 'rest'){
            $active = mysqli_num_rows($this->dbhandler->_response($request));
            $request2 = $this->_request_construct($row, $column, TRUE);
            $passive = mysqli_num_rows($this->dbhandler->_response($request2));
            $value = $active - $passive;
        }else{
            $value = $request;//Первый столбец
        }
        if($column == 9){
            $value = $this->sum;
        }else{
            $this->sum += $value;
        }
        return $value;        
    }

    public function _rows($tabname = FALSE) {
        switch ($this->tabname) {
            case 'move':
                return 3;
            case 'rest':
                $this->stotages = $this->_get_stotages();
                return count($this->stotages);
        }
    }

    public function _columns() {
        return 10;
    }

    private function _request_construct($row, $column, $passive = FALSE){
        $request = "SELECT * FROM `across` WHERE ";
        $tabname = $this->_get_tabname();
        if($tabname == 'move'){
            switch ($row) {
                case 0:
                    if($column == 0) {return 'Перемещение';
                    }else{
                        $label = $this->_get_label($column);
                        $condition = $this->_get_condition($column);
                        $request .= $this->_implement_settings("(`increment_label` = '".$label."' AND `increment_condition` = '".$condition."' AND `decrement_id` IS NOT NULL) OR (`decrement_label` = '".$label."'  AND `decrement_condition` = '".$condition."' AND `increment_id` IS NOT NULL) AND `rotate` = TRUE"); 
                    }
                    break;
                case 1:
                    if($column == 0) {return 'Приём';
                    }else{
                        $label = $this->_get_label($column);
                        $condition = $this->_get_condition($column);                        
                        $request .= $this->_implement_settings("(`increment_label` = '".$label."' AND `increment_condition` = '".$condition."' AND `decrement_id` IS NULL) AND `rotate` = FALSE");                       
                    }
                    break;
                case 2:
                    if($column == 0) {return 'Выдача';
                    }else{
                        $label = $this->_get_label($column);
                        $condition = $this->_get_condition($column);                        
                        $request .= $this->_implement_settings("(`decrement_label` = '".$label."' AND `decrement_condition` = '".$condition."' AND `increment_id` IS NULL) AND `rotate` = FALSE");                       
                    }
                    break;
            }
        }else{
            if($column == 0){
                return $this->storages[$row];
            }else{
                $label = $this->_get_label($column);
                $condition = $this->_get_condition($column);   
                if(!$passive){
                    $request .= $this->_implement_settings("(`increment_label` = '".$label."' AND `increment_condition` = '".$condition."' AND `increment_storage` = '".$this->storages[$row]."' and `decrement_id` IS NULL)");                    
                }else{
                    $request .= $this->_implement_settings("(`decrement_label` = '".$label."' AND `decrement_condition` = '".$condition."' AND `decrement_storage` = '".$this->storages[$row]."' and `increment_id` IS NULL)");                    
                }
            }
        }
        return $request . " AND `reverse_date` IS NULL;";
    }

    /**
     * Деление по модулю опреляет столбец - jabra или logitech
     */
    private function _get_label($column){
        if($column % 2 != 0){
            return 'Jabra';
        }else{
            return 'Logitech';
        }
    }

    private function _get_condition($column){
        if($column < 3){
            return 'Новое';
        }
        if($column > 2 & $column < 5){
            return 'Восстановленное';
        }
        if($column > 4 & $column < 7){
            return 'Неисправное';
        }
        if($column > 6){
            return 'Некондиционное';
        }
    }

    private function _get_stotages(){
        $this->storages = array();
        // $request = "select * FROM `across` where (`increment_id` is null and `decrement_id` is not null) or (`decrement_id` is null and `increment_id` is not null) AND `reverse_date` IS NULL ORDER BY `increment_storage` ASC, `decrement_storage` ASC";
        $request = $this->_implement_settings("select * FROM `across` where (`increment_id` is null and `decrement_id` is not null) or (`decrement_id` is null and `increment_id` is not null) AND `reverse_date` IS NULL", TRUE);
        // var_dump($request);
        $dbresponse = $this->dbhandler->_response($request);
        // var_dump($dbresponse);
        // exit();
        while ($row = mysqli_fetch_array($dbresponse)){
            if($row['increment_storage'] != NULL){
                $this->_ifexist($row['increment_storage'], $this->storages);
            }else{
                $this->_ifexist($row['decrement_storage'], $this->storages);
            }            
        }
        return $this->storages;
    }

    private function _ifexist($storage, &$array){
        $boo = FALSE;
        foreach ($array as $value) {
            if ($storage == $value) {
                $boo = TRUE;
                break;
            }
        }
        if(!$boo){
            $array[] = $storage;
        }
    }

    private function _implement_settings($str, $sorting = FALSE){
        if(!$sorting){
            $a = $str . " and `row_date` >= '".$_COOKIE['start_date']."' and `row_date` <= '".$_COOKIE['end_date']."'";
        }else{
             $a = $str . " and `row_date` >= '".$_COOKIE['start_date']."' and `row_date` <= '".$_COOKIE['end_date']."'ORDER BY `increment_storage` ASC, `decrement_storage` ASC";
        }
        return $a;
    }
}
    
?>