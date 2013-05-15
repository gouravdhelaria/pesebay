<?php
session_start();
error_reporting(-1);
header( "Content-type:text/html; charset=utf-8" );
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">

    <title>Welcome &mdash; PESeBay</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" type="text/css" href="../../css/header.css" />
    <link rel="stylesheet" type="text/css" href="../../css/button.css" />  
    <link rel="stylesheet" href="../../css/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/bootstrap/css/bootstrap-responsive.min.css">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

      
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
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>

  <body>

	  <header id="header-class" style="margin-top:-10px;">
    		<div id="wrapper" >
      			<h1>  <a href="http://gaming.alwaysdata.net/pesebay/p/home.php"> PESebay | Administration </a> </h1>
    		</div>
 	   </header>


    <div class="container">

      <div id="login" class="row">
        <div class="span5 offset3">
            <fieldset>
	            <form class="form-horizontal" method="post" action="login.php" >
	                <legend>Login</legend>
			<div class="control-group error" id="warning_container" style="display:none;">
				<div class="controls">
					<span class="help-inline">Invalid Username-Password combination</span>
				</div>
			</div>
			<div class="control-group">
	                    <label class="control-label" for="uname">User name</label>
	                    <div class="controls">
	                	        <input type="text" name="uname" id="uname" />
	                    </div>
	                </div>
	                <div class="control-group">
	                    <label class="control-label" for="pword">Password</label>
	                    <div class="controls">
	                            <input type="password" name="pword" id="pword" />
	                    </div>
	                </div>
	                <div class="control-group">
		             <div class="controls">
	        	            <button type="submit" class="btn" name="submit">Login</button>
	        	     </div>
	        	</div>
	         </form>
         </fieldset>
       </div>
     </div>
     <div>
       <hr>

       <footer>
        <p>&copy; PESebay 2012</p>
      </footer>

     </div>
    </div> <!-- /container -->
    <script type="text/javascript">
		function raiseWarning(){
		document.getElementById("warning_container").style.display = "block";
		}
	</script>
<?php
	function login($uname, $pword)
	{
		return true;
		//Login Functionality
	}

	if( isset( $_POST[ "submit" ] ) ){
   		$username = $_POST[ "uname" ];
   		$password = $_POST[ "pword" ];
		$u = null;
		$u = login( $username, $password);
		if( $u !== false){
	    		$_SESSION[ "login" ] = true;
			session_write_close();
				header( "Location:home.php" );
		}
		else{
	//		echo "in else User::login";
		echo '	<script type="text/javascript">
						raiseWarning();
					</script>
				';
		}
		
	}
?>

  </body>
</html>
