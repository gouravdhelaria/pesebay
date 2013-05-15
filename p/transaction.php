<?php
    class Transaction{
        private $user_id;
        private $tid;
        private $book_id;
        private $time;
        private $in_store = 0;
        private $sold_out = 0;
        private $db;
        private $savedOnce = false;
        
        public function __construct( $db, $info ){
            $this->db = $db;
            $this->user_id = $info[ "user_id" ];
            $this->tid = $info[ "tid" ];
            $this->book_id = $info[ "book_id" ];
            if( array_key_exists( "in_store", $info ) )
                $this->in_store = $info[ "in_store" ];
            if( array_key_exists( "sold_out", $info ) )
                $this->sold_out = $info[ "sold_out" ];
            if( array_key_exists( "time", $info ) )
                $this->time = $info[ "time" ];
        }
        public function getTime(){
            return $this->time;
        }
        public function getSoldOut(){
            return $this->sold_out;
        }
        public function getInStore(){
            return $this->in_store;
        }
        public function getUserId(){
            return $this->user_id;
        }
        public function getTransId(){
            return $this->tid;
        }
        public function getBookId(){
            return $this->book_id;
        }
        public function save(){
            if( !$this->savedOnce ){
                try{
                    // create query.
                    $sql = "insert into Transaction ( uid, transaction_id, book_id ) values ( :uid, :tid, :bid );";                    
                    $stmt = $this->db->prepare( $sql );
                    $stmt->bindValue( ":uid", $this->user_id, PDO::PARAM_INT );
                    $stmt->bindValue( ":tid", $this->tid, PDO::PARAM_STR );
                    $stmt->bindValue( ":bid", $this->book_id, PDO::PARAM_INT );
                    
                    // execute the query.
                    $stmt->execute();
                    $this->savedOnce = true;
                }
                catch( PDOException $e ){
                    // need to log this error.
                    Logger::log( "Failed to insert row into Transaction table: ".$e->getMessage(), "46" );
                }
            }
            else{
                $this->update();
            }
        }
        public function update(){
            //  need book_id, user_id and book_id for update.
            if( $this->savedOnce ){
                try{
                    $sql = "update table Transaction set uid = :uid, transaction_id = :tid, book_id = :bid, in_store = :is, 
                            sold_out = :so;";
                    $stmt = $this->db->prepare( $sql );
                    
                    $stmt->bindValue( ":uid", $this->user_id, PDO::PARAM_INT );
                    $stmt->bindValue( ":tid", $this->tid, PDO::PARAM_INT );
                    $stmt->bindValue( ":bid", $this->book_id, PDO::PARAM_INT );
                    $stmt->bindValue( ":is", $this->in_store, PDO::PARAM_INT );
                    $stmt->bindValue( ":so", $this->sold_out, PDO::PARAM_INT );
                    
                    $stmt->execute();
                }
                catch( PDOException $e ){
                    Logger::log( "Falied update Transaction table: ".$e->getMessage(), "70" );
                }
            }
        }
    public static function checkBookInStore( $db, $bid ){
        $sql = "select * from Transaction where book_id = :bid and in_store = 1 and sold_out != 1;";
        try{
            $stmt = $db->prepare( $sql );
            $stmt->bindParam( ":bid", $bid, PDO::PARAM_INT );
            $stmt->execute();
            $row = $stmt->fetch( PDO::FETCH_ASSOC );            
            if( $row )
                return true;
            else
                return false;
        }
        catch( PDOException $e ){
            Logger::log( "Error while fetching data from Transaction table: ".$e->getMessage(), "86" );
        }
    }
    public static function getAllTransactions( $db ){
        $sql = "select * from Transaction order by time desc";
        try{
            $stmt = $db->prepare( $sql );
            $stmt->execute();
            $trans = array();
            while( ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) ){
                $row[ "user_id" ] = $row[ "uid" ];
                $row[ "tid" ] = $row[ "transaction_id" ];
                array_push( $trans, new Transaction( $db, $row ) );
            }
            return $trans;
        }
        catch( PDOException $e ){
            echo $e->getMessage()."\n";
        }
    }
    
    //gourav
    public static function getTransactionUser ( $db, $b_id ) {
        $sql = "select uid from Transaction where book_id = :bid and in_store=0";
        try {
            $stmt = $db->prepare($sql);    
            $stmt->bindValue( ":bid", $b_id , PDO::PARAM_INT );
            $stmt->execute();
        }
        catch( PDOException $e ){
                Logger::log( "Failed in getting book ids: ".$e->getMessage(), "86" );
        }
        
        $row = $stmt->fetch(  PDO::FETCH_ASSOC );
        $user_id = $row["uid"];
        return $user_id;    
    }

    //gourav
    public static function getBookIds( $db , $tid ) {        
        $bookidlist = array();      
        try {
        
            $sql = "select book_id from Transaction where transaction_id= :tid";
            $stmt = $db->prepare( $sql );
            $stmt->bindValue( ":tid", $tid, PDO::PARAM_STR );
            $stmt->execute();
        }
        catch( PDOException $e ){
                Logger::log( "Failed in getting book ids: ".$e->getMessage(), "86" );
        }
        $row = $stmt->fetch( PDO::FETCH_ASSOC );
        while( $row ){
            array_push($bookidlist, $row['book_id']);
            $row = $stmt->fetch( PDO::FETCH_ASSOC );
        }
        return $bookidlist;
    
    }
    //gourav
    public static function updateInstore( $db, $tid, $bid ) {
        try {
            $sql = "update Transaction set in_store=1 where transaction_id=:tid and book_id=:bid and in_store=0"; 
            $stmt = $db->prepare( $sql );
            $stmt->bindValue( ":tid", $tid , PDO::PARAM_STR );
            $stmt->bindValue(":bid", $bid , PDO::PARAM_INT);
            $stmt->execute();
         }
        catch( PDOException $e ){
            Logger::log( "Failed in getting book ids: ".$e->getMessage(), "86" );
        }
    
    }

    public static function soldout ( $db, $bookid) {
        try{

            $sql = "update Transaction set sold_out=1 where book_id=:bid and in_store=1"; 
            $stmt = $db->prepare( $sql );
            $stmt->bindValue( ":bid", $bookid, PDO::PARAM_STR );
            $stmt->execute();
        }
        catch( PDOException $e ){
            // need to log this error.
        }
    }   

}

?>
