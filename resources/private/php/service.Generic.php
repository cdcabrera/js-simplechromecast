<?php
    /*
     * Utilized resource: Arun Kumar Sekar, http://www.9lessons.info/2012/05/create-restful-services-api-in-php.html
     */

    class Service
    {
        protected $request = array();

        private $statusCodes = array(
            100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            306 => '(Unused)',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported'
        );


        protected function __construct()
        {
            $this->parseParams();
        }


        protected function response( $status, $data, $contentType='text/html' )
        {
            $this->headers( $status, $contentType );
            exit( $data );
        }


        private function parseParams()
        {
            $method = $_SERVER['REQUEST_METHOD'];

            switch( $method )
            {
                case "POST":
                    $this->request = $this->cleanInput($_POST);
                    break;

                case "GET":
                case "DELETE":
                    $this->request = $this->cleanInput($_GET);
                    break;

                case "PUT":
                    parse_str( file_get_contents('php://input'), $this->request );
                    $this->request = $this->cleanInput($this->request);
                    break;

                default:
                    $this->response(406, '');
                    break;
            }
        }


        private function headers( $status, $contentType )
        {
        	header('HTTP/1.1 '.$status.' '.$this->status( $status ));
            header('Content-Type:'.$contentType);
        }


        private function status( $status )
        {
            $message = $this->statusCodes[500];

            if($this->statusCodes[$status])
            {
                $message = $this->statusCodes[$status];
            }

            return $message;
        }


        protected function cleanInput( $value )
        {
            $value = trim(utf8_decode($value));
            $value = stripslashes($value);
            $value = strip_tags($value);

            return $value;
        }
    }
?>