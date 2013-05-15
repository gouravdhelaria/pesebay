<?php
require_once( "../db.php" );
require_once( "../transaction.php" );
require_once( "../user.php" );
require_once( "../book.php" );
require_once( "../purchase.php" );


function getApproveBookList(){

	$approve = "";
	$approve =<<<END
	    <div class="span12" style="margin-bottom:40px;">
	    	  <div class="row" style="margin-top:20px;">
	    	  	<div class="span3 offset10">
				<button class="btn btn-success btn-small" onclick="approveBooks();">Approve</button>
				<!--<button class="btn btn-danger btn-small onclick="disapproveBooks()">Disapprove</button> -->
			</div>
		    <form class="form-search form-horizontal" action="" method="get">
				<div class="control-group offset1">
					<label class="control-label" for="transid">Transaction ID: </label>
					<div class="controls">
						<input type="hidden" id="q" name="q" value="search">
						<input type="text" id="transid" name="transid" class="input-xxlarge search-query">
				         	<button type="submit" class="btn">Search</button>
					</div>
				</div>
		    </form>
		  </div>
		  <hr />
		  <div class="row">
		      <table class="table table-striped table-condensed" style="margin-bottom:40px;">
			<legend>Books Yet to be Collected</legend>
			<thead>
				<tr>
				  <th><input type="checkbox" id="selectall" title="Select All"/></th>
				  <th>Sl No.</th>
				  <th>Book ID</th>
				  <th>Transaction ID</th>
				  <th>Book</th>
				  <th>Date Added</th>
				  <th>Seller</th>
				  <th>Marked Price</th>
				  <th>Selling Price</th>
				  <!--<th>Discount</th>-->
				  <th>Action</th>
				</tr>
			</thead>			  
			<tbody>

END;
			$approve .= getApproveBooks();
			$approve.=<<<END
  			</tbody>
			</table>
			<!-- <div class="pagination pagination-small">
			    <ul>
				    <li><a href="#">Prev</a></li>
  				    <li><a href="#">1</a></li>
				    <li><a href="#">2</a></li>
				    <li><a href="#">3</a></li>
 				    <li><a href="#">4</a></li>
				    <li><a href="#">Next</a></li>
			    </ul>
		        </div> -->
		      </div><!-- end of row -->
		    </div><!-- end of span12 -->
END;
	return $approve;
}

function getApproveBooks()
{
    $db = Database::connect();
    $trans = Transaction::notApprovedBooks( $db );
    $output = array();
    $ans = array();
    $count = 1;
    foreach( $trans as $t ){
    	if($count > 10)
    	 break;
  	$output = array();
        $tid = $count++;
        $book = Book::getBookById( $db, $t->getBookId() );
        $user = User::getUserById( $db, $t->getUserId() );
        
        $output[ "id" ] = $t->getBookId();
        $output[ "book" ] = $book->getName();
        $output[ "time" ] = $t->getTime();
        $output[ "seller" ] = $user->getName();
 /*       if( $t->getSoldOut() == 0 ){
            if( $t->getInStore() == 0 )
                $status = "Yet to be approved";
            else
                $status = "Approved (yet to be sold)";
        }
        else
            $status = "Sold out";
   */
        $output[ "transid" ] = $t->getTransId();
        $output[ "mrp" ] = $book->getMarkedPrice();
        $output[ "sp" ] = $book->getSellingPrice();
        array_push( $ans, $output );
    }
    
	$statistics = "";
	$count = 1;
	foreach( $ans as $a ){
	$statistics .="<tr><td><input type='checkbox' title='Select' class='checkbox' id='" . $a[ "id" ] . "' transid='" . $a["transid"] . "'/></td><td>" . $count++ . "</td><td>".$a[ "id" ]."</td>";
	$statistics .="<td>".$a[ "transid" ]."</td>";
	$statistics .="<td>".$a[ "book" ]."</td>";
	$originalDate = $a[ "time" ];
	$newDate = date("d-m-Y", strtotime($originalDate));
	$statistics .="<td>".$newDate."</td>";
	$statistics .="<td>".$a[ "seller" ]."</td>";
	$statistics .="<td>".$a[ "mrp" ]."</td>";
	$statistics .="<td>".$a[ "sp" ]."</td>";
	$statistics .="<td><a href='view.php?q=approve&transid=" . $a["transid"] . "&bid=" . $a["id"] . "' title='View Book Details'>View Details</a> <!--| <a href='edit.php?q=approve&transid=" . $a["transid"] . "&bid=" . $a["id"] . "' title='Edit Book Details'>Edit</a>--></td></tr>";
	}
	return $statistics;


}

function getSearchBookList($transid){

	$approve = "";
	$approve =<<<END
	    <div class="span12" style="margin-bottom:40px;">
	    	  <div class="row" style="margin-top:20px;">
	    	  	<div class="span3 offset10">
				<button class="btn btn-success btn-small" onclick="approveBooks()">Approve</button>
				<button class="btn btn-danger btn-small" onclick="disapproveBooks()">Disapprove</button>
			</div>
		    <form class="form-search form-horizontal" action="" method="get">
				<div class="control-group offset1">
					<label class="control-label" for="transid">Transaction ID: </label>
					<div class="controls">
						<input type="hidden" id="q" name="q" value="search">
						<input type="text" id="transid" name="transid" class="input-xxlarge search-query">
				         	<button type="submit" class="btn">Search</button>
					</div>
				</div>
		    </form>
		  </div>
		  <hr />
		  <div class="row">
		      <table class="table table-striped table-condensed" style="margin-bottom:40px;">
			<legend>Transanction ID : $transid</legend>
			<thead>
				<tr>
				  <th><input type="checkbox" id="selectall" title="Select All"/></th>
				  <th>Sl No.</th>
				  <th>Book ID</th>
				  <th>Book</th>
				  <th>Date Added</th>
				  <th>Seller</th>
				  <th>Marked Price</th>
				  <th>Selling Price</th>
				  <th>Action</th>
				</tr>
			</thead>			  
			<tbody>

END;
			$approve .= searchBooks($transid);
			$approve.=<<<END
  			</tbody>
			</table>
			<!-- <div class="pagination pagination-small">
			    <ul>
				    <li><a href="#">Prev</a></li>
  				    <li><a href="#">1</a></li>
				    <li><a href="#">2</a></li>
				    <li><a href="#">3</a></li>
 				    <li><a href="#">4</a></li>
				    <li><a href="#">Next</a></li>
			    </ul>
		        </div> -->
		      </div><!-- end of row -->
		    </div><!-- end of span12 -->
END;
	return $approve;
}

function searchBooks($transid)
{
    $db = Database::connect();
    $trans = Transaction::searchBooks( $db, $transid );
    $output = array();
    $ans = array();
    $count = 1;
    foreach( $trans as $t ){
//    	if($count > 10)
  //  	 break;
  	$output = array();
        $tid = $count++;
        $book = Book::getBookById( $db, $t->getBookId() );
        $user = User::getUserById( $db, $t->getUserId() );
        
        $output[ "id" ] = $t->getBookId();
        $output[ "book" ] = $book->getName();
        $output[ "time" ] = $t->getTime();
        $output[ "seller" ] = $user->getName();
     /*   if( $t->getSoldOut() == 0 ){
            if( $t->getInStore() == 0 )
                $status = "Yet to be approved";
            else
                $status = "Approved (yet to be sold)";
        }
        else
            $status = "Sold out";
       */
//        $output[ "transid" ] = $t->getTransId();
        $output[ "mrp" ] = $book->getMarkedPrice();
        $output[ "sp" ] = $book->getSellingPrice();
        array_push( $ans, $output );
    }
    
	$statistics = "";
	$count = 1;
	foreach( $ans as $a ){
	$statistics .="<tr><td><input type='checkbox' class='checkbox' title='Select' id='" . $a[ "id" ] . "' transid='" . $a["transid"] . "'/></td><td>" . $count++ . "</td><td>".$a[ "id" ]."</td>";
//	$statistics .="<td>".$a[ "transid" ]."</td>";
	$statistics .="<td>".$a[ "book" ]."</td>";
	$originalDate = $a[ "time" ];
	$newDate = date("d-m-Y", strtotime($originalDate));
	$statistics .="<td>".$newDate."</td>";
	$statistics .="<td>".$a[ "seller" ]."</td>";
	$statistics .="<td>".$a[ "mrp" ]."</td>";
	$statistics .="<td>".$a[ "sp" ]."</td>";
	$statistics .="<td><a href='view.php?q=approve&transid=" . $transid . "&bid=" . $a["id"] . "' title='View Details'>View  Book Details</a> <!--| <a href='edit.php?q=approve&transid=" . $a["transid"] . "&bid=" . $a["id"] . "' title='Edit Book Details'>Edit</a>--></td></tr>";
	}
	return $statistics;


}

function getViewApproveBookTable($transId,$bid){

    $db = Database::connect();
    $trans = Transaction::searchBooks( $db, $transId );
    $output = array();
    $ans = array();
    $count = 1;
    foreach( $trans as $t ){
    	if($t->getBookId() == $bid){
	  	$output = array();
		$book = Book::getBookById( $db, $t->getBookId() );
		$user = User::getUserById( $db, $t->getUserId() );
		
		$output[ "id" ] = $t->getBookId();
		$output[ "book" ] = $book->getName();
		$output[ "time" ] = $t->getTime();
		$output[ "seller" ] = $user->getName();
		$output[ "transid" ] = $t->getTransId();
		$output[ "mrp" ] = $book->getMarkedPrice();
		$output[ "sp" ] = $book->getSellingPrice();
		array_push( $ans, $output );
	}
    }
    

	$table = "";
	foreach( $ans as $a ){
	$bookId = $a["id"];
	$bookName = $a["book"];
	$originalDate = $a[ "time" ];
	$date = date("d-m-Y", strtotime($originalDate));	
	$seller = $a["seller"];
	$status = "";
	$mrp = $a["mrp"];
	$sp = $a["sp"];
	
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
				    	<label class="control-label">$bookName</label>
			    	</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label">Date Added</label>
			    	<div class="controls">
				    	<label class="control-label">$date</label>
			    	</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label">Seller</label>
			    	<div class="controls">
				    	<label class="control-label">$seller</label>
			    	</div>
			    </div>
<!--     			    <div class="control-group">
			    	<label class="control-label">Status</label>
			    	<div class="controls">
				    	<label class="control-label">$status</label>
			    	</div>
			    </div> -->
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
		    </fieldset>
	    </form>
END;
	}
	return $table;
}

function getEditApproveBookForm($transId,$bid){
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
			    	<label class="control-label" for="date">Date Added</label>
			    	<div class="controls">
			     		<input type="datetime" id="date">
			    	</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label" for="seller">Seller</label>
			    	<div class="controls">
			     		<input type="text" id="seller">
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


?>
