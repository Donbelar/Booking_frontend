<?php 
/*-------------------------------------------------------+
| Petwa CMS - Hotel Booking
| http://www.espressowebdesign.co.uk
+--------------------------------------------------------+
| Author: James Simpson
+--------------------------------------------------------+*/

// Include the main files reqired
require('includes/config.php'); 
// Now we need to figure out what page the user is trying to view
// If no hotel is selected, we need to redirect back to search page
// Just put a id in here for now to test
if(!isset($_GET['h'])){
	$q = mysql_query("SELECT * FROM hotel_hotel RIGHT JOIN hotel_desc ON `hotel_hotel`.`id`=`hotel_desc`.`hotelid` WHERE `hotel_hotel`.`id`='428808'");
} else { //load requested page based on the id
	$id = $_GET['h']; //get the requested id
	$id = mysql_real_escape_string($id); //make it safe for database use
	$q  = mysql_query("SELECT * FROM hotel_hotel RIGHT JOIN hotel_desc ON `hotel_hotel`.`id`=`hotel_desc`.`hotelid` WHERE `hotel_hotel`.`id`='$id'");
}

// get page data from database and create an object - hotel_hotel table
$r = mysql_fetch_object($q);

// Need to post a title
if(isset($r->Name)){
	$title = $r->Name . ' ' . $r->AddressCity;
}
// Include the head template
require('template_parts/head.php'); 
// Load the header template 
require('template_parts/header.php');

// Initialise the Easy To Book API
$etb = new Easytobook ('petwa_sirike','318b1942','production','280832193');
$array0 = array('Hotelid' => $r->id, 'Startdate' => '2015-11-10', 'Enddate' => '2015-11-14', 'Language' => 'en', 'Shownoavailrooms' => '1');
//print_r($array0);
//print_r($d->GetHotelAvailability($array0));

// Require the hotel template part
require('template_parts/page-hotel.php');


// Close everything off with the footer
require('template_parts/footer.php');
?>