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

// *** Redirect if username exists
$MM_flag="MM_insert";
if (isset($_POST[$MM_flag])) {
  $MM_dupKeyRedirect="SignUp.php";
  $loginUsername = $_POST['Username'];
  $LoginRS__query = sprintf("SELECT Username FROM main WHERE Username=%s", GetSQLValueString($loginUsername, "text"));
  mysql_select_db($database_localhost, $localhost);
  $LoginRS=mysql_query($LoginRS__query, $localhost) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);

  //if there is a row in the database, the username was found - can not add the requested username
  if($loginFoundUser){
    $MM_qsChar = "?";
    //append the username to the redirect page
    if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
    $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."requsername=".$loginUsername;
    header ("Location: $MM_dupKeyRedirect");
    exit;
  }
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "RegisterForm")) {
  $insertSQL = sprintf("INSERT INTO main (FName, LName, Gender, Phone, Email, Username, MoneyOrder, Password) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['FName'], "text"),
                       GetSQLValueString($_POST['LName'], "text"),
                       GetSQLValueString($_POST['Gender'], "text"),
                       GetSQLValueString($_POST['Phone'], "int"),
                       GetSQLValueString($_POST['Email'], "text"),
                       GetSQLValueString($_POST['Username'], "text"),
                       GetSQLValueString($_POST['MoneyOrder'], "text"),
                       GetSQLValueString($_POST['Password'], "text"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());

  $insertGoTo = "LogIn.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_localhost, $localhost);
$query_Register = "SELECT * FROM main";
$Register = mysql_query($query_Register, $localhost) or die(mysql_error());
$row_Register = mysql_fetch_assoc($Register);
$totalRows_Register = mysql_num_rows($Register);
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
        <script src="../SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
        <title>www.uSE.co.ke/sign up</title>
    <link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
    <link href="../SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
    <link href="../SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
</head>

	<body>
   	  <div id="Holder">
        	<div id="Header"></div>
            <div id="Navbar">
            	<nav>
                	<ul>
                    	<li><a href="SignUp.php">SignUp</a></li>
                        <li><a href="LogIn.php">LogIn</a></li>
                        <li><a href="PasswordRecovery.php">Recovery</a></li>
                    </ul>
                </nav>
                
            </div>
            <div id="Content">
            	<div id="PageHeading">
            	  <h1>Sign Up</h1>
           	  </div>
                <div id="ContentLeft">
                  <h2>Fill in the forms to the right...</h2>
                  <br  />
                  <h6>Navigation...</h6>
                  <ul class="unstyled">
                  	<li>
                    	<h5><a href="#">Link one</a>
               	      </h5>
                  	</li>
                    <li>
                    	<h5><a href="#">Link two</a>
                  	  </h5>
                    </li>
                  </ul>
            </div>
                <div id="ContentRight">
                  <form id="RegisterForm" name="RegisterForm" method="POST" action="<?php echo $editFormAction; ?>">
                    <table width="400" align="center">
                      <tr>
                        <td><table>
                          <tr>
                            <td><h5><span id="sprytextfield1">
                              <label for="FName"></label>
                              First Name:<br />
                              <br />
                              <input name="FName" type="text" class="StyleTextField" id="FName" required="required" autofocus />
                            </span></h5>
                            <span><span class="textfieldRequiredMsg">A value is required.</span></span></td>
                            <td><h5><span id="sprytextfield2">
                              <label for="LName"></label>
                              Last Name:<br />
                              <br />
                              <input name="LName" type="text" class="StyleTextField" id="LName" required="required" />
                            </span></h5>
                            <span><span class="textfieldRequiredMsg">A value is required.</span></span></td>
                          </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><table>
                          <tr>
                            <td><h6><span id="sprytextfield3">
                              <label for="Gender"></label>
                              Gender:<br />
                              <br />
                              <input name="Gender" type="text" class="StyleTextField" id="Gender" required="required" />
                            </span></h6>
                            <span><span class="textfieldRequiredMsg">A value is required.</span></span></td>
                            <td><h5><span id="sprytextfield4">
                              <label for="Phone"></label>
                              Phone No. :<br />
                              <br />
                            <input name="Phone" type="text" class="StyleTextField" id="Phone" required="required" />
                              <span class="textfieldInvalidFormatMsg">Invalid format.</span></span></h5>
                            <span><span class="textfieldRequiredMsg">A value is required.</span></span></td>
                          </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><table>
                          <tr>
                            <td><h5><span id="sprytextfield5">
                              <label for="Email"></label>
                              Email :<br />
                              <br />
                            <input name="Email" type="email" class="StyleTextField" id="Email" required="required" />
                              <span class="textfieldInvalidFormatMsg">Invalid format.</span></span></h5>
                            <span><span class="textfieldRequiredMsg">A value is required.</span></span></td>
                            <td><h5><span id="sprytextfield6">
                              <label for="Username"></label>
                              Username :<br />
                              <br />
  <input name="Username" type="text" class="StyleTextField" id="Username" required="required" />
                            </span></h5>
                            <span><span class="textfieldRequiredMsg">A value is required.</span></span></td>
                          </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><table width="169" align="center">
                          <tr>
                            <td><h5><span id="sprytextfield7">
                              <label for="MoneyOrder"></label>
                              Money Order :<br />
                              <br />
  <input name="MoneyOrder" type="text" class="StyleTextField" id="MoneyOrder" required="required" />
                            </span></h5>
                            <span><span class="textfieldRequiredMsg">A value is required.</span></span></td>
                          </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><table width="209">
                          <tr>
                            <td width="126"><h5><span id="sprypassword1">
                              <label for="Password"></label>
                              Password :<br />
                              <br />
  <input name="Password" type="password" class="StyleTextField" id="Password" required="required" />
                            </span></h5>
                            <span><span class="passwordRequiredMsg">A value is required.</span></span></td>
                            <td width="71"><h5><span id="spryconfirm1">
                              <label for="ConfirmPassword"></label>
                              Confirm Password :<br />
                              <br />
  <input name="ConfirmPassword" type="password" class="StyleTextField" id="ConfirmPassword" required="required" />
                            </span></h5>
                            <span><span class="confirmRequiredMsg">A value is required.</span><span class="confirmInvalidMsg">The values don't match.</span></span></td>
                          </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><input name="RegisterButton" type="submit" id="RegisterButton" value="Sign Up" class="btn btn-success btn-block" /></td>
                      </tr>
                    </table>
                    <input type="hidden" name="MM_insert" value="RegisterForm" />
                  </form>
                  <br />
                </div>
            </div>
            <div id="Footer">
            	<footer id="footer" class="">
                	<div class="container">
                    	<div class="row">
                        	<div class="col-sm-6">
                            	<h6><u>Navigation</u></h6>
                                <br />
                          <ul class="unstyled">
                                	<li><a href="#">No Ads</a></li>
                                    <li><a href="#">Developer</a></li>
                                    <li><a href="#">About Us</a></li>
                                    <li><a href="#">Terms &amp; Conditions</a></li>
                                </ul>
                            </div>
                            <div class="col-sm-6">
                            	<h6><u>Follow Us</u></h6>
                                <br />
                                <ul class="unstyled">
                                	<li><a href="#" data-original-title="uSE" rel="tooltip">Facebook</a></li>
                                    <li><a href="#" data-original-title="@uSE" rel="tooltip">Twitter</a></li>
                                    <li><a href="#" data-original-title="#uSE" rel="tooltip">Instagram</a></li>
                                    <li><a href="#" data-original-title="+254718148637" rel="tooltip">Whatsapp</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    <script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3");
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "phone_number", {format:"phone_custom"});
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5", "email");
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6");
var sprytextfield7 = new Spry.Widget.ValidationTextField("sprytextfield7");
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1");
var spryconfirm1 = new Spry.Widget.ValidationConfirm("spryconfirm1", "Password");
    </script>
</body>
    <script src="../includes/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../includes/myjs/custom js.js" type="text/javascript"></script>
</html>
<?php
mysql_free_result($Register);
?>
