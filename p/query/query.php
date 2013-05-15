<?php
require_once( "simple_html_dom.php" );

abstract class Query{
    protected $url = "", 
              $memory = array(),
              $cache = false;
            
    
    public function __construct( $location, $c ){
        $this->url = $location;
        if( $c )
            $this->cache = true;        
    }
    
    abstract public function query( $keyword );    
    function load(){
        if( file_exists( ".cache" ) ){
            $file = fopen( ".cache", "r" );
            if( $file ){
                while( !feof( $file ) ){
                    $line = fgets( $file );
                    if( ( $line = trim( $line ) ) ){
                        list( $key, $obj ) = explode( "~!", $line );
                        $this->memory[ $key ] = preg_replace( "/(-~-)/"," ", $obj );
                    }
                }
                fclose( $file );
            }
        }
    }
}
