<?php

require_once("settings.php");

class DB
{
    static private $connection;

    static function get_instance()
    {
        if (DB::$connection == null)
        {
            mysql_pconnect(HOSTNAME, USERNAME, PASSWORD);
            mysql_select_db(DATABASE);
            DB::$connection = new DB();
        }
        return DB::$connection;
    }

    private function __construct() {}

    function connect()
    {
    }

    function query($query)
    {
        // Assoc query
        $result = mysql_query($query);
        $response = array();
        while ($row = mysql_fetch_assoc($result))
        {
            $response[] = $row;
        }
        return $response;
    }

    function query_r($query)
    {
        // Raw query
        return mysql_query($query);
    }
}

?>
