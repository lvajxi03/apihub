<?php

require_once('libs/module.php');
require_once('libs/response.php');


class features extends ApiHubModule
{
    function process($request)
    {
        $response = new Response();
        $d = array("proxy");
        $response->set_data($d);
        return $response;
    }
}
