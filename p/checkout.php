<?php
session_start();
require_once( "Mail.php" );
require_once( "db.php" );
require_once( "transaction.php" );
require_once( "purchase.php" );
require_once( "helper.php" );
require_once( "confirmation.php" );


if( isset( $_POST[ "submit"] ) ){
    $db = Database::connect();
    list( $user, $book, $auth ) = getPostData( $db, false );

    $user->save();
    $secretkey = $user->getSecretKey(true);
    $books = array();

    for( $i = 0; $i < count( $_SESSION[ "cart"] ); $i++ ){
        if( $_SESSION[ "cart" ][ $i ] != null ){
            $book_id = $_SESSION[ "cart" ][ $i ];
            $book = Book::getBookById( $db, $book_id );
            array_push ( $books, $book);
            $p = new Confirmation( $db, array( "uid" => $user->getId(),
                                           "book_id" => $book_id,
                                           "key" => $secretkey
                                         )
                             );
            $p->save();
        }
    }

    $authorarray = Author::getAuthorByBook( $db, $books );
 
    $user_message = getPurchaseMessage ( $user, $secretkey , $books, $authorarray );

    
    $user->sendMail($secretkey, $user_message,true);
    destroy_session();
    header( "Location:../h/thankyou.html" );
}
else{
    printHTML();
}
function printHTML(){
    
$html =<<<'END'

<!DOCTYPE html>

<html>
<head>
<title>Books.inherit()</title>
<link rel="stylesheet" type="text/css" href="../css/header.css" />
<style>

html, body
{
    height: 100%;
}

body
{
    font: 12px 'Lucida Sans Unicode', 'Trebuchet MS', Arial, Helvetica;    
    margin: 0;
    background-color: #d9dee2;
    background-image: -webkit-gradient(linear, left top, left bottom, from(#ebeef2), to(#d9dee2));
    background-image: -webkit-linear-gradient(top, #ebeef2, #d9dee2);
    background-image: -moz-linear-gradient(top, #ebeef2, #d9dee2);
    background-image: -ms-linear-gradient(top, #ebeef2, #d9dee2);
    background-image: -o-linear-gradient(top, #ebeef2, #d9dee2);
    background-image: linear-gradient(top, #ebeef2, #d9dee2);    
}

/*--------------------*/

#login
{
    background-color: #fff;
    background-image: -webkit-gradient(linear, left top, left bottom, from(#fff), to(#eee));
    background-image: -webkit-linear-gradient(top, #fff, #eee);
    background-image: -moz-linear-gradient(top, #fff, #eee);
    background-image: -ms-linear-gradient(top, #fff, #eee);
    background-image: -o-linear-gradient(top, #fff, #eee);
    background-image: linear-gradient(top, #fff, #eee);  
    height: 100%;/* height: 240px; */
    /*width: 400px;*/
    margin: 61px 0 0 0px; /* -230px left margin, top -150px  */
    padding: 30px;
    position: absolute;
    top: 0%;
    left: 0%; /* 50% */
    z-index: 0;
    -moz-border-radius: 3px;
    -webkit-border-radius: 3px;
    border-radius: 3px;  
    -webkit-box-shadow:
          0 0 2px rgba(0, 0, 0, 0.2),
          0 1px 1px rgba(0, 0, 0, .2),
          0 3px 0 #fff,
          0 4px 0 rgba(0, 0, 0, .2),
          0 6px 0 #fff,  
          0 7px 0 rgba(0, 0, 0, .2);
    -moz-box-shadow:
          0 0 2px rgba(0, 0, 0, 0.2),  
          1px 1px   0 rgba(0,   0,   0,   .1),
          3px 3px   0 rgba(255, 255, 255, 1),
          4px 4px   0 rgba(0,   0,   0,   .1),
          6px 6px   0 rgba(255, 255, 255, 1),  
          7px 7px   0 rgba(0,   0,   0,   .1);
    box-shadow:
          0 0 2px rgba(0, 0, 0, 0.2),  
          0 1px 1px rgba(0, 0, 0, .2),
          0 3px 0 #fff,
          0 4px 0 rgba(0, 0, 0, .2),
          0 6px 0 #fff,  
          0 7px 0 rgba(0, 0, 0, .2);
}

#login:before
{
    content: '';
    position: absolute;
    z-index: -1;
    border: 1px dashed #ccc;
    top: 5px;
    bottom: 5px;
    left: 5px;
    right: 5px;
    -moz-box-shadow: 0 0 0 1px #fff;
    -webkit-box-shadow: 0 0 0 1px #fff;
    box-shadow: 0 0 0 1px #fff;
}

/*--------------------*/

h1
{
    /* text-shadow: 0 1px 0 rgba(255, 255, 255, .7), 0px 2px 0 rgba(0, 0, 0, .5);  */
    /* text-transform: uppercase; */ 
    text-align: center;
    color: #666;
    margin: 0 0 30px 0;
    /* letter-spacing: 4px; */
    /* font: normal 26px/1 Verdana, Helvetica; */
    position: relative;
}


/*h1:after, h1:before
{
    background-color: #777;
    content: "";
    height: 1px;
    position: absolute;
    top: 15px;
    width: 120px;   
}

h1:after
{ 
    background-image: -webkit-gradient(linear, left top, right top, from(#777), to(#fff));
    background-image: -webkit-linear-gradient(left, #777, #fff);
    background-image: -moz-linear-gradient(left, #777, #fff);
    background-image: -ms-linear-gradient(left, #777, #fff);
    background-image: -o-linear-gradient(left, #777, #fff);
    background-image: linear-gradient(left, #777, #fff);      
    right: 0;
}

h1:before
{
    background-image: -webkit-gradient(linear, right top, left top, from(#777), to(#fff));
    background-image: -webkit-linear-gradient(right, #777, #fff);
    background-image: -moz-linear-gradient(right, #777, #fff);
    background-image: -ms-linear-gradient(right, #777, #fff);
    background-image: -o-linear-gradient(right, #777, #fff);
    background-image: linear-gradient(right, #777, #fff);
    left: 0;
}*/

/*--------------------*/

fieldset
{
    border: 0;
    padding: 0;
    margin: 0;
}

/*--------------------*/

#inputs input
{
    background: #f1f1f1 ;     /* url(http://www.red-team-design.com/wp-content/uploads/2011/09/login-sprite.png) no-repeat; */
    padding: 15px 15px 15px 30px;
    margin: 0 0 10px 0;
    width: 353px; /* 353 + 2 + 45 = 400 */
    border: 1px solid #ccc;
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    border-radius: 5px;
    -moz-box-shadow: 0 1px 1px #ccc inset, 0 1px 0 #fff;
    -webkit-box-shadow: 0 1px 1px #ccc inset, 0 1px 0 #fff;
    box-shadow: 0 1px 1px #ccc inset, 0 1px 0 #fff;
}


#username
{
    background-position: 5px -2px !important;
}

#password
{
    background-position: 5px -52px !important;
}

#inputs input:focus
{
    background-color: #fff;
    border-color: #e8c291;
    outline: none;
    -moz-box-shadow: 0 0 0 1px #e8c291 inset;
    -webkit-box-shadow: 0 0 0 1px #e8c291 inset;
    box-shadow: 0 0 0 1px #e8c291 inset;
}

/*--------------------*/
#actions
{
    margin: 25px 0 0 0;
}

#submit
{		
    background-color: #ffb94b;
    background-image: -webkit-gradient(linear, left top, left bottom, from(#fddb6f), to(#ffb94b));
    background-image: -webkit-linear-gradient(top, #fddb6f, #ffb94b);
    background-image: -moz-linear-gradient(top, #fddb6f, #ffb94b);
    background-image: -ms-linear-gradient(top, #fddb6f, #ffb94b);
    background-image: -o-linear-gradient(top, #fddb6f, #ffb94b);
    background-image: linear-gradient(top, #fddb6f, #ffb94b);
    
    -moz-border-radius: 3px;
    -webkit-border-radius: 3px;
    border-radius: 3px;
    
    text-shadow: 0 1px 0 rgba(255,255,255,0.5);
    
     -moz-box-shadow: 0 0 1px rgba(0, 0, 0, 0.3), 0 1px 0 rgba(255, 255, 255, 0.3) inset;
     -webkit-box-shadow: 0 0 1px rgba(0, 0, 0, 0.3), 0 1px 0 rgba(255, 255, 255, 0.3) inset;
     box-shadow: 0 0 1px rgba(0, 0, 0, 0.3), 0 1px 0 rgba(255, 255, 255, 0.3) inset;    
    
    border-width: 1px;
    border-style: solid;
    border-color: #d69e31 #e3a037 #d5982d #e3a037;

    float: left;
    height: 35px;
    padding: 0;
    width: 120px;
    cursor: pointer;
    font: bold 15px Arial, Helvetica;
    color: #8f5a0a;
}

#submit:hover,#submit:focus
{		
    background-color: #fddb6f;
    background-image: -webkit-gradient(linear, left top, left bottom, from(#ffb94b), to(#fddb6f));
    background-image: -webkit-linear-gradient(top, #ffb94b, #fddb6f);
    background-image: -moz-linear-gradient(top, #ffb94b, #fddb6f);
    background-image: -ms-linear-gradient(top, #ffb94b, #fddb6f);
    background-image: -o-linear-gradient(top, #ffb94b, #fddb6f);
    background-image: linear-gradient(top, #ffb94b, #fddb6f);
}	

#submit:active
{		
    outline: none;
   
     -moz-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.5) inset;
     -webkit-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.5) inset;
     box-shadow: 0 1px 4px rgba(0, 0, 0, 0.5) inset;		
}

#submit::-moz-focus-inner
{
  border: none;
}

#actions a
{
    color: #3151A2;    
    float: right;
    line-height: 35px;
    margin-left: 10px;
}

/*--------------------*/

#back
{
    display: block;
    text-align: center;
    position: relative;
    top: 60px;
    color: #999;
}

ul, li{
    margin:0;
    padding:0;
    list-style-type:none;
}
div.bcontrols{
    position:absolute;
    top:15px;
    left:313px;
}
div.bcontrols a{
    margin-right:5px;
    margin-left:5px;
}
ul.books{
    position:relative;
}
div.book_container ul{
    float:left;
}

</style>
</head>

<body>
	<header id="header-class" >
		<div id="wrapper" >
			<h1> 	<a href="http://gaming.alwaysdata.net/pesebay/p/home.php"> Books.inherit() </a> </h1>
		</div>
	</header>


<form id="login" action="checkout.php" method = "post" >
    <h1>Submit Your Details</h1>
    <fieldset id="inputs">
        <input id="usn" name = "usn" type="text" placeholder="Enter USN" autofocus class="usn_tip" />
        <input id="name" name = "name" type="text" placeholder="Enter your name"  class="name_tip"/>
        <input id="sem" name = "sem" type="number"  min="1"  max="8" placeholder="Semester" class="sem_tip" />
        <input id="section" name = "section" type="text" placeholder="Section" class="sec_tip" />
        <input id="mobile" name = "mobile" type="text" placeholder="Mobile Number" class="mob_tip" />
        <input id="email" name = "email" type="email"  placeholder="Email" class="email_tip" />
        <br /><br />
    </fieldset>
    <fieldset id="actions">
        <input type="submit" id="submit" value="Submit" name="submit">
        <!-- <a href="">Forgot your password?</a><a href="">Register</a>  -->
    </fieldset>
    <!-- <a href="http://www.red-team-design.com/slick-login-form-with-html5-css3" id="back">Back to article...</a>   -->
</form>

<!-- BSA AdPacks code -->
<script src="../js/jquery.js"></script>
<script src="../js/tooltip/tooltipsy.min.js"></script>
<script type="text/javascript">
    var book_count = 1;
    var bstring = "Book ";
    var login = $("form#login");    
    var height = $("ul.books" ).height();
    var usn_tooltip = undefined;
    var mob_tooltip = undefined, name_tooltip = undefined, sem_tooltip = undefined, sec_tooltip = undefined, email_tooltip = undefined;
    var usn_tip = false, mob_tip = false;
    // Regex
    var usn = /(?:1(P|p)(I|i))\d{2}([ABCDEFGHIJKLMNOPQRSTUVWXYZ]){2}\d{3}/;
    var phone = /\d{10}/;
    
    $( "a#add" ).click( function( e ){
        var container = $( "div.book_container ul.books" ).eq( book_count - 1 ).clone( true );
        var div = $( "div.book_container" );
        if( book_count % 3 == 0 ){
            container.css( "margin-left", "0px" );
            login.css( "height", login.height() + height + "px" );
        }
        else 
            container.css( "margin-left", "20px" );
        book_count++;
        container.find( "li" ).first().find( "h3" ).first().text( bstring + book_count );
        div.append( container );
        $(this).remove();
        return false;
    });
    $( "div.book_container" ).click( function( e ){
        var parent = $(e.target).parent();
        var add_button;
        var uls;
        if( $( e.target ).attr( "class" ) == "remove" ){
            book_count--;
            if( $( e.target ).prev().is( "a#add" ) ){
                add_button = $( e.target ).prev();
                $( "ul.books" ).eq( book_count - 1 ).first().find( "div.bcontrols" ).prepend( add_button );
                
            }
            parent.parents( "ul.books" ).first().remove();
            uls = $( "ul.books" );
            for( var i = 0; i < uls.length; i++ ){
                if( i == 0 )
                    uls.eq(i).css( "margin-left", "0px" );
                else
                    uls.eq(i).css( "margin-left", "20px" );
                uls.eq( i ).find( "h3" ).text( bstring + (i + 1) );
            }
         }
        return false;        
    });
    $( "input#usn" ).focus( function( e ){
        if( usn_tooltip ){
            usn_tooltip.data( "tooltipsy" ).destroy();            
            usn_tooltip = undefined;
        }
    });
    $( "input#mobile" ).focus( function( e ){
        if( mob_tooltip ){
            mob_tooltip.data( "tooltipsy" ).destroy();
            mob_tooltip = undefined;
        }
    });
    $( "input#name" ).focus( function( e ){
        if( name_tooltip ){
            name_tooltip.data( "tooltipsy" ).destroy();
            name_tooltip = undefined;
        }
    });
    $( "input#section" ).focus( function( e ){
        if( sec_tooltip ){
            sec_tooltip.data( "tooltipsy" ).destroy();
            sec_tooltip = undefined;
        }
    });
    $( "input#semester" ).focus( function( e ){
        if( sem_tooltip ){
            sem_tooltip.data( "tooltipsy" ).destroy();
            sem_tooltip = undefined;
        }
    });
    $( "input#email" ).focus( function( e ){
        if( email_tooltip ){
            email_tooltip.data( "tooltipsy" ).destroy();
            email_tooltip = undefined;
        }
    });
    $( "input#submit" ).click( function( e ){
        var usn_string = $( "input#usn" ).val();
        var mobile = $( "input#mobile" ).val();
        var name = $( "input#name" ).val();
        var semester = $( "input#sem" ).val();
        var section = $( "input#section" ).val();
        var email = $( "input#email" ).val();
        
        if( !( usn.test( usn_string ) ) ){
            if( !usn_tooltip ){
                usn_tooltip = $('.usn_tip').tooltipsy({
                                                    offset:[ 0, -5 ],
                                                    content: "Please fill the correct USN",
                                                    css: {
                                                            'padding': '10px',
                                                            'max-width': '200px',
                                                            'color': '#303030',
                                                            'background-color': '#f5f5b5',
                                                            'border': '1px solid #deca7e',
                                                            '-moz-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                                                            '-webkit-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                                                            'box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                                                            'text-shadow': 'none'
                                                         }
                                                    });
                usn_tooltip.data( "tooltipsy" ).show();
            }
           return false;
        }
        else if( name.trim() == "" ){
            if( !name_tooltip ){
                name_tooltip = $('.name_tip').tooltipsy({
                                                    offset:[ 0, -5 ],
                                                    content: "Please fill the name field",
                                                    css: {
                                                            'padding': '10px',
                                                            'max-width': '200px',
                                                            'color': '#303030',
                                                            'background-color': '#f5f5b5',
                                                            'border': '1px solid #deca7e',
                                                            '-moz-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                                                            '-webkit-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                                                            'box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                                                            'text-shadow': 'none'
                                                         }
                                                    });
                name_tooltip.data( "tooltipsy" ).show();
            }
           return false;
        }
        else if( semester.trim() == "" ){
            if( !sem_tooltip ){
                sem_tooltip = $('.sem_tip').tooltipsy({
                                                    offset:[ 0, -5 ],
                                                    content: "Please fill the semester field",
                                                    css: {
                                                            'padding': '10px',
                                                            'max-width': '200px',
                                                            'color': '#303030',
                                                            'background-color': '#f5f5b5',
                                                            'border': '1px solid #deca7e',
                                                            '-moz-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                                                            '-webkit-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                                                            'box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                                                            'text-shadow': 'none'
                                                         }
                                                    });
                sem_tooltip.data( "tooltipsy" ).show();
            }
           return false;
        }
        else if( section.trim() == "" ){
            if( !sec_tooltip ){
                sec_tooltip = $('.sec_tip').tooltipsy({
                                                    offset:[ 0, -5 ],
                                                    content: "Please fill the section field",
                                                    css: {
                                                            'padding': '10px',
                                                            'max-width': '200px',
                                                            'color': '#303030',
                                                            'background-color': '#f5f5b5',
                                                            'border': '1px solid #deca7e',
                                                            '-moz-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                                                            '-webkit-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                                                            'box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                                                            'text-shadow': 'none'
                                                         }
                                                    });
                sec_tooltip.data( "tooltipsy" ).show();
            }
           return false;
        }        
        else if( !phone.test( mobile ) ){
            if( !mob_tooltip ){
                mob_tooltip = $('.mob_tip').tooltipsy({
                                                    offset:[ 0, 10 ],
                                                    content: "Please fill the correct 10 digit mobile number",
                                                    css: {
                                                            'padding': '10px',
                                                            'max-width': '200px',
                                                            'color': '#303030',
                                                            'background-color': '#f5f5b5',
                                                            'border': '1px solid #deca7e',
                                                            '-moz-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                                                            '-webkit-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                                                            'box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                                                            'text-shadow': 'none'
                                                         }
                                                    });
                mob_tooltip.data( "tooltipsy" ).show();
            }
           return false;
        }
        else if( email.trim() == "" ){
            if( !email_tooltip ){
                email_tooltip = $('.email_tip').tooltipsy({
                                                    offset:[ 0, -5 ],
                                                    content: "Please fill the email field",
                                                    css: {
                                                            'padding': '10px',
                                                            'max-width': '200px',
                                                            'color': '#303030',
                                                            'background-color': '#f5f5b5',
                                                            'border': '1px solid #deca7e',
                                                            '-moz-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                                                            '-webkit-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                                                            'box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                                                            'text-shadow': 'none'
                                                         }
                                                    });
                email_tooltip.data( "tooltipsy" ).show();
           }
           return false;           
        }
    });
</script>
</body>
</html>
END;
echo $html;
}
?>

