<!-- Parallax Effect -->
<script type="text/javascript">
	$(document).ready(function()
		{
			$('#parallax-pagetitle').parallax("50%", -0.55);
	});
	
function changePagination(pageId,liId){
	
     $(".flash").show();
     $(".flash").fadeIn(400).html
                ('Loading <img src="ajax-loader.gif" />');
     var dataString = 'pageId='+ pageId;
     $.ajax({
           type: "POST",
           url: "loadData.php",
           data: dataString,
           cache: false,
           success: function(result){
                 $(".flash").hide();
                 $(".link a").removeClass("In-active current") ;
                 $("#"+liId+" a").addClass( "In-active current" );
                 $("#pageData").html(result);
           }
      });
}

</script>


<section class="parallax-effect">
	<div id="parallax-pagetitle" style="background-image: url(./images/parallax/1900x911.gif);">
		<div class="color-overlay">
			<!-- Page title -->
			<div class="container">
				<div class="row">
					<div class="col-sm-12">
						<ol class="breadcrumb">
							<li>
								<a href="index.php">
									Home
								</a>
							</li>
							<li class="active">
								Hotel list view
							</li>
						</ol>
						<h1>
							Hotel list view
						</h1>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Filter -->
<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<ul class="nav nav-pills" id="filters">
				<li class="active">
					<a href="#" data-filter="*">
						All
					</a>
				</li>
				<li>
					<a href="#" data-filter=".single">
						Single Room
					</a>
				</li>
				<li>
					<a href="#" data-filter=".double">
						Double Room
					</a>
				</li>
				<li>
					<a href="#" data-filter=".executive">
						Executive Room
					</a>
				</li>
				<li>
					<a href="#" data-filter=".apartment">
						Apartment
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>

<!-- Hotels -->
<section class="rooms mt100">
	<div class="container">
		<div id = "pagination">
			
				<!--div id="content" class="content"-->
				<?php

				if($_SESSION){
					//if(isset($_SESSION['location']))
					$location = $_SESSION['location'];
					//if(isset($_SESSION['checkin']))
					$checkin  = $_SESSION['checkin'];
					//if(isset($_SESSION['checkout']))
					$checkout = $_SESSION['checkout'];
					//if(isset($_SESSION['room']))
					$room     = $_SESSION['room'];
					//if(isset($_SESSION['adult']))
					$adults   = $_SESSION['adults'];
					//if(isset($_SESSION['children']))
					$children = $_SESSION['children'];
				}
				// session_destroy();

				$location = mysql_real_escape_string($location);

				$result   = mysql_query("SELECT location_cities.CityId FROM location_cities WHERE LOWER(location_cities.CityName) LIKE LOWER('$location');");

				if(!$result){
					//error
				}

				$cityId   = mysql_fetch_row($result);

				$xml_parm = array("Cityid"   =>$cityId[0],"Startdate"=>$checkin,"Enddate"  => $checkout);

				$return_value = $etb->SearchCityAvailability($xml_parm);

				$h_Id         = '';

				$index        = 0;

				foreach($return_value[0]->Hotel as $key=>$value){
					$h_Id = $h_Id.$value->Id->__toString().',';
					$index = $index + 1;
				}

				

				if( $index <= 0 )
				{
					echo '<script>alert "No search result, sorry!"';
					echo 'location.href = "index.php"</script>';
					exit();
				}
				$h_Id      = rtrim($h_Id, ",");
				$h_Id      = mysql_real_escape_string($h_Id);

				$sql       = "SELECT hotel_hotel.id AS hotel_id,hotel_hotel.Name AS hotel_name, hotel_hotel.Picture AS hotel_img,hotel_hotel.MinPrice AS hotel_minprice,hotel_desc.description AS hotel_desc,hotel_desc.ImportantDescription AS hotel_importantdesc,hotel_rooms.BreakfastIncluded AS hotel_breakfast
				FROM hotel_hotel LEFT OUTER JOIN (hotel_desc,hotel_rooms) ON hotel_desc.hotelId = hotel_hotel.id AND hotel_rooms.HotelId = hotel_hotel.id WHERE hotel_hotel.id IN ($h_Id)";

					$_SESSION['hotel'] = $h_Id;
					
					echo '<script>changePagination(\'0\',\'first\');</script>';

					//$query = "select id from pagination order by id desc";
					$res   = mysql_query($sql);
					$count = mysql_num_rows($res);
					
					if($count > 0)
					{
						$paginationCount = getPagination($count);
					}

					?>
					<div id="pageData"></div>
					<?php
					//echo "<script>alert('".$paginationCount."');</script>";
					$content = "";
					if($count > 0)
					{
						//echo "<script>alert('".$count."')</script>";
						
						$content .= '<ul class="tsc_pagination tsc_paginationC tsc_paginationC01">
						<li class="first link" id="first">
						<a  href="javascript:void(0)" onclick="changePagination(\'0\',\'first\')">F i r s t</a>
						</li>';
						
						for($i = 0;$i < $paginationCount;$i++)
						{

							$content .= '<li id="'.$i.'_no" class="link">
							<a  href="javascript:void(0)" onclick="changePagination(\''.$i.'\',\''.$i.'_no\')">
							'.($i + 1).'
							</a>
							</li>';
						}
						
						$content .= '<li class="last link" id="last">
						<a href="javascript:void(0)" onclick="changePagination(\''.($paginationCount - 1).'\',\'last\')">L a s t</a>
						</li>
						<li class="flash"></li>
						</ul>';
					}
				?>
		</div>
	</div>
	<div style="height: 30px;"></div>
	<div style="width: 800px; margin: 0 auto;"><?php echo $content; ?></div>
</section>

