<?php
require_once("logger.php");

class Confirmation {
	
	private $id;
	private $uid;
	private $book_id;
	private $_key;
	private $cbit=0;
	private $savedOnce=false;
	private $db;

	public function __construct( $db, $info ){
        
        $this->db = $db;
        $this->uid = $info[ "uid" ];
        $this->book_id = $info[ "book_id" ];
        $this->_key = $info[ "key" ];
        //echo "Sagar is a good boy.";
    }
 
    private function id(){
        // create a query.
        $sql = "SELECT Auto_increment FROM information_schema.tables WHERE table_name='ConfirmationInfo' AND table_schema = DATABASE();";
        try{
            $stmt = $this->db->prepare( $sql );
            $stmt->execute();
            $id = $stmt->fetch();
            return $id[ "Auto_increment" ];
        }
        catch( PDOException $e ){
            // need to log this error.
            Logger::log( "Failed to get auto_increment value from ConfirmationInfo table: ". $e->getMessage(), "34" );
        }
    }

	public function save(){
        if( !$this->savedOnce ){
            try{
                // construct query
                $sql = "insert into ConfirmationInfo ( uid, book_id, _key  ) values ( :uid, :book_id, :key );";                
                $stmt = $this->db->prepare( $sql );
                $stmt->bindValue( ":uid", $this->uid, PDO::PARAM_INT );
                $stmt->bindValue( ":book_id", $this->book_id, PDO::PARAM_INT );
                $stmt->bindValue( ":key", $this->_key, PDO::PARAM_STR );

                // execute the query.
                $stmt->execute();
                $this->id = $this->id() - 1;
                $this->savedOnce = true;                
                
            }
            catch( PDOException $e ){
                // need to log this error.                                
                Logger::log( "Faild to insert row into Book table: ".$e->getMessage(), "72" );
            }
        }
        else{
            // no need to insert, just update.
            $this->update();
        }
    }
    public function update() {

    }
    public static function setCBit( $db, $key, $u_id, $b_id ){
        //if( $this->savedOnce ){
        $sql = "update ConfirmationInfo set cbit = 1  where _key = :key AND uid = :user_id AND book_id = :bid ;";
        try{
            $stmt = $db->prepare( $sql );
            $stmt->bindValue( ":key", $key, PDO::PARAM_STR );        
            $stmt->bindValue( ":user_id", $u_id, PDO::PARAM_INT );
            $stmt->bindValue( ":bid", $b_id, PDO::PARAM_INT );
            $stmt->execute();           
        }
        catch( PDOException $e ){
                    //  need to log this error.
            Logger::log( "Failed to update Book table: ".$e->getMessage(), "98" );
        }
            //}
 	}   


    public static function getPurchaseInfo ( $db, $key){
        
    	$sql = "select uid, book_id from ConfirmationInfo where _key=:key";
    	try {
    			$stmt = $db->prepare($sql);
    			$stmt->bindValue(":key", $key, PDO::PARAM_STR);
    			$stmt->execute();

    			$purchaseInfo = array();
                while( ($row = $stmt->fetch(PDO::FETCH_ASSOC) ) ){
    				$details = array();
                    $details['uid'] = $row['uid'];
                    $details['book_id'] = $row['book_id'];
    				array_push($purchaseInfo, $details);
    					
    			}
    			return $purchaseInfo;
    	}
        catch( PDOException $e ){
                    //  need to log this error.
            echo $e->getMessage();
            Logger::log( "Failed to get info from purchase table: ".$e->getMessage(), "107" );
        }
    
    }  
    //gourav- add the condition where cbit = 1
    public static function delete ( $db, $key , $u_id, $b_id ) {
            
        $sql = "delete from ConfirmationInfo where _key=:key AND uid = :user_id AND book_id = :bid ;";
        try {
                $stmt = $db->prepare($sql);
                $stmt->bindValue(":key", $key, PDO::PARAM_STR);
                $stmt->bindValue( ":user_id", $u_id, PDO::PARAM_INT );
                $stmt->bindValue( ":bid", $b_id, PDO::PARAM_INT );
                $stmt->execute();
        }
        catch ( PDOException $e ) {
                
                echo $e->getMessage();
                Logger::log( "Failed to get info from purchase table: ".$e->getMessage(), "107" ); 
        }       

    }
   

}
   




