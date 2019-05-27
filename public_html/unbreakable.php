<?php

// $a = $_GET['reverse'];
// var_dump($_GET);



foreach ($_GET as $key => $value) {

    switch ($key) {
        case 'reverse':
            if(_acceppted($value)){
                _redirect('./submit.php?reverse=' . $value);
            }
            break;

        }
}


function _acceppted($input){
    return true;
}

/**
 * 
 * @param type $url
 * @param type $pause
 */
function _redirect($url, $pause = 0) {
    echo '<script type="text/javascript">';
    echo 'setTimeout(function(){window.location.href="' . $url . '"},' . $pause . ');';
    echo '</script>';
//    exit();
}