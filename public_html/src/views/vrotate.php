<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of rotation
 *
 * @author ua638
 */
class vrotate extends selfprint{

    private $header;
    private $reverse;
    function __construct() {
        $this->header = '</div><div id="content">';
        $this->header = '<form id="form1" class="rotation" method="post" action="./submit.php?rotate=true">';
        if (strlen($_GET['increment']) > 0) {
            $autofocus1 = ' ';
            $autofocus2 = ' autofocus ';
        }else{
            $autofocus1 = ' autofocus ';
            $autofocus2 = ' ';
        }
        $this->header .= '<p>Неисправное: <input' . $autofocus1 . 'tabindex="1" type="text" name="increment" placeholder="например, G2P90" value="' . $_GET['increment'] . '">';
        $this->header .= ' Восстановленое: <input' . $autofocus2 . 'tabindex="2" type="text" name="decrement" placeholder="например, W6B79"></p>';
        $this->header .= '<input tabindex="3" type="submit" value="Новая запись"></form>';
        echo '<h1 style="color:blue;">Перемещение</h1>';
        echo $this->header;
        $table = '';
        $colunm_title = array('№ записи', 'Дата', 'Неисправное', 'РМ', 'Восстановленое', 'РМ');
        $table .= parent::_thead($colunm_title);
        $tab_model = new rotate();
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
                        $table .= parent::_as_link('rotate', $model_value, $row, $column);
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
