<?php
require_once("includes/common.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST))
        $_POST = json_decode(file_get_contents('php://input'), true);

if (!$_POST) {
    if (isset($_SESSION["userinfo"])) {
        require_once(TEMPLATE_DIR. "index.html");
    }
    else{
        require_once(TEMPLATE_DIR. "login.html");
    }
}

if ($_POST) {
    require_once("includes/{$_POST["page"]}.php");
    if ($_POST["page"] == 'doc') {
        require_once("includes/PHPMailer/PHPMailerAutoload.php");        
        require_once("includes/mail.php");        
    }
    echo call_user_func(array($controller, $_POST["function"]), $_POST["args"]);
}
?>