<?php

error_reporting(0);
ini_set('display_errors', '0');

require_once('libs/request.php');
require_once('libs/response.php');
require_once('libs/db.php');

$headers = apache_request_headers();
$method = METHOD_NONE;

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    $method = METHOD_GET;
}
else if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $method = METHOD_POST;
}
else if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
    $method = METHOD_PUT;
}
else if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
    $method = METHOD_DELETE;
}

$token = '';
$params = array();
$actions = array();

if (isset($headers['Authorization']))
{
    $bearer = $headers['Authorization'];
    if (substr($bearer, 0, 7) === "Bearer ")
    {
        $token = str_replace("Bearer ", "", $bearer);
        if ($token != "")
        {
            $token = base64_decode($token, true);
            if ($token == false)
            {
                $token = '';
            }
        }
    }
}

$response = new Response();
if ($token != '')
{
    $db = DB::get_instance();
    $query = "SELECT data, token from auth_tokens WHERE token='".$token."'";
    $result = $db->query($query);
    if ($result)
    {
        if (count($result) > 0)
        {
            if (isset($_GET))
            {
                $actions = array();
                $params = array();
                // GET first, to check what to handle.
                if (isset($_GET['url']))
                {
                    $actions = explode( "/", $_GET['url']);
                    unset($_GET['url']);
                }
                foreach ($_GET as $key => $value)
                {
                    $params[$key] = $value;
                }
            }
            $data = array();

            if ($method == METHOD_POST)
            {
                // POST first
                if (isset($HTTP_RAW_POST_DATA))
                {
                    $data = json_decode($HTTP_RAW_POST_DATA, true);
                    if (!is_array($data))
                    {
                        $data = null;
                    }
                }
            }

            if ($method == METHOD_PUT)
            {
                $raw_data = file_get_contents("php://input");
                $data = json_decode($raw_data, true);
                if (!is_array($data))
                {
                    $data = null;
                }
            }

            else if ($method == METHOD_DELETE)
            {
                // No extra processing needed.
            }

            if (count($actions) > 0)
            {
                $modname = $actions[0];
                array_shift($actions);
                $request = new Request($method, $token, $actions, $params, $data);

                $modfile = "libs/".$modname.".php";
                if (file_exists($modfile))
                {
                    include_once($modfile);
                    $module = new $modname();
                    $response = $module->process($request);
                }
                else
                {
                    $response->set_http_code(RESPONSE_NOT_FOUND);
                    $response->set_error_code(ERROR_NO_SUCH_MODULE);
                    $response->set_error_message("No such module: ".$modname);
                }
            }
            else
            {
                $response->set_http_code(RESPONSE_NOT_ACCEPTABLE);
                $response->set_error_code(ERROR_NOT_ALLOWED);
                $response->set_error_message("Not allowed.");
            }
        }
        else
        {
            $response->set_http_code(RESPONSE_UNAUTHORIZED);
            $response->set_error_code(ERROR_NOT_AUTHORIZED);
            $response->set_error_message("Not authorized.");
        }
    }
    else
    {
        $response->set_http_code(RESPONSE_UNAUTHORIZED);
        $response->set_error_code(ERROR_NOT_AUTHORIZED);
        $response->set_error_message("Not authorized.");
    }
}
else
{
    $response->set_http_code(RESPONSE_UNAUTHORIZED);
    $response->set_error_code(ERROR_NOT_AUTHORIZED);
    $response->set_error_message("Not authorized.");
}


http_response_code($response->http_code);
header('Content-Type: application/json');
header('Server: Tarrantula/0.2', true);

echo $response->to_json();

?>
