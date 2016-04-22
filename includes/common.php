<?php
session_start();
define("TEMPLATE_DIR", "templates/template_content_angularjs/");

define ("DB_NAME", "eventdb");
define ("DB_HOST", "127.1");
define ("DB_PORT", "5432");
define ("DB_USER", "duyao");
define ("DB_PASSWORD", "");

global $dbh;
$dbh = new PDO("pgsql:dbname=".DB_NAME.";host=".DB_HOST, DB_USER, DB_PASSWORD); 
?>
