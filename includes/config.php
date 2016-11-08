<?php

/*-------------------------------------------------------+
| Petwa CMS - Hotel Booking
| http://www.espressowebdesign.co.uk
+--------------------------------------------------------+
| Author: James Simpson
+--------------------------------------------------------+*/

ob_start();
session_start();

// db properties
define('DBHOST','localhost');
//define('DBUSER','granulr.uk');
//define('DBPASS','AhM1cpnmM6G0QkVJ');
define('DBUSER','root');
define('DBPASS','');
define('DBNAME','cl50-petwa');

// Easy To Book Properties
define('ETBUSER','petwa_sirike');
define('ETBPASS','318b1942');
define('ETBCAMP','280832193');

// make a connection to mysql here
$conn = @mysql_connect (DBHOST, DBUSER, DBPASS);
$conn = @mysql_select_db (DBNAME);
if(!$conn){
	die( "Sorry! There seems to be a problem connecting to our database.");
}

// define site path
define('DIR','http://dev.granulr.uk/petwa/');

// define admin site path
define('DIRADMIN','http://dev.granulr.uk/petwa/admin/');

// define site title for top of the browser
define('SITETITLE','SìYESOUI');

//define include checker
define('included', 1);

include('functions.php');
?>
