<?php
require_once( "logger.php" );
require_once( "db.php" );
require_once( "Mail.php" );
require_once( "user.php" );
require_once( "book.php" );
require_once( "transaction.php" );
require_once( "helper.php" );

if( isset( $_POST[ "submit" ] ) ){
    // getPostData function will return a User Object, and Book array object.
    
    $db = Database::connect();
    list( $user, $books, $authors, $auths ) = getPostData( $db );
    
    // Do some processing if you want to.
    
    // save function will put the data into database.
    
    // Save user data into User table.
    $user->save();
    
    // get the secret key.
    $key = $user->getSecretkey();

    
    
    for( $i = 0; $i < count( $books ); $i++ ){
        // save book info.        
        $books[ $i ]->save();
        $id = $books[ $i ]->getId();        
        for( $j = 0; $j < count( $authors[ $i ] ); $j++ ){
            $authors[ $i ][ $j ]->setBookId( $id );
            $authors[ $i ][ $j ]->save();            
        }

        $transaction = new Transaction( $db, array( "user_id" => $user->getId(),
                                                    "book_id" => $books[ $i ]->getId(),
                                                    "tid" => $key,
                                                    "in_store" => 0,
                                                    "sold_out" => 0 ) );
        $transaction->save();

    }
    // close database connection.
    Database::close( $db );
    
    // send mail.
    $msg = getSenderMessage($user, $key, $books, $auths );

    if( $user->sendMail( $key, $msg) )
        Logger::log( "Mail:Mail sent successfully", "42" );
    else
        Logger::log( "Mail:Mail sending failed", "44" );

    header( "Location:../h/thanks.html" );
}
else{
    header( "Location:home.php" );
}
?>
