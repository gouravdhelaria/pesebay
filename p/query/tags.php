<?php
$conc = array();
if( file_exists( "concepts.txt" ) ){
        $file = fopen( "concepts.txt", "r" );
        if( $file ){
            while( !feof( $file ) ){
                $line = fgets( $file );
                array_push( $conc, trim( $line ) );
            }
        }
}

function getTags( $book ){
    global $conc;
    $summary = $book->getDescription();
    $subjects = $book->getSubjects();
    $summary = tokenize( $summary );
    $subjects = tokenize( implode( " ", $subjects ) );
            
    $tags = union( $summary, $subjects );
    return intersect( $tags, $conc );
}

function union( $a1, $a2 ){
    $target = array();
    foreach( $a1 as $val ){
        $target[ $val ] = $val;
    }
    foreach( $a2 as $val ){
        $target[ $val ] = $val;
    }
    $retVal = array();
    foreach( $target as $val ){
        $retVal[] = $val;
    }
    return $retVal;
}

function intersect( $a1, $a2 ){
    sort( $a1 );
    sort( $a2 );
    $len1 = count( $a1 );
    $len2 = count( $a2 );
    $retVal = array();
    $i = 0;
    $j = 0;
    while( $i < $len1 && $j < $len2 ){
        //echo "intersect loop.$j\n";
        if( $a1[ $i ] === $a2[ $j ] ){
            $retVal[] = $a1[ $i ];
            $i++;
            $j++;
        }
        else if( $a1[ $i ] < $a2[ $j ] ){
            $i++;
        }
        else if( $a1[ $i ] > $a2[ $j ] ){
            $j++;
        }
    }
    return $retVal;
}

function tokenize( $data ){
    $pattern = "/[\s,.()\[\]&;?-]+/";
    return preg_split( $pattern, $data, -1, PREG_SPLIT_NO_EMPTY );
}
?>
