<?php
if(!empty($_GET['msg'])){
			$errmsg=$_GET['msg'];	
}
else
{
    $errmsg = '';
}

$file = 'main.json';
 
//Use the function is_file to check if the file already exists or not.
if(!is_file($file)){
    //Some simple example content.
    $contents = '';
    //Save our content to the file.
    file_put_contents($file, $contents);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
if(isset($_FILES["logo"]["tmp_name"])){
copy("../img/logo.png", "../img/old/logo".date('ymdhis').".png");
move_uploaded_file($_FILES["logo"]["tmp_name"],"../img/logo.png");
}
    $basic_info=array(
        "email",
        "pwd",
        "cname",
        "isdatabase",
        "dbip",
        "dbport",
        "dbuser",
        "dbpassword",
        "select_db"
    );

    $final=array();

    foreach ($basic_info as $filter) {
        $final[$filter]=$_POST[$filter]?$_POST[$filter]:"";
    }
	$final["database"]=array(
        array(
            "isdatabase"=>$final["isdatabase"],
            "dbip"=>$final["dbip"],
            "dbport"=>$final["dbport"],
            "dbuser"=>$final["dbuser"],
            "dbpassword"=>$final["dbpassword"],
            "select_db"=>$final["select_db"],
        )
    );

    $unsets=array(
        "isdatabase",
        "ipaddress",
        "port",
        "dbuser",
        "dbpassword",
        "select_db"
    );

    foreach ($unsets as $unset) {
        unset($final[$unset]);
    }
	

   $info = json_encode($final);
  $file = fopen('main.json','w+');
   fwrite($file, $info);
   fclose($file);
    header("Location: installer.php?msg=Details+Saved");
}
?><!DOCTYPE html>
<html>
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
<link rel="apple-touch-icon" sizes="57x57" href="../favicon/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="../favicon/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="../favicon/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="../favicon/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="../favicon/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="../favicon/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="../favicon/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="../favicon/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="../favicon/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="../favicon/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="../favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="../favicon/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="../favicon/favicon-16x16.png">
<link rel="manifest" href="../favicon/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="../favicon/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Installer</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
 <style>.login-form{border:2px solid #ccc;padding:5px;border-radius:10px;}.btn{margin-right: 10px;margin-left: 10px;}.form-inline label{padding-top: 20px;padding-bottom: 20px;padding-right: 20px;padding-left: 20px;}.login-container{margin-top:10%;text-align:center}.login-error{border:1px dashed #ccc;padding:5px;border-radius:4px;background:rgba(255,255,255,0.1)}.form-signin input{margin-top:20px;border-radius:2px;padding:20px;font-family:"Roboto",Helvetica;font-size:16px;font-weight:200}.form-signin .btn{margin-top:30px;border-radius:2px;padding:10px;font-family:"Roboto",Helvetica;font-size:16px;background-color:rgba(241,210,0,1);transition-duration:1000ms}.form-signin .btn:hover{background-color:rgba(230,190,0,1);transition-duration:500ms}.login-error{color:red;font-family:"Roboto";font-weight:300}footer{position:absolute;bottom:0;color:#fff;width:100%;font-family:"Helvetica";font-weight:200}</style>
<style>
.img-responsive{height:150px;!important}
</style>
<script type = "text/javascript" > $(document).ready(function () {
		$('#isdatabase').change(function () {
			$("select option:selected").each(function () {
				if ($(this).attr("value") == "Nil") {
					$("#db_values").hide();
				}
				if ($(this).attr("value") == "Yes") {
					$("#db_values").show();
				}
				if ($(this).attr("value") == "No") {
					$("#db_values").hide();
				}
			});
		}).change();
	});
</script>
 </head>
    <body>
	<div class="container login-page-container">
	<div class="logo">
<img class="img-responsive" src="../img/logo.png?<?php echo date('his'); ?>">
</div>
<!--https://www.tutorialrepublic.com/php-tutorial/php-json-parsing.php -->
<h4 style="text-align:center">Configure the Email system</h4>
        <form action="" method="post" enctype="multipart/form-data" class="login-form" >

		<div id="error">
 <?php if(!empty($errmsg)){
        echo '<div class="box-small login-error"><span class="glyphicon glyphicon-exclamation-sign"></span> &nbsp;'.$errmsg.' !</div>';
    } 
	$json_read = file_get_contents('main.json');

//Decode JSON
$read = json_decode($json_read, true);

?>
</div><div class="form-inline">
		 <div class="form-group" >
    <label for="email">Email Address:</label>
    <input type="email" class="form-control" name="email" value="<?php echo isset($read["email"])? $read["email"]: ""; ?>">
    <label for="pwd">Password:</label>
    <input type="text" class="form-control" name="pwd" value="<?php echo isset($read["pwd"])? $read["pwd"]: ""; ?>">
  </div></div>
  <div class="form-inline">
  <div class="form-group">
    <label for="pwd">Company Name:</label>
    <input type="text" class="form-control" name="cname" value="<?php echo isset($read["cname"])? $read["cname"]: ""; ?>">
  <label for="sel1">Using Database </label>
  <select class="form-control" name="isdatabase" id="isdatabase">
  <option value="<?php echo isset($read["database"][0]["isdatabase"])? $read["database"][0]["isdatabase"]: "Nil"; ?>" selected="selected"><?php echo isset($read["database"][0]["isdatabase"])? $read["database"][0]["isdatabase"]: "Select Value"; ?></option>
	<option value="Yes">Yes</option>
    <option value="No">No</option>
  </select>
</div></div>
<div id="db_values" class="form-inline" style="display:none;">
  <div class="form-inline"><div class="form-group" >
    <label for="email">Database ip:</label>
    <input type="IP" class="form-control" name="dbip" value="<?php echo isset($read["database"][0]["dbip"])? $read["database"][0]["dbip"]: ""; ?>">
    <label for="pwd">Database port:</label>
    <input type="number" class="form-control" name="dbport" value="<?php echo isset($read["database"][0]["dbport"])? $read["database"][0]["dbport"]: ""; ?>">
  </div></div>
  <div class="form-inline">
  <div class="form-group">
    <label for="pwd">Database user:</label>
    <input type="text" class="form-control" name="dbuser" value="<?php echo isset($read["database"][0]["dbuser"])? $read["database"][0]["dbuser"]: ""; ?>">
    <label for="pwd">Database Password:</label>
    <input type="text" class="form-control" name="dbpassword" value="<?php echo isset($read["database"][0]["dbpassword"])? $read["database"][0]["dbpassword"]: ""; ?>">
    </div><div class="form-group"><label for="pwd">Database Schema/Db:</label>
    <input type="text" class="form-control" name="select_db" value="<?php echo isset($read["database"][0]["select_db"])? $read["database"][0]["select_db"]: ""; ?>">
  </div></div></div>
  <div class="form-group"><label for="logo">Logo (PNG File)</label>
     <input type="file" name="logo">
  </div>
  <a href="../index.php"  class="btn btn-danger">Go Back </a>
 <input type="submit"  class="btn btn-primary" value="Submit"/>
 <a href="../mailer.php"  class="btn btn-success"> Send Emails </a>
 
</form>

   </div>

</body>
</html>