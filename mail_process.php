<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
	$json_read = file_get_contents('config/main.json');

//Decode JSON
$read = json_decode($json_read, true);
$owner_email_address= $read["email"];
$owner_email_password=$read["pwd"];
$owner_name=$read["cname"];
?>
<html>
<head>
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
<title>Bulk Emails By CSV</title>
<link rel="stylesheet" type="text/css" href="css/style.css"/>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
</head>
<body>
<style>.login-container{margin-top:10%;text-align:center}.login-error{border:1px dashed #ccc;padding:5px;border-radius:4px;background:rgba(255,255,255,0.1)}.form-signin input{margin-top:20px;border-radius:2px;padding:20px;font-family:"Roboto",Helvetica;font-size:16px;font-weight:200}.form-signin .btn{margin-top:30px;border-radius:2px;padding:10px;font-family:"Roboto",Helvetica;font-size:16px;background-color:rgba(241,210,0,1);transition-duration:1000ms}.form-signin .btn:hover{background-color:rgba(230,190,0,1);transition-duration:500ms}.login-error{color:#f0f0f0;font-family:"Roboto";font-weight:300}footer{position:absolute;bottom:0;color:#fff;width:100%;font-family:"Helvetica";font-weight:200}</style>
<style>
.img-responsive{height:150px;!important}
</style>
</head>
<body>
	<div class="container login-page-container">
	<div class="logo">
<img class="img-responsive" src="img/logo.png?<?php echo date('his'); ?>">
</div>

<?php
// Include PHPMailerAutoload.php library file
include("lib/PHPMailerAutoload.php");
$msg = "";
$user_list = array();
$status = array();
$file_path = "";

// Retrieving & storing user's submitted information
if (isset($_POST['user_list'])) {
//$user_list = json_decode($_POST['user_list']);
$user_list = array_slice(json_decode($_POST['user_list'], true), 1);
}
if (isset($_POST['uploaded_file_path'])) {
$file_path = $_POST['uploaded_file_path'];
}

// Sending personalized email
foreach ($user_list as $list) {
	
sleep(2);//sleep for 2 seconds
$receiver_name = "";
$singleErrorMessage="";
$receiver_add = "";
$per_msg = "";
$template_code="";
$per_email_sub = "";
$receiver_name = $list[0];
$receiver_add = $list[1];
if(strlen($list[2])>5){
	$email_sub = $list[2];
}
else
{
	$email_sub = "Test News Letter";
}

$template_code = $list[3];


// Replacing {receiver} with client name from subject and message
//$per_msg = str_replace("{receiver}", $receiver_name, $msg);
$per_email_sub = str_replace("{receiver}", $receiver_name, $email_sub);


$mail = new PHPMailer();
$mail->IsSMTP();
$mail->Mailer = "smtp";
$mail->Host = "smtp.gmail.com";
$mail->Port = 465;

// Enable SMTP authentication
$mail->SMTPAuth = true;


//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 0;

$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);


// SMTP username
$mail->Username =$owner_email_address;// '<-- SMTP Username -->';

// SMTP password
$mail->Password = $owner_email_password; //'<-- SMTP Password -->';

// Enable encryption, 'tls' also accepted
$mail->SMTPSecure = 'ssl';

// Sender Email address
$mail->From = $owner_email_address;///'<-- Sender Email Address -->';

// Sender name
$mail->FromName = $owner_name;//"<-- Sender Name -->";

// Receiver Email address
$mail->addAddress($receiver_add);
$mail->isHTML(true);
//$mail->msgHTML(file_get_contents('Email_Template1.html'), dirname(__FILE__));

$message = file_get_contents("Template/".$template_code."/index.html");
$per_msg = str_replace("{receiver}", $receiver_name, $message);	
$mail->Subject = $per_email_sub;
$mail->Body = $per_msg;
$mail->WordWrap = 50;

// Sending message and storing status
if (!$mail->send()) {
$status[$receiver_add] = False;
$singleErrorMessage = $mail->ErrorInfo;
} else {
$status[$receiver_add] = TRUE;
$singleErrorMessage="";
}
}
?>
<div id="main" class="col-sm-12 col-md-6 col-lg-6">
<h1>Message Status</h1>
<div id="status">
<ul>
<?php
foreach ($status as $user => $sent_status) {
if ($sent_status == True) {
$img = "img/errorFree.png";
} else {
$img = "img/error.png";
}
echo "<li> <img src='$img'/>" . $user ."-".$singleErrorMessage;
}
// Deleting iuploaded CSV file from the uploads folder
if (file_exists($file_path)) { unlink ($file_path); }
?>
</ul>
<a href="mailer.php" id="more">Send More Emails...</a>
</div>
</div>
</body>
</html>