<?php 
	
	// check if domain registered
	if( strpos(file_get_contents("domains.txt"), $_GET['domain']) !== false) {
        // do stuff
        echo "registered";
    } else {
    	echo "not registered";
    }


?>