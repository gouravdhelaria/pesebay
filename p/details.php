<?php
	require_once( "db.php" );
	require_once( "book.php" );

	$db = Database::connect();
	$book_title = urldecode($_POST[ "q" ]);
	$books = Book::getBooksByTitle( $db, $book_title );
	$authors = Author::getAuthorByBook( $db, $books );
	$ids = array();
        $html = "";
	if(count($books) < 1)
	{
		$html .="<p>Error while fetching Book Details</p>";
	}
	else{
	    	for( $i = 0; $i < count( $books ); $i++ ){
			$count = $i + 1;
			$mp = intval( $books[ $i ]->getMarkedPrice() );
			$sp = intval( $books[ $i ]->getSellingPrice() );
			$discount = $mp - $sp;
			$id = $books[ $i ]->getId();
			$name = $books[ $i ]->getName();
			$description = $books[ $i ]->getDescription();
			$condition = $books[ $i ]->getCondition();
			$auth = implode(",", $authors[ $i ] );
			$ratingStars = "";
			for($k=0;$k<$condition;$k++)
			{
				$ratingStars .= '<i class="icon-star"></i>';
			}
		}
		$html .=<<<END
						<div id="detailsHeader"><h3>$name</h3></div>
						<div id="detailsImg"><img src="../images/no_image.gif" /></div>
<!--						<div id="detailsRate" class="well">
							<span class="help-block">
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rate this Book:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<span class="help-block">
								Book Condition
								<i class="icon-star-empty" onmouseover="colorStars(3)" onclick="rateBook(3)"></i>
								<i class="icon-star-empty" onmouseover="colorStars(4)" onclick="rateBook(4)"></i>
								<i class="icon-star-empty" onmouseover="colorStars(5)" onclick="rateBook(5)"></i></span>
							 </span>
						</div>
	-->					<div id="detailsDescription">
							<div id="detailsRating">
								<span class="help-block">
									Book Condition:
									     $ratingStars
								 </span>
							</div>
							<div id="detailsAuthor"><span class="help-block">Authors: $auth</span></div>
						     	<div class="detailsPrice">
		        	          			 <span id="detailsPrice_mp">Marked Price:  $mp</span>
								 <span id="detailsPrice_sp">Selling Price:  $sp</span>
								 <span id="detailsPrice_dp">Discount:  $discount</span>
		        	      			</div>
		        	      			<div id="detailsDesc" class="help-block">
		        	      				<p>$description</p>
		        	      			</div>
						</div> 
END;
	}
	echo $html;

?>
