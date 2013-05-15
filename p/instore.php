<?php
require_once("db.php");
require_once("book.php");
require_once("transaction.php");
require_once("user.php");

$db = Database::connect();

$htmlstr = "<html>";	
$htmlstr .= "<head><title>Books.inherit()</title><style></style></head>";
$htmlstr .=<<<END
<body><form id='instoreQuery' action='instore.php' method='post' >
	<input name='tid' type='text' placeholder='Enter transaction id'  required/>	
    <input type='submit' value='submit' name='submit'/> </form>
END;

if(!isset($_POST['submit']))
	echo $htmlstr."</body></html>";
else {
	$htmlstr.= "<div id='results'>";
	$bookidlist = Transaction::getBookIds( $db, $_POST['tid']);	
	$htmlstr .= "<form id='instore' action='updateinstore.php' method='post' >" ;
	$htmlstr .= "<table><tbody>";
	for( $i = 0; $i < count($bookidlist); $i++ ){
		$id = $bookidlist[ $i ];
		$htmlstr .= "<tr><td>"."<input type='select' name='id[]' value='". $id."' /></td>";
		$book = Book::getBookById( $db, $id );
        $user_id = Transaction::getTransactionUser( $db,  $id );  
        $user = User::getUserById( $db, $user_id );
		$htmlstr .= "<td>".$book->getName()."</td>"."<td>".implode( ",", Author::getAuthorByBook( $db, array($book) ))."</td>";
        $htmlstr .= "<td>".$book->getPublication()."</td>";
		$htmlstr .= "<td>".$book->getEdition()."</td>";
        $htmlstr .= "<td>".$book->getId()."</td>";
        $htmlstr .= "<td>".$user->getName()."</td>";
        $htmlstr .= "<td>".$user->getUsn()."</td>";
        $htmlstr .= "<td>".$user->getEmail()."</td>";
		$htmlstr .= "</tr>";
	}
	$htmlstr .= "</tbody></table>";
	$htmlstr .= "<input type='hidden' name='tid' value='".$_POST['tid']."'/>";	
	$htmlstr .= "<input type='hidden' name='usn' value='".$_POST['usn']."'/>";
	$htmlstr .= "<input type='submit' value='update' name='update'/>";
	$htmlstr .= "</form></div></body></html>";
	echo $htmlstr;
}
	                     
?>

