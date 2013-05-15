<?php
require_once( "query.php" );
require_once( "nbook.php" );
require_once( "tags.php" );


class AmazonBookQuery extends Query{
    private $book = null,
            $editorial_review = null,
            $user_review = null,
            $obj = null;
    public function __construct( $url, $cache ){
        parent::__construct( $url, $cache );
        parent::load();
        $this->obj = $this;
    }
    public function query( $keyword, $force_load = false ){    
        $link = $this->url.$keyword;
        if( array_key_exists( $link, $this->memory ) && !$force_load ){        
            $this->book = unserialize( $this->memory[ $link ] );
        }
        else{
            $html = simplexml_load_file( $link );
            // dom created.
            $this->book = new NBook( $html );
            if( $this->cache ){
//            $b = memory_get_usage();
                $this->memory[ $link ] = serialize( $this->book );
  //          $a = memory_get_usage();
    //        $usage = ( $a - $b ) / ( 1024 * 1024 );
      //      echo "usage: ".$usage."\n";
            }
            $html = null;
        }
        return $this;
    }
    function __destruct(){
        $file = fopen( ".cache", "w" );
        if( $file ){
            foreach( $this->obj->memory as $key => $value ){
                $value = preg_replace( "/\s+/", "-~-", $value );
                fwrite( $file, $key."~!".$value."\n" );
            }
            fclose( $file );
        }
    }
    function getEditorialReview(){
        return $this->editorial_review;
    }
    function getUserReview(){
        return $this->user_review;
    }
    function getBook(){
        return $this->book;
    }
}
/*
$obj = new AmazonBookQuery( "http://isbndb.com/api/books.xml?access_key=ZXI4YGN9&results=texts,subjects&index1=isbn&value1=", true );

$obj = $obj->query( "1565926102" );
//$obj = $obj->query( "0521513383" );

$book = $obj->getBook();
//$book1 = $obj->query( "0672325616" )->getBook();
echo $book->getDescription()."\n";
print_r( $book->getSubjects() );//."\n";
/*
print_r( getTags( $book ) );
print_r( getTags( $book1 ) );

print_r( intersect( getTags( $book ), getTags( $book1 ) ) );
*/
