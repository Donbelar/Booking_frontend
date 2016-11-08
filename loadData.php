<?php
require("includes/config.php");
if(isset($_POST['pageId']) && !empty($_POST['pageId'])){
   $id=$_POST['pageId'];
}else{
   $id='0';
}

//$h_Id = $_POST['hotelid'];
$h_Id = $_SESSION['hotel'];

$sql       = "SELECT hotel_hotel.id AS hotel_id,hotel_hotel.Name AS hotel_name, hotel_hotel.Picture AS hotel_img,hotel_hotel.MinPrice AS hotel_minprice,hotel_desc.description AS hotel_desc,hotel_desc.ImportantDescription AS hotel_importantdesc,hotel_rooms.BreakfastIncluded AS hotel_breakfast
				FROM hotel_hotel LEFT OUTER JOIN (hotel_desc,hotel_rooms) ON hotel_desc.hotelId = hotel_hotel.id AND hotel_rooms.HotelId = hotel_hotel.id WHERE hotel_hotel.id IN ($h_Id)";
				

$pageLimit=PAGE_PER_NO*$id;

$query= $sql." limit ".$pageLimit.",".PAGE_PER_NO;

$res = mysql_query($query);
$count = mysql_num_rows($res);
$HTML='';
if($count > 0){
	$i = 0;
	while($row = mysql_fetch_array ($res)){
		$i = $i + 1;

		echo '<div class="col-sm-4 single">';
		echo '<div class="room-thumb"> <img src="'.$row["hotel_img"].'" alt="room'.$i.'" class="img-responsive" />
		<div class="mask">
		<div class="main">
		<h5>'.$row["hotel_name"].'</h5>
		<div class="price">&euro;'.$row["hotel_minprice"].'<span>a night</span></div>
		</div>
		<div class="content">
		<p><span>Description Position</span> Description Position</p>
		<div class="row">
		<div class="col-xs-6">
		<ul class="list-unstyled">
		<li><i class="fa fa-check-circle"></i>'.$row["hotel_breakfast"].'</li>
		<li><i class="fa fa-check-circle"></i> facility 02</li>
		<li><i class="fa fa-check-circle"></i> facility 03</li>
		</ul>
		</div>
		<div class="col-xs-6">
		<ul class="list-unstyled">
		<li><i class="fa fa-check-circle"></i>Free WiFi</li>
		<li><i class="fa fa-check-circle"></i> '.$row["hotel_breakfast"].'</li>
		<li><i class="fa fa-check-circle"></i> facility 06</li>
		</ul>
		</div>
		</div>
		<a href="http://dev.granulr.uk/petwa/hotel.php?h='.$row["hotel_id"].'" class="btn btn-primary btn-block">Book Now</a> </div>
		</div>
		</div>
		</div>';
	}
	}else{
	    $HTML='No Data Found';
	}
echo $HTML;

?>