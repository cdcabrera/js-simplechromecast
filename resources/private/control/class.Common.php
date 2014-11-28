<?php

    date_default_timezone_set('America/New_York');

    $Common             = new Common();
    $FileRootPath       = $Common->FileRootPath();
    $RootPath           = $Common->RootPath();
    $AppPath            = 'resources/private/';


    include_once('resources/private/control/class.FileLoader.php');
    //include_once("service.Media.php");
    include_once('resources/private/control/class.JSSqueeze.php');


    class Common
    {
        public $cachedir        = null;
        public $publiccachedir  = null;
        public $errorpage       = null;

        public function RootPath()
        {
            $IncludePathArray = explode("/", get_include_path());
            $IncludePathArray = array_map('strtolower', $IncludePathArray);

            $RootPath = explode( "/", $_SERVER['PHP_SELF'] );
            $RootPath = array_map('strtolower', $RootPath);

            $RootPath = array_intersect($IncludePathArray, $RootPath);
            $RootPath = implode( "/", $RootPath );

            return $RootPath."/";
        }


        public function PHPReadFilePath()
        {
            return $_SERVER["DOCUMENT_ROOT"].$this->RootPath();
        }


        public function FileRootPath()
        {
            $FileRootPath = explode( PATH_SEPARATOR, get_include_path() );
            return $FileRootPath[1]."/";
        }


        public function PackJS()
        {
            $args = func_get_args();
            return call_user_func_array(array($this, 'Packer'), $args);
        }


        public function PackCSS()
        {
            $args = func_get_args();
            return call_user_func_array(array($this, 'Packer'), $args);
        }


        private function Packer()
        {
            $args = func_get_args();

            $loader = new FileLoader;

            $files = call_user_func_array(array($loader, 'loadFiles'), $args);

            $content = '';

            foreach($files as $file)
            {
                $content .= $file['raw'];
            }

            // remove newline/return
            $content = preg_replace( '/\n|\r/', '', $content );

            // remove comments
            $content = preg_replace( '/\\/\\/[^\\n\\r]*[\\n\\r]/', '', $content );
            $content = preg_replace( '/\\/\\*[^*]*\\*+([^\\/][^*]*\\*+)*\\//', '', $content );

            // remove extra spaces
            $content = preg_replace( '/\s+/', ' ', $content );

            return $content;
            /*
            $args = func_get_args();

            $loader = new FileLoader;

            $files = call_user_func_array(array($loader, 'loadFiles'), $args);

            $content = '';

            foreach($files as $file)
            {
                $content .= $file['raw'];
            }

            //$packed = new JavaScriptPacker($content);
            //return $packed->pack();

            $parser = new JSqueeze;
            return $parser->squeeze($content);
            */
            //$content = new JavaScriptPacker

            //return JSMin::minify($content);
        }
    }




?>