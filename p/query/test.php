<?php
require_once( "db.php" );

$db = Database::connect();
$file = fopen( "booklist.txt", "r" );
try{
    $sql = "insert into Book ( isbn, name, edition, publication, discount, _condition, marked_price, selling_price ) values ( :_1, :_2, :_3, :_4, :_5, :_6, :_7, :_8 )";
    $stmt = $db->prepare( $sql );
    $stmt->bindValue( ":_1", "", PDO::PARAM_STR );
    $stmt->bindValue( ":_3", 3, PDO::PARAM_INT );
    $stmt->bindValue( ":_4", "Wiley", PDO::PARAM_STR );
    $stmt->bindValue( ":_5", 0.6, PDO::PARAM_STR );
    $stmt->bindValue( ":_6", "good", PDO::PARAM_STR );
    $stmt->bindValue( ":_7", 250, PDO::PARAM_STR );
    $stmt->bindValue( ":_8", 200, PDO::PARAM_STR );
    while( !feof( $file ) ){
        $line = trim( fgets( $file ) );
        $stmt->bindValue( ":_2", $line, PDO::PARAM_STR );
        $stmt->execute();
        echo $line." Inserted.\n";
    }
    fclose( $file );
}
catch( PDOException $e ){
    echo $e->getMessage()."\n";
}
?>
