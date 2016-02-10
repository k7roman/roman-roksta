<?php require_once('../Connections/localhost.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_localhost, $localhost);
$query_Login = "SELECT * FROM main";
$Login = mysql_query($query_Login, $localhost) or die(mysql_error());
$row_Login = mysql_fetch_assoc($Login);
$totalRows_Login = mysql_num_rows($Login);
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['Username'])) {
  $loginUsername=$_POST['Username'];
  $password=$_POST['Password'];
  $MM_fldUserAuthorization = "UserLevel";
  $MM_redirectLoginSuccess = "Account.php";
  $MM_redirectLoginFailed = "LogIn.php";
  $MM_redirecttoReferrer = true;
  mysql_select_db($database_localhost, $localhost);
  	
  $LoginRS__query=sprintf("SELECT Username, Password, UserLevel FROM main WHERE Username=%s AND Password=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $localhost) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'UserLevel');
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && true) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="" />
        <meta name="author" content="r03a2" />
        <link rel="icon" href="../favicon/s.gif" />
        <link href="../includes/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="../includes/mycss/custom css.css" rel="stylesheet" type="text/css" />
        <link href="../includes/mycss/Layout.css" rel="stylesheet" type="text/css" />
        <link href="../includes/mycss/Menu.css" rel="stylesheet" type="text/css" />
        <script src="../includes/ie/ie-emulation-modes-warning.js" type="text/javascript"></script>
        <script src="../includes/jquery/jquery.js" type="text/javascript"></script>
        <script src="../includes/modernizr/modernizr.js" type="text/javascript"></script>
        <script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
        <script src="../SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
        <title>www.uSE.co.ke/Login</title>
    <link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
    <link href="../SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
</head>

	<body>
    	<div id="Holder">
   	    <div id="Header"></div>
            <div id="Navbar">
            	<div class="" id="myNav">
              	<nav class="navbar navbar-responsive" role="navigation">
                  	<div class="navbar-header">
                      	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNav-navbar-collapse">
                      	<span class="sr-only"></span>
                          <span class="icon-bar"></span>
                      	</button>
                      	<a class="navbar-brand" target="_blank"><h2>uSE</h2></a>
                      </div>
                      <div class="collapse navbar-collapse" id="myNav-navbar-collapse">
                      	<ul class="nav navbar-nav">
                          	<li><a href="LogIn.php">Login</a></li>
                              <li><a href="SignUp.php">SignUp</a></li>
                              <li><a href="PasswordRecovery.php">Recovery</a></li>
                          </ul>
                      </div>                 	
                  </nav>
              </div>                
            </div>
            <div id="Content">
            	<div id="PageHeading">
            	  <h1>u S E.</h1>
           	  </div>
                <div id="ContentLeft">
                  <h2>Login.</h2>
                  <br  />
                  <h6>To ...</h6>
                  <br />
                  <ul class="unstyled">
                    <li>
                    	<h5><a href="AdminLogIn.php">Admin</a>
                  	  </h5>
                    </li>
                  </ul>
            </div>
                <div id="ContentRight">
                  <form id="LoginForm" name="LoginForm" method="POST" action="<?php echo $loginFormAction; ?>">
                    <table width="400" align="center">
                      <tr>
                        <td><h5><span id="sprytextfield1">
                          <label for="Username"></label>
                          Username :<br />
                          <br />
  <input name="Username" type="text" class="StyleTextField" id="Username" required="required" autofocus />
                        </span></h5>
                        <span><span class="textfieldRequiredMsg">A value is required.</span></span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><h5><span id="sprypassword1">
                          <label for="Password"></label>
                          Password :
                          <br />
                          <br />
  <input name="Password" type="password" class="StyleTextField" id="Password" required="required" />
                          <br />
                        </span></h5>
                        <span><span class="passwordRequiredMsg">A value is required.</span></span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><input type="submit" name="LoginButton" id="LoginButton" value="Log In" class="btn btn-primary"/></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                    </table>
                  </form>
                </div>
            </div>
            <div id="Footer">
            	<footer id="footer" class="">
                	<div class="container">
                    	<div class="row">
                        	<div class="col-sm-5">
                            	<br />
                            	<h6><u>Navigation</u></h6>
                                <br />
                          <ul class="unstyled">
                                	<li>
                                	  <h5><a href="#">No Ads</a></h5>
                                	</li>
                            <li>
                              <h5><a href="#" data-original-title="Kelvin Roman" rel="tooltip">Developer</a></h5>
                            </li>
                            <li>
                              <h5><a href="#" data-original-title="Roman-Pkhan" rel="tooltip">About Us</a></h5>
                            </li>
                                <li>
                                  <h5><a href="#">Terms &amp; Conditions</a></h5>
                                </li>
                                </ul>
                            </div>
                            <div class="col-sm-2">
                            	<br />
                                <h6>Created by:</h6>
                                <br />
                                <ul class="unstyled">
                                	<li>
                                	  <h5><a href="#" target="_blank">Ephantus Wamichwe</a></h5>
                                	</li>
                                    <br />
                                    
                              </ul>
                            </div>
                            <div class="col-sm-5">
                            	<br />
                            	<h6><u>Follow Us</u></h6>
                                <br />
                                <ul class="unstyled">
                                	<li>
                                	  <h5><a href="#" data-original-title="uSE" rel="tooltip">Facebook</a></h5>
                                	</li>
                                  <li>
                                    <h5><a href="#" data-original-title="@uSE" rel="tooltip">Twitter</a></h5>
                                  </li>
                                  <li>
                                    <h5><a href="#" data-original-title="#uSE" rel="tooltip">Instagram</a></h5>
                                  </li>
                                    <li>
                                      <h5><a href="#" data-original-title="+254718148637" rel="tooltip">Whatsapp</a></h5>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    <script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1");
    </script>
</body>
    <script src="../includes/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../includes/myjs/custom js.js" type="text/javascript"></script>
</html>
<?php
mysql_free_result($Login);
?>
