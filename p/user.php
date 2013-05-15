<?php
require_once( "logger.php" );
require_once( "db.php" );

    class User{
        private $id;
        private $usn;
        private $name;
        private $semester;
        private $section;
        private $mobile_no;
        private $email;
        private $payment;
        private $db;
        private $savedOnce = false;
        
        public function __construct( $db, $info ){
            $this->db = $db;
            $this->usn       = $info[ "usn" ];
            $this->name      = $info[ "name" ];
            $this->semester  = $info[ "semester" ];
            $this->section   = $info[ "section" ];
            if( array_key_exists( "mobile_no", $info ) )            
                $this->mobile_no = $info[ "mobile_no" ];
            $this->email     = $info[ "email" ];
        }
        // getter interface
        public function getId(){
            return $this->id;            
        }
        public function getUsn(){
            return $this->usn;
        }
        public function getName(){
            return $this->name;            
        }
        public function getSemester(){
            return $this->semester;
        }
        public function getSection(){
            return $this->section;
        }
        public function getMobileNo(){
            return $this->mobile_no;
        }
        public function getEmail(){
            return $this->email;
        }
        
        //  setter interface
        public function setId( $id ){
            $this->usn = $id;
        }
        public function setName( $name ){
            $this->name = $name;
        }
        public function setSection( $section ){
            $this->section = $section;
        }
        public function setSemester( $s0em ){
            $this->semester = $sem;
        }
        public function setMobileNo( $num ){
            $this->mobile_no = $num;
        }
        public function setEmail( $email ){
            $this->email = $email;
        }
        private function id(){
        // create a query.
            $sql = "SELECT Auto_increment FROM information_schema.tables WHERE table_name='User' AND table_schema = DATABASE();";
            try{
                $stmt = $this->db->prepare( $sql );
                $stmt->execute();
                $id = $stmt->fetch();
                return $id[ "Auto_increment" ];
            }
            catch( PDOException $e ){
            // need to log this error.
            Logger::log( "Failed to get auto_increment value from User table: ". $e->getMessage(), "74" );
            }
        }
        
        public function save(){
            if( !$this->savedOnce ){
                // construct query.
                $sql = "insert into User ( usn, name, semester, section, mobile_number, email ) values ( :usn, :name, :semester, :section,
                        :mobile_no, :email );";
                try{
                    $stmt = $this->db->prepare( $sql );
                    // bind values
                    $stmt->bindValue( ":usn", $this->usn, PDO::PARAM_STR );
                    $stmt->bindValue( ":name", $this->name, PDO::PARAM_STR );
                    $stmt->bindValue( ":semester", $this->semester, PDO::PARAM_INT );
                    $stmt->bindValue( ":section", $this->section, PDO::PARAM_STR );
                    $stmt->bindValue( ":mobile_no", $this->mobile_no, PDO::PARAM_INT );
                    $stmt->bindValue( ":email", $this->email, PDO::PARAM_STR );
                    
                    // execute the query.
                    $stmt->execute();
                    $this->id = $this->id() -1;
                    $this->savedOnce = true; // next time update the table, do not insert.
                }
                catch( PDOException $e ){
                    // need to log this error
                    Logger::log( "Failed to insert row into User table: ".$e->getMessage(). "46" );
                }
            }
            else{
                $this->update();
            }
        }
        public function update(){
            //if( $this->savedOnce ){
                // construct query
                try{
                    $sql = "update User set usn = :usn,  name = :name, semester = :sem, section = :section, mobile_number = :mob_no,
                            email = :email where uid = :uid;";
                            
                    $stmt = $this->db->prepare( $sql );
                    $stmt->bindValue( ":usn", $this->usn, PDO::PARAM_STR );
                    $stmt->bindValue( ":name", $this->name, PDO::PARAM_STR );
                    $stmt->bindValue( ":sem", $this->semester, PDO::PARAM_INT );
                    $stmt->bindValue( ":section", $this->section, PDO::PARAM_STR );
                    $stmt->bindValue( ":mob_no", $this->mobile_no, PDO::PARAM_INT );
                    $stmt->bindValue( ":email", $this->email, PDO::PARAM_STR );
                    
                    $stmt->execute();               
                }
                catch( PDOException $e ){
                    //  need to log this error.
                    Logger::log( "Failed to update User table: ".$e->getMessage(), "72" );
                }
            //}
        }
        public function sendMail( $key , $message= "This is your transaction key:" , $flag=false ){
        
            $headers["From"]   = "<replyno123@gmail.com>";
            $headers["to"]    = "<".$this->email.">";
            $headers["subject"]   = "Books.inherit() Transaction Info";  
            $headers["Content-Type"] = "text/html; charset=UTF-8\r\n"; 
           /* if (!$flag)
                $mailmsg    = $message.$key;
            else*/
                $mailmsg = $message;

            /* SMTP server name, port, user/passwd */

            $smtpinfo["host"]   = "ssl://smtp.gmail.com";   
            $smtpinfo["port"]   = 465;    
            $smtpinfo["auth"]   = true;    
            $smtpinfo["username"]   = "replyno123@gmail.com";
            $smtpinfo["password"]   = "NewBaDPesiT2908";
            $smtpinfo["debug"]  = true;

            /* Create the mail object using the Mail::factory method */

            // $mail_object =& Mail::factory("smtp", $smtpinfo);
            // EDIT -- removed reference   


            $mail_object = Mail::factory( "smtp", $smtpinfo );

            /* Ok send mail */

            $result = $mail_object->send( $headers[ "to" ], $headers, $mailmsg );

            if( PEAR::isError( $result ) )
                return false;
            else    
                 return true;
        }
        public function getSecretKey($flag = false){
            if(!$flag) {
                return md5( $this->usn );
            }
            else {
                //added time to key. To get unique keys for different transactions by the same user
                $time_val = time();
                $key = $this->mobile_no.$this->usn.$time_val;
                return md5($key);
            }
        }

        public function getMobileNum(){
            return $this->mobile_no;
        }
        public static function getUserById( $db, $id ){                
            $sql = "select * from User where id = :i";
            try{
                $stmt = $db->prepare( $sql );
                $stmt->bindValue( ":i", $id, PDO::PARAM_INT );
                $stmt->execute();
                
                $row = $stmt->fetch( PDO::FETCH_ASSOC );
                $user = null;
                if( $row ){
                    $user = new User( $db, $row );                                    
                }
                return $user;
            }
            catch( PDOException $e ){
                echo $e->getMessage();
            }
            
        }
    }
?>
