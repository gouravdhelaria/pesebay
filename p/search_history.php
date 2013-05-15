<?php
class SearchHistory{
    private $hash = "";
    private $bid = -1;
    private $db = null;

    function __construct( $db, $keys ){
        $this->db = $db;
        if( array_key_exists( "hash", $keys ) )
            $this->hash = $keys[ "hash" ];
        if( array_key_exists( "bid", $keys ) )
            $this->bid = $keys[ "bid" ];        
    }
    function insert(){
        $sql = "insert into SearchHistory ( hash, bid ) values ( :hash, :bid )";
        try{
            $stmt = $this->db->prepare( $sql );
            $stmt->bindValue( ":hash", $this->hash, PDO::PARAM_STR );
            $stmt->bindValue( ":bid", $this->bid, PDO::PARAM_INT );
            $stmt->execute();
        }
        catch( PDOException $e ){            
        }
    }
    public static function getUsersByBookId( $db, $book ){
        $sql = "select count(*) from SearchHistory where bid = :bid";
        try{
            $stmt = $db->prepare( $sql );
            $stmt->bindValue( ":bid", $book->getId(), PDO::PARAM_INT );
            $stmt->execute();
            $count = $stmt->fetch( PDO::FETCH_ASSOC );
            return $count[ "count(*)" ];
        }
        catch( PDOException $e ){
        }
    }
    public static function getUsersForBoth( $db, $bid1, $bid2 ){
        try {
            $sql = "select count(*) from SearchHistory S1, SearchHistory S2 where S1.hash = S2.hash and S1.bid = :bid1 and S2.bid = :bid2";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(":bid1", $bid1->getId(), PDO::PARAM_INT );
            $stmt->bindValue(":bid2", $bid2->getId(), PDO::PARAM_INT );
            $stmt->execute();
            $count = $stmt->fetch( PDO::FETCH_ASSOC );
            return $count[ "count(*)" ];
        }
        catch (PDOException $e ){
        }
    }    
    public static function getBooksByUser( $db, $hash_id ) {
        $books = array();
        try {
            $sql = "select bid from SearchHistory where hash = :hash_id";
            $stmt = $db->prepare( $sql );
            $stmt->bindValue( ":hash_id" , $hash_id , PDO::PARAM_STR );
            $stmt->execute();
            
            while ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ){
                $books[] = $row[ "bid" ];
            }
            return $books;
        }
        catch( PDOException $e ){
        }
    }
    public static function getUserUnionCount( $db, $bid1, $bid2 ){
        $sql = <<<END
SELECT count(*) 
FROM (
(
SELECT S1.hash
FROM SearchHistory S1
WHERE S1.bid =:b1
)
UNION
(
SELECT S2.hash
FROM SearchHistory S2
WHERE S2.bid =:b2
)
)S
END;
        try{
            $stmt = $db->prepare( $sql );
            $stmt->bindValue( ":b1", $bid1->getId(), PDO::PARAM_INT );
            $stmt->bindValue( ":b2", $bid2->getId(), PDO::PARAM_INT );
            $stmt->execute();
            $count = $stmt->fetch( PDO::FETCH_ASSOC );
            return $count[ "count(*)" ];
        }
        catch( PDOException $e ){
        }
    }
}
?>
