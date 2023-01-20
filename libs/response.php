<?php

define('RESPONSE_OK', 200);
define('RESPONSE_CREATED', 201);

define('RESPONSE_BAD_REQUEST', 400);
define('RESPONSE_UNAUTHORIZED', 401);
define('RESPONSE_FORBIDDEN', 403);
define('RESPONSE_NOT_FOUND', 404);
define('RESPONSE_METHOD_NOT_ALLOWED', 405);
define('RESPONSE_NOT_ACCEPTABLE', 406);
define('RESPONSE_CONFLICT', 409);
define('RESPONSE_GONE', 412);

define('RESPONSE_SERVER_ERROR', 500);


define('ERROR_OK', 0);
define('ERROR_INVALID_DATA', 1);
define('ERROR_NO_DATA', 2);
define('ERROR_NOT_ALLOWED', 3);
define('ERROR_NO_SUCH_MODULE', 4);
define('ERROR_DB_ERROR', 5);
define('ERROR_NOT_AUTHORIZED', 6);

class Response
{
    var $error_code;
    var $error_message;
    var $http_code;
    var $data;
    function __construct() {
        $this->error_code = ERROR_OK;
        $this->error_message = 'OK';
        $this->http_code = RESPONSE_OK;
        $this->data = null;
    }

    function set_error_code($ec) {
        $this->error_code = $ec;
    }

    function set_error_message($em) {
        $this->error_message = $em;
    }

    function set_http_code($hc) {
        $this->http_code = $hc;
    }

    function append($key, $value) {
        if ($this->data == null) {
            $this->data = array();
        }
        $this->data[$key] = $value;
    }

    function set_data($d) {
        $this-> data = $d;
    }

    function to_json() {
        $data = array(
            'error-code'=> $this->error_code,
            'error-message' => $this->error_message,
            'data' => $this->data
        );
        return json_encode($data);
    }
}

?>
