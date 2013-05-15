<?php
session_start();
require_once( "logger.php" );
require_once( "db.php" );
require_once( "book.php" );
require_once( "helper.php" );

$in_cart = false;
if( !isset( $_SESSION[ "cart"] ) )
	$_SESSION[ "cart"] = array();
$book_id = $_POST[ "id" ];
$db = Database::connect();
$book = Book::getBookById( $db, $book_id );
$books = array();
$id = null;

for( $i = 0; $i < count( $_SESSION[ "cart" ] ); $i++ ){
    $id = $_SESSION[ "cart" ][ $i ];
    if( $id == $book_id )
        $in_cart = true;
	if(  $id != null )
		array_push( $books, Book::getBookById( $db, $_SESSION[ "cart" ][ $i ] ) );
}
if( !$in_cart ){
    array_push( $books, $book );
    array_push( $_SESSION[ "cart" ], $book_id );
}

session_write_close();


$authors = Author::getAuthorByBook( $db, $books );
echo generateBookInfoHTML( $books, $authors, false );

?>
