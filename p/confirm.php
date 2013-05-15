<?php
require_once( "logger.php" );
require_once("db.php");
require_once( "purchase.php" );
require_once("confirmation.php");
require_once("transaction.php");

$db = Database::connect();

if(isset($_GET["k"]) ){
	$key = $_GET["k"];

	$purchasearray = Confirmation::getPurchaseInfo( $db, $key );		
	for($i=0; $i < count($purchasearray);$i++){
		//echo $purchasearray[ $i ][ "book_id" ];
		$purchase = new Purchase ( $db, array( "uid" => $purchasearray[ $i ][ "uid" ],
                                               "book_id" => $purchasearray[ $i ][ "book_id" ] ) );
        $user_id = $purchasearray[ $i ]["uid"];
        $book_id = $purchasearray[ $i ][ "book_id" ];
        Transaction::soldout ( $db, $book_id );
         
        //gourav   
		Confirmation::setCBit( $db, $key , $user_id , $book_id );
		$purchase->save();
        Confirmation::delete( $db, $key , $user_id , $book_id );
	}

	//header( "Location:../h/confirm.html");
}
else {
	echo "Transaction not yet confirmed";
}
