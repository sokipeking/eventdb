<?php
include_once("common.php");
class User {
    private $userinfo;
    function login($username, $password) {
        global $dbh;
        $sql = "SELECT id, username FROM sys_user WHERE username=:username and passwd=:password and status=true";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array("username"=>$username, "password"=>md5($password))) or die("sql错误");
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        for ($i=0; $i<count($res);$i++) {
            $this->userinfo = (object)$res[$i]; 
        }
        if ($this->userinfo)
            return true;
        else
            return false;
    }

    function get_userinfo() {
        return $this->userinfo;
    }
}

class UserController {
    
    function login($params = array()) {
        $username = $params["username"];
        $password = $params["password"];
        $user = new User();
        if (!$user->login($username, $password))
            return "登录失败，用户名或密码错误";
        else
            $_SESSION["userinfo"] = $user->get_userinfo();
            return "ok";
    }

    function get_user() {
        return json_encode($_SESSION["userinfo"]);
    }

    function logout() {
        unset($_SESSION["userinfo"]);
    }
}
$controller = new UserController();
?>
