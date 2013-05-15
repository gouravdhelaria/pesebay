<?php
require_once( "db.php" );
require_once( "recommendation.php" );

$db = Database::connect();
$sim = new SimilarityTable( $db );
$sim->buildSimilarityTable();
?>
