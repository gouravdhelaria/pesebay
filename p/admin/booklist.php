<?php
require_once( "../db.php" );
require_once( "../transaction.php" );
require_once( "../user.php" );
require_once( "../book.php" );
require_once( "../purchase.php" );

function getInventoryOverview()
{
    $db = Database::connect();
    $trans = Transaction::getAllApprovedTransactions( $db );
    $output = array();
    $ans = array();
    $count = 1;
    foreach( $trans as $t ){
    	if($count > 4)
    	 break;
        $tid = $count++;
        $book = Book::getBookById( $db, $t->getBookId() );
        $user = User::getUserById( $db, $t->getUserId() );
        
        $output[ "id" ] = $tid;
        $output[ "book" ] = $book->getName();
        $output[ "time" ] = $t->getTime();
        $output[ "seller" ] = $user->getName();
/*        if( $t->getSoldOut() == 0 ){
            if( $t->getInStore() == 0 )
                $status = "Yet to be approved";
            else
                $status = "Approved (yet to be sold)";
        }
        else
            $status = "Sold out";
*/
        $output[ "status" ] = $status;
        $output[ "mrp" ] = $book->getMarkedPrice();
        $output[ "sp" ] = $book->getSellingPrice();
        array_push( $ans, $output );
    }
    
	$statistics = "";
	foreach( $ans as $a ){
	$statistics .="<tr><td>".$a[ "id" ]."</td>";
	$statistics .="<td>".$a[ "book" ]."</td>";
	$originalDate = $a[ "time" ];
	$newDate = date("d-m-Y", strtotime($originalDate));
        $statistics .="<td>".$newDate."</td>";
	$statistics .="<td>".$a[ "seller" ]."</td>";
//	$statistics .="<td>".$a[ "status" ]."</td>";
	$statistics .="<td>".$output[ "mrp" ]."</td>";
	$statistics .="<td>".$output[ "sp" ]."</td></tr>";
	}
	return $statistics;
}

function getBookList(){

    $db = Database::connect();
    $trans = Transaction::getAllApprovedTransactions( $db );
    $output = array();
    $ans = array();
    $count = 1;
    foreach( $trans as $t ){
        $tid = $count++;
        $book = Book::getBookById( $db, $t->getBookId() );
        $user = User::getUserById( $db, $t->getUserId() );
        
        $output[ "id" ] = $book->getId();
        $output[ "book" ] = $book->getName();
        $output[ "authors" ] = Author::getAuthorByBookObj( $db, $book);
        $output[ "time" ] = $t->getTime();
        $output[ "seller" ] = $user->getName();
        $buyer = Purchase::getUserGivenBookId( $db, $book->getId() );
        if( $buyer != null )
            $output[ "buyer" ] = $buyer->getName();
        else
            $output[ "buyer" ] = "--";
/*        if( $t->getSoldOut() == 0 ){
            if( $t->getInStore() == 0 )
                $status = "Yet to be approved";
            else
                $status = "Approved (yet to be sold)";
        }
        else
            $status = "Sold out";
        $output[ "status" ] = $status;
  */
        $output[ "mrp" ] = $book->getMarkedPrice();
        $output[ "sp" ] = $book->getSellingPrice();
        $output[ "discount" ] = $output[ "mrp" ] - $output[ "sp" ];
        array_push( $ans, $output );
    }
    
	$statistics = "";
	$counter = 1;
	foreach( $ans as $a ){
//	if($counter > 10)
//	   break;
	$statistics .='<tr><!--<td><input type="checkbox" class="checkbox" id="' . $a["id"] .'" title="Select" /></td>--><td>'. $counter++ .'</td>';
	$statistics .="<td>".$a[ "id" ]."</td>";
	$statistics .="<td>".$a[ "book" ]."</td>";
	$statistics .="<td>".$a[ "authors" ]."</td>";
	$statistics .="<td>".$a[ "seller" ]."</td>";
	$statistics .="<td>".$a[ "buyer" ]."</td>";
//	$statistics .="<td>".$a[ "status" ]."</td>";
	$originalDate = $a[ "time" ];
	$newDate = date("d-m-Y", strtotime($originalDate));
        $statistics .="<td>".$newDate."</td>";
	$statistics .="<td>".$a[ "mrp" ]."</td>";
	$statistics .="<td>".$a[ "sp" ]."</td>";
	$statistics .="<td><a href='view.php?q=bookdetails&bid=" . $a["id"] . "' title='View Book Details'>View Details</a> <!--| <a href='edit.php?q=bookdetails&bid=" . $a["id"] . "' title='Edit Book Details'>Edit</a>--></td></tr>";
	}
	$booklist = "";
	$booklist =<<<END
	    <script type="text/javascript">
	    	function deleteBook(){
	    		
	    	}
	    </script>
	    <div class="span12" style="margin-bottom:40px;">
	   <!-- 	  <div class="row" style="margin-top:20px;">
	    	  	<div class="span3 offset10">
				<button class="btn btn-success btn-small" id="insertBook" onclick="window.location='edit.php?q=insertBook'">Insert</button>
				<button class="btn btn-danger btn-small" id="deleteBook" onclick="deleteBook();">Delete</button>
			</div>
		  </div>-->

		  <hr />
		  <div class="row">
		      <table class="table table-striped table-condensed" style="margin-bottom:40px;">
			<legend>Inventory Details</legend>
			<thead>
				<tr>
			<!--	  <th><input type="checkbox" id="selectall" title="Select All"/></th>-->
				  <th>Sl No.</th>
				  <th>Book ID</th>
				  <th>Book</th>
				  <th>Author</th>
				  <th>Seller</th>
				  <th>Buyer</th>
				  <!--<th>Status</th>-->
				  <th>Date Added</th>
				  <th>Marked Price</th>
				  <th>Selling Price</th>
				  <!--<th>Discount</th>-->
				  <th>Action</th>
				</tr>
			</thead>
			<tbody>
END;
$booklist .= $statistics."</tbody></table>";
$booklist .=<<<END
			<!--<div class="pagination pagination-small">
			    <ul>
				    <li><a href="#">Prev</a></li>
  				    <li><a href="#">1</a></li>
				    <li><a href="#">2</a></li>
				    <li><a href="#">3</a></li>
 				    <li><a href="#">4</a></li>
				    <li><a href="#">Next</a></li>
			    </ul>
		        </div>-->
		      </div><!-- end of row -->
		    </div><!-- end of span12 -->
END;
	return $booklist;
}

function getViewBookTable($bookId){

    $db = Database::connect();
    $trans = Transaction::getApprovedTransaction( $db , $bookId);
    $output = array();
    $ans = array();
    $count = 1;
    foreach( $trans as $t ){
	$tid = $count++;
	$book = Book::getBookById( $db, $t->getBookId() );
	$user = User::getUserById( $db, $t->getUserId() );
	
	$output[ "id" ] = $book->getId();
	$output[ "book" ] = $book->getName();
	$output[ "authors" ] = Author::getAuthorByBookObj( $db, $book);
	$output[ "time" ] = $t->getTime();
	$output[ "seller" ] = $user->getName();
	$buyer = Purchase::getUserGivenBookId( $db, $book->getId() );
	if( $buyer != null )
	    $output[ "buyer" ] = $buyer->getName();
	else
	    $output[ "buyer" ] = "--";
	$output[ "mrp" ] = $book->getMarkedPrice();
	$output[ "sp" ] = $book->getSellingPrice();
	$output[ "discount" ] = $output[ "mrp" ] - $output[ "sp" ];
	array_push( $ans, $output );
    }

	foreach( $ans as $a ){
	
	$name = $a["book"];
	$authors = $a["authors"];
	$seller = $a["seller"];
	$buyer = $a["buyer"];
//	$status = "";
	$originalDate = $a[ "time" ];
	$date = date("d-m-Y", strtotime($originalDate));
	$mrp = $a["mrp"];
	$sp = $a["sp"];
	$discount = $a["discount"];


	$table = "";
	$table .=<<<END
	    <form class="form-horizontal">
		    <fieldset>
			    <legend>View Book Details</legend>
     			    <div class="control-group">
			    	<label class="control-label">Book ID</label>
			    	<div class="controls">
				    	<label class="control-label">$bookId</label>
			    	</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label">Book Name</label>
			    	<div class="controls">
				    	<label class="control-label">$name</label>
			    	</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label">Authors</label>
			    	<div class="controls">
				    	<label class="control-label">$authors</label>
			    	</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label">Seller</label>
			    	<div class="controls">
				    	<label class="control-label">$seller</label>
			    	</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label">Buyer</label>
			    	<div class="controls">
				    	<label class="control-label">$buyer</label>
			    	</div>
			    </div>
<!--     			    <div class="control-group">
			    	<label class="control-label">Status</label>
			    	<div class="controls">
				    	<label class="control-label">$status</label>
			    	</div>
			    </div>-->
     			    <div class="control-group">
			    	<label class="control-label">Date Added</label>
			    	<div class="controls">
				    	<label class="control-label">$date</label>
			    	</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label">Marked Price</label>
			    	<div class="controls">
				    	<label class="control-label">$mrp</label>
				</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label">Selling Price</label>
			    	<div class="controls">
				    	<label class="control-label">$sp</label>
				</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label">Discount</label>
			    	<div class="controls">
				    	<label class="control-label">$discount</label>
				</div>
			    </div>
		    </fieldset>
	    </form>
END;
	}
	return $table;
}


function getEditBookForm($bookId){
	$form = "";
	$form .=<<<END
	    <form class="form-horizontal">
		    <fieldset>
			    <legend>Edit Book Details</legend>
     			    <div class="control-group">
			    	<label class="control-label" for="bookName">Book Name</label>
			    	<div class="controls">
			     		<input type="text" id="bookName">
			    	</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label" for="bookName">Author</label>
			    	<div class="controls">
			     		<input type="text" id="author">
			    	</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label" for="seller">Seller</label>
			    	<div class="controls">
			     		<input type="text" id="seller">
			    	</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label" for="buyer">Buyer</label>
			    	<div class="controls">
			     		<input type="text" id="buyer">
			    	</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label" for="status">Status</label>
			    	<div class="controls">
			     		<select id="status">
			     			<option value="0">Yet to be approved</option>
			     			<option value="1">Approved (yet to be sold)</option>
			     		</select>
			    	</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label" for="date">Date Added</label>
			    	<div class="controls">
			     		<input type="datetime" id="date">
			    	</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label" for="mp">Marked Price</label>
			    	<div class="controls">
					<div class="input-prepend">
						<span class="add-on">INR</span>
						<input class="span2" id="mp" type="text" />
					</div>			    	
				</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label" for="sp">Selling Price</label>
			    	<div class="controls">
					<div class="input-prepend">
						<span class="add-on">INR</span>
						<input class="span2" id="sp" type="text" />
					</div>			    	
				</div>
			    </div>
			    <button type="submit" class="offset6 btn-success">Update</button>
		    </fieldset>
	    </form>
END;
	return $form;
}

function getInsertBookForm(){
	$form = "";
	$form .=<<<END
	    <form class="form-horizontal">
		    <fieldset>
			    <legend>Insert Book Details</legend>
     			    <div class="control-group">
			    	<label class="control-label" for="bookName">Book Name</label>
			    	<div class="controls">
			     		<input type="text" id="bookName">
			    	</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label" for="bookName">Author</label>
			    	<div class="controls">
			     		<input type="text" id="author">
			    	</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label" for="seller">Seller</label>
			    	<div class="controls">
			     		<input type="text" id="seller">
			    	</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label" for="buyer">Buyer</label>
			    	<div class="controls">
			     		<input type="text" id="buyer">
			    	</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label" for="status">Status</label>
			    	<div class="controls">
			     		<select id="status">
			     			<option value="0">Yet to be approved</option>
			     			<option value="1">Approved (yet to be sold)</option>
			     		</select>
			    	</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label" for="date">Date Added</label>
			    	<div class="controls">
			     		<input type="datetime" id="date">
			    	</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label" for="mp">Marked Price</label>
			    	<div class="controls">
					<div class="input-prepend">
						<span class="add-on">INR</span>
						<input class="span2" id="mp" type="text" />
					</div>			    	
				</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label" for="sp">Selling Price</label>
			    	<div class="controls">
					<div class="input-prepend">
						<span class="add-on">INR</span>
						<input class="span2" id="sp" type="text" />
					</div>			    	
				</div>
			    </div>
			    <button type="submit" class="offset6 btn-success">Insert</button>
		    </fieldset>
	    </form>
END;
	return $form;

}


?>
