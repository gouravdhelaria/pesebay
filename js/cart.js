var cart_button_visible = false;
var hide = true;
$( document ).ready( function( e ){
	$( "#inline").fancybox({
		width:767,
		height:121
	});
	var mycart = $( "div.mycart" ).find( "ol" );
	var cart_button = $( "div#cart_button" );
	$( "a.cart" ).click( function( e ){
		//  get the book id.
		var id = $( this ).attr( "id" );
		// send an ajax request to cart.php script at the server.
		$.post( "cart.php", { "id" : id }, function( data, status ){
				//mycart.find( "table" ).remove();
				mycart.find( "li#cart_info" ).html( data );
				cart_button.show();
		});
		return false;
	});
	$( "div#cart_data" ).click( function( e ){		
		if( $(e.target).attr( "class" ) == "delete" ){

			var id = $( e.target ).attr( "id" );
			var $this = e.target;
			$.post( "delete.php", { "id": id }, function( data, status ){
				data = JSON.parse( data );				
				if( data.status == true ){
					$($this).parents( "table" ).remove();
				}
				else{
					alert( "Failed to remove the item." );
				}
			});			
	   }
	   else if( $( e.target ).attr( "custom") == "next" ){
	   	return true;
	   }
	   return false;
	});
});