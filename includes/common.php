<?php
session_start();
define("TEMPLATE_DIR", "templates/template_content_angularjs/");

define ("DB_NAME", "");
define ("DB_HOST", "");
define ("DB_PORT", "");
define ("DB_USER", "");
define ("DB_PASSWORD", "");

global $dbh;
$dbh = new PDO("pgsql:dbname=".DB_NAME.";host=".DB_HOST, DB_USER, DB_PASSWORD);
?>
