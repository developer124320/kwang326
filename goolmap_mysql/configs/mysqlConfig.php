<?php
//	require_once "config_setting.php";
	define('DB_NAME', 'test');    		// The name of the database
	define('DB_USER', 'root');     		// Your MySQL username
	define('DB_PWD', '1234'); 				// ...and password
	define('DB_SERVER', 'localhost');    // 99% chance you won't need to change this value
	define('DB_CHARSET', 'utf8');
	define('DB_COLLATE', '');	

//make the connection
  $conn = @mysqli_connect(DB_SERVER, DB_USER, DB_PWD, DB_NAME);      //this is procedural style
  if (!$conn) { die ("Could not connect to database"); }
  
Function safeEscapeString($string)
{
// replace HTML tags '<>' with '[]'
  $temp1 = str_replace("<", "[", $string);
  $temp2 = str_replace(">", "]", $temp1);

// but keep <br> or <br />
// turn <br> into <br /> so later it will be turned into ""
// using just <br> will add extra blank lines
  $temp1 = str_replace("[br]", "<br />", $temp2);
  $temp2 = str_replace("[br /]", "<br />", $temp1);
  
  if (get_magic_quotes_gpc()) {
    return $temp2;
  } else {
    return mysqli_escape_string($GLOBALS['conn'], $temp2);
  }
}
?>
