<?php

require_once('libs/module.php');
require_once('libs/response.php');
require_once('libs/db.php');

class adres extends ApiHubModule
{
    function process($request)
    {
        $db = DB::get_instance();
        $response = new Response();
        if ($request->method == METHOD_PUT)
        {
            if (is_array($request->data))
            {
                $hostname = $request->data['hostname'];
                $ip = $request->data['ip'];
                $data = date("Y-m-d");
                $godzina = date("H:i:s");
                $query = "INSERT INTO adresy (hostname, ip, data, godzina) VALUES ('".$hostname."', '".$ip."', '".$data."', '".$godzina."')";
                $result = $db->query_r($query);
                if ($result)
                {
                    $response->set_http_code(RESPONSE_CREATED);
                    $response->set_error_code(ERROR_OK);
                    $response->set_error_message("Created.");
                }
                else
                {
                    $response->set_http_code(RESPONSE_CONFLICT);
                    $response->set_error_code(ERROR_DB_ERROR);
                    $response->set_error_message("Database error.");
                }
            }
            else
            {
                $response->set_http_code(RESPONSE_BAD_REQUEST);
                $response->set_error_code(ERROR_INVALID_DATA);
                $response->set_error_message("Missing param.");
            }
        }
        else if ($request->method == METHOD_GET)
        {
            if (count($request->actions) > 0)
            {
                $hostname = $request->actions[0];
                if ($hostname == 'all')
                {
                    $query = "select hostname, ip, max(data) as md, max(godzina) as mg from adresy group by hostname ORDER BY data DESC, godzina DESC;";
                    $result = $db->query($query);
                    if (is_array($result))
                    {
                        $d = array();
                        foreach ($result as $row)
                        {
                            $w = array(
                                'hostname' => $row['hostname'],
                                'ip' => $row['ip'],
                                'data' => $row['md'],
                                'godzina' => $row['mg']
                            );
                            $d[] = $w;
                        }
                        $response->set_data($d);
                    }
                    else
                    {
                        $response->set_http_code(RESPONSE_NOT_FOUND);
                        $response->set_error_code(ERROR_NO_DATA);
                        $response->set_error_message("No data at all.");
                    }
                }
                else if ($hostname != '')
                {
                    $query = "SELECT ip, data, godzina FROM adresy WHERE hostname='".$hostname."' ORDER BY data DESC, godzina DESC LIMIT 1";
                    $result = $db->query($query);
                    if (is_array($result))
                    {
                        $d = array(
                            'hostname' => $hostname,
                            'ip' => $result[0]['ip'],
                            'data' => $result[0]['data'],
                            'godzina' => $result[0]['godzina']
                        );
                        $response->set_data($d);
                    }
                    else
                    {
                        $response->set_http_code(RESPONSE_NOT_FOUND);
                        $response->set_error_code(ERROR_NO_DATA);
                        $response->set_error_message("No data for ".$hostname);
                    }
                }
                else
                {
                    $response->set_http_code(RESPONSE_BAD_REQUEST);
                    $response->set_error_code(ERROR_INVALID_DATA);
                    $response->set_error_message("Missing param.");
                }
            }
            else
            {
                $response->set_http_code(RESPONSE_BAD_REQUEST);
                $response->set_error_code(ERROR_INVALID_DATA);
                $response->set_error_message("Missing param.");
            }
        }
        else
        {
            $response->set_http_code(RESPONSE_METHOD_NOT_ALLOWED);
            $response->set_error_code(ERROR_NOT_ALLOWED);
            $response->set_error_message("Method not allowed.");
        }
        return $response;
    }
}

?>
