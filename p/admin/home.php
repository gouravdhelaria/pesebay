<?php
session_start();
error_reporting(-1);
header( "Content-type:text/html; charset=utf-8" );
require_once( "helper.php");
$html = "";
$nav =<<<END
			<script type="text/javascript">
			
			function approveBooks(){
					id = [];
					transid = [];
					$(".checkbox").each(function(){
						if($(this).is(":checked")){
							id.push($(this).attr("id"));
							transid.push($(this).attr("transid"));
						}
					});
					
					if(id.length > 0){
						$.ajax({        
						       type: "POST",
						       url: "approveBooksList.php",
						       data: { books : id,
						               transid : transid },
						       success: function() {
						       	    alert("Books Approved. Added to Inventory.");
						       	    document.location.reload();
						       }
						    }); 
					}
					else{
						alert("Select Books to Approve.");
					}
			}
			
			
			function disapproveBooks(){
					id = [];
					$(".checkbox").each(function(){
						if($(this).is(":checked")){
							id.push($(this).attr("id"));
						}
					});
					
					if(id.length >1){
					}
					else{
						alert("Select Books to Disapprove.");
					}
			
			}
			
			$(document).ready(function(){
				$("#selectall").change(function(){
					if($("#selectall").is(":checked")){
						$(".checkbox").each(function(){
							$(this).attr("checked",true);
						});
					}
					else{
						$(".checkbox").each(function(){
							$(this).attr("checked",false);
						});
					}
				});
				$("li.active").removeClass("active");
END;
if(isset($_GET["q"])){
	switch($_GET["q"]){
	     case "list": $html = getBookList();
	     		$nav .=<<<END
				$("#listBooksTab").addClass("active");
END;
	     		break;

	     case "approve": $html = getApproveBookList();
	     		$nav .=<<<END
				$("#approveBooksTab").addClass("active");
END;
	     		break;

	     case "deliver": $html = getDeliverTable();
	     		$nav .=<<<END
				$("#deliverBooksTab").addClass("active");
END;
	     		break;

	     case "help": $html = help();
	     		$nav .=<<<END
				$("#helpTab").addClass("active");
END;
	     		break;

	     case "search": 	     		
	     		if(isset($_GET["transid"]) && $_GET["transid"] != ""){
		     		$html = getSearchBookList($_GET["transid"]);
			}
			else{
				$html = getApproveBookList();
			}
	     		$nav .=<<<END
				$("#approveBooksTab").addClass("active");
END;
	     		break;

	     default: header( "Location:home.php" );
	     		$nav .=<<<END
				$("#overviewTab").addClass("active");
END;
	     		break;
	}
	$nav .= '}); </script>';
}
else{
	     		$nav .=<<<END
				$("#overviewTab").addClass("active");
END;
	$nav .= '}); </script>';
	$html = getHome();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">

    <title>Administration &mdash; Books.inherit()</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" type="text/css" href="../../css/header.css" />
    <link rel="stylesheet" type="text/css" href="../../css/button.css" />  
    <link rel="stylesheet" href="../../css/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/bootstrap/css/bootstrap-responsive.min.css">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">
    <script src="../../js/jquery.js"></script>

      
    <style type="text/css">
      html {
	background-color: #CCCCCC;
	background-image: -moz-linear-gradient(whiteSmoke, #CCCCCC);
   	border-bottom: 1px solid #AAAAAA;
   	border-top: medium none !important;
   	text-align: left;
   	width: 100%;    	
      }
      body {
        background-color: #fff;
        background-image: -webkit-gradient(linear, left top, left bottom, from(#fff), to(#eee));
        background-image: -webkit-linear-gradient(top, #fff, #eee);
        background-image: -moz-linear-gradient(top, #fff, #eee);
        background-image: -ms-linear-gradient(top, #fff, #eee);
        background-image: -o-linear-gradient(top, #fff, #eee);
        background-image: linear-gradient(top, #fff, #eee); 
        }
      div#login>div{
      	border: 1px dotted #ccc;
      	padding:15px;
    	box-shadow: 5px 5px 10px #888888;
    	border:1px solid #ccc;
    	border-radius:5px;
	-moz-border-radius:5px; /* Firefox 3.6 and earlier */
	margin-top:80px;
	margin-bottom:190px;
      }
      #wrapper h2{
        font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
 	font-size: 30px;
        line-height: 20px;
        padding: 20px 15px 5px 60px;
        text-align: left;
      }
      #wrapper h2 a {
        color: #833526;
        font-weight: bold;
        text-decoration: none;
        text-shadow: 1px 1px 1px rgba(39, 95, 39, 0.11);
      }
    </style>
    <link href="../../css/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
	    <?php 
		    echo $nav;
	    ?>
  </head>

  <body>

	  <header id="header-class" style="top:-10px;position:fixed;">	
    		<div id="wrapper" >
      			<h2>  <a href="http://gaming.alwaysdata.net/pesebay/p/home.php"> Books.inherit() | Administration</a> </h2>
    		</div>
 	   </header>
 	   
	    <div class="navbar navbar-inverse navbar-fixed-top" style="top:56px;">
	      <div class="navbar-inner">
		<div class="container">
		  <div class="nav-collapse collapse">
		    <ul class="nav">
		      <li class="" id="overviewTab">
		        <a href="home.php">Overview</a>
		      </li>
		      <li class="divider-vertical"></li>
		      <li class="" id="listBooksTab">
			<a href="home.php?q=list" tabindex="-1">List Books</a>
		      </li>
		      <li class="divider-vertical"></li>
		      <li class="" id="approveBooksTab">
			<a href="home.php?q=approve" tabindex="-1">Approve Books</a>
		      </li>
		      <li class="divider-vertical"></li>
		      <li class="" id="deliverBooksTab">
		        <a href="home.php?q=deliver"> Books to be delivered</a>
		      </li>
		      <li class="divider-vertical"></li>
		      <li class="" id="helpTab">
		        <a href="home.php?q=help">Help</a>
		      </li>
		    </ul>
		  </div><!-- nav-collapse -->
		</div><!-- container -->
	      </div>
	    </div>
	    <div class="container" style="margin-top:98px;">
	    <?php 
		    echo $html;
	    ?>
	       <hr />

	       <footer>
		<p>&copy; Books.inherit() 2012</p>
	      </footer>

	     </div>
	     </div>
	    </div> 
	    </div> <!-- /container -->

  </body>
</html>	
