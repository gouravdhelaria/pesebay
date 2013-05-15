<?php
require_once("db.php");
require_once("transaction.php");
$db = Database::connect();
if(isset($_POST['update'])) {
	//echo $_POST['usn']."<br />". $_POST['tid'];
	//echo "here";
	if(isset($_POST['id'])) {
		echo "here";
		$idarray = $_POST['id'];
		for($i =0 ; $i < count( $idarray); $i++ ) {
			$id = $idarray[$i];
			Transaction::updateInstore( $db,$_POST['tid'], $id );	
		}

		echo "<html><head></head><body>Transaction complete";
		echo "<a href='instore.php'>". "Back to form"."</a>";
		echo "</body></html>";
	}
	else {
		echo "<html><head></head><body> No books were selected";
		echo "<a href='instore.php'>". "Back to form"."</a>";
		echo "</body></html>";
 	}
}

else {
		echo "<html><head></head><body> No books were selected";
		echo "<a href='instore.php'>". "Back to form"."</a>";
		echo "</body></html>";
}
?>		
