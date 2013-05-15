<?php
function getApproveBookList(){

	$approve = "";
	$approve =<<<END
	    <div class="span12" style="margin-bottom:40px;">
	    	  <div class="row" style="margin-top:20px;">
	    	  	<div class="span3 offset10">
				<button class="btn btn-success btn-small">Approve</button>
				<button class="btn btn-danger btn-small">Disapprove</button>
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
				  <th>Status</th>
				  <th>Date Added</th>
				  <th>Marked Price</th>
				  <th>Selling Price</th>
				  <th>Discount</th>
				  <th>Action</th>
				</tr>
			</thead>			  
			<tbody>
				<tr>
				  <td><input type="checkbox" id="select_book1"/></td>
				  <td>1</td>
				  <td>PHP Programming</td>
				  <td>Alexander Flemming</td>
				  <td>Mark</td>
				  <td>Yet to be approved</td>
				  <td>12/11/2012</td>
				  <td>INR 395</td>
				  <td>INR 295</td>
				  <td>INR 100</td>
				  <td><a href="#view">View</a> | <a href="#edit">Edit</a></td>
				</tr>
				<tr>
				  <td><input type="checkbox" id="select_book3"/></td>
				  <td>3</td>
				  <td>C</td>
				  <td>K & R</td>
				  <td>Larry</td>
				  <td>Yet to be approved</td>
				  <td>12/11/2012</td>
				  <td>INR 395</td>
				  <td>INR 295</td>
				  <td>INR 100</td>
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
	return $approve;
}


?>
