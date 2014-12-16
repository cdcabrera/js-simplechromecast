<?php

    require_once('resources/private/php/service.Generic.php');

    /**
     *  ToDo: look at media directory paths and subs as what gets passed back through the service for navigation. Detail only pulled when highlighted...
     */
	class Media extends Service
	{
		private $typesMedia = array(
            0 => 'ext1',
            1 => 'ext2'
        );

        private $folderImage = 'folder.jpg';
        
        private $mediaDirectories = array(
            'med1' => '/mediaone/',
            'med2' => '/mediatwo/'
        );
        
        public function __construct()
        {
            $sanitized = $this->cleanInput( $_SERVER['PATH_INFO'] );
            //$sanitized = strtolower( $sanitized );

            $calls = array_filter( explode('/', $sanitized ) );
            $calls = array_values( $calls );
			
			$queries = array();
                        
            foreach ($_GET as $key=>$value)
            {
            	$newkey = $this->cleanInput($key);
            	$newvalue = $this->cleanINput($value);
            	$queries[$newkey] = $newvalue;
            }
			
            $this->process( $calls, $queries );
        }

        private function process( $calls, $queries )
        {   
            switch( $calls[0] )
            {
                case 'directories':
                    $data = $this->directories( $calls[1], $calls[2] );
                    $this->response(200, $data, 'application/json');
                    break;
				
				case 'file':
					//$data = $this->file( $calls[1] );
                    //$this->response(200, $data, 'application/json');
					echo $calls[1];
					break;
				
				case 'cover':
					//cover art service
					break;
				
                default:
                    $this->response(404, '');
                    break;
            }
        }
        
        
        private function directories( $mediaType, $mediaTitle )
        {
        	//echo $mediaType.' '.$mediaTitle."\n";
        	
        	$paths          = $this->mediaDirectories;
            $typesMedia     = $this->typesMedia;
        	$data 			= array();
        	
        	
        	if(isset( $mediaType ) && isset( $mediaTitle ))
        	{
        		$data = $this->getDirectory( $mediaType, $mediaTitle );
        	}
        	else
        	{
        		// if cache file exists load it instead...
        		
        		foreach ($paths as $key=>$value)
	        	{
	            $data = $this->getDirectories( $data, $key, $value );
	        	}
	        
	        	usort($data, function($a, $b)
    			{
    			$a = preg_replace('/^(a\s|the\s)/i', '', $a['name']);
    			$b = preg_replace('/^(a\s|the\s)/i', '', $b['name']);
    			
    			if($a == $b)
    			{
    				return 0;
    			}
    			
    			return ($a < $b)? -1 : 1;
    			});
    		
    			// end cache file alternative
        	}
        	
        	
        	return json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT );
        }
        
        
        private function getDirectories( $data, $key, $path )
        {
        	$iterator = new IteratorIterator(new DirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);
				
			foreach ($iterator as $fileInfo) 
			{
    			if($fileInfo->isDot())
    			{ 
    				continue;
    			}
    			
    			$realpath = $fileInfo->getRealPath();
    			$coverpath = $realpath.'/folder';
    			$coverfile = file_exists($coverpath.'.jpg');
    			
    			//if(file_exists($coverpath.'.jpg'))
    			//{
    				//$coverfile = true;
    				//$coverfile = $coverpath;
    				//$im = file_get_contents($coverfile);
			        //$coverfile = base64_encode($im); 
    			//}
    			
    			//$data[md5($fileInfo->getRealPath())] = array(
	    		//		'name'  	=> $fileInfo->getFilename(),
    			//		'type'		=> $key,
    			//		'cover' 	=> $coverfile
    			//);
    				
    			array_push($data, array(
    				//'id'	=> md5($fileInfo->getRealPath()),
    				'name'  => $fileInfo->getFilename(),
					'type'	=> $key,
					'cover' => $coverfile
    			));
			}
    		
    		return $data;
        }
        
        
        private function getDirectory( $mediaType, $mediaTitle )
        {
        	$data = array();
        	$path = $this->mediaDirectories[$mediaType];
            
            if( !isset($path) || isset($path) && !file_exists($path.$mediaTitle))
            {
            	return (object)$data;
            }
            
            $path = $path.$mediaTitle;
            $data[$mediaTitle] = array('type'=>$mediaType, 'children'=>array());
            
            $iterator = new IteratorIterator(new DirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);
				
			foreach ($iterator as $fileInfo) 
			{
				if($fileInfo->isDot())
    			{ 
    				continue;
    			}
    			
    			$fileType = NULL;
    			$cover = NULL;
    			$realpath = $fileInfo->getRealPath();
    			$coverfile = false;
    			
				if(is_file($realpath))
				{
					$fileType = 'file';
				}
				elseif(is_dir($realpath))
				{
					$fileType = 'directory';
					$coverpath = $realpath.'/folder.jpg';
					$coverfile = file_exists($coverpath);
				}
    			
    			
    			array_push($data[$mediaTitle]['children'], array(
    				'name'  => $fileInfo->getFilename(),
					'filetype'	=> $fileType,
					'path' => $mediaTitle.'/'.$fileInfo->getFilename(),
					'cover' => $coverfile
    			));
			}
            
            return $data;
        }
        
        
        private function getDirectoriesOLDTOTALLYWORKS()
        {
        	$paths          = $this->mediaDirectories;
            $typesMedia     = $this->typesMedia;
        	
        	$data = array();

            foreach ($paths as $key=>$value)
            {
				$iterator = new IteratorIterator(new DirectoryIterator($value), RecursiveIteratorIterator::SELF_FIRST);
				
				foreach ($iterator as $fileInfo) 
				{
    				if($fileInfo->isDot())
    				{ 
    					continue;
    				}
    				
    				//$dir = ($key)
    				
    				array_push($data, array(
    					'name'  	=> $fileInfo->getFilename(),
    					//'path'		=> "/".$fileInfo->getFilename(),
    					//"path"        => '/'. array_pop(explode('/', $fileInfo->getPath() )) .'/'. $fileInfo->getFilename(),
    					//'realpath' 	=> $fileInfo->getRealPath(),
    					//'modified'  => date("m/d/Y h:i:s A", $fileInfo->getMTime()),
    					'type'		=> $key,
    					'cover' 	=> NULL,
    					'children'	=> array()
    				));
				}
            }
        	
        	return json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT );
        }
	}
	
	new Media();
	


?>