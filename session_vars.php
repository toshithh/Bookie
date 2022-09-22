<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
	print("<script>window.location.href='index.html'; </script>");
	exit;
}
$fnm = $_SESSION['fnm'];
$lnm = $_SESSION['lnm'];
$email = $_SESSION['email'];
$gender = $_SESSION['gender'];
print "<script>";
print "session_vars = ['$fnm', '$lnm', '$email', '$gender'];";
print "</script>";

?>