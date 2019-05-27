<?php

class vcodeprint {

    // private $header = '';

    function __construct() {
        $this->header .= '</div><form class="no_print" method="post" action="./submit.php?barcode=true">';
        $this->header .= '<input type="submit" value="На экран ">';
        $this->header .= '</form>';
        echo '<h1>Печать ШК</h1>';
        echo $this->header;
        session_start();
        if (isset($_SESSION['run'])) {
            echo '<div id="content"><div class="A4">';
            $code_arr = $_SESSION['codes'];
            foreach ($code_arr as $value) {
                $span = '<div class="barcode">';
                $src = "<img style='height: 50px; width: 80px; margin-top: -15px;' src='http://barcode.tec-it.com/barcode.ashx?translate-esc=on&data=".$value."&code=Code93&unit=Fit&dpi=96&imagetype=Png&rotation=0&color=000000&bgcolor=FFFFFF&qunit=Mm&quiet=0'/></div>";
                // $src = "<img style='height: 50px; width: 80px; margin-bottom: 5px; margin-top: -15px;' src='http://barcode.tec-it.com/barcode.ashx?translate-esc=on&data=".$value."&code=Code93&unit=Fit&dpi=96&imagetype=Png&rotation=0&color=000000&bgcolor=FFFFFF&qunit=Mm&quiet=0'/></div>";
                echo "$span" . "$src";
            }
            echo '</div></div></body></html>';
        }
        session_destroy();
    }

}

