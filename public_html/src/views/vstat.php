<?php

class vstat extends selfprint {
    private $stat_model;
    private $ttl_arr = array('Новое', 'Восстановленное', 'Неисправное', 'Некондиционное', 'Всего');

    function __construct() {
        $this->stat_model = new stat();
        $header = '</div><div id="content">';
        echo "<h1>Статистика</h1>";
        $header .= '<p>Движение</p>';
        $header .= $this->_print_report(0);
        $header .= '<p>Остатки</p>';
        $header .= $this->_print_report(1);
        echo $header;
        echo '</div></body></html>';
        
        session_destroy();
    }
    
    /**
     * 
     * @param type $id
     * @return type
     */
    protected function _print_report($id) {
        $thead = ''; $tab = '';
        if ($id > 0) {
            $first_column = 'РМ';
            $this->stat_model->_set_tabname('rest');//определить внутреннее состояние модели
        } else {
            $first_column = 'Тип';
            $this->stat_model->_set_tabname('move');
        }
        $thead = '<table class="table" border="1"><thead><tr><th colspan="2" '
                . 'rowspan="2" style="position: relative; text-align:center; width:' .
                '10%;">' . $first_column . '</th>';
        foreach ($this->ttl_arr as $value) {
            if($value != 'Всего'){
                $thead .= '<th colspan="2" style="text-align:center; width:' .
                '20%;">' . $value . '</th>';
            }else{
                $thead .= '<th rowspan="2" style="text-align:center; width:' .
                '10%;">' . $value . '</th>';
            }
        }
        $thead .= '<tr>';
        for ($i = 0; $i < count($this->ttl_arr); $i++) {
            if($i != count($this->ttl_arr) -1){
                $thead .= '<th style="position: relative; width: 10%;">Jabra</th>' .
                '<th style="position: relative; width: 10%;">Logitech</th>';                
            }
        }
        $thead .= '</tr></thead><tbody>';
        $tab .= $thead . $this->_fillrows();
        return $tab . '</tbody></table>';
    }

    function _fillrows(){
        $tbody = '';
        $rows = $this->stat_model->_rows();
        for($row = 0; $row < $rows; $row++){
            $tbody .= "<tr>";
            for ($column=0; $column < $this->stat_model->_columns(); $column++){ 
                $tbody .= $this->_isfirstcol($column);
                $value = $this->stat_model->_push($row, $column);
                if($value == 0 & $column != 0){
                    $value = '-';
                }
                $tbody .= $this->_lesszero($value);
            }
            $tbody .= "</tr>";
        }
        return $tbody;
    }    

    private function _isfirstcol($column){        
        if($column == 0){
            return '<td colspan="2"';
        }elseif($column == 9){
            return '<td style="text-align:center; font-weight: bold;';
        }else{
            return '<td style="text-align:right;';
        }
    }

    private function _lesszero($value){
        if ($value < 0) {
            return 'color: red;">' . $value . '</td>';
        }else{                    
            return '">' . $value . '</td>';
        }
    }
}

?>