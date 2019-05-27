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
class vsupply extends selfprint {

    private $header;

    function __construct() {
        $this->header = '</div><div id="content">';
        $this->header = '<form class="supply" method="post" action="./submit.php?supply=true">';
        if (strlen($_GET['device']) > 0) {
            $autofocus1 = ' ';
            $autofocus2 = ' autofocus ';
        }else{
            $autofocus1 = ' autofocus ';
            $autofocus2 = ' ';
        }        
        $this->header .= 'Артикул: <input'.$autofocus1.'type="text" name="device" placeholder="например, 4Y8H5" value="' . $_GET['device'] . '">';
        $this->header .= ' РМ: <input'.$autofocus2.'type="text" name="workstation" placeholder="например, it">';
        $this->header .= '<p><b>Категория:</b><input type="radio" name="used" value="new">Новое';
        $this->header .= '<input type="radio" name="used" value="false" checked>Восстановленое';
        $this->header .= '<input type="radio" name="used" value="true">Неисправное';
        $this->header .= '<input type="radio" name="used" value="null">Некондиционное</p>';
        $this->header .= '<p><b>Модель:</b><input type="radio" name="vendor" value="jabra">Jabra';
        $this->header .= '<input type="radio" name="vendor" value="logitech" checked>Logitech</p>';
        $this->header .= '<input tabindex="3" type="submit" value="Новая запись"></form>';
        echo '<h1 style="color:green;">Приём</h1>';
        echo $this->header;
        $table = '';
        $colunm_title = array('№ записи', 'Дата', 'Артикул', 'Модель', 'Категория', 'РМ');
        $table .= parent::_thead($colunm_title);
        $tab_model = new supply();
        $rows = $tab_model->_rows();
        $columns = $tab_model->_columns();
        for ($row = 0; $row < $rows; $row++) {//не хочу думать почему так
            $table .= '<tr class="cell">';
            for ($column = 0; $column < $columns; $column++) {
                $reverse = $tab_model->_push($row, $columns);
                $model_value = $tab_model->_push($row, $column);
                if ($_GET['reverse'] != NULL && $_GET['reverse'] == $row) {
                    $table .= parent::_reverse($model_value, $row, $column);
                } else {
                    
                    if($reverse > 0){
                        $model_value = '<span style="text-decoration: line-through;">'.$model_value.'</span>';
                        $table .= '<td>'. $model_value . '</td>';    
                    }else{
                        $table .= parent::_as_link('supply', $model_value, $row, $column);
                    }
                }
            }
            $table .= '</tr>';
        }
        echo $table . '</table>';
        echo '</div></body></html>';
        
        session_destroy();
    }

}
