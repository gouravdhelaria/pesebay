<?php
require_once("../transaction.php");
if(isset($_POST['submit'])){
	
	$db = Database::connect();
	Transaction::soldout( $db, $_POST['bid']); 	
	echo "<html><body> Transaction successful";
	echo  "<form action='soldout.php' method='post'>";
	echo "<input type='text' name='bid' />";
	echo "<input type='submit' name='submit' />";
	echo "</form>";
	echo "</body></html>";
}

else {
	echo "<html><body>";
	echo  "<form action='soldout.php' method='post'>";
	echo "<input type='text' name='bid' />";
	echo "<input type='submit' name='submit' />";
	echo "</form>";
	echo "</body></html>";
}

?>
