<?php
include("db.php");

if ( !$_COOKIE['pesebay'] ){
    $hash = md5(time()) ;
    $db = Database::connect();
    try {
        $sql = "insert into AnonymousUserTable (hash) values (:h)";
        $stmt = $db->prepare( $sql );
        $stmt->bindValue( ":h", $hash, PDO::PARAM_STR );        
        $stmt->execute();
        setCookie( "pesebay" , $hash , time()+365*24*60*60, "/", "", false, true );
        echo "New user";   
    }
   catch ( PDOException $e ) {
    }
}  
else {
   echo "old user";
   $identity = $_COOKIE['pesebay'];
   echo "<br />".$identity;
} 

