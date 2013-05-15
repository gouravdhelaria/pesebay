<?php
require_once( "db.php" );
require_once( "book.php" );
require_once( "search_history.php" );
require_once( "book_tag.php" );
//require_once( "./query/amazon_book_query.php" );

define( "JAC", 0 );

abstract class Recommendation{
    protected $type;
    protected $similarity_metric = JAC;
    protected $db;
    
    abstract function recommend( $user_id );
}

class ItemBasedRecommendation extends Recommendation{
    
    function __construct( $db, $kw ){
        if( array_key_exists( "type", $kw ) )
            $this->type = $kw[ "type" ];
        if( array_key_exists( "similarity_metric", $kw ) )
            $this->similarity_metric = $kw[ "similarity_metric" ];
        if( array_key_exists( "book", $kw ) )
            $this->book = $kw[ "book" ];
        $this->db = $db;
    }    
    public function recommend( $hash ){
        $book_list = Book::getAllBooks( $this->db, false );
        $user_booklist = SearchHistory::getBooksByUser( $this->db, $hash );
        //print_r( $user_booklist );
        $unseen_books_list = array_diff( $book_list, $user_booklist );
        $recommend = array();
        $max_search = getMaxSearch( $this->db );
        //echo "\n\n";
        foreach( $unseen_books_list as $i => $value ){
                $item1 = $unseen_books_list[ $i ];
                $search_count_i = getSearchCount( $this->db, $item1 );
                $sim_total = 0.0;
                $weighted_sim = 0.0;
                $rating = 0.0;
                //echo "item1: $item1\n";
                foreach( $user_booklist as $j => $val ){
                    $item2 = $user_booklist[ $j ];
                    $search_count_j = getSearchCount( $this->db, $item2 );
                    $sim = SimilarityTable::getSimilarity( $this->db, $item1, $item2 );
                    $tsim = SimilarityTable::getSimilarity( $this->db, $item1, $item2, "TopicSimilarityTable" );
                    $freq = $search_count_i / $max_search;
                    
                    $weighted_sim += ( $sim + $tsim )* $freq;
                }
                //echo "item1: $item1,\titem2: $item2,\tsim: $weighted_sim\n";
                //sleep( 1 );
                $recommend[$item1] = ( $weighted_sim / count( $user_booklist ) );
        }
            // sort the array before sending it.
           arsort( $recommend );
           return $recommend;
           
     }
}
// 1. create tables.
// 2. fill the data. [ 20 books, 10 users ]
// 3. create specific classes.
// 4. check this on the terminal.

class SimilarityTable{

    private $db, $similarity_metric = JAC;
    function __construct( $db ){
        $this->db = $db;
    }
    public function buildSimilarityTable(){
        // this returns a list of all book ids.
        // numbered from 1 - n for convenience.
        $book_list = Book::getAllBooks( $this->db );
        $len = count( $book_list );
        //echo "Book length: ".$len."\n";
        
        for( $i = 0; $i < $len; $i++ ){
            $item1 = $book_list[ $i ];
            for( $j = $i + 1; $j < $len; $j++ ){
                $item2 = $book_list[ $j ];
                // finds the similarity between the two books.
                $sim = $this->findSimilarity( $item1, $item2 );
                $tsim = $this->findTopicSimilarity( $item1, $item2 );
                // then store it in the similarity table if not zero.
                $this->save( $item1, $item2, $sim );
                $this->save( $item1, $item2, $tsim, "TopicSimilarityTable" );
                echo "".$item1->getName()."-->".$item2->getName()." : ".$sim."\t".$tsim."\n";
                
            }
        }
        echo "Similarity Table Generated!!\n";
    }
    public function findSimilarity( $item1, $item2 ){
        $sim = 0.0;
        
        if( $this->similarity_metric == JAC ){
            $user_union = SearchHistory::getUserUnionCount( $this->db, $item1, $item2 );
            $user_countfor_both = SearchHistory::getUsersForBoth( $this->db, $item1, $item2 );
            if( ( $user_union ) > 0 )
                $sim = $user_countfor_both / ( $user_union );
        }
        return $sim;
    }
    public function findTopicSimilarity( $item1, $item2 ){
        $sim = 0.0;
        $topic_union = BookTag::getTopicUnionCount( $this->db, $item1, $item2 );
        $topic_intersect = BookTag::getTopicIntersectCount( $this->db, $item1, $item2 );
        if( $topic_union > 0 )
            $sim = $topic_intersect / $topic_union;
        return $sim;
    }
    public function save( $item1, $item2, $sim, $table_name = "SimilarityTable" ){
        $item1_id = $item1->getId();
        $item2_id = $item2->getId();
        try{
            $sql = "select count(*) from $table_name where bid1=:b1 and bid2=:b2";
            $stmt = $this->db->prepare( $sql );
            $stmt->bindValue( ":b1", $item1_id, PDO::PARAM_INT );
            $stmt->bindValue( ":b2", $item2_id, PDO::PARAM_INT );
            $stmt->execute();
            $count = $stmt->fetch( PDO::FETCH_ASSOC );
            if( $count[ "count(*)" ] > 0 ){
                //echo "here\n";
                $sql = "update $table_name set sim=:sim where bid1=:b1 and bid2=:b2";
                $stmt = $this->db->prepare( $sql );
                $stmt->bindValue( ":b1", $item1_id, PDO::PARAM_INT );
                $stmt->bindValue( ":b2", $item2_id, PDO::PARAM_INT );
                $stmt->bindValue( ":sim", $sim, PDO::PARAM_STR );
                $stmt->execute();
            }
            else{
                $sql = "insert into $table_name ( bid1, bid2, sim ) values ( :b1, :b2, :sim )";
                $stmt = $this->db->prepare( $sql );
                $stmt->bindValue( ":b1", $item1->getId(), PDO::PARAM_INT );
                $stmt->bindValue( ":b2", $item2->getId(), PDO::PARAM_INT );
                $stmt->bindValue( ":sim",$sim, PDO::PARAM_STR );
                $stmt->execute();
            }
        }
        catch( PDOException $e ){
        }
    }
    public static function getSimilarity( $db, $bid1, $bid2, $table_name = "SimilarityTable" ){
        $sql = "select sim from $table_name where bid1 = :b1 and bid2 = :b2";
        try{
            $stmt = $db->prepare( $sql );
            $stmt->bindValue( ":b1", $bid1, PDO::PARAM_INT );
            $stmt->bindValue( ":b2", $bid2, PDO::PARAM_INT );
            $stmt->execute();            
            $sim = $stmt->fetch( PDO::FETCH_ASSOC );
            if( $sim )
                return $sim[ "sim" ];
            else
                return 0.0;
        }
        catch( PDOException $e ){
        }
    }
}

function getMaxSearch( $db ){
    $sql = "select max( count ) as c from BookSearchCount";
    try{
        $stmt = $db->prepare( $sql );
        $stmt->execute();
        $count = $stmt->fetch( PDO::FETCH_ASSOC );
        return $count[ "c" ];
    }
    catch( PDOException $e ){
        echo $e->getMessage()."\n";
    }
}

function getSearchCount( $db, $item ){
    $sql = "select count from BookSearchCount where book_id = :_1";
    try{
        $stmt = $db->prepare( $sql );
        $stmt->bindValue( ":_1", $item, PDO::PARAM_INT );
        $stmt->execute();
        $count = $stmt->fetch( PDO::FETCH_ASSOC );
        return $count[ "count" ];
    }
    catch( PDOException $e ){
        echo $e->getMessage()."\n";
    }
}
//$db = Database::connect();
//$a = new ItemBasedRecommendation( $db, array() );
//print_r( $a->recommend( "071b7de2f5dcdff18f13518611aa9147" ) );
?>

