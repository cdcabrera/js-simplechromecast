<?php

    /**
     *
     */
    class FileLoader
    {
        public $File            = null;
        public $CacheDirectory  = null;
        public $CacheTime       = 300;
        public $CacheAll        = true;
        public $CacheMD5ID      = null;
        public $CacheFileName   = null;
        public $CacheExtension  = 'txt';


        function __construct()
        {
            $args = func_get_args();
            //$i = func_num_args();
            //call_user_func_array(array($this, "processCSS"), $args);
        }


        /**
         *
         */
        private function getFile( $file )
        {
            $content = file_get_contents($file);

            if(strlen($content) === 0)
            {
                $content = null;
            }

            return $content;
        }


        /**
         *
         */
        private function fileExists( $file )
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
        private function loadFile($file)
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
            $doc 				= null;
            $tempdoc            = null;
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
                    $tempdoc = $this->GetFile($cachefile);

                    if(!is_null($tempdoc))
                    {
                        //return $tempdoc;
                        return array("raw"=>$tempdoc, "name"=>$cachefilename, "cache"=>$cachefile);
                    }
                }
            }

            if( $this->CheckFileExists( $file ) )
            {

                $doc = $this->GetFile($file);

                if( !is_null($cachedirectory) && $externalfile )
                {
                    file_put_contents($cachefile, $doc);
                }
            }
            elseif( $cachefileExists && $externalfile )
            {
                $tempdoc = $this->GetFile($cachefile);

                if(!is_null($tempdoc))
                {
                    $doc = $tempdoc;
                }
            }

            return array("raw"=>$doc, "name"=>$cachefilename, "cache"=>$cachefile);
        }


    }

?>