<?php
define( "log_path", "log.txt" );

class Logger{
    private static $log_handle = null;
    public static function log( $message, $line_no, $time = null ){
        if( self::$log_handle == null )
            self::open();
        fwrite( self::$log_handle, $line_no.": ".$message."\n" );        
    }
    public static function close(){
        fclose( self::$log_handle );
    }
    public static function open(){
        self::$log_handle = fopen( log_path, "a" );
    }
}

?>
