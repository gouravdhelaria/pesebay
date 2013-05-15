<?php
require_once( "amazon_book_query.php" );
require_once( "tags.php" );
require_once( "db.php" );

$q = new AmazonBookQuery( "http://isbndb.com/api/books.xml?access_key=ZXI4YGN9&results=texts,subjects&index1=isbn&value1=", true );
$db = Database::connect();

try{
    $sql = "select book_id, isbn from Book";
    $isql = "insert into BookSearchCount ( book_id, count ) values ( :_1, :_2 )";
    $wsql = "insert into BookTag ( book_id, tag ) values ( :_1, :_2 )";
    
    $stmt = $db->prepare( $sql );
    $istmt = $db->prepare( $isql );
    $wstmt = $db->prepare( $wsql );
    $stmt->execute();
    $before = memory_get_usage();
    while( ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) ){
        //echo "loop entered\n";
        $isbn = $row[ "isbn" ];
        $q = $q->query( $isbn );
        $after = memory_get_usage();
        $tags = getTags( $q->getBook() );
        
        // insert into the book search count table.
        $count = rand( 1, 100 );
        $istmt->bindValue( ":_1", $row[ "book_id" ], PDO::PARAM_INT );
        $istmt->bindValue( ":_2", $count, PDO::PARAM_INT );
        $istmt->execute();
        
        // insert tags into BookTag table.
        $wstmt->bindValue( ":_1", $row[ "book_id" ], PDO::PARAM_INT );
        foreach( $tags as $tag ){
            $wstmt->bindValue( ":_2", $tag, PDO::PARAM_STR );
            $wstmt->execute();
        }
        echo $row[ "book_id" ]." Done\n";
    }
    Database::close( $db );
}
catch( PDOException $e ){
    echo $e->getMessage()."\n";
}
?>
