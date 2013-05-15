<?php
require_once( "../book.php" );
require_once( "../user.php" );
require_once("../purchase.php");
require_once( "booklist.php" );
require_once( "sales.php" );
require_once( "approveBooks.php" );
require_once( "help.php" );

function getHome()
{
	$home = "";
	$home =<<<END
	    <div class="span12">
		<div class="row">
		     <div class="span6">
		      <table class="table table-condensed table-bordered">
			<legend>Overview</legend>
			<tbody>
END;
		$home .= getOverview();
		$home .=<<<END
  			</tbody>
			</table>
		     </div>
		     <div class="divider-vertical"></div>
		     <div class="span6">
		      <table class="table table-striped table-condensed" style="margin-bottom:40px;">
			<legend>Inventory Details</legend>
			<thead>
				<tr>
				  <th>ID</th>
				  <th>Book</th>
				  <th>Date</th>
				  <th>Seller</th>
				  <th>Status</th>
				  <th>MRP</th>
				  <th>SP</th>
				</tr>
			</thead>			  
			<tbody>
END;
		$home .= getInventoryOverview();
		$home .=<<<END
  			</tbody>
			</table>
		     </div>
		  </div> <!-- end of row -->
		  <hr />
		  <div class="row">
		      <table class="table table-striped table-condensed" style="margin-bottom:40px;">
			<legend>Order Details</legend>
			<thead>
				<tr>
				  <th>Order ID</th>
				  <th>USN</th>
				  <th>Customer</th>
				  <th>Email-Id</th>
				  <th>Phone No.</th>
				  <th>Book</th>
				  <th>Marked Price</th>
				  <th>Selling Price</th>
				  <th>Action</th>
				</tr>
			</thead>			  
			<tbody>
END;
	$home .= getOrders();
	$home .=<<<END
  			</tbody>
			</table>
		      </div><!-- end of row -->
		  </div><!-- end of span12 -->
	       
END;
	return $home;
}

function getOverview()
{
	$overview = "";
	$overview .=<<<END
				<tr>
				  <td>Total Sales:</td>
				  <td>INR 32,459</td>
				</tr>
				<tr>
				  <td>Total Books Sold:</td>
				  <td>125</td>
				</tr>
				<tr>
				  <td>No. of Customers:</td>
				  <td>120</td>
				</tr>
				<tr>
				  <td>Books Awaiting Approval:</td>
				  <td>14</td>
				</tr>
				<tr>
				  <td>Books Yet To Be Sold:</td>
				  <td>9</td>
				</tr>
				<tr>
				  <td>Books Yet To Be Sold:</td>
				  <td>9</td>
				</tr>
END;
	return $overview;
}

?>
