<?php @session_start(); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "UpdateAccountForm")) {
  $updateSQL = sprintf("UPDATE main SET Phone=%s, Email=%s, MoneyOrder=%s, Password=%s WHERE UserID=%s",
                       GetSQLValueString($_POST['Phone'], "int"),
                       GetSQLValueString($_POST['Email'], "text"),
                       GetSQLValueString($_POST['MoneyOrder'], "text"),
                       GetSQLValueString($_POST['Password'], "text"),
                       GetSQLValueString($_POST['UserIDHiddenField'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());

  $updateGoTo = "Account.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_UserUpdate = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_UserUpdate = $_SESSION['MM_Username'];
}
mysql_select_db($database_localhost, $localhost);
$query_UserUpdate = sprintf("SELECT * FROM main WHERE Username = %s", GetSQLValueString($colname_UserUpdate, "text"));
$UserUpdate = mysql_query($query_UserUpdate, $localhost) or die(mysql_error());
$row_UserUpdate = mysql_fetch_assoc($UserUpdate);
$totalRows_UserUpdate = mysql_num_rows($UserUpdate);
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
        <title>www.uSE.co.ke/Update Account</title>
    <link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
    <link href="../SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
</head>

	<body>
    	<div id="Holder">
        	<div id="Header"></div>
            <div id="Navbar">
            	<nav>
                	<ul>
                    	<li><a href="UpdateAccount.php">Update Center</a></li>
                    </ul>
                </nav>
                
            </div>
            <div id="Content">
            	<div id="PageHeading">
            	  <h1>uSE</h1>
           	  </div>
                <div id="ContentLeft">
                  <h2>Account Update Center.</h2>
                  <br  />
                  <h6>To...</h6>
                  <ul class="unstyled">
                  	<li>
                    	<h5><a href="Account.php">Home</a>
               	      </h5>
                  	</li>
                    <li>
                    	<h5><a href="LogOut.php">Log Out</a>
                  	  </h5>
                    </li>
                  </ul>
            </div>
                <div id="ContentRight">
                  <form id="UpdateAccountForm" name="UpdateAccountForm" method="POST" action="<?php echo $editFormAction; ?>">
                    <table width="600">
                      <tr>
                        <td> <h5>Account : <?php echo $row_UserUpdate['FName']; ?> <?php echo $row_UserUpdate['LName']; ?> Username : <?php echo $row_UserUpdate['Username']; ?></h5>
                          <table width="400" align="center">
                            <tr>
                              <td><span id="sprytextfield1">
                                <label for="Email"></label>
                                Email :
                                <br />
                                <br />
<input name="Email" type="text" class="StyleTextField" id="Email" value="<?php echo $row_UserUpdate['Email']; ?>" />
                              <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td><span id="sprytextfield2">
                                <label for="Phone"></label>
                                Phone :<br />
                                <br />
<input name="Phone" type="text" class="StyleTextField" id="Phone" value="<?php echo $row_UserUpdate['Phone']; ?>" />
                              <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td><span id="sprytextfield3">
                                <label for="MoneyOrder"></label>
                                Money Order :<br />
                                <br />
<input name="MoneyOrder" type="text" class="StyleTextField" id="MoneyOrder" value="<?php echo $row_UserUpdate['MoneyOrder']; ?>" />
                              <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td><span id="sprypassword1">
                              <label for="Password"></label>
                                Password :<br />
                                <br />
<input name="Password" type="password" class="StyleTextField" id="Password" value="<?php echo $row_UserUpdate['Password']; ?>" />
                              <span class="passwordRequiredMsg">A value is required.</span></span></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td><input type="submit" name="UpdateAccountButton" id="UpdateAccountButton" value="Update Information" class="btn btn-danger" />
                              <input name="UserIDHiddenField" type="hidden" id="UserIDHiddenField" value="<?php echo $row_UserUpdate['UserID']; ?>" /></td>
                            </tr>
                        </table></td>
                      </tr>
                    </table>
                    <input type="hidden" name="MM_update" value="UpdateAccountForm" />
                  </form>
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
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1");
    </script>
</body>
    <script src="../includes/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../includes/myjs/custom js.js" type="text/javascript"></script>
</html>
<?php
mysql_free_result($UserUpdate);
?>
