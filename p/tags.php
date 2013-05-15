<?php
include('db.php');

$file = fopen('tags','r');
$db = Database::connect();
$sql = "insert into BookTag ( book_id, tag ) values ( :_1, :_2 )";
try{
    $stmt = $db->prepare( $sql );
    while($line=fgets($file)){
        $tags = explode(',',$line);
        try {
            $stmt->bindValue( ":_1", $tags[0], PDO::PARAM_INT );
            for( $i = 1; $i < count( $tags ); $i++ ){
                $stmt->bindValue( ":_2", $tags[ $i ], PDO::PARAM_STR );
                $stmt->execute();
            }
            echo "Inserted: ".$line."\n";
        }
        catch( PDOException $e ){
            echo $e->getMessage()."\n";
        }
    }
    fclose( $file );
} 
catch( PDOException $e ){
    echo $e->getMessage()."\n";
}
         
