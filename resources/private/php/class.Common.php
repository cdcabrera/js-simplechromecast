<?php

    date_default_timezone_set('America/New_York');

    //$Common             = new Common();
    //$FileRootPath       = $Common->fileRootPath();
    //$RootPath           = $Common->rootPath();
    //$AppPath            = 'resources/private/';

    require_once('resources/private/php/class.FileLoader.php');
    require_once('resources/private/php/class.Include.php');


    class Common
    {
        public $cachedir        = NULL;
        public $publiccachedir  = NULL;
        public $errorpage       = NULL;

        public function rootPath()
        {
            $IncludePathArray = explode("/", get_include_path());
            $IncludePathArray = array_map('strtolower', $IncludePathArray);

            $RootPath = explode( "/", $_SERVER['PHP_SELF'] );
            $RootPath = array_map('strtolower', $RootPath);

            $RootPath = array_intersect($IncludePathArray, $RootPath);
            $RootPath = implode( "/", $RootPath );

            return $RootPath."/";
        }


        public function readFilePath()
        {
            return $_SERVER["DOCUMENT_ROOT"].$this->RootPath();
        }


        public function fileRootPath()
        {
            $FileRootPath = explode( PATH_SEPARATOR, get_include_path() );
            return $FileRootPath[1]."/";
        }

    }




?>