<?php
require_once( "db.php" );
$db = Database::connect();
for ( $i=1;$i<=5;$i++){
$curl = curl_init();
$header = array(
        "Accept: text/xml,application/xml,application/xhtml+xml,
        text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5",
        "Accept-Language: ru-ru,ru;q=0.7,en-us;q=0.5,en;q=0.3",
        "Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.7",
        "Keep-Alive: 300");
        $cookie = null;
echo "User: ".$i."\n";
for( $j = 1; $j<=10;$j++){
    $rand_num = rand(2,51);
    $sql = "select name from Book where book_id = :id ";
    try {
        $stmt = $db->prepare( $sql );
        $stmt->bindValue(":id",$rand_num,PDO::PARAM_INT);
        $stmt->execute();
        if ( $row = $stmt->fetch( PDO::FETCH_ASSOC )) {
            $book_name = $row["name"];
        }
        $book_name =  urlencode( $book_name );
        $url = 'http://localhost/pesebay/p/search.php?q='.$book_name.'&submit=search';
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $curl, CURLOPT_HEADER, $header );
        curl_setopt($curl, CURLOPT_URL, $url );
        setCookie_( $curl , $cookie );
        $response = curl_exec($curl);
        //$info = curl_getinfo( $curl );
        //echo "\nHeader: ".$info[ "request_header" ]."\n";
        //sleep( 6 );
        $cookie = parse ( $response );
        //echo "\nCookie: $cookie\n";
        echo "\t$book_name, processed\n";
    }
    catch ( PDOException $e ) {
        echo( " Exception ");
    }
}    
echo "User: ".$i." Done\n";
curl_close($curl);
sleep( 1 );
}
function setCookie_ ( &$curl, $cookie ){
    if ( $cookie != null ) {
        curl_setopt ($curl, CURLOPT_COOKIE, $cookie);
    }
}

function parse ( $response ) {
    list( $header, $body ) = explode("\r\n\r\n", $response );
    preg_match( "/Set-Cookie:(.*?)\r\n/", $header, $match );
    if( count( $match ) > 1 )
        return $match[ 1 ];
    return null;
}
?>
