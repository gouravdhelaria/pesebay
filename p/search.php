<?php
/*
    Pending Works by siddu:
            1. When input box is empty, do not send the ajax call.
            2. When there are no recommendations/results then show appropriate message.
    Our work:
        1.If a new user comes and queries the existing book then recommend him similar items based on the current query.
        2.Change searching books from Book table to Transaction table.
*/
require_once( "db.php" );
require_once( "book.php" );
require_once( "helper.php" );
require_once( "search_history.php" );
require_once( "recommendation.php" );

$new_user = true;
$identity = "";

if ( !$_COOKIE['pesebay'] ){
    $identity = md5(time()) ;
    $db = Database::connect();
    try {
        $sql = "insert into AnonymousUserTable (hash) values (:h)";
        $stmt = $db->prepare( $sql );
        $stmt->bindValue( ":h", $identity, PDO::PARAM_STR );        
        $stmt->execute();
        setCookie( "pesebay" , $identity , time()+365*24*60*60, "/", "", false, true );   
    }
   catch ( PDOException $e ) {
    }
}  
else {
   //echo "old user";
   $identity = $_COOKIE['pesebay'];
   $new_user = false;
   //echo "<br />".$identity;
}

if( isset( $_GET[ "submit"] ) || true ){
	$book_title = $_GET[ "q" ];
	if(trim($book_title) == "")
	{
		header("Location: home.php");
	}

	$db = Database::connect();
	$books = Book::getBooksByTitle( $db, $book_title );
    
	$authors = Author::getAuthorByBook( $db, $books );
	$ids = array();
	if ( count($books) > 0){
	    increment( $db, $books[ 0 ]->getId() );
	    $sh = new SearchHistory( $db, array ( "hash" => $identity, "bid" => $books[0]->getId()) );
	    $sh->insert();
        $it = new ItemBasedRecommendation( $db, array ( "similarity_metric" => JAC ));
  //      echo "obj instantiated.\n";
        $rec = $it->recommend( $identity );
   //     echo "rec done\n";
        foreach( $rec as $key => $val ){
            $ids[] = Book::getBookById( $db, $key )->getName();
        }
     //   print_r( $ids );
	}
	$init_string = getHTML();
	$content = generateBookInfoHTML( $books, $authors, true, $recos = $ids);
	echo $init_string.$content."<script type='text/javascript'>$('#id_q').val('$book_title')</script></body></html>";
	
}
function increment( $db, $id ){
    $sql = "select count from BookSearchCount where book_id = :id";
    try{
        $stmt = $db->prepare( $sql );
        $stmt->bindValue( ":id", $id, PDO::PARAM_INT );
        $stmt->execute();
        $count = $stmt->fetch( PDO::FETCH_ASSOC );
        $sql = "update BookSearchCount set count = :c where book_id = :id";
        $stmt = $db->prepare( $sql );
        $count = $count[ "count" ] + 1;
        $stmt->bindValue( ":c", $count, PDO::PARAM_INT );
        $stmt->bindValue( ":id", $id, PDO::PARAM_INT );
        $stmt->execute();
    }
    catch( PDOException $e ){
        echo $e->getMessage()."\n";        
    }
}
?>
