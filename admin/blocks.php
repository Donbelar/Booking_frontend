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
	$sql = mysql_query("DELETE FROM site_blocks WHERE id = '$delpage'");
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

<h1>Manage Blocks</h1>

<table>
<tr>
	<th><strong>Block Name</strong></th>
	<th><strong>Modified</strong></th>
	<th><strong>Action</strong></th>
</tr>

<?php
$sql = mysql_query("SELECT * FROM site_blocks ORDER BY id");
while($row = mysql_fetch_object($sql)) 
{
		echo "<tr>";
			echo "<td>$row->block_name</td>";
			echo "<td>$row->modified</td>";
			echo "<td><a href=\"".DIRADMIN."editblock.php?id=$row->id\">Edit</a></td>";
		echo "</tr>";
}
?>
</table>

<p><a href="<?php echo DIRADMIN;?>addblock.php" class="button">Add Block</a></p>
</div>

<div id="footer">	
		<div class="copy">&copy; <?php echo SITETITLE.' '. date('Y');?> </div>
</div><!-- close footer -->
</div><!-- close wrapper -->

</body>
</html>