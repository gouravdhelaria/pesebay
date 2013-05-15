<?php

class EditorialReview{
    private $description = "";
    
    public function __construct( $dom ){
        if( is_object( $dom ) ){
            $ed_revs = $dom->find( "div#productDescription > div.content div" );
            foreach( $ed_revs as $rev ){
                $this->description = trim( $rev->plaintext ). "\n";
            }
        }
        else{
            throw new Exception( "parameter needs to be a html dom object with simple_html_dom object interfaces" );
        }        
    }
    public function getDescription(){
        return $this->description;
    }
    
}

class UserReview{
    private $description = "";
    
    public function __construct( $dom ){
        if( is_object( $dom ) ){
            $u_revs = $dom->find( "div.reviewText" );
            foreach( $u_revs as $rev ){
                $this->description = trim( $rev->plaintext ). "\n";
            }
        }
        else{
            throw new Exception( "parameter needs to be a html dom object with simple_html_dom object interfaces" );
        }
    }
    public function getDescription(){
        return $this->description;
    }
}
?>
