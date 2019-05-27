<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of writeoff
 *
 * @author ua638
 */
class vwriteoff extends selfprint {

    private $header;

    function __construct() {
        $this->header = '</div><div id="content">';
        $this->header = '<form class="writeoff no_print" method="post" action="./submit.php?writeoff=true">';
        if (strlen($_GET['device']) > 0) {
            $autofocus1 = ' ';
            $autofocus2 = ' autofocus ';
        } else {
            $autofocus1 = ' autofocus ';
            $autofocus2 = ' ';
        }
        $this->header .= 'Артикул: <input' . $autofocus1 . 'type="text" name="device" placeholder="например, 9K18U" value="' . $_GET['device'] . '">';
        $this->header .= ' РМ: <input' . $autofocus2 . 'type="text" name="workstation" placeholder="например, it" value="' . $_GET['storage'] . '">';
        if (strlen($_GET['condition']) > 0) {

            switch ($_GET['condition']) {
                case 'new':
                    $condition1 = ' checked';
                    $condition2 = '';
                    $condition3 = '';
                    $condition4 = '';
                    break;
                case 'faulty':
                    $condition1 = '';
                    $condition2 = '';
                    $condition3 = ' checked';
                    $condition4 = '';
                    break;
                case 'refurbished':
                    $condition1 = '';
                    $condition2 = ' checked';
                    $condition3 = '';
                    $condition4 = '';
                    break;
                case 'inadequate':
                    $condition1 = '';
                    $condition2 = '';
                    $condition3 = '';
                    $condition4 = 'checked';
                    break;
            }
        }

        $this->header .= '<p><b>Категория:</b><input type="radio" name="used" value="new"'
                . $condition1 . '>Новое';
        $this->header .= '<input type="radio" name="used" value="false"'
                . $condition2 . '>Восстановленое';
        $this->header .= '<input type="radio" name="used" value="true"'
                . $condition3 . '>Неисправное';
        $this->header .= '<input type="radio" name="used" value="null"'
                . $condition4 . '>Некондиционное</p>';
        if (strlen($_GET['label']) > 0) {

            switch ($_GET['label']) {
                case "Jabra":
                    $label1 = ' checked';
                    $label2 = '';
                    break;
                case "Logitech":
                    $label1 = '';
                    $label2 = ' checked';
                    break;
            }
        }
        $this->header .= '<p><b>Модель:</b><input type="radio" name="vendor" value="jabra"' 
                . $label1 . '>Jabra';
        $this->header .= '<input type="radio" name="vendor" value="logitech"' 
                . $label2 . '>Logitech</p>';
        $this->header .= '<input tabindex="3" type="submit" value="Новая запись"></form>';
        echo '<h1 style="color:red;">Выдача</h1>';
        echo $this->header;
        $table = '';
        $colunm_title = array('№ записи', 'Дата', 'Артикул', 'Модель', 'Категория', 'РМ');
        $table .= '<div class="A4">';
        $table .= parent::_thead($colunm_title);
        $tab_model = new writeoff();
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
                        $table .= parent::_as_link('writeoff', $model_value, $row, $column);
                    }
                }
            }
            $table .= '</tr>';
        }
        echo $table . '</table></div>';
        echo '</div></body></html>';
        
        session_destroy();
    }

}
