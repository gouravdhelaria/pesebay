<?php
require_once( "db.php" );

class BookTag{
    public static function getTopicUnionCount( $db, $item1, $item2 ){
        $sql = <<<END
SELECT count(*) as c
FROM (
(
SELECT S1.tag
FROM BookTag S1
WHERE S1.book_id =:b1
)
UNION
(
SELECT S2.tag
FROM BookTag S2
WHERE S2.book_id =:b2
)
)S
END;
        try{
            $stmt = $db->prepare( $sql );
            $stmt->bindValue( ":b1", $item1->getId(), PDO::PARAM_INT );
            $stmt->bindValue( ":b2", $item2->getId(), PDO::PARAM_INT );
            $stmt->execute();
            $ucount = $stmt->fetch( PDO::FETCH_ASSOC );
            return $ucount[ "c" ];
        }
        catch( PDOException $e ){
            echo $e->getMessage()."\n";
        }
        return 0;
    }
    public static function getTopicIntersectCount( $db, $item1, $item2 ){
        $sql = "select count(*) as c from BookTag S1, BookTag S2 where S1.tag = S2.tag and S1.book_id = :bid1 and S2.book_id = :bid2";
        try{
            $stmt = $db->prepare( $sql );
            $stmt->bindValue( ":bid1", $item1->getId(), PDO::PARAM_INT );
            $stmt->bindValue( ":bid2", $item2->getId(), PDO::PARAM_INT );
            $stmt->execute();
            $count = $stmt->fetch( PDO::FETCH_ASSOC );
            return $count[ "c" ];
        }
        catch( PDOException $e ){
            echo $e->getMessage()."\n";
        }
    }
}
?>
