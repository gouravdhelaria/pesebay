<?php
require_once( "../db.php" );
require_once( "../transaction.php" );
require_once( "../user.php" );
require_once( "../book.php" );
require_once( "../purchase.php" );

function getInventoryOverview()
{
    $db = Database::connect();
    $trans = Transaction::getAllTransactions( $db );
    $output = array();
    $ans = array();
    $count = 1;
    foreach( $trans as $t ){
        $tid = $count++;
        $book = Book::getBookById( $db, $t->getBookId() );
        $user = User::getUserById( $db, $t->getUserId() );
        
        $output[ "id" ] = $tid;
        $output[ "book" ] = $book->getName();
        $output[ "time" ] = $t->getTime();
        $output[ "seller" ] = $user->getName();
        if( $t->getSoldOut() == 0 ){
            if( $t->getInStore() == 0 )
                $status = "Yet to be approved";
            else
                $status = "Approved (yet to be sold)";
        }
        else
            $status = "Sold out";
        $output[ "status" ] = $status;
        $output[ "mrp" ] = $book->getMarkedPrice();
        $output[ "sp" ] = $book->getSellingPrice();
        array_push( $ans, $output );
    }
    
	$statistics = "";
	foreach( $ans as $a ){
	$statistics .="<tr><td>".$a[ "id" ]."</td>";
	$statistics .="<td>".$a[ "book" ]."</td>";
    $statistics .="<td>".$a[ "time" ]."</td>";
	$statistics .="<td>".$a[ "seller" ]."</td>";
	$statistics .="<td>".$a[ "status" ]."</td>";
	$statistics .="<td>".$output[ "mrp" ]."</td>";
	$statistics .="<td>".$output[ "sp" ]."</td></tr>";
	}
	return $statistics;
}

function getBookList(){

    $db = Database::connect();
    $trans = Transaction::getAllTransactions( $db );
    $output = array();
    $ans = array();
    $count = 1;
    foreach( $trans as $t ){
        $tid = $count++;
        $book = Book::getBookById( $db, $t->getBookId() );
        $user = User::getUserById( $db, $t->getUserId() );
        
        $output[ "id" ] = $book->getId();
        $output[ "book" ] = $book->getName();
        $output[ "authors" ] = implode( ",", array( "Bhimsen" ) );//Author::getAuthorByBook( $db, array( $book ) ) );
        $output[ "time" ] = $t->getTime();
        $output[ "seller" ] = $user->getName();
        $buyer = Purchase::getUserGivenBookId( $db, $book->getId() );
        if( $buyer != null )
            $output[ "buyer" ] = $buyer->getName();
        else
            $output[ "buyer" ] = "--";
        if( $t->getSoldOut() == 0 ){
            if( $t->getInStore() == 0 )
                $status = "Yet to be approved";
            else
                $status = "Approved (yet to be sold)";
        }
        else
            $status = "Sold out";
        $output[ "status" ] = $status;
        $output[ "mrp" ] = $book->getMarkedPrice();
        $output[ "sp" ] = $book->getSellingPrice();
        $output[ "discount" ] = $output[ "mrp" ] - $output[ "sp" ];
        array_push( $ans, $output );
    }
    
	$statistics = "";
	foreach( $ans as $a ){
	$statistics .='<tr><td><input type="checkbox" id="select_book1"/></td>';
	$statistics .="<td>".$a[ "id" ]."</td>";
	$statistics .="<td>".$a[ "book" ]."</td>";
	$statistics .="<td>".$a[ "authors" ]."</td>";
	$statistics .="<td>".$a[ "seller" ]."</td>";
	$statistics .="<td>".$a[ "buyer" ]."</td>";
	$statistics .="<td>".$a[ "status" ]."</td>";
    $statistics .="<td>".$a[ "time" ]."</td>";
	$statistics .="<td>".$output[ "mrp" ]."</td>";
	$statistics .="<td>".$output[ "sp" ]."</td>";
	$statistics .="<td>".$output[ "discount" ]."</td><td>Action</td></tr>";
	}
	$booklist = "";
	$booklist =<<<END
	    <div class="span12" style="margin-bottom:40px;">
	    	  <div class="row" style="margin-top:20px;">
	    	  	<div class="span3 offset10">
				<button class="btn btn-success btn-small">Insert</button>
				<button class="btn btn-danger btn-small">Delete</button>
			</div>
		  </div>

		  <hr />
		  <div class="row">
		      <table class="table table-striped table-condensed" style="margin-bottom:40px;">
			<legend>Inventory Details</legend>
			<thead>
				<tr>
				  <th><input type="checkbox" id="selectall_booklist"/></th>
				  <th>Book ID</th>
				  <th>Book</th>
				  <th>Author</th>
				  <th>Seller</th>
				  <th>Buyer</th>
				  <th>Status</th>
				  <th>Date Added</th>
				  <th>Marked Price</th>
				  <th>Selling Price</th>
				  <th>Discount</th>
				  <th>Action</th>
				</tr>
			</thead>
			<tbody>
END;
$booklist .= $statistics."</tbody></table>";
$booklist .=<<<END
			<div class="pagination pagination-small">
			    <ul>
				    <li><a href="#">Prev</a></li>
  				    <li><a href="#">1</a></li>
				    <li><a href="#">2</a></li>
				    <li><a href="#">3</a></li>
 				    <li><a href="#">4</a></li>
				    <li><a href="#">Next</a></li>
			    </ul>
		        </div>
		      </div><!-- end of row -->
		    </div><!-- end of span12 -->
END;
	return $booklist;
}

echo getInventoryOverview()."\n";
?>
