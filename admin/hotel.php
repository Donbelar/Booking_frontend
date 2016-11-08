<?php 
/*-------------------------------------------------------+
| Petwa CMS - Hotel Booking
| http://www.espressowebdesign.co.uk
+--------------------------------------------------------+
| Author: James Simpson
+--------------------------------------------------------+*/

require('../includes/config.php'); 

//make sure user is logged in, function will redirect use if not logged in
login_required();

//if logout has been clicked run the logout function which will destroy any active sessions and redirect to the login page
if(isset($_GET['logout'])){
	logout();
}

//run if a page deletion has been requested
if(isset($_GET['delblock'])){
		
	$delpage = $_GET['delblock'];
	$delpage = mysql_real_escape_string($delpage);
	$sql = mysql_query("DELETE FROM hotel_hotel WHERE id = '$delpage'");
    $_SESSION['success'] = "Block Deleted"; 
    header('Location: ' .DIRADMIN);
   	exit();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITETITLE;?></title>
<link href="<?php echo DIR;?>assets/css/style.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/javascript">
	function delblock(id, title)
	{
	   if (confirm("Are you sure you want to delete '" + title + "'"))
	   {
		  window.location.href = '<?php echo DIRADMIN;?>?delpage=' + id;
	   }
	}
</script>
</head>
<body>

<div id="wrapper">

<div id="logo"><a href="<?php echo DIRADMIN;?>"><img src="../assets/images/logo.png" alt="<?php echo SITETITLE;?>" border="0" /></a></div>

<!-- NAV -->
<div id="navigation">
	<ul class="menu">
		<?php include('parts/navigation.php'); ?>
	</ul>
</div>
<!-- END NAV -->

<div id="content">

<?php 
	//show any messages if there are any.
	messages();
?>

<h1>Manage Hotels</h1>

<table>
<tr>
	<th><strong>id</strong></th>
	<th><strong>Name</strong></th>
	<th><strong>City</strong></th>
	<th><strong>Action</strong></th>
</tr>

<?php
$sql = mysql_query("SELECT * FROM hotel_hotel ORDER BY id LIMIT 10");
while($row = mysql_fetch_object($sql)) 
{
		echo "<tr>";
			echo "<td>$row->id</td>";
			echo "<td>$row->Name</td>";
			echo "<td>$row->AddressCity</td>";
			echo "<td><a href=\"".DIRADMIN."editblock.php?id=$row->id\">Edit</a> | <a href=\"javascript:delblock('$row->id','$row->block_name');\">Delete</a></td>";
		echo "</tr>";
}
?>
</table>
<div class="col-md-6 mt100">
<ul class="pagination pagination-sm clearfix">
        <li class="disabled"><a href="#">«</a></li>
        <li class="active"><a href="#">1</a></li>
        <li><a href="#">2</a></li>
        <li><a href="#">3</a></li>
        <li><a href="#">4</a></li>
        <li><a href="#">5</a></li>
        <li><a href="#">»</a></li>
      </ul>
</div>

</div>

<div id="footer">	
		<div class="copy">&copy; <?php echo SITETITLE.' '. date('Y');?> </div>
</div><!-- close footer -->
</div><!-- close wrapper -->

</body>
</html>