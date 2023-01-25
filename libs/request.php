<?php

define('METHOD_NONE', -1);
define('METHOD_GET', 0);
define('METHOD_POST', 1);
define('METHOD_PUT', 2);
define('METHOD_DELETE', 3);
define('METHOD_HEAD', 4);

class Request
{
    var $method;
    var $actions;
    var $token;
    var $params;
    var $data;

    function __construct($method, $token, $actions, $params, $data) {
        $this->method = $method;
        $this->token = $token;
        $this->actions = $actions;
        $this->params = $params;
        $this->data = $data;
    }
}

?>
