$(document).ready( function(){
    
    autocomplete( $( "input#query" ), {
                                            //url: "bcomplete.php",
                                            keys : [ "bhimsen", "sagar", "santosh", "gourav", "ganesh", "santo", "santu" ],
                                            threshold: 3,
                                            overflow : false,
                                      }
                );

});

function autocomplete( obj, opt ){
    var keys_seen = 0,
        url_given = false,
        BACKSPACE = 8,
        KEYDOWN   = 40,
        KEYUP     = 38,
        container = $( "<div></div>" ),
        width     = obj.width(),
        leftX     = obj.offset().left,
        topY      = obj.offset().top + obj.height() + 10,
        query, sub_container, tmp, cursor = 0;
    
    opt.overflow  =  opt.overflow || true;
    opt.threshold = opt.threshold || 1;
    if( typeof opt.url !== 'undefined' )
        url_given = true;
    container.addClass( "bcomplete_container" );
    container.width( width );
    container.css( { "position" : "absolute", "left" : leftX + "px" , "top" : topY + "px" } );
    $("body").append( container );
    // bind the keyup event to the jquery object.
    obj.bind( "keyup", function( event ){
                            if( event.keyCode == KEYDOWN ){
                                return;
                            }
                            else if( event.keyCode == KEYUP ){
                                return;
                            }
                            else if( event.keyCode == BACKSPACE )
                                keys_seen--;
                            else
                                keys_seen++;
                            
                            if( keys_seen > opt.threshold ){
                                query  = $(this).val();
                                result = Array();
                                if( url_given ){
                                    // send the ajax request to the server.
                                    // show the result in a div.
                                }
                                else{
                                    for( var i = 0; i < opt.keys.length; i++ ){
                                        if( opt.keys[ i ].indexOf( query ) != -1 )
                                            result.push( opt.keys[ i ] );
                                    }
                                    sub_container = [];
                                    for( var i = 0; i < result.length; i++ ){
                                        tmp = $( "<div></div>" );
                                        tmp.addClass( "bcomplete_sub_container" );
                                        tmp.text( result[ i ] );
                                        sub_container.push( tmp );
                                    }
                                    container.empty().append( sub_container );
                                }
                            }
                       });
    obj.bind( "keydown", function( event ){
                            if( event.keyCode == KEYDOWN ){
//                                $(this).blur();
                                cursor = processKeyDown( container, cursor );
                                return;
                            }
                            else if( event.keyCode == KEYUP ){
//                                $(this).blur();                            
                                cursor = processKeyUp( container, cursor );
                                return;
                            }
                         });
}
function processKeyDown( obj, cursor ){
    var divs = obj.find( "div.bcomplete_sub_container" );
    cursor++;
    if( cursor >= divs.length )
        cursor = divs.length - 1;
    
    divs.eq( ( cursor - 1 < 0 ? 0: cursor - 1 ) ).css( "color", "black" );
    divs.eq( cursor ).css( "color", "red" );
    return cursor;
}

function processKeyUp( obj, cursor ){
    var divs = obj.find( "div.bcomplete_sub_container" );
    cursor--;
    if( cursor < 0 )
        cursor = 0;
    else
        divs.eq( cursor + 1 ).css( "color", "black" );
    divs.eq( cursor ).css( "color", "red" );
    return cursor;
}
