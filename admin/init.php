<?php

/**
 * Init File Make Connection Between All Files
 * You Can Edit All Files From Here Only This Is Amazing!!!
 */

include ('db_connect.php');

// Routes
$temp = 'includes/templates/';  // templates directory
$lang = 'includes/languages/'; // languages directory
$func = 'includes/functions/'; // functions directory
$css = 'layout/css/';
$js = 'layout/js/';

include $func . 'functions.php';
include $lang . ('english.php');
// include $lang . ('arabic.php');
include $temp . ('header.php');

if (!isset($noNavBar)):
  include $temp . ('navbar.php');
endif;