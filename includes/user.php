<?php
include_once("common.php");
class User {
    private $userinfo;
    private $dbh;

    function __construct(){
        global $dbh;
        $this->dbh = $dbh;
    }


    function get($username) {
        $sql = "SELECT COUNT(1) as c FROM sys_user WHERE username=:username";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute(array("username"=>$username));
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $res[0]["c"];
    }

    function list_user() {
        $sql = "SELECT id, username, status FROM sys_user";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function get_info($id) {
        $sql = "SELECT id, username, status FROM sys_user WHERE id=:id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute(array("id"=>$id));
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows[0];
    }

    function edit($id, $username, $password, $status, $create_user){
        if ($status) {
            $status = "true";
        } else {
            $status = "false";
        }
        if (!$id) {
            $c = $this->get($username);
            if ($c) {
                return "已存在同名账户";
            }
            $sql = "INSERT INTO sys_user VALUES(default, :username, :password, :status, current_timestamp, current_timestamp, :create_user,:create_user)";
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute(array("username"=> $username, "password"=>md5($password), "status"=>$status, "create_user"=>$create_user)) or die("编辑用户失败");
        } else {
            $c = $this->get($username);
            if ($c > 1) {
                return "已存在同名账户";
            }
            $sql = "UPDATE sys_user SET username=:username, passwd=:password, status=:status, update_dt=current_timestamp, last_editor=:create_user WHERE id=:id";
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute(array("username"=> $username, "password"=>md5($password), "status"=>$status, "create_user"=>$create_user, "id"=>$id)) or die("编辑用户失败");
        }
        return true;
    }

    function login($username, $password) {
        $sql = "SELECT id, username FROM sys_user WHERE username=:username and passwd=:password and status=true";
        $stmt = $this->dbh->prepare($sql);
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
    private $user;

    function __construct(){
        $this->user = new User();
    }

    function edit_user($params = array()) {
        extract($params);
        return $this->user->edit($id, $username, $password, $status, $_SESSION["userinfo"]->username);
    }

    function list_user($params = array()) {
        return json_encode($this->user->list_user());
    }

    function get_user_info($params = array()) {
        extract($params);
        return json_encode($this->user->get_info($id));
    }

    function login($params = array()) {
        $username = strtolower($params["username"]);
        $password = $params["password"];
        if (!$this->user->login($username, $password))
            return "登录失败，用户名或密码错误";
        else
            $_SESSION["userinfo"] = $this->user->get_userinfo();
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
