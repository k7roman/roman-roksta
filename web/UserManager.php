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

$currentPage = $_SERVER["PHP_SELF"];

if ((isset($_POST['DeleteUserHiddenField'])) && ($_POST['DeleteUserHiddenField'] != "")) {
  $deleteSQL = sprintf("DELETE FROM main WHERE UserID=%s",
                       GetSQLValueString($_POST['DeleteUserHiddenField'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($deleteSQL, $localhost) or die(mysql_error());

  $deleteGoTo = "UserManager.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

$maxRows_ManageUsers = 10;
$pageNum_ManageUsers = 0;
if (isset($_GET['pageNum_ManageUsers'])) {
  $pageNum_ManageUsers = $_GET['pageNum_ManageUsers'];
}
$startRow_ManageUsers = $pageNum_ManageUsers * $maxRows_ManageUsers;

mysql_select_db($database_localhost, $localhost);
$query_ManageUsers = "SELECT * FROM main ORDER BY `Timestamp` DESC";
$query_limit_ManageUsers = sprintf("%s LIMIT %d, %d", $query_ManageUsers, $startRow_ManageUsers, $maxRows_ManageUsers);
$ManageUsers = mysql_query($query_limit_ManageUsers, $localhost) or die(mysql_error());
$row_ManageUsers = mysql_fetch_assoc($ManageUsers);

if (isset($_GET['totalRows_ManageUsers'])) {
  $totalRows_ManageUsers = $_GET['totalRows_ManageUsers'];
} else {
  $all_ManageUsers = mysql_query($query_ManageUsers);
  $totalRows_ManageUsers = mysql_num_rows($all_ManageUsers);
}
$totalPages_ManageUsers = ceil($totalRows_ManageUsers/$maxRows_ManageUsers)-1;

$queryString_ManageUsers = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_ManageUsers") == false && 
        stristr($param, "totalRows_ManageUsers") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_ManageUsers = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_ManageUsers = sprintf("&totalRows_ManageUsers=%d%s", $totalRows_ManageUsers, $queryString_ManageUsers);
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
		<title>www.uSE.co.ke/SU</title>
	</head>

	<body>
    	<div id="Holder">
        	<div id="Header"></div>
            <div id="Navbar">
            	<nav>
                	<ul>
                    	<li><a href="UserManager.php">Manager</a></li>
                    </ul>
                </nav>
                
            </div>
            <div id="Content">
            	<div id="PageHeading">
            	  <h1>uSE</h1>
           	  </div>
                <div id="ContentLeft">
                  <h2>SuperUser</h2>
                  <br  />
                  <h6>Navigation</h6>
                  <br />
                  <ul class="unstyled">
                  	<li>
                    	<h5><a href="AdminCP.php">Admin Control Panel</a>
               	      </h5>
                  	</li>
                    <br />
                    <li>
                    	<h5><a href="AdminUpdateAccount.php">Update Account</a>
               	      </h5>
                  	</li>
                    <br />
                  	<li>
                    	<h5><a href="AdminLogOut.php">LogOut</a>
                  	  </h5>
                    </li>
                  </ul>
            </div>
                <div id="ContentRight">
                  <table width="670" align="center">
                    <tr>
                      <td align="right" valign="top"><h5>Showing: &nbsp;<?php echo ($startRow_ManageUsers + 1) ?> to: <?php echo min($startRow_ManageUsers + $maxRows_ManageUsers, $totalRows_ManageUsers) ?> of: <?php echo $totalRows_ManageUsers ?></h5></td>
                    </tr>
                    <tr>
                      <td align="center" valign="top"><?php if ($totalRows_ManageUsers > 0) { // Show if recordset not empty ?>
                        <?php do { ?>
                          <table width="500" border="0">
                            <tr>
                              <td><h5><?php echo $row_ManageUsers['FName']; ?>
                              <?php echo $row_ManageUsers['LName']; ?> | <?php echo $row_ManageUsers['Email']; ?> | <?php echo $row_ManageUsers['Phone']; ?> | <?php echo $row_ManageUsers['Username']; ?> | <?php echo $row_ManageUsers['MoneyOrder']; ?></h5></td>
                            </tr>
                            <tr>
                              <td><form id="DeleteUser" name="DeleteUser" method="post" action="">
                                <input name="DeleteUserHiddenField" type="hidden" id="DeleteUserHiddenField" value="<?php echo $row_ManageUsers['UserID']; ?>" />
                                <input type="submit" name="DeleteUserButton" id="DeleteUserButton" value="Delete User" class="btn btn-danger" />
                              </form></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                          </table>
                        <?php } while ($row_ManageUsers = mysql_fetch_assoc($ManageUsers)); ?>
                      <?php } // Show if recordset not empty ?></td>
                    </tr>
                    <tr>
                      <td align="right" valign="top"><?php if ($pageNum_ManageUsers > 0) { // Show if not first page ?>
                          <h5><a href="<?php printf("%s?pageNum_ManageUsers=%d%s", $currentPage, min($totalPages_ManageUsers, $pageNum_ManageUsers + 1), $queryString_ManageUsers); ?>">Next</a></h5>
                      <?php } // Show if not first page ?></td>
                    </tr>
                    <tr>
                      <td align="right" valign="top"><h5>
                        <?php if ($pageNum_ManageUsers > 0) { // Show if not first page ?>
                          <a href="<?php printf("%s?pageNum_ManageUsers=%d%s", $currentPage, max(0, $pageNum_ManageUsers - 1), $queryString_ManageUsers); ?>">Previous</a>
                          <?php } // Show if not first page ?>
                      </h5></td>
                    </tr>
                  </table>
                </div>
            </div>
            <div id="Footer">
            	<footer id="footer" class="">
                	<div class="container">
                    	<div class="row">
                        	<div class="col-sm-6">
                            	<br />
                       	    <h6><u>Navigation</u></h6>
                                <br />
                          <ul class="unstyled">
                                	<li>
                                	  <h5><a href="#">No Ads</a></h5>
                                	</li>
                            <li>
                              <h5><a href="#">Developer</a></h5>
                            </li>
                            <li>
                              <h5><a href="#">About Us</a></h5>
                            </li>
                                <li>
                                  <h5><a href="#">Terms &amp; Conditions</a></h5>
                                </li>
                                </ul>
                            </div>
                            <div class="col-sm-6">
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
	</body>
    <script src="../includes/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../includes/myjs/custom js.js" type="text/javascript"></script>
</html>
<?php
mysql_free_result($ManageUsers);
?>
