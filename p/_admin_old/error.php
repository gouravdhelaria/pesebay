<?php
 /*
 save as error.php
 usage: type in your browser http://domain.com/error.php?page=index.php
 replace domain.com with your domain
 and index.php with the page you wish to find errors on
 */
 $page = $_GET["page"];
 error_reporting *(E_ALL);
 ini_set ('display_errors', true);
 include("$page");
 ?> 
