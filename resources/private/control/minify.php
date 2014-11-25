<?php

    /**
     *
     */
    class Minify
    {


        function __construct()
        {
            //$args = func_get_args();
            //$i = func_num_args();
            //call_user_func_array(array($this, "processCSS"), $args);
        }


        /**
         *
         */
        public function css()
        {
            $args = func_get_args();
            return call_user_func_array(array($this, "minifyCSS"), $args);
        }


        /**
         *
         */
        public function js()
        {
            $args = func_get_args();
            return call_user_func_array(array($this, "minifyJS"), $args);
        }


        /**
         *
         */
        private function minifyCSS()
        {
            $args = func_get_args();
            //$i = func_num_args();


        }


        /**
         *
         */
        private function minifyJS()
        {
            $args = func_get_args();
            //$i = func_num_args();


        }









    }



?>