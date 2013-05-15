<?php
require_once('db.php');

$db = Database::connect();
$curl = curl_init();
$header = array(
        "Accept: text/xml,application/xml,application/xhtml+xml,
        text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5",
        "Accept-Language: ru-ru,ru;q=0.7,en-us;q=0.5,en;q=0.3",
        "Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.7",
        "Keep-Alive: 300");
$options = array
(
    CURLOPT_URL=>$url,
    CURLOPT_HEADER=>true,
    CURLOPT_RETURNTRANSFER=>true,
    CURLOPT_FOLLOWLOCATION=>true,
    CURLOPT_USERAGENT=>$browser_id
);
curl_setopt_array($curl,$options);

for( $i = 1; $i<=200;$i++){
    $rand_num = rand(2,51);
    $sql = "select name from Book where book_id = :id ";
    try {
        $stmt = $db->prepare( $sql );
        $stmt->bindValue(":id",$rand_num,PDO::PARAM_INT);
        $stmt->execute();
        if ( $row = $stmt->fetch( PDO::FETCH_ASSOC )) {
            $book_name = $row["name"];
        }   
    }
    catch ( PDOException $e ) {
        echo( " Exception ");
    }
    $book_name =  urlencode( $book_name );
    $url = 'http://localhost/pesebay/p/search.php?q='.$book_name.'&submit=search';
    curl_setopt($curl, CURLOPT_URL, $url );
    $response = curl_exec($ch); 
    list($header, $body) = explode("\r\n\r\n", $response, 2);
    if(!curl_exec($curl)){
       die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
    }
    echo $url. "\n\n";
    //sleep(5);
    if ( $i % 10 == 0) {
        
    }
}
curl_close($curl);
  
