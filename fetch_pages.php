<?php
echo "<script>alert('1');</script>";
//Get page number from Ajax






if(isset($_POST["page"])){
    $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
    if(!is_numeric($page_number)){die('Invalid page number!');} //incase of invalid page number
}else{
    $page_number = 1; //if there's no page number, set it to 1
}
$h_Id = $_SESSION['HotelID'];
$sql = "SELECT hotel_hotel.id AS hotel_id,hotel_hotel.Name AS hotel_name, hotel_hotel.Picture AS hotel_img,hotel_hotel.MinPrice AS hotel_minprice,hotel_desc.description AS hotel_desc,hotel_desc.ImportantDescription AS hotel_importantdesc,hotel_rooms.BreakfastIncluded AS hotel_breakfast
FROM hotel_hotel LEFT OUTER JOIN (hotel_desc,hotel_rooms) ON hotel_desc.hotelId = hotel_hotel.id AND hotel_rooms.HotelId = hotel_hotel.id WHERE hotel_hotel.id IN ($h_Id);";

$q = "SELECT COUNT(*) FROM hotel_hotel LEFT OUTER JOIN (hotel_desc,hotel_rooms) ON hotel_desc.hotelId = hotel_hotel.id AND hotel_rooms.HotelId = hotel_hotel.id WHERE hotel_hotel.id IN ($h_Id);";
//get total number of records from database
$results = $mysqli_conn->query($q);
$get_total_rows = $results->fetch_row(); //hold total records in variable

echo "<script>alert('".$get_total_rows."');</script>";

//get total number of records from database
$results = $mysqli_conn->query("SELECT COUNT(*) FROM paginate");
$get_total_rows = $results->fetch_row(); //hold total records in variable
//break records into pages
$total_pages = ceil($get_total_rows[0]/$item_per_page);

//position of records
$page_position = (($page_number-1) * $item_per_page);

//Limit our results within a specified range.
$results = $mysqli->prepare("SELECT id, name, message FROM paginate ORDER BY id ASC LIMIT $page_position, $item_per_page");
$results->execute(); //Execute prepared Query
$results->bind_result($id, $name, $message); //bind variables to prepared statement

//Display records fetched from database.
echo '<ul class="contents">';
while($results->fetch()){ //fetch values
    echo '<li>';
    echo  $id. '. <strong>' .$name.'</strong> &mdash; '.$message;
    echo '</li>';
}
echo '</ul>';

echo '<div align="center">';
// To generate links, we call the pagination function here.
echo paginate_function($item_per_page, $page_number, $get_total_rows[0], $total_pages);
echo '</div>';

?>