<?php
session_start();
$book_id = $_POST[ "id" ];
for( $i = 0; $i < count( $_SESSION["cart"] ); $i++ ){
	if( $_SESSION[ "cart"][$i] == $book_id ){
		$_SESSION[ "cart"][$i] = null;
		session_write_close();
		echo '{ "status":true }';
		break;
	}
}
?>