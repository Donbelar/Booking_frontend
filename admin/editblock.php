<?php 
/*-------------------------------------------------------+
| Petwa CMS - Hotel Booking
| http://www.espressowebdesign.co.uk
+--------------------------------------------------------+
| Author: James Simpson
+--------------------------------------------------------+*/

require('../includes/config.php'); 

if(!isset($_GET['id']) || $_GET['id'] == ''){ //if no id is passed to this page take user back to previous page
	header('Location: '.DIRADMIN); 
}

if(isset($_POST['submit'])){

	$blockName = $_POST['blockName'];
	$content = $_POST['pageCont'];
	$id = $_POST['id'];
	
	$title = mysql_real_escape_string($title);
	$content = mysql_real_escape_string($content);
	
	mysql_query("UPDATE site_blocks SET block_name='$blockName', block_text='$content' WHERE id='$id'");
	$_SESSION['success'] = 'Page Updated';
	header('Location: '.DIRADMIN.'blocks.php');
	exit();

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITETITLE;?></title>
<link href="<?php echo DIR;?>assets/css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="wrapper">

<div id="logo"><a href="<?php echo DIR;?>"><img src="../assets/images/logo.png" alt="<?php echo SITETITLE;?>" title="<?php echo SITETITLE;?>" border="0" /></a></div><!-- close logo -->

<!-- NAV -->
<div id="navigation">
<ul class="menu">
<?php include('parts/navigation.php'); ?>
</ul>
</div>
<!-- END NAV -->

<div id="content">

<h1>Edit Page</h1>

<?php
$id = $_GET['id'];
$id = mysql_real_escape_string($id);
$q = mysql_query("SELECT * FROM site_blocks WHERE id='$id'");
$row = mysql_fetch_object($q);
?>


<form action="" method="post">
<input type="hidden" name="id" value="<?php echo $row->id;?>" />
<p>Block Name:<br /> <input name="blockName" type="text" value="<?php echo $row->block_name;?>" size="103" readonly />
</p>
<p>Content<br /><textarea name="pageCont" cols="100" rows="20"><?php echo $row->block_text;?></textarea>
</p>
<p><input type="submit" name="submit" value="Submit" class="button" /></p>
</form>

</div>

<div id="footer">	
		<div class="copy">&copy; <?php echo SITETITLE.' '. date('Y');?> </div>
</div><!-- close footer -->
</div><!-- close wrapper -->

</body>
</html>