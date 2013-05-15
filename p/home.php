<?php
    
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">

    <title>Welcome &mdash; Books.inherit()</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
  <link rel="stylesheet" type="text/css" href="../css/header.css" />
  <link rel="stylesheet" type="text/css" href="../css/button.css" />  
    <style type="text/css" >
        body {
        background-color: #fff;
        background-image: -webkit-gradient(linear, left top, left bottom, from(#fff), to(#eee));
        background-image: -webkit-linear-gradient(top, #fff, #eee);
        background-image: -moz-linear-gradient(top, #fff, #eee);
        background-image: -ms-linear-gradient(top, #fff, #eee);
        background-image: -o-linear-gradient(top, #fff, #eee);
        background-image: linear-gradient(top, #fff, #eee); 
        }
        .description {
        line-height:2em !important;
        font-size:1.2em;
        }
.ui-autocomplete {
    max-height: 190px;
    overflow-y: auto;
    /* prevent horizontal scrollbar */
    overflow-x: hidden;
    /* add padding to account for vertical scrollbar */
    padding-right: 20px;
  }
  /* IE 6 doesn't support max-height
   * we use height instead, but this forces the menu to always be this tall
   */
  * html .ui-autocomplete {
    height: 100px;
  }        
    #button-wrapper {
      position : absolute;
      top : 100px;
      right : 100px;
    
    }
    #button-wrapper a {
      color : white;
      text-decoration : none;
    }
    
    </style>
          <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.min.css">
      <link rel="stylesheet" href="../css/bootstrap/css/bootstrap-responsive.min.css">

      <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

            <link rel="stylesheet" href="../css/home.css">
      <script type="text/javascript" src="../js/autocomplete/js/jquery-1.8.0.min.js"></script>
      <link rel="stylesheet" href="../js/autocomplete/db/themes/base/jquery.ui.all.css">
      <script src="../js/autocomplete/db/ui/jquery.ui.core.js"></script>
      <script src="../js/autocomplete/db/ui/jquery.ui.widget.js"></script>
      <script src="../js/autocomplete/db/ui/jquery.ui.position.js"></script>
      <script src="../js/autocomplete/db/ui/jquery.ui.autocomplete.js"></script>      
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
      </head>
  <body class="body" id="search">
  <header id="header-class" style="margin-top:-10px;">
    <div id="wrapper" >
      <h1>  <a href="http://gaming.alwaysdata.net/pesebay/p/home.php"> Books.inherit() </a> </h1>
    </div>
  </header>
    
  <div id="button-wrapper" >
    <a href="../h/upload.html" class="btn btn-success" > Offer Books </a>
  </div>
  
    <div id="body-inner">
      <div id="content-wrapper">
            <div class="container">
               <div class="row">
            <div id="main-title" class="span4">
                     <h1>Books.inherit()</h1>
            
          </div>
                </div>

  
  
  
  
  <div class="row">
    <div class="span12">
      <div id="search-area">
        <form class="form-search" method="GET" action="search.php">
          <div class="control-group">
            <label class="control-label visuallyhidden" for="id_q">Book Title</label>
            <div class="controls">

<div class="ui-widget">  
              <div id="search-box" class="input-append">
                <input id="id_q" name="q" type="text" placeholder="Book Title" class="span11"><button class="btn" type="submit" name="submit" value="search"><i class="icon-big-search"></i><span class="visuallyhidden">Search</span></button>
              </div>
</div>              
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

        <p  class="description">Welcome to Books.inherit(). This is an exclusive site for exchange of books among PESITians. People who wish to sell their books can upload their details by clicking on the 'Offer Books' button at the top. People who wish to purchase books can search for a particular book and fill in necessary details.
        <br />Click on this link to see how it works. &nbsp;<a href="http://gaming.alwaysdata.net/pesebay/h/works.html">How it Works</a>
        </p>
     </div>
              </div>
      <div class="push"><!--//--></div>
    </div>

</body>
</html>
