<?php
require_once( "db.php" );
require_once( "book.php" );

$term = $_GET[ "term" ];
$db = Database::connect();
$books = Book::getBookNameArray( $db, $term );
echo $books;
?>