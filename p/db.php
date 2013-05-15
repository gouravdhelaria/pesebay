<?php
require_once( "logger.php" );
/*define( "HOST_NAME", "localhost" );
define( "DB_NAME", "pesebay_temp" );
define( "USER_NAME", "root" );
define( "PASSWORD", "root" );*/

define( "path", "../cred/credentials.txt" );


class Database{
    public static function connect(){
        $auth = self::getCredentials();
        $dsn = "mysql:host=".$auth[ "HOST_NAME" ].";dbname=".$auth[ "DB_NAME" ];
        try{
            $conn = new PDO( $dsn, $auth[ "USER_NAME" ], $auth[ "PASSWORD" ] );
            $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            return $conn;
        }
        catch( PDOException $e ){
            // need to log this message.
            Logger::log( "Connection to database failed: ". $e->getMessage(), "23" );
        }
    }
    public static function close( &$conn ){
        $conn = null;
    }
    public static function getCredentials(){
        // need to get this data from file.
        $auth = array();
        if( file_exists( path ) ){
            $handle = fopen( path, "r" );
            while( ( $line = fgets( $handle ) ) ){
                list( $key, $value ) = explode( ":", $line );
                $auth[ trim( $key ) ] = trim( $value );
            }
        }
        return $auth;
        //return array( "HOST_NAME" => HOST_NAME, "DB_NAME" => DB_NAME, "USER_NAME" => USER_NAME, "PASSWORD" => PASSWORD );
    }
}
?>
