<?php
require_once( "db.php" );
require_once( "logger.php" );

class Purchase{
	private $purchase_id;
	private $usn;
	private $book_id;
	private $db;
	private $savedOnce = false;

	public function __construct( $db, $info ){		
		$this->db = $db;
		$this->purchase_id = $info[ "uid" ];
		$this->book_id = $info[ "book_id" ];		
	}
	public function getBookId(){
		return $this->book_id;
	}
    public function getUserId(){
            return $this->purchase_id;
    }

	public function save(){
		if( !$this->savedOnce ){
			try{
				$sql = "insert into Purchase ( uid, book_id ) values ( :uid, :bid );";				
				$stmt = $this->db->prepare( $sql );
				$stmt->bindValue( ":uid", $this->purchase_id, PDO::PARAM_INT );
				$stmt->bindValue( ":bid", $this->book_id, PDO::PARAM_INT );				
				$stmt->execute();				
				$this->savedOnce = true;
			}
			catch( PDOException $e ){
				echo $e->getMessage();
				Logger::log( "Error while inserting row into Purchase table: ".$e->getMessage(), "24" );
			}
		}
		else{
			$this->update();
		}
	}
	public function update(){
	}
	public function getUserGivenBookId( $db, $book_id ){
	    $sql = "select * from User where id in ( select uid from Purchase where book_id = :bid )";
	    try{
	        $stmt = $db->prepare( $sql );
	        $stmt->bindParam( ":bid", $book_id, PDO::PARAM_INT );
	        $stmt->execute();
	        $row = $stmt->fetch( PDO::FETCH_ASSOC );
	        $user = null;
	        if( $row ){
	            $user = new User( $db, $row );
	        }
	        return $user;
	    }
	    catch( PDOException $e ){
	        echo $e->getMessage()."\n";
	    }
	}
    //gourav
    public static function getAllPurchases( $db ){
        $sql = "select * from Purchase";
        try{
            $stmt = $db->prepare( $sql );
            $stmt->execute();
            $purchases = array();
            while( ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) ){
                $row[ "user_id" ] = $row[ "uid" ];
                $row[ "b_id" ] = $row[ "book_id" ];
                array_push( $purchases, new Purchase( $db, $row ) );
            }
            return $purchases;
        }
        catch( PDOException $e ){
            echo $e->getMessage()."\n";
        }
    }
}
?>
