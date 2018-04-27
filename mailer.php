<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
<link rel="apple-touch-icon" sizes="57x57" href="favicon/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="favicon/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="favicon/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="favicon/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="favicon/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="favicon/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="favicon/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="favicon/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="favicon/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="favicon/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
<link rel="manifest" href="favicon/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="favicon/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Send Emails </title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
<style>
.img-responsive{height:150px;!important}
</style>
</head>
<body>
	<div class="container login-page-container">
	<div class="logo">
<img class="img-responsive" src="img/logo.png?<?php echo date('his'); ?>">
</div>

<div style="border-radius: 10px;font-family:raleway;border: 2px solid skyblue;">
<h2 style="text-align:center"><span class="badge badge-pill badge-primary">Step 1</span></h2><small style="float:right"><a class="btn btn-xs btn-danger" href="index.php">Home</a><a  class="btn btn-xs btn-warning" href="samplefile/Test_file.csv" >Download sample file</a></small>
<div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1 login-container">
 
<form action="" class="form" method="POST" enctype="multipart/form-data">
		<div class="form-group">
		<input type="file" name="csv_data" class="csv_upload"/>
		</div><div class="form-group">
<input type="submit" class="btn btn-primary" id="csv_upload" value="Upload CSV file" />
		</div>
		</form>
		</div>	</div>

<?php
$ext_error = "";
$data = "";
$csv_data = array();
if (isset($_FILES) && (bool) $_FILES) {

// Define allowed extensions
$allowedExtentsoins = "csv";
$file_name = $_FILES['csv_data']['name'];
$temp_name = $_FILES['csv_data']['tmp_name'];
$path_part = pathinfo($file_name);
$ext = $path_part['extension'];

// Checking for extension of attached files
if ($ext != $allowedExtentsoins) {
echo "<script>alert('Sorry!!! ." . $ext . " file is not allowed!!! Try Again.')</script>";
$ext_error = TRUE;
} else {
$ext_error = FALSE;
}
if ($ext_error == FALSE) {
echo "<script>alert('File successfully uploaded!!! Continue...');</script>";

// Store attached files in uploads folder
$file_path = dirname(__FILE__) . "/uploads/" . $path_part['basename'];
move_uploaded_file($temp_name, $file_path);

// Retrieve data from the CSV file and storing in $csv_data
$file = new SplFileObject($file_path);
$file->setFlags(SplFileObject::READ_CSV);
foreach ($file as $row) {
// Remove empty row and empty values from the uploaded csv data
$csv_data[] = array_filter($row);
}
$csv_data = array_filter($csv_data);
$data = htmlspecialchars(json_encode($csv_data));
?>
<div style="border-radius: 10px;font-family:raleway;border: 2px solid green;">
<h2 style="text-align:center"><span class="badge badge-pill badge-info">Step 2</span></h2><small style="float:right"><a href="mailer.php">go back</a></small>

<form action="mail_process.php" method="post">
<label>use <code>{receiver} </code> for each user_name </label>
<div class="form-group" style="text-align:right">
<input type="submit" class="btn btn-success" value="Send Emails" id="submit"/>
</div>
<div class="form-group">
<label>Subject ( if common for All ) : </label>
<input type="hidden" name="uploaded_file_path" value="<?php echo $file_path; ?>" />
<input type="hidden" name="user_list" value="<?php echo $data; ?>" />
<input type="text" name="email_sub" class="email_sub" />
</div>
<div class="form-group" style="text-align:center">
<button class="btn btn-success" >Your Excel Perview :</button>
</div><div class="form-group">
<div class="table-responsive" style="overflow-y:scroll;overflow-x:wordwrap;height:400px"><table id="csvtable" class="table table-bordered">
<tr><th>Recevier Name</th><th>Receiver Email ID</th><th>Email Subject</th><th>Email Template Code(1,2,3)</th></tr>
<?php
$row = 1;

if (($handle = fopen("$file_path", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
		
  //  echo "$num fields in line $row: <br/>";
  
		echo "<tr><td>".$data[0]."</td><td>".$data[1]."</td><td>".$data[2]."</td><td>".$data[3]."</td></tr>";
		
        $row++;
		
		
        for ($c=1; $c < $num; $c++) {
			
			//echo "<tr><td>".$data[0][$c]."</td><td>".$data[1][$c]."</td><td>".$data[2][$c]."</td><td>".$data[3][$c]."</td></tr>";
	
       //    echo $data[$c] . "<br/>";
        }
    }

    fclose($handle);
}

?>
</table></div></div></form>
</div>
<?php
}
}
?>
</div></div></div></div>
<script>
jQuery("#csv_upload").click(function(e) {
var upload = jQuery('.csv_upload').val();
if (upload == "") {
alert('Please Upload a CSV file!!!');
e.preventDefault();
}
});
jQuery("#submit").click(function(e) {
e.preventDefault();
});
</script>
</body>
</html>