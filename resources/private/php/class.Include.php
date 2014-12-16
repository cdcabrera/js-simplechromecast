<?php

    class IncludeFiles
    {
        public $startWrapper    = '';
        public $endWrapper      = '';


        public function files()
        {
            $loader = new FileLoader;
            $start  = '';
            $end    = '';
            $content= '';
            $args   = func_get_args();
            $files  = call_user_func_array(array($loader, 'loadFiles'), $args);

            if( $this->startWrapper !== NULL && $this->endWrapper !== NULL )
            {
                $start = $this->startWrapper;
                $end = $this->endWrapper;
            }

            foreach($files as $file)
            {
                $content .= $file['raw'];
            }

            return $start.$content.$end;
        }


        public function packJs()
        {
            $args   = func_get_args();

            $this->startWrapper = '<script>'.$this->startWrapper;
            $this->endWrapper .= '</script>';

            return call_user_func_array(array($this, 'PackJSCSS'), $args);
        }


        public function packCss()
        {
            $args   = func_get_args();

            $this->startWrapper = '<style>'.$this->startWrapper;
            $this->endWrapper .= '</style>';

            return call_user_func_array(array($this, 'PackJSCSS'), $args);
        }


        private function packJsCss()
        {
            $start  = '';
            $end    = '';
            $args   = func_get_args();
            $content= call_user_func_array(array($this, 'Packer'), $args);

            if( $this->startWrapper !== NULL && $this->endWrapper !== NULL )
            {
                $start = $this->startWrapper;
                $end = $this->endWrapper;
            }

            return $start.$content.$end;
        }


        private function packer()
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

            // remove extra spaces... not worrying about making an allowance for "pre"
            $content = preg_replace( '/\s+/', ' ', $content );

            return $content;
        }
    }

?>