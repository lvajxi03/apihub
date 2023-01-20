<?php

require_once("settings.php");

class DB
{
    static private $connection;
    var $mysqli;
    static function get_instance()
    {
        if (DB::$connection == null)
        {
            DB::$connection = new DB();
            DB::$connection->mysqli = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
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
        $result = $this->mysqli->query($query);
        if ($result)
        {
            $response = array();
            while ($row = $result->fetch_assoc())
            {
                $response[] = $row;
            }
            return $response;
        }
        return false;
    }

    function query_r($query)
    {
        // Raw query
        return $this->mysqli->query($query);
    }
}

?>
