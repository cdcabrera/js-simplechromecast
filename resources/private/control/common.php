<?php

    date_default_timezone_set('America/New_York');

    $Common             = new Common();
    $FileRootPath       = $Common->FileRootPath();
    $RootPath           = $Common->RootPath();

    include_once("fileloader.php");
    include_once("mileage.php");
    include_once("minify.php");



?>