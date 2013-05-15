<?php
require_once( "db.php" );
$db = Database::connect();

$sql = "insert into Book ( name, edition,publication, discount, _condition,marked_price, selling_price ) values ( :name , 1, 'Wrox', 20, 2, 300,456)";
$stmt = $db->prepare( $sql );

// this returns lines as array.
$books = file( "../booklist.txt" );
foreach( $books as $book ){
    $book = rtrim( $book );
    $stmt->bindParam( ":name", $book, PDO::PARAM_STR );
    $stmt->execute();
    echo $book.": Inserted to BookTable.";
}
Database::close( $db );
?>
