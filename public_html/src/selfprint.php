<?php

class selfprint {

    protected function _thead($colunm_title) {
        $colunm_width = 100 / count($colunm_title);
        $thead = '<table class="table" border="1"><thead><tr>';
        foreach ($colunm_title as $value) {
            $thead .= '<th style="text-align:center; width:' . $colunm_width .'%">' . $value . '</th>';
        }
        $thead .= '</tr></thead>';
        return $thead;
    }
    

    protected function _as_link($journal, $model_value, $row, $column) {
        setcookie('reverse',  $journal, time()+60);

        if (!$column) {
            return '<td><a href="?'.$journal.'=true&reverse=' . $row . '">' . $model_value . '</a></td>';
        } else {
            return '<td>' . $model_value . '</td>';        
        }
    }

    protected function _reverse($model_value, $row = null, $column) {
        if ($column == 0) {
            $form = '<td><form id="form2"  class="reverse" method="post" action="./unbreakable.php?reverse='.$model_value.'"><input type="text" name="row" value="' . $model_value . '" disabled><a href="" onClick="this.parentNode.submit();"></a></form></td>';
            return $form;
        } else {
            return '<td>' . $model_value . '</td>';
        }
    }
}
