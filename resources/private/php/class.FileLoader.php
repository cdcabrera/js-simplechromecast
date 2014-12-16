<?php

    /**
     *
     */
    class FileLoader
    {
        public $File            = NULL;
        public $CacheDirectory  = NULL;
        public $CacheTime       = 300;
        public $CacheAll        = true;
        public $CacheMD5ID      = NULL;
        public $CacheFileName   = NULL;
        public $CacheExtension  = 'txt';


        //function __construct()
        //{
            //$args = func_get_args();
            //return call_user_func_array(array($this, "loadFiles"), $args);
        //}

        //public static function loadFiles()
        //{
        //    $fileloader = new FileLoader();
        //    return $fileloader->loadFiles();
        //}


        /**
         *
         */
        public function loadFiles()
        {
            //return $this->loadFile( $file );
            $args = func_get_args();
            $returnedData = array();
            //$i = func_num_args();
            //call_user_func_array(array($this, "processCSS"), $args);

            foreach($args as $arg)
            {
                $fileData = $this->loadFile( $arg );
                array_push($returnedData, $fileData);
            }

            return $returnedData;
        }


        /**
         *
         */
        private function getFile( $file )
        {
            $content = file_get_contents($file);

            if(strlen($content) === 0)
            {
                $content = NULL;
            }

            return $content;
        }


        /**
         *
         */
        private function checkFileExists( $file )
        {
            $ret = false;
            if( preg_match("/^(https?|ftp):\/\//i", $file) )
            {
                $header_response = get_headers($file);

                if ( strpos( $header_response[0], "200" ) !== false )
                {
                  $ret = true;
                }
            }
            else
            {
                $ret = file_exists( $file );
            }
            return true;
        }



        /**
         *
         */
        private function loadFile( $file )
        {
            //$file               = $this->File;
            $cachedirectory     = $this->CacheDirectory;
            $cachetime          = $this->CacheTime;
            $cacheeverything    = $this->CacheAll;
            $globalmd5id        = $this->CacheMD5ID;
            $filenameoverride   = $this->CacheFileName;
            $cacheextension     = $this->CacheExtension;

            $cachefile  		= '';
            $cachefileExists 	= false;
            $cachefilename 		= '';
            $tempcachefilename  = '';
            $doc 				= NULL;
            $tempdoc            = NULL;
            $externalfile       = ($cacheeverything)? $cacheeverything : preg_match("/^(https?|ftp):\/\//i", $file);


            if( !is_null($cachedirectory) && $externalfile )
            {

                $cachefilename = '{0}.'.$cacheextension;

                if( is_null($filenameoverride) )
                {
                    if( !is_null($globalmd5id) )
                    {
                        $tempcachefilename = md5($globalmd5id);
                    }
                    else
                    {
                        $tempcachefilename = md5($file.'_somestring');
                    }
                }
                else
                {
                    $tempcachefilename = $filenameoverride;
                }

                $cachefilename =  preg_replace('/\{0\}/', $tempcachefilename, $cachefilename);
                $cachefile = $cachedirectory.$cachefilename;
                $cachefileExists = (file_exists($cachefile))? true : false;

                //-- use seconds until file is updated
                if( is_numeric( $cachetime ) && $cachefileExists && (time() - $cachetime < filemtime($cachefile)) )
                {
                    $tempdoc = $this->getFile($cachefile);

                    if(!is_null($tempdoc))
                    {
                        //return $tempdoc;
                        return array("raw"=>$tempdoc, "name"=>$cachefilename, "cache"=>$cachefile);
                    }
                }
            }

            if( $this->CheckFileExists( $file ) )
            {
                $doc = $this->getFile($file);

                if( !is_null($cachedirectory) && $externalfile )
                {
                    file_put_contents($cachefile, $doc);
                }
            }
            elseif( $cachefileExists && $externalfile )
            {
                $tempdoc = $this->getFile($cachefile);

                if(!is_null($tempdoc))
                {
                    $doc = $tempdoc;
                }
            }

            return array("raw"=>$doc, "name"=>$cachefilename, "cache"=>$cachefile);
        }

    }

?>