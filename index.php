<?php 
/*-------------------------------------------------------+
| Petwa CMS - Hotel Booking
| http://www.espressowebdesign.co.uk
+--------------------------------------------------------+
| Author: James Simpson
+--------------------------------------------------------+*/

// Include the main files reqired
require('includes/config.php'); 
require('template_parts/head.php'); 

// Now we need to figure out what page the user is trying to view
// If no page clicked on load home page default to it of 1
if(!isset($_GET['p'])){
	$q = mysql_query("SELECT * FROM site_pages WHERE id='1'");
} else { //load requested page based on the id
	$id = $_GET['p']; //get the requested id
	$id = mysql_real_escape_string($id); //make it safe for database use
	$q  = mysql_query("SELECT * FROM site_pages WHERE id='$id'");
}
	
//get page data from database and create an object

$r = mysql_fetch_object($q);
?>
<script>
$(document).ready(function() {
    $("#results" ).load( "fetch_pages.php"); //load initial records
   
    //executes code below when user click on pagination links
    $("#results").on( "click", ".pagination a", function (e){
        e.preventDefault();
        $(".loading-div").show(); //show loading element
        var page = $(this).attr("data-page"); //get page number from link
        $("#results").load("fetch_pages.php",{"page":page}, function(){ //get content from PHP page
            $(".loading-div").hide(); //once done, hide loading element
        });
       
    });
});
</script>
<?php
// Load the header template 
require('template_parts/header.php');

// See if a template has been set to be displayed
if(isset($r->pageTemplate)){
	require('template_parts/'.$r->pageTemplate);
}else{
	//print the pages content 	
	echo "<h1>$r->pageTitle</h2>";
	echo $r->pageCont;
}
?>


<?php
require('template_parts/footer.php');
?>