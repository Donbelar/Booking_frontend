<?php 
/*-------------------------------------------------------+
| Petwa CMS - Hotel Booking
| http://www.espressowebdesign.co.uk
+--------------------------------------------------------+
| Author: James Simpson
+--------------------------------------------------------+*/
require('includes/config.php'); 

if($_POST){

	$location   = $_POST['location'];
	$checkin    = $_POST['checkin'];
	$checkout   = $_POST['checkout'];
	$room    	= $_POST['room'];
	$adults    	= $_POST['adults'];
	$children   = $_POST['children'];

	if(trim($location) == '') {
		// Location is not set
		echo '<div class="alert alert-danger alert-dismissable">
	  	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Attention! Please enter a valid location.</div>';
//	    echo("<script>location.href='index.php';</script>"); 
		exit();
	} else if(trim($room) == '') {
		// Room Type not set
		echo '<div class="alert alert-danger alert-dismissable">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Attention! Please enter a your room.</div>';
//		echo("<script>location.href='index.php';</script>"); 
		exit();
	} else if(trim($checkin) == '') {
		// Check In date not set
		echo '<div class="alert alert-danger alert-dismissable">
	  	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Attention! Please enter your check-in date.</div>';
//	  	echo("<script>location.href='index.php';</script>"); 
		exit();
	} else if(trim($checkout) == '') {
		// Check Out date not set
		echo '<div class="alert alert-danger alert-dismissable">
	  	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Attention! Please enter your check-out date.</div>';
//	  	echo("<script>location.href='index.php';</script>"); 
		exit();
	} else{
		// Everything has been set - redirect user
		//header('Location: http://www.provider.com/process.jsp?id=12345&name=John');
 	
		$_SESSION['location']   = $location;
		$_SESSION['checkin']    = $checkin;
		$_SESSION['checkout']   = $checkout;
		$_SESSION['room']       = $room;
		$_SESSION['adults']     = $adults;
		$_SESSION['children']   = $children;
	    
	    /*
    	apc_store('location', $user_profile['id']);
    	apc_store('checkin', $user_profile['id']);
    	apc_store('checkout', $user_profile['id']);
    	apc_store('room', $user_profile['id']);
    	apc_store('adults', $user_profile['id']);
    	apc_store('children', $user_profile['id']);
    	*/    	
    	
		echo 'success';
		exit();
}
}else{

// Include the main files reqired


// Now we need to figure out what page the user is trying to view
// If no page clicked on load home page default to it of 1
if(!isset($_GET['s'])){
	// Return the user to the homepage here as no search set
	//header('Location: ' .DIR , true);
    //exit();
} else { //load requested page based on the id
	$id = $_GET['s']; //get the requested id
	$id = mysql_real_escape_string($id); //make it safe for database use
	$q  = mysql_query("SELECT * FROM site_pages WHERE id='$id'");
	
}

	require('template_parts/head.php'); 
//get page data from database and create an object


//$r = mysql_fetch_object($q);


// Load the header template 
	require('template_parts/header.php');

// See if a template has been set to be displayed
	include('template_parts/page-search.php');

?>


<?php
	require('template_parts/footer.php');
}
?>