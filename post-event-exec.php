<?php
	//Start session
	session_start();
	
	//Include database connection details
	require_once('config.php');
	
	//Array to store validation errors
	$errmsg_arr = array();
	
	//Validation error flag
	$errflag = false;
	
	//Connect to mysql server
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	if(!$link) {
		die('Failed to connect to server: ' . mysql_error());
	}
	
	//Select database
	$db = mysql_select_db(DB_DATABASE);
	if(!$db) {
		die("Unable to select database");
	}
	
	//Function to sanitize values received from the form. Prevents SQL injection
	function clean($str) {
		$str = @trim($str);
		if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		return mysql_real_escape_string($str);
	}
	
	$myFile = "rawDataFile.txt";
	$fh = fopen($myFile, 'w') or die("can't open file");
	$stringData = "ensure update 1";
	fwrite($fh, $stringData);
	$stringData = $_POST['City'];
	fwrite($fh, $stringData);
		$stringData = "ensure update 2";
	fwrite($fh, $stringData);

	
	//Sanitize the POST values
	$EventName = clean($_POST['EventName']);
	$LocationName = clean($_POST['LocationName']);
	$StreetAddress = clean($_POST['StreetAddress']);
	$City = clean($_POST['City']);
	$ZipCode = clean($_POST['ZipCode']);
	$AddressState = clean($_POST['AddressState']);
	$EventDate = clean($_POST['EventDate']);
	$StartTime = clean($_POST['StartTime']);
	$EndTime = clean($_POST['EndTime']);
	

$stringData = "'$EventName'";
fwrite($fh, $stringData);
$stringData = "ensure update 3";
	fwrite($fh, $stringData);

fclose($fh);
	
	//Input Validations
	if($EventName == '') {
		$errmsg_arr[] = 'Event Name missing';
		$errflag = true;
	}
	if($LocationName == '') {
		$errmsg_arr[] = 'Location Name missing';
		$errflag = true;
	}
	if($EventDate == '') {
		$errmsg_arr[] = 'Event Date missing';
		$errflag = true;
	}
	
	//If there are input validations, redirect back to the login form
	if($errflag) {
		$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
		session_write_close();
		header("location: PostEvent.html");
		exit();
	}
	

	
	//Create query
	$qry="INSERT INTO events(EventName, LocationName, Address1, City, State, Zip, EventDate, StartTime, EndTime) VALUES ('$EventName','$LocationName','$StreetAddress','$City','$AddressState','$ZipCode','$EventDate','$StartTime','$EndTime')";
	
	$myFile = "queryFile.txt";
$fh = fopen($myFile, 'w') or die("can't open file");
$stringData = $qry;
fwrite($fh, $stringData);


	
//INSERT INTO events
//(EventName, LocationName, Address1, Address2, City, State, Zip, EventDate, StartTime, EndTime, Description)
//VALUES
//("First Event", "My House", "123 Easy St","", "Lakeville", "MN", 55044, 2001-12-11, "11:12:00", "22:23:00","This is the best event every");

	$result=mysql_query($qry);
	fwrite($fh, $result);
	fclose($fh);
	
	//Check whether the query was successful or not
	/*if($result) {
		if(mysql_num_rows($result) == 1) {
			//Login Successful
			session_regenerate_id();
			$member = mysql_fetch_assoc($result);
			$_SESSION['SESS_MEMBER_ID'] = $member['member_id'];
			$_SESSION['SESS_FIRST_NAME'] = $member['firstname'];
			$_SESSION['SESS_LAST_NAME'] = $member['lastname'];
			session_write_close();
			header("location: member-index.php");
			exit();
		}else {
			//Login failed
			header("location: login-failed.php");
			exit();
		}
	}else {
		die("Query failed");
	}*/
	
	header("location: EventPosted.html");
?>