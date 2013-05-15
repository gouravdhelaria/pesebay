<?php
require_once( "logger.php" );
require_once( "transaction.php" );

// Book code.
class Book{
    private $bid;
    private $name;
    private $edition;
    private $publication;
    private $discount;
    private $condition;
    private $marked_price;
    private $selling_price;
    private $time;
    private $description;
    private $db;
    private $savedOnce = false;
        
    public function __construct( $db, $info ){
        $this->db = $db;
        if( array_key_exists( "book_id", $info ) )
            $this->bid = $info[ "book_id" ];
        $this->name = $info[ "name" ];
        $this->edition = $info[ "edition" ];
        $this->publication = $info[ "publication" ];
        $this->marked_price = $info[ "marked_price" ];
        $this->selling_price = $info[ "selling_price" ];
        $this->discount = $info[ "discount" ];
        if( array_key_exists( "isbn", $info ) )
            $this->isbn = $info[ "isbn" ];
        if( array_key_exists( "description", $info ) )
            $this->description = $info[ "description" ];
        if( array_key_exists( "condition", $info ) )
            $this->condition = $info[ "condition" ];
        else
            $this->condition = $info[ "_condition" ];
    }
    
    // getter interface.
    public function getId(){
        return $this->bid;   
    }
    public function getName(){
        return $this->name;
    }
    public function getEdition(){
        return $this->edition;
    }
    public function getPublication(){
        return $this->publication;
    }
    public function getDiscount(){
        return $this->discount;
    }
    public function getCondition(){
        return $this->condition;
    }
    public function getMarkedPrice(){
        return $this->marked_price;
    }
    public function getSellingPrice(){
        return $this->selling_price;
    }
    public function getAuthors(){
        return $this->authors;
    }
    public function getDescription(){
        return $this->description;
    }
    
    // setter interface.
    public function setId( $id ){
        $this->bid = $id;        
    }
    public function setName( $name ){
        $this->name = $name;
    }
    public function setEdition( $edition ){
        $this->edition = $edition;
    }
    public function setPublication( $pub ){
        $this->publication = $pub;
    }
    public function setDiscount ( $disc) {
        $this->discount = $disc;
    }
    public function setCondition ( $cond ) {
        $this->condition = $cond;
    }
    public function setMarkedPrice( $price ){
        $this->marked_price = $price;
    }
    public function setSellingPrice( $price ){
        $this->selling_price = $price;
    }
    public function setDescription( $desc ){
        $this->description = $desc;
    }

    private function id(){
        // create a query.
        $sql = "SELECT Auto_increment FROM information_schema.tables WHERE table_name='Book' AND table_schema = DATABASE();";
        try{
            $stmt = $this->db->prepare( $sql );
            $stmt->execute();
            $id = $stmt->fetch();
            return $id[ "Auto_increment" ];
        }
        catch( PDOException $e ){
            // need to log this error.
            Logger::log( "Failed to get auto_increment value from Book table: ". $e->getMessage(), "46" );
        }
    }
    public function save(){
        if( !$this->savedOnce ){
            try{
                // construct query
                $sql = "insert into Book ( name, edition, publication, discount, _condition ,marked_price, selling_price, description ) values ( :name, :ed, :pub, :disc, :cond, :mp, :sp , :desc);";
                $stmt = $this->db->prepare( $sql );
                $stmt->bindValue( ":name", $this->name, PDO::PARAM_STR );
                $stmt->bindValue( ":ed", $this->edition, PDO::PARAM_INT );
                $stmt->bindValue( ":pub", $this->publication, PDO::PARAM_STR );
                $stmt->bindValue( ":disc", $this->discount, PDO::PARAM_STR);
                $stmt->bindValue( ":cond", $this->condition, PDO::PARAM_STR);
                $stmt->bindValue( ":mp", $this->marked_price, PDO::PARAM_STR );
                $stmt->bindValue( ":sp", $this->selling_price, PDO::PARAM_STR );
                $stmt->bindValue( ":desc", $this->description, PDO::PARAM_STR );

                // execute the query.
                $stmt->execute();                
                $this->bid = $this->id() - 1;
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
    public function update( $flag ){
        //if( $this->savedOnce ){
        $sql = "update Book set name = :name, edition = :ed, publication = :pub, marked_price = :mp, selling_price = :sp, description = :desc
                where book_id = :id;";
        try{
            $stmt = $this->db->prepare( $sql );
                    
            $stmt->bindValue( ":name", $this->name, PDO::PARAM_STR );
            $stmt->bindValue( ":ed", $this->edition, PDO::PARAM_INT );
            $stmt->bindValue( ":pub", $this->publication, PDO::PARAM_STR );
            $stmt->bindValue( ":mp", $this->marked_price, PDO::PARAM_STR );
            $stmt->bindValue( ":sp", $this->selling_price, PDO::PARAM_STR );
            $stmt->bindValue( ":desc", $this->description, PDO::PARAM_STR );
            $stmt->bindValue( ":id", $this->bid, PDO::PARAM_INT );
            
            $stmt->execute();
        }
        catch( PDOException $e ){
                    //  need to log this error.
            Logger::log( "Failed to update Book table: ".$e->getMessage(), "98" );
        }
            //}
    }
    public static function getBooksByTitle( $db, $book_title ){
        // construct query.        
        $sql = "select * from Book where name like :book";
        try{
            $stmt = $db->prepare( $sql );
            $book_title = "%".$book_title."%";
            $stmt->bindParam( ":book", $book_title, PDO::PARAM_STR );
            $stmt->execute();        
                $books = array();
            $row = $stmt->fetch( PDO::FETCH_ASSOC );            
            while( $row ){                
                //$leg_row = Transaction::checkBookInStore( $db, $row[ "book_id" ] );
                if( true){//$leg_row ){
                    $b = new Book( null, array( 
                                                "name" => $row[ "name" ],
                                                "edition" => $row[ "edition" ],
                                                "publication" => $row[ "publication" ],
                                                "marked_price" => $row[ "marked_price" ],
                                                "selling_price" => $row[ "selling_price"],
                                                "description" => $row[ "description"],
                                                "condition" => $row["_condition"]
                                              ) );
                    $b->setId( $row[ "book_id"] );                
                    array_push( $books, $b );
                }
                $row = $stmt->fetch( PDO::FETCH_ASSOC );
            }            
        }
        catch( PDOException $e ){            
            Logger::log( "Error in getBookByTitle function: ".$e->getMessage(), "156" );
        }
        return $books;
    }
    public static function getBookNameArray( $db, $title ){
        $books = self::getBooksByTitle( $db, $title );
        $ret_val = "[";
        $tmp_string="";
        $string = array();
        for( $i = 0; $i < count( $books ); $i++ ){
            $name = $books[ $i ]->getName();
            $tmp_string=<<<END
            { "id": "$name", "label": "$name", "value": "$name" }
END;

            array_push( $string, $tmp_string );
        }
        $string = implode( ",", $string );
        $ret_val .= $string."]";        
        return $ret_val;
    }
    public static function getBookById( $db, $id ){
        // construct query.
        $sql = "select * from Book where book_id = :id;";
        try{
            $stmt = $db->prepare( $sql );
            $stmt->bindValue( "id", $id, PDO::PARAM_INT );
            $stmt->execute();
            $row = $stmt->fetch( PDO::FETCH_ASSOC );            
            $b = new Book( $db, $row );
            $b->setId( $row[ "book_id"] );            
            return $b;
        }
        catch( PDOException $e ){
            Logger::log( "Error in getBookById function: ". $e->getMessage(), "165" );
        }
    }
    public static function getAllBooks( $db, $flag = true ){
        $sql = "select * from Book";
        try{
            $stmt = $db->prepare( $sql );
            $stmt->execute();
            $books = array();
            while( ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) ){
                if( $flag )
                    $books[] = new Book( $db, $row );
                else
                    $books[] = $row[ "book_id" ];
            }
            return $books;
        }
        catch( PDOException $e ){
        }
    }
}
    
    // Author Code.
class Author{
    private $author_id;
    private $book_id;
    private $author;
    private $db;
    private $savedOnce = false;
        
    public function __construct( $db, $info ){
        $this->db = $db;
        $this->book_id = $info[ "id" ];
        $this->author = $info[ "author" ];
    }
    private function id(){
        // create a query.
        $sql = "SELECT Auto_increment FROM information_schema.tables WHERE table_name='BookAuthors' AND table_schema = DATABASE();";
        try{
            $stmt = $this->db->prepare( $sql );
            $stmt->execute();
            $id = $stmt->fetch();
            return $id[ "Auto_increment" ];
        }
        catch( PDOException $e ){
            // need to log this error.
            Logger::log( "Failed to get auto_increment value from Book table: ". $e->getMessage(), "46" );
        }
    }
    public function getAuthorId( $id ){
        return $this->author_id;
    }
    public function getBookId(){
        return $this->book_id;
    }
    public function getAuthor(){
        return $this->author;
    }
    public function setBookId( $id ){
        $this->book_id = $id;
    }
    public function setAuthorId( $id ){
        $this->author_id = $id;
    }
    public function setAuthor( $authors ){
        if( is_array( $authors ) ){
            $this->authors = array();
            for( $i = 0; $i < count( $authors ); $i++ )
                array_push( $this->author, $authors[ $i ] );
        }
    }
    public function save(){
        if( !$this->savedOnce ){
            try{
                // create query.
                $sql = "insert into BookAuthors ( book_id, name ) values ( :id, :name );";
                $stmt = $this->db->prepare( $sql );
                $stmt->bindValue( ":id", $this->book_id, PDO::PARAM_INT );
                
                $stmt->bindValue( ":name", $this->author, PDO::PARAM_STR );
                $stmt->execute();                
                $this->author_id = $this->id() - 1;
                $this->savedOnce = true;
            }
            catch( PDOException $e ){
                // need to log this error.
                Logger::log( "Failed to insert row into BookAuthors table: ".$e->getMessage(), "142" );
            }
        }
        else{
            $this->update();
        }
    }
    public function update(){
        //if( $this->savedOnce ){
        try{
            $sql = "update BookAuthors set book_id = :id, name = :name where author_id = :aid;";
            $stmt = $this->db->prepare( $sql );

            $stmt->bindValue( ":aid", $this->author_id, PDO::PARAM_INT );
            $stmt->bindValue( ":id", $this->book_id, PDO::PARAM_INT );
            $stmt->bindValue( ":name", $this->author, PDO::PARAM_STR );
            $stmt->execute();
        }
        catch( PDOException $e ){
            // need to log this error.
            Logger::log( "Falied to update BookAuthors table: ".$e->getMessage(), "163" );
        }
    }

    // This function only retures author names not Author objects.
    public static function getAuthorByBook( $db, $books ){
        try{
            $sql = "select * from BookAuthors where book_id = :bid;";
            $stmt = $db->prepare( $sql );
            $authors = array();
            for( $i = 0; $i < count( $books); $i++ ){                
                $stmt->bindValue( ":bid", $books[ $i ]->getId(), PDO::PARAM_INT );
                $stmt->execute();
                $auth = array();
                $row = $stmt->fetch( PDO::FETCH_ASSOC );
                while(  $row ){
                    array_push( $auth, $row[ "name"] );
                    $row = $stmt->fetch( PDO::FETCH_ASSOC );
                }
                array_push( $authors, $auth );
            }
        }
        catch( PDOException $e ){
            Logger::log( "Error in getAuthorByBook: ".$e->getMessage(), "270" );
        }
        return $authors;
    }
}
?>
