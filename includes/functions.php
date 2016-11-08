<?php
/*-------------------------------------------------------+
| Petwa CMS - Hotel Booking
| http://www.espressowebdesign.co.uk
+--------------------------------------------------------+
| Author: James Simpson
+--------------------------------------------------------+*/

if (!defined('included')){
die('You cannot access this file directly!');
}

// Easy To Book Functions
require 'easytobook_xml.class.php';
require 'easytobook_http.class.php';

// Start the instance
$etb = new Easytobook ('petwa_sirike','318b1942','test','280832193');

class Easytobook {


    // Environment (String): "production" | "test"
    protected static $env = 'test';

    // Easytobook API credentials (String). Provided by EasyToBook
    protected static $username, $password;
    protected static $campaignid = 1;

    // API environment URLs (Array)
    protected static $api_urls = array(
        'test'          => 'http://testnl.etbxml.com/api',
        'production'    => 'http://www.etbxml.com/webservice/server_v21.php'
    );

    public static $credit_cards = array(
        1 => 'Visa',
        2 => 'MasterCard',
        3 => 'AmericanExpress',
        4 => 'Diners',
        5 => 'Discover',
        6 => 'JCB',
        7 => 'LaserDebit',
        8 => 'VisaDebit',
        9 => 'VisaElectronDebit',
        10 => 'CarteBleueDebit'
    );


    function __construct($username, $password, $env, $campaignid) {
        if (!in_array($env, array('test', 'production'))) {
            $this->error('Environment "'.htmlentities($env).'" is not valid. Only "production" and "test" are supported.');
        }

        self::$username = $username;
        self::$password = $password;
        self::$env      = $env;
        self::$campaignid = $campaignid;
    }


    /**
     * Get Easytobook API url by current app environment
     *
     * @return string
     */
    protected static function api_url() {
        return self::$api_urls[self::$env];
    }

    /**
     *
     */
    public static function parse_credit_cards($cc_ids){
        // convert ids to Array and clean non existent ids
        $cc_ids = array_filter(explode(',', $cc_ids), function($id){
            return isset(Easytobook::$credit_cards[$id]);
        });
        // convert ids array to cc names array
        return array_map(function($id){
            return Easytobook::$credit_cards[$id];
        }, $cc_ids);
    }

    public static function get_credit_card_id($name){
        $ccs = array_flip(self::$credit_cards);
        return $ccs[$name];
    }

    /**
     * Returns a list of the top cities where EasytoBook hotels are present where ID, name, country, country-code for each city is provided.
     * Important: This function only returns the list of top cities, but not the entire list of cities where EasytoBook hotels could be found;
     * the full list can be obtained from the static data feeds available at this API.
     *
     * More info: http://etbxml.com/protocol/ApiFunctions.php?name=GetCityList
     */
    function GetCityList(){
        $xml = EasytobookXML::build_request(
            array('Function' => 'GetCityList'),
            array('plain_xml' => true)
        );

        $xml_obj = simplexml_load_string(EasytobookHTTP::post($xml));
        return $xml_obj->xpath('/soap:Envelope/soap:Body/EasytobookResponse/Response/City');
    }


    /**
     * Returns information about the specific city being requested.
     * The information includes ID, name, country, country-code and a description text.
     * More info: http://etbxml.com/protocol/ApiFunctions.php?name=GetCityList
     *
     * @param array $xml_args additional input params, ex: array( 'Cityid' => 123 )
     */
    function GetCityInfo($xml_args){
        $xml_args = array_merge(
            array('Function' => 'GetCityInfo'),
            $xml_args
        );

        $xml = EasytobookXML::build_request( $xml_args, array('plain_xml' => true) );
        $xml_obj = simplexml_load_string(EasytobookHTTP::post($xml));

        return $xml_obj->xpath('/soap:Envelope/soap:Body/EasytobookResponse/Response/Cityinfo');
    }


    /**
     * Returns a list of all active and selling hotels in the city being requested.
     * Information for each hotel includes id, name, address details, star rating,
     * brief description text and url for main picture.
     * More info: http://etbxml.com/protocol/ApiFunctions.php?name=GetCityHotelList
     *
     * @param array $xml_args additional input params (or override default), ex: array( 'Cityid' => 123, 'Language' => 'it' )
     */
    function GetCityHotelList($xml_args){
        $xml_args = array_merge(
            array('Function' => 'GetCityHotelList', 'Language' => 'en'),
            $xml_args
        );

        $xml = EasytobookXML::build_request( $xml_args, array('plain_xml' => true) );
        $xml_obj = simplexml_load_string(EasytobookHTTP::post($xml));

        return $xml_obj->xpath('/soap:Envelope/soap:Body/EasytobookResponse/Response/Hotel');
    }

    /**
     * Returns a list and detailed information of all active and selling hotels in the city being requested.
     * Information for each hotel includes id, name, address details, star rating, brief description text,
     * brief location text, url for main picture, number of total rooms, check-in & out times, longitued
     * and lattitude of the hotel location, and also text fields describing wireless internet connection availability,
     * parking availability, tourist tax, breakfast and cancellation policy.
     * More info: http://etbxml.com/protocol/ApiFunctions.php?name=HotelInformationInCity
     *
     * @param array $xml_args additional input params (or override default), ex: array( 'Cityid' => 123, 'Language' => 'it' )
     */

    function HotelInformationInCity($xml_args){
        $xml_args = array_merge(
            array('Function' => 'HotelInformationInCity', 'Language'  => 'en'),
            $xml_args
        );

        $xml = EasytobookXML::build_request( $xml_args, array('plain_xml' => true) );
        $xml_obj = simplexml_load_string(EasytobookHTTP::post($xml));

        return $xml_obj->xpath('/soap:Envelope/soap:Body/EasytobookResponse/Response/Hotel');
    }


    /**
     * Returns a list of hotel images (indicated by URL) for a given hotel.
     *
     * @param array $xml_args additional input params (or override default), ex: array( 'Hotelid' => 123 )
     */
    function GetHotelPhotos($xml_args){
        $xml_args = array_merge( array('Function' => 'GetHotelPhotos'), $xml_args );

        $xml = EasytobookXML::build_request( $xml_args, array('plain_xml' => true) );
        $xml_obj = simplexml_load_string(EasytobookHTTP::post($xml));

        return $xml_obj->xpath('/soap:Envelope/soap:Body/EasytobookResponse/Response/Photos/Image');
    }

    /**
     * Returns a list of available hotels for a given city, list of hotels, or a list of hotel id's 
     * Returns room availability in a given city, between given dates and for given number of occupants by listing the 
     * hotels and the rooms where there is an availability. Hotel information includes id, name, address details, star rating, 
     * brief description text and url for main picture. Information on each room includes id, name, capacity, availability, total & rack 
     * rates, breakfast, URLs for hotel details and booking pages. Billable currency is indicated in currency attribute. If requested 
     * currency is not billable, conversion to that currency will be provided in corresponding attribute. 
     * If currency is not requested, conversion rates will be appended for the following currencies: EUR, USD, GBP, AUD, CAD, CZK.
     * More info: https://www.etbxml.com/protocol/ApiFunctions.php?name=SearchAvailability
     *
     * @param array $xml_args additional input params (or override default), ex: array()....
    */

    function SearchAvailability($xml_args){
        $xml_args = array_merge(
            array('Function' => 'SearchAvailability' ),
            $xml_args
        );

        $xml = EasytobookXML::build_request( $xml_args,array('pain_xml' => true) );
        $xml_obj = simple_load_string(EasytobookHTTP::post($xml));
        return $xml_obj->xpath('/soap:Envelope/soap:Body/EasytobookResponse/Response/Hotel');
    }


    /**
     * Returns room availability at a given hotel, between given dates by listing all available rooms at the hotel.
     * Hotel information includes id, name, address details, description text, star rating, url for main picture,
     * list of facilities, number of total rooms and total room types. Information on each room includes
     * id, name, total & rack rates, discount percentage and discount value, room description, room image,
     * list of facilities, capacity, availability, info on breakfast, info on tourist tax, URLs for hotel details and
     * booking pages, as well as cancellation policy text. Billable currency is indicated in currency attribute.
     * If requested currency is not billable, conversion to that currency will be provided in corresponding attribute.
     * If currency is not requested, conversion rates will be appended for the following currencies: EUR, USD, GBP, AUD, CAD, CZK.
     * More info: http://etbxml.com/protocol/ApiFunctions.php?name=GetHotelAvailability
     *
     * @param array $xml_args additional input params (or override default), ex: array( 'Hotelid' => 123, 'Startdate' => '2011-08-27', 'Enddate' => '2011-08-28' )
     */
    function GetHotelAvailability($xml_args){
        $xml_args = array_merge(
            array('Function' => 'GetHotelAvailability', 'Currency'  => 'GBP', 'Language' => 'en', 'Shownoavailrooms' => 0),
            $xml_args
        );

        $xml = EasytobookXML::build_request( $xml_args, array('plain_xml' => true) );
        $xml_obj = simplexml_load_string(EasytobookHTTP::post($xml));
        return $xml_obj->xpath('/soap:Envelope/soap:Body/EasytobookResponse/Response/Hotelinfo');
    }


    /**
     * Returns room availability in a given city, between given dates and for given number of occupants
     * by listing the hotels where there is an availability and only the cheapest room per hotel. Hotel information
     * includes id, name, city. Room information includes id, name, capacity, availability, total & rack rates, breakfast,
     * URLs for hotel details and booking pages. Billable currency is indicated in currency attribute. If requested
     * currency is not billable, conversion to that currency will be provided in corresponding attribute.
     * If currency is not requested, conversion rates will be appended for the following currencies: EUR, USD, GBP, AUD, CAD, CZK.
     * More info: http://etbxml.com/protocol/ApiFunctions.php?name=SearchHotelCheapestRoomInCity
     *
     *
     */
    function SearchHotelCheapestRoomInCity($xml_args){
        $xml_args = array_merge(
            array('Function' => 'SearchHotelCheapestRoomInCity', 'Currency'  => 'EUR', 'Language' => 'en', 'Shownoavailrooms' => 0),
            $xml_args
        );

        $xml = EasytobookXML::build_request( $xml_args, array('plain_xml' => true) );
        $xml_obj = simplexml_load_string(EasytobookHTTP::post($xml));

        return $xml_obj->xpath('/soap:Envelope/soap:Body/EasytobookResponse/Response/Hotel');
    }


    function SearchCityAvailability($xml_args){
        $xml_args = array_merge(
            array('Function' => 'SearchCityAvailability', 'Currency'  => 'EUR', 'Language' => 'en', 'Shownoavailrooms' => 0),
            $xml_args
        );

        $xml = EasytobookXML::build_request( $xml_args, array('plain_xml' => true) );
        $xml_obj = simplexml_load_string(EasytobookHTTP::post($xml));

        return $xml_obj->xpath('/soap:Envelope/soap:Body/EasytobookResponse/Response/Hotelinfo');
    }


    function CreateBooking($xml_args){
        $xml_args = array_merge(
            array('Function' => 'CreateBooking', 'campaignid' => 1, 'Currency'  => 'GBP', 'Language' => 'en'),
            $xml_args
        );

        $xml = EasytobookXML::build_request( $xml_args, array('plain_xml' => true) );
        $response = EasytobookHTTP::post($xml);
        $xml_obj = simplexml_load_string($response);
        return $xml_obj->xpath('/soap:Envelope/soap:Body/EasytobookResponse/Response');
    }

    protected function error($msg){
        trigger_error($msg, E_USER_ERROR);
    }

    /**
	* Function to import the database files from Easy To Book
	* There are multiple files that are updated every week from the website
	* This will need to be ran via a cron to update the database every week
    * To add a new feed if one is released, just add it to the database
    */
    function import_etb_files($file){
    	$username = 'petwa_sirike';
		$password = '318b1942'; 
		$file_url = 'http://etbxml.com/static/'.$file.' --auth-no-challenge --http-user='.$username.' --http-password='.$password;
        $db_file  = $file; // Used to tell script what file is being called

		// Download the file from the Easy To Book Website
		$outputfile = "feeds/".$file;
		$cmd = "wget ".$file_url." -O $outputfile";
		exec($cmd);

		// Now we need to uncompress the file 
		// This input should be from somewhere else, hard-coded in this example
		$file_name = $outputfile;

		// Raising this value may increase performance
		$buffer_size = 4096; // read 4kb at a time
		$out_file_name = str_replace('.gz', '', $file_name); 
		// Open our files (in binary mode)
		$file = gzopen($file_name, 'rb');
		$out_file = fopen($out_file_name, 'wb'); 
		// Keep repeating until the end of the input file
        
    	while(!gzeof($file)) {
            // Read buffer-size bytes
            // Both fwrite and gzread and binary-safe
            fwrite($out_file, gzread($file, $buffer_size));
        }  
        
		// Files are done, close files
		fclose($out_file);
		gzclose($file);

        // Now need to update the database - we need to get the corresponding db table
        // This is done by getting the table name from the database for the file used
        echo $db_file;
        echo "<br>";
        $get_table = mysql_result(mysql_query("SELECT `dbName`  FROM `site_db_files` WHERE `catalog` = ".$db_file),0);

        if(!$get_table == ''){
            // We now need to import all the contents to the db
            mysql_query("TRUNCATE TABLE".$get_table.";"); // Clear the contents of the table first
            exit();
            mysql_query("LOAD DATA LOCAL INFILE '".getcwd() . "/" . $out_file_name."'
                         REPLACE
                         INTO TABLE ".$get_table." FIELDS TERMINATED BY ';' 
                         OPTIONALLY ENCLOSED BY '\"' 
                         LINES TERMINATED BY '\n'
                         IGNORE 1 LINES") or die('Query failed. ' . mysql_error());
        }else{
            // There is no database table set in the database
            // We will now throw an error message to say this.
            $_SESSION['error'] = "No Database Entry"; 
            header('Location: ' .DIRADMIN.'feeds.php');
            exit();
        }
        // The data has successfully been updated to the database
        // We will update the database table with the time and date
        mysql_query("UPDATE `site_db_files` SET  `lastupdated` =  NOW() WHERE `catalog` = '".$db_file."'");

        // We will now tell the user that the feed has successfully updated
		$_SESSION['success'] = "Feed (".$db_file.") Updated"; 
        header('Location: ' .DIRADMIN.'feeds.php');
        exit();
	}

    // Function to build the facility list table on the hotel page
    function render_facility_table($hotel_id){

        $id = mysql_result(mysql_query("SELECT id FROM games LIMIT 1"),0);

        echo "<tr>";
        $i = 0;
        while($row = mysql_fetch_array($result)){
            echo $row['name']. " - ". $row['age'];
            echo "<br />";
            $i++;
        }


        echo'
        <tr>
            <td><i class="fa fa-check-circle"></i> Double Bed</td>
            <td><i class="fa fa-check-circle"></i> Free Internet</td>
            <td><i class="fa fa-check-circle"></i> Free Newspaper</td>
        </tr>';

    }

}


//print_r($d->GetCityList());
//echo "success";
//print_r($d->GetCityInfo(2134));


// Website Functions

// Login
function login($user, $pass){

   //strip all tags from varible   
   $user = strip_tags(mysql_real_escape_string($user));
   $pass = strip_tags(mysql_real_escape_string($pass));

   $pass = md5($pass);

   // check if the user id and password combination exist in database
   $sql = "SELECT * FROM site_users WHERE username = '$user' AND password = '$pass'";
   $result = mysql_query($sql) or die('Query failed. ' . mysql_error());
      
   if (mysql_num_rows($result) == 1) {
      // the username and password match,
      // set the session
	  $_SESSION['authorized'] = true;
					  
	  // direct to admin
      header('Location: '.DIRADMIN);
	  exit();
   } else {
	// define an error message
	$_SESSION['error'] = 'Sorry, wrong username or password';
   }
}

// Authentication
function logged_in() {
	if($_SESSION['authorized'] == true) {
		return true;
	} else {
		return false;
	}	
}

function login_required() {
	if(logged_in()) {	
		return true;
	} else {
		header('Location: '.DIRADMIN.'login.php');
		exit();
	}	
}

function logout(){
	unset($_SESSION['authorized']);
	header('Location: '.DIRADMIN.'login.php');
	exit();
}

// Render error messages
function messages() {
    $message = '';
    if($_SESSION['success'] != '') {
        $message = '<div class="msg-ok">'.$_SESSION['success'].'</div>';
        $_SESSION['success'] = '';
    }
    if($_SESSION['error'] != '') {
        $message = '<div class="msg-error">'.$_SESSION['error'].'</div>';
        $_SESSION['error'] = '';
    }
    echo "$message";
}

function errors($error){
	if (!empty($error))
	{
			$i = 0;
			while ($i < count($error)){
			$showError.= "<div class=\"msg-error\">".$error[$i]."</div>";
			$i ++;}
			echo $showError;
	}// close if empty errors
} // close function

// Render the home page slider
function home_slider(){
    
}
// This will be the main function to search for a location
// User will enter a city name into the search
// Will return a list of hotel id's
function search_location(){

}

// Used to get all the hotel images with just the id passed
function GetHotelImages($hotelid){

    $data = array();
    // Get all the images from 
    $sql = mysql_query("SELECT * FROM `hotel_img` WHERE `HotelId`='".$hotelid."'");
    while($data = mysql_fetch_assoc($sql)){
        $img_url_array[] = $data;
    }
    return $img_url_array;
}

################ pagination function #########################################
define('PAGE_PER_NO',9); // number of results per page.
function getPagination($count){
      $paginationCount= floor($count / PAGE_PER_NO);
      $paginationModCount= $count % PAGE_PER_NO;
      if(!empty($paginationModCount)){
               $paginationCount++;
      }
      return $paginationCount;
}
?>