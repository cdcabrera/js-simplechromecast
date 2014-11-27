<?php

    date_default_timezone_set('America/New_York');

    $Common             = new Common();
    $FileRootPath       = $Common->FileRootPath();
    $RootPath           = $Common->RootPath();

    include_once("class.FileLoader.php");
    include_once("service.Media.php");
    include_once("class.JavaScriptPacker.php");



?>