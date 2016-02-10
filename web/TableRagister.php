<?php @session_start(); ?>
<?php require_once('../Connections/localhost.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "2";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "LogIn.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
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
  $MM_dupKeyRedirect="TableRagister.php";
  $loginUsername = $_POST['Company'];
  $LoginRS__query = sprintf("SELECT Company FROM stocks WHERE Company=%s", GetSQLValueString($loginUsername, "text"));
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "TableRegisterForm")) {
  $insertSQL = sprintf("INSERT INTO stocks (Company, Shares, Prev, Curr, High, Low) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['Company'], "text"),
                       GetSQLValueString($_POST['Shares'], "text"),
                       GetSQLValueString($_POST['Prev'], "text"),
                       GetSQLValueString($_POST['Curr'], "text"),
                       GetSQLValueString($_POST['High'], "text"),
                       GetSQLValueString($_POST['Low'], "text"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());

  $insertGoTo = "AdminCP.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_localhost, $localhost);
$query_CoRegister = "SELECT * FROM stocks";
$CoRegister = mysql_query($query_CoRegister, $localhost) or die(mysql_error());
$row_CoRegister = mysql_fetch_assoc($CoRegister);
$totalRows_CoRegister = mysql_num_rows($CoRegister);
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
        <link href="../includes/mycss/ALayout.css" rel="stylesheet" type="text/css" />
        <link href="../includes/mycss/AMenu.css" rel="stylesheet" type="text/css" />
        <script src="../includes/ie/ie-emulation-modes-warning.js" type="text/javascript"></script>
        <script src="../includes/jquery/jquery.js" type="text/javascript"></script>
        <script src="../includes/modernizr/modernizr.js" type="text/javascript"></script>
        <script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
        <title>www.uSE.co.ke/Company Register</title>
    <link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
</head>

	<body>
    	<div id="Holder">
        	<div id="Header"></div>
            <div id="Navbar">
            	<nav>
                	<ul>
                    	<li><a href="TableRagister.php">Company Registration</a></li>
                    </ul>
                </nav>
                
            </div>
            <div id="Content">
            	<div id="PageHeading">
            	  <h1>Company Registration</h1>
           	  </div>
                <div id="ContentLeft">
                  <h2>Enter Data:</h2>
                  <br  />
                  <h6>Navigation...</h6>
                  <ul class="unstyled">
                  	<li>
                  	  <h5><a href="AdminCP.php">Admin CP</a></h5>
                  	</li>
                    <br />
                    <li>
                      <h5><a href="UserManager.php">SuperUser</a></h5>
                    </li>
                    <br />
                    <li>
                      <h5><a href="AdminLogOut.php">LogOut</a></h5>
                    </li>
                  </ul>
            </div>
                <div id="ContentRight">
                  <form id="TableRegisterForm" name="TableRegisterForm" method="POST" action="<?php echo $editFormAction; ?>">
                    <table width="500" border="0" align="center">
                      <tr>
                        <td><h5><span id="sprytextfield1">
                          <label for="Company"></label>
                          Company:<br />
                          <input name="Company" type="text" class="StyleTextField" id="Company" />
                        </span></h5>
                        <span><span class="textfieldRequiredMsg">A value is required.</span></span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><h5><span id="sprytextfield2">
                          <label for="Shares"></label>
                          Shares:<br />
                          <input name="Shares" type="text" class="StyleTextField" id="Shares" />
                        </span></h5>
                        <span><span class="textfieldRequiredMsg">A value is required.</span></span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><h5><span id="sprytextfield3">
                          <label for="Prev"></label>
                          Previous:<br />
                          <input name="Prev" type="text" class="StyleTextField" id="Prev" />
                        </span></h5>
                        <span><span class="textfieldRequiredMsg">A value is required.</span></span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><h5><span id="sprytextfield4">
                          <label for="Curr"></label>
                          Current:<br />
                          <input name="Curr" type="text" class="StyleTextField" id="Curr" />
                        </span></h5>
                        <span><span class="textfieldRequiredMsg">A value is required.</span></span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><h5><span id="sprytextfield5">
                          <label for="High"></label>
                          High<br />
                          <input name="High" type="text" class="StyleTextField" id="High" />
                        </span></h5>
                        <span><span class="textfieldRequiredMsg">A value is required.</span></span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><h5><span id="sprytextfield6">
                          <label for="Low"></label>
                          Low:<br />
                          <input name="Low" type="text" class="StyleTextField" id="Low" />
                        </span></h5>
                        <span><span class="textfieldRequiredMsg">A value is required.</span></span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><input type="submit" name="TableRegisterButton" id="TableRegisterButton" value="Submit" class="btn btn-success btn-parent" /></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                    </table>
                    <input type="hidden" name="MM_insert" value="TableRegisterForm" />
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
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4");
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5");
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6");
    </script>
</body>
    <script src="../includes/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../includes/myjs/custom js.js" type="text/javascript"></script>
</html>
<?php
mysql_free_result($CoRegister);
?>
