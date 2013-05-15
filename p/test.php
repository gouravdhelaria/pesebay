<?php
preg_match( "/Set-Cookie:(.*?)\r\n/", "Set-Cookie: pesebay=e44046c4a7b760162016ce3278b6142f; expires=Wed, 25-Dec-2013 09:14:30 GMT; path=/; httponly\r\n", $match );
print_r( $match );
?>
