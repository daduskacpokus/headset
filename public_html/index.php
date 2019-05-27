
<?php

require_once ('./vendor/autoload.php');

if(!isset($_COOKIE['start_date']) & !isset($_COOKIE['end_date'])){
    $today = substr(date('c'), 0, 10);
    // setcookie('start_date', $today);
    // setcookie('end_date', $today);
}

ini_set('display_errors', 'Off');
error_reporting(0);

include './header.inc.php';
switch (true) {
    case ($_GET['rotate'] == true):
        // controller_cookie::_set_cookie('rotate');
        new vrotate();
        break;
    case ($_GET['supply'] == true):
        // controller_cookie::_set_cookie('supply');
        new vsupply();
        break;
    case ($_GET['writeoff'] == true):
        // controller_cookie::_set_cookie('writeoff');
        new vwriteoff();
        break;
    case ($_GET['stat'] == true):
        // controller_cookie::_set_cookie('stat');
        new vstat();
        break;
    case ($_GET['search'] == true):
        // controller_cookie::_set_cookie('search');
        new vsearch();
        break;
    case ($_GET['barcode'] == true):
        // controller_cookie::_set_cookie('barcode');
        new vcodeprint();
        break;
    case ($_GET['settings'] == true):
        // controller_cookie::_set_cookie('barcode');
        new vsettings();
        break;

}

