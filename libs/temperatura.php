<?php

require_once('libs/module.php');
require_once('libs/response.php');

require_once('libs/db.php');

class temperatura extends ApiHubModule
{
    function process($request)
    {
        $db = DB::get_instance();
        $response = new Response();
        if ($request->method == METHOD_PUT)
        {
            if (count($request->actions) > 0)
            {
                $pomieszczenie = $request->actions[0];
                if ($pomieszczenie != '')
                {
                    if (is_array($request->data))
                    {
                        if (isset($request->data['temperatura']))
                        {
                            $temperatura = $request->data['temperatura'];
                            if ($temperatura != '')
                            {
                                $data = date("Y-m-d");
                                $godzina = date("H:i:s");
                                $query = "INSERT INTO temperatura (data, godzina, pomieszczenie, temperatura) VALUES('".$data."', '".$godzina."', '".$pomieszczenie."', '".$temperatura."')";
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
                                $response->set_error_message("Missing param");
                                $response->set_data(null);
                            }
                        }
                        else
                        {
                            $response->set_http_code(RESPONSE_BAD_REQUEST);
                            $response->set_error_code(ERROR_INVALID_DATA);
                            $response->set_error_message("Missing param");
                            $response->set_data(null);
                        }
                    }
                    else
                    {
                        $response->set_http_code(RESPONSE_BAD_REQUEST);
                        $response->set_error_code(ERROR_INVALID_DATA);
                        $response->set_error_message("Missing param");
                        $response->set_data(null);
                    }
                }
                else
                {
                    $response->set_http_code(RESPONSE_BAD_REQUEST);
                    $response->set_error_code(ERROR_INVALID_DATA);
                    $response->set_error_message("Missing param");
                    $response->set_data(null);
                }
            }
            else
            {
                $response->set_http_code(RESPONSE_BAD_REQUEST);
                $response->set_error_code(ERROR_INVALID_DATA);
                $response->set_error_message("Missing param");
                $response->set_data(null);
            }
        }
        else if ($request->method == METHOD_GET)
        {
            if (count($request->actions) > 0)
            {
                $pomieszczenie = $request->actions[0];
                $query = 'SELECT data, godzina, temperatura FROM temperatura WHERE pomieszczenie="'.$pomieszczenie.'" ORDER BY data DESC, godzina DESC';
                $result = $db->query($query);
                if ($result)
                {
                    $data = $result[0]['data'];
                    $godzina = $result[0]['godzina'];
                    $temperatura = $result[0]['temperatura'];
                    $d = array(
                        'data' => $data,
                        'godzina' => $godzina,
                        'temperatura' => $temperatura,
                        'pomieszczenie'=> $pomieszczenie
                    );
                    $response->set_data($d);
                }
                else
                {
                    $response->set_error_code(ERROR_NO_DATA);
                    $response->set_error_message("No data for ".$pomieszczenie);
                }
            }
            else
            {
                $response->set_http_code(RESPONSE_BAD_REQUEST);
                $response->set_error_code(ERROR_INVALID_DATA);
                $response->set_error_message("Missing param");
                $response->set_data(null);
            }
        }
        else
        {
            $response->set_http_code(RESPONSE_METHOD_NOT_ALLOWED);
            $response->set_error_code(ERROR_NOT_ALLOWED);
            $response->set_error_message("Method not allowed");
            $response->set_data(null);
        }
        return $response;
    }
}
