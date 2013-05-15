<?php
require_once( "book.php" );
require_once( "user.php" );

function getPostData( $db, $flag = true ){
    $ret_array = array();
    
    $user = new User( $db, array( "usn" => $_POST[ "usn" ], 
                                  "name" => $_POST[ "name" ],
                                  "semester" => intval( $_POST[ "sem" ] ),
                                  "section" => $_POST[ "section" ],
                                  "mobile_no" => intval($_POST[ "mobile" ]),
                                  "email" => $_POST[ "email" ] ) );
    $book = array();
    $auth_array = array(); // auth_array will contain Author objects which is need to save into the database.
    $auth_return = array(); // $auth_return will contain comma seperated author names. This info is need to construct email messages.
    if( $flag ){
      for( $i = 0; $i < count( $_POST[ "bname" ] ); $i++ ){
          list( $authors, $auths ) = getAuthors( $db, $_POST[ "author" ][ $i ] );          
          array_push( $auth_return, $auths );
          array_push( $auth_array, $authors );
          array_push( $book, new Book( $db, array( "name" => $_POST[ "bname" ][ $i ],
                                        "edition"     => $_POST[ "edition" ][ $i ],
                                        "publication" => $_POST[ "publication" ][ $i ],
                                        "discount"    =>  $_POST["discount"][$i],
                                        "condition"   =>  $_POST["condition"][$i],
                                        "marked_price" => 0,
                                        "selling_price" => 0,
                                        "description" => $_POST["description"][ $i ]) ) );
      }
    }
    $ret_array[] = $user;
    $ret_array[] = $book;
    $ret_array[] = $auth_array;
    $ret_array[] = $auth_return;
    return $ret_array;         
}

function getAuthors( $db, $auth_string ){
    $tmp_array = array();    
    $authors = explode( ",", $auth_string );
    for( $i = 0; $i < count( $authors ); $i++ ){        
        array_push( $tmp_array, new Author( $db, array(  "book_id" => null, "author" => trim( $authors[ $i ] ) ) ) );
    }
    return array( $tmp_array, $authors );
}

function destroy_session(){
  if( isset( $_COOKIE[ session_name() ] ) ){
    setcookie( session_name(), "", time() - 3600, "/" );
  }
  $_SESSION = array();
  session_destroy();
}


function generateBookGraph( $books, $authors, $link_gen_flag = true )
{
	$html = "";

}




function generateBookInfoHTML( $books, $authors, $link_gen_flag, $recos = null){
    $html = "";    
   if( $link_gen_flag ){
    
          $html .=<<<END
          <link rel="stylesheet" type="text/css" href="../css/bootstrap/css/bootstrap.min.css" />
          <link rel="stylesheet" type="text/css" href="../css/book_info.css" /> 
	  <script type="text/javascript">
	  	$(document).ready(function(){
	  	staticArrowTop = parseInt($("#arrowleftcontainer").css("top"));
		  	showDetailsBool = false;
		  	$("#info").css("height",Math.max(parseInt($("#info").css("height")),(parseInt($("#recommendationsContainer").css("height")) + 60)) + "px");
	  	});

		function fillDetails(id,name){
			$("#details").html("");
//			$("#loading").css("display","block");
			showDetailsBool = true;
			$("#detailsContainer").css("display","none");
			$("#info").css("opacity","60%");										
			$("#info").css("background-color","#FCFCFC");										
			$("#leftBlock_"+id).css("background-color","white");										
			$("#details").load("details.php",{'q' : encodeURIComponent(name)},function(){
//				$("#loading").css("display","none");
				$("#info").css("opacity","100%");										
				$("#info").css("background-color","white");	
				$("#leftBlock_"+id).css("background-color","");										
				id="#leftBlock_"+id;
				pos = $(id).offset();
				currentTop = parseInt(pos.top) - parseInt($(id).css("height"));
				var eTop = $(id).offset().top;
	//  			currentTop = currentTop - $(id).height() * ( eTop - $(window).scrollTop()  ) / $("#detailsContainer").height();
				// calculate the top position of the book list item wrt screen size.			
				var percentEtop = 1 -((parseInt($(window).height()) - (parseInt($(id).offset().top) - parseInt($(window).scrollTop())))/ parseInt($(window).height()));
	//  			currentTop = currentTop - $(id).height() * ( eTop - $(window).scrollTop()  ) / $("#detailsContainer").height();
	  			currentTop = currentTop - percentEtop * currentTop;
	  			arrowTop = parseInt($(id).position().top) + parseInt(staticArrowTop) - parseInt(currentTop);
				$("#arrowleftcontainer").css("top",arrowTop + "px");
				detailsHeight = parseInt($("#detailsContainer").height());
				$("#detailsContainer").css("top",currentTop + "px");
				$("#detailsContainer").css("display","block");
			});
		}
		function colorStars(num){
		}
		function rateBook(num){
		}
		
		function hideDetails(){
			if(!showDetailsBool)
			$("#detailsContainer").css("display","none");
		}
	  </script>
END;


	$recoList = "";
        $html.=<<<END

            <div id="info" class="info span12">
			<div id="recommendationsContainer" class="span5 offset6" >
				<div id="recommendationsHeader">
					<h4>Recommended Books:</h4>
				</div>
END;
	if(count($recos) < 1){
        $html.=<<<END
        			<div id="recommendationsContent">
					<p>There are No Recommendations.</p>
        			</div>
END;
	}
	else{
		$recoList = "<ul>";
	 	for( $i = 0; $i < min(8,count( $recos )); $i++ ){
	 		$recoList .= "<li><a href='search.php?q=" . urlencode($recos[$i]) . "&submit=search' >" . $recos[$i] . "</a></li>";
	 	}
        $html.=<<<END
        			<div id="recommendationsContent">
END;
	$html.= $recoList . "</ul></div>";

	}
	$html .=<<<END
				</div>
END;
	if(count($books) < 1){
        	$html.=<<<END
		<div id="leftBlock_$id" class="left_block span6">
			<p> There are No Books Available matching the query.</p>
		</div>
END;
	}
    	for( $i = 0; $i < count( $books ); $i++ ){
        	$count = $i + 1;
        	$mp = intval( $books[ $i ]->getMarkedPrice() );
        	$sp = intval( $books[ $i ]->getSellingPrice() );
        	$discount = $mp - $sp;
        	$id = $books[ $i ]->getId();
        	$name = $books[ $i ]->getName();
        	$condition = $books[$i]->getCondition();
        	$auth = implode(",", $authors[ $i ] );
        	$html.=<<<END
		<div id="leftBlock_$id" class="left_block span6" data-animation="true" data-html="true" data-placement="right" data-trigger="hover" onmouseover="fillDetails($id,'$name');" >
	                <div class="book_info span3">
				
        	            <div class="book_header">
        	                <h4>$name</h4>
        	            </div>
			    <div class="author">
        	                <span class="help-block">Authors:
END;

            $html.= " " .$auth."</span></div>";            

	    $ratingStars = "";
	    
	    for($k=0;$k<$condition;$k++){
	    	$ratingStars .= '<i class="icon-star"></i>';
	    }

              $html .=<<<AEND
			  <span class="rating_stars help-block">
	                          Book Condition:
	                          $ratingStars
			  </span>
AEND;


              $html.=<<<AEND
                	      <div class="price">
                	          <strong class="span2">Marked Price: $mp </strong>
                	          <strong class="span3">Selling Price: $sp </strong>
                	          <strong class="span2">Discount: $discount </strong>
                	      </div>
        		      <div class="links span4">
        		          <span class="add_to_cart pull-right"><a href="#" class="btn btn-success cart" id = "$id" >Add to cart</a></span>

        		      </div>
        		  </div>
			  <div class="span2">
				<div class="imageHolder"><img src="../images/no_image.gif" /></div>
			  </div>
			</div>
AEND;
		}
		$html.=<<<AEND
			<div id="detailsContainer" class="span5 offset6">
				<div id="arrowleftcontainer"><div id="arrowleft"></div><div id="arrowleftoverlapping"></div></div>
				<div id="details" onmousein="showDetailsBool=true;" onmouseout="showDetailsBool=false;hideDetails();">
					<img id="loading" src="../images/loading.gif"/>
				</div>				
			</div>
		</div>
AEND;
     }
      else{        
  	for( $i = 0; $i < count( $books ); $i++ ){
		$count = $i + 1;
		$mp = intval( $books[ $i ]->getMarkedPrice() );
		$sp = intval( $books[ $i ]->getSellingPrice() );
		$discount = $mp - $sp;
		$id = $books[ $i ]->getId();
		$name = $books[ $i ]->getName();
		$auth = implode(",", $authors[ $i ] );
	
	        $html.=<<<END
	            <table class="table table-bordered">
	                <theader>
	                <tr>                    
	                        <th>Type</th>
	                        <th class="item">Item</th>
	                        <th class="author">Authors</th>
	                        <th class="quant">Quantity</th>
	                        <th class="mp">Marked Price</th>
	                        <th class="sp">Selling Price</th>
	                        <th></th>
	                </tr>
	                <theader>                    
	                    <tbody>
	                        <tr>
	                            <td>Book</td>
	                            <td class="item">$name</td>
	                            <td class="author">$auth</td>
	                            <td class="quant">1</td>
	                            <td class="mp">$mp</td>
	                            <td class="sp">$sp</td>
	                            <td><a href="#" id="$id" class="delete">Delete</a></td>
	                        </tr>
	                    </tbody>
	                </tr>
	            </table>
	            <!--
	            <div class="ninfo">
	                <div class="nbook_info">
	                    <div class="nbook_header">
	                        <h4>$name</h4>
	                    </div><div class="nauthor">
	                        <div><span class="nauth_heading">Authors :</span></div>
	                        <div> -->
END;
	            
        }
    }    
    return $html;
}

function getSenderMessage( $user, $key, $books, $authors) {

    $message = "Transaction Details are: <br /> Name: ".$user->getName(). "<br /> Usn : ".$user->getUsn()."<br />";
    //$message .= generateBookInfoHTML( $books, $authors, false);
    $message .= "<br /> Transaction key :".$key;
    $message .= "<br /><br />Please note down the key. We would need it for validation when we collect the books.";
    $message .= " We will contact you on your cellphone soon and let you know the details of our meeting.";
    $message .= "<br /><br /><br /> Regards,<br /> Books.inherit()";

    return $message;
}


function getPurchaseMessage ( $user, $secretkey , $books, $authorarray ) {

  $message = "Transaction Details are: <br /> Name: ". $user->getName(). "<br /> Usn : ".$user->getUsn()."<br />";
  //$message .= generateBookInfoHTML( $books, $authors, false);
  $message .=<<<END
      Please click on the following link to confirm your purchase:
    <a href="http://gaming.alwaysdata.net/pesebay/p/confirm.php?k=$secretkey" > Confirm Purchase</a><br />
    We will contact you on your cellphone soon and let you know the details of our meeting.
    <br /><br /><br /> Regards, <br /> <br /> Books.inherit()
END;
    return $message;
}

function getHTML(){
    $html = <<<END
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">

    <title>Welcome &mdash; Books.inherit()</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
    <script type="text/javascript" src="../js/cart.js"></script>
            <script type="text/javascript" src="../fancy/source/jquery.fancybox.js?v=2.0.6"></script>
      <link rel="stylesheet" type="text/css" href="../fancy/source/jquery.fancybox.css?v=2.0.6" media="screen" />
	<link rel="stylesheet" type="text/css" href="../css/nbook_info.css" />       
	<link rel="stylesheet" type="text/css" href="../css/header.css" />	
      <link rel="stylesheet" href="../js/autocomplete/db/themes/base/jquery.ui.all.css">
      <script src="../js/autocomplete/db/ui/jquery.ui.core.js"></script>
      <script src="../js/autocomplete/db/ui/jquery.ui.widget.js"></script>
      <script src="../js/autocomplete/db/ui/jquery.ui.position.js"></script>
      <script src="../js/autocomplete/db/ui/jquery.ui.autocomplete.js"></script>      
      <script src="../css/bootstrap/js/bootstrap.js"></script>      
      <script type="text/javascript">
$(function() {
    $( "#id_q" ).autocomplete({
      source: "autocomplete_books.php",
      scroll: true,
      highlight: false,
      minLength: 2
    });
  });
      </script>  
	<style type="text/css" >

        .description {
        line-height:2em !important;
        ont-size:1.2em;
        }
        div.pwrapper{
          margin-left:170px;
        }
        h4{
          line-height: 0px;
        }
        div.mycart ol{
          list-style:none;
          /*margin:5px;
          padding:5px;*/
        }
        div#cart_button{
          display:none;
        }
        div#cart_button{
          position:fixed;
          top:87px;
          right:65px;
        }
/*        div.mycart{
          position:fixed;
          top:28px;
          right:155px;
        }*/
    </style>
      <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.min.css">
      <link rel="stylesheet" href="../css/bootstrap/css/bootstrap-responsive.min.css">

      <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

       <link rel="stylesheet" href="../css/home.css">
          
      </head>
  <body class="body" id="search">
	<header id="header-class" style="margin-top:-10px;">
		<div id="wrapper" >
			<h1> 	<a href="http://gaming.alwaysdata.net/pesebay/p/home.php"> Books.inherit() </a> </h1>
		</div>
	</header>
  <!--  
    <div id="body-inner">
      <div id="content-wrapper">
                <div class="container">
            
              <div class="row">
    <div id="main-title" class="span4">
      
    </div>
  </div>-->
<div class="pwrapper">
  <div class="row">
    <div class="span12">
      <div id="search-area" class="well">
        <form class="form-search" method="GET" action="search.php">
          <div class="control-group">
            <label class="control-label visuallyhidden" for="id_q">Book Title</label>
            <div class="controls">
              <div id="search-box" class="input-append">
                <input id="id_q" name="q" type="text" placeholder="Book Title" class="span11"><button class="btn" type="submit" name="submit" value="search"><i class="icon-big-search"></i><span class="visuallyhidden">Search</span></button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div id="cart_button">
  <a href="#cart_data" class="btn btn-success" id="inline">My Cart</a>
</div>
<div id="data_cont" style="display:none;">
<div class="mycart" id="cart_data">
  <ol>
    <li id="cart_info"></li>
    <li><div id="space"></div></li>
    <li><a href="checkout.php" class="btn btn-success" custom = "next" >Close Shopping and Buy These Books</a></li>
  </ol>
</div>
</div>
END;
return $html;
}
?>
