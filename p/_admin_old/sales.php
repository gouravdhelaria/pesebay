<?php

function getOrders()
{
	$statistics = "";
    $db = Database::connect();
    $purchases = Purchase::getAllPurchases( $db );
    $output = array();
    $ans = array();
    $count = 1;
    foreach( $purchases as $t ){
        $pid = $count++;
        $book = Book::getBookById( $db, $t->getBookId() );
        $user = User::getUserById( $db, $t->getUserId() );
        
        $output[ "id" ] = $pid;
        $output[ "usn" ] = $user->getUsn();
        $output[ "customer" ] = $user->getName();
        $output[ "email_id" ] = $user->getEmail();
        $output[ "phone" ] = $user->getMobileNo();
        $output[ "book" ] = $book->getName();
        $output[ "mrp" ] = $book->getMarkedPrice();
        $output[ "sp" ] = $book->getSellingPrice();
        array_push( $ans, $output );
    }
    
	$statistics = "";
	foreach( $ans as $a ){
	$statistics .="<tr><td>".$a[ "id" ]."</td>";
	$statistics .="<td>".$a[ "usn" ]."</td>";
    $statistics .="<td>".$a[ "customer" ]."</td>";
	$statistics .="<td>".$a[ "email_id" ]."</td>";
	$statistics .="<td>".$a[ "phone" ]."</td>";
    $statistics .="<td>".$a[ "book" ]."</td>";
	$statistics .="<td>".$a[ "mrp" ]."</td>";
	$statistics .="<td>".$a[ "sp" ]."</td></tr>";
	}
	return $statistics;
}



function getDeliverTable(){

	$sales = "";
	$sales =<<<END
	    <div class="span12" style="margin-bottom:40px;">
	    	  <div class="row" style="margin-top:20px;">
	    	  	<div class="span3 offset9">
				<button class="btn btn-success btn-small">Insert</button>
				<button class="btn btn-danger btn-small">Delete</button>
				<button class="btn btn-info btn-small">Print Invoice</button>
			</div>
		  </div>
		  <hr />
		  <div class="row">
		      <table class="table table-striped table-condensed" style="margin-bottom:40px;">
			<legend>Inventory Details</legend>
			<thead>
				<tr>
				  <th><input type="checkbox" id="selectall_booklist"/></th>
				  <th>USN</th>
				  <th>Student Name</th>
                  <th> Semester </th>
                  <th> Section </th>
				  <th>Phone No.</th>
				  <th>Email-Id</th>
                  <th>Book</th>
                  <th>Edition </th>
                  <th>Publication </th>
  				  <th>Book Id</th>
				  <th>Marked Price</th>
				  <th>Selling Price</th>
				</tr>
			</thead>			  
			<tbody>
END;
    		      <td><input type="checkbox" id="select_book[]"/></td>
				  <td>1</td>
				  <td>1pi09cs098</td>
				  <td>Sid</td>
				  <td>siddhudev813@gmail.com</td>
				  <td>9858958955</td>
				  <td>PHP Programming</td>
				  <td>12/11/2012</td>
				  <td>INR 395</td>
				  <td>INR 295</td>
				  <td><a href="#view">View</a> | <a href="#edit">Edit</a></td>
				</tr>
				<tr>
				  <td><input type="checkbox" id="select_book2"/></td>
				  <td>2</td>
				  <td>1pi09cs024</td>
				  <td>Bhimsen</td>
				  <td>bhimsen@gmail.com</td>
				  <td>9858945485</td>
				  <td>C</td>
				  <td>12/11/2012</td>
				  <td>INR 395</td>
				  <td>INR 295</td>
				  <td><a href="#view">View</a> | <a href="#edit">Edit</a></td>
				</tr>
				<tr>
				  <td><input type="checkbox" id="select_book3"/></td>
				  <td>3</td>
				  <td>1pi09cs030</td>
				  <td>Gourav</td>
				  <td>gourav@gmail.com</td>
				  <td>9858989596</td>
				  <td>Data Structures</td>
				  <td>12/11/2012</td>
				  <td>INR 395</td>
				  <td>INR 295</td>
				  <td><a href="#view">View</a> | <a href="#edit">Edit</a></td>
				</tr>
  			</tbody>
			</table>
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
	return $sales;
}


?>
