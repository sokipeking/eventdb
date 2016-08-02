<?php
include_once("common.php");
include_once("doc.php");

class IndustryObj{
    private $dbh;

    function __construct(){
        global $dbh;
        $this->dbh = $dbh;
    }

    public $properties = array(
        "id"=>null,
        "industry_name"=>null,
        "sub_industry_name"=>null,
        "region"=>null,
        "summary"=>null,
        "release_projects"=>null,
        "last_update"=>null,
        "create_dt"=>null,
        "update_dt"=>null,
        "create_user"=>null,
        "last_editor"=>null,
        "industry_option"=>null,
        "industry_option_free"=>null,
        "industry_option_text"=>null,
        "status"=>null
    );
    function create(){
        $sql = "INSERT INTO industry values(
            default,
            :industry_name,
            :sub_industry_name,
            :region,
            :summary,
            :release_projects,
            :last_update,
            now(),
            now(),
            :create_user,
            :last_editor,
            :industry_option,
            :industry_option_free,
            :industry_option_text,
            true
        )";
        $stmt = $this->dbh->prepare($sql);
        $params = array();
        foreach($this->properties as $k=>$v){
            if (in_array($k, array("id", "create_dt", "update_dt", "status"))){
                continue;
            }
            $params[$k] = $this->$k;
        }
        $res = $stmt->execute(
            $params
        ) or die ("行业创建失败, 请联系技术人员");
        //print_r($stmt->errorInfo());

        return $this->dbh->lastInsertId("industry_id_seq");
    } 
    function update(){
        $sql = "UPDATE industry
            set industry_name = :industry_name,
            sub_industry_name = :sub_industry_name,
            region = :region,
            summary = :summary,
            releease_projects = :release_projects,
            last_update = :last_update,
            update_dt = now(),
            last_editor = :last_editor,
            industry_option = :industry_option,
            industry_option_free = :industry_option_free,
            industry_option_text = :industry_option_text
            WHERE id=:id
        ";
        $stmt = $this->dbh->prepare($sql);
        $params = array();
        foreach($this->properties as $k=>$v){
            if (in_array($k, array(
                "create_dt", "update_dt", "status",
                "create_user"
            ))){
                continue;
            }
            $params[$k] = $this->$k;
        }
        $res = $stmt->execute(
            $params
        ) or die ("行业修改失败, 请联系技术人员");
        //print_r($stmt->errorInfo());

        return true;
    }
    function delete($id) {
        $sql = "UPDATE industry SET status=false WHERE id=:id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute(array("id"=>$id));
        return true;
    }
    function get_list() {
        $sql = "SELECT * FROM industry WHERE status=true";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    function get_info($id) {
        $sql = "SELECT * FROM industry WHERE id=:id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute(array("id"=>$id));
        $info = $stmt->fetch();
        $release_projects = explode(";", $info["releease_projects"]);
        for($i=0; $i<count($release_projects);$i++){
            $release_projects[$i] = intval(str_replace("P", "", $release_projects[$i]));
        }
        $sql = "SELECT id, file_name FROM customer WHERE id in (".join(",", $release_projects).")";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $info["release_projects_link"] = $stmt->fetchAll();
        return $info;
    }
}

class IndustryInfo{
    private $dbh;

    function __construct(){
        global $dbh;
        $this->dbh = $dbh;
    }
    
    public $properties = array(
        "id"=>null,
            "industry_option"=>null,
            "industry_option_free"=>null,
            "industry_option_text"=>null,
            "status"=>null,
            "industry_id"=>null
        );

    function create(){
        $sql = "INSERT INTO industry_info values
            (
                default,
                :industry_option,
                :industry_option_free,
                :industry_option_text,
                true,
                :industry_id
            )
            ";
        $stmt = $this->dbh->prepare($sql);
        $params = array();
        foreach($this->properties as $k=>$v){
            if (in_array($k, array("id", "status"))){
                continue;
            }
            $params[$k] = $this->$k;
        }
        $res = $stmt->execute(
            $params
        ) or die ("行业信息创建失败, 请联系技术人员");
        //print_r($stmt->errorInfo());
        return $this->dbh->lastInsertId("industry_info_id_seq");
    }

    function update(){
        $sql = "UPDATE industry_info 
                set industry_option=:industry_option,
                industry_option_free=:industry_option_free,
                industry_option_text=:industry_option_text
                WHERE id=:id
            ";
        $stmt = $this->dbh->prepare($sql);
        $params = array();
        foreach($this->properties as $k=>$v){
            if (in_array($k, array("status", "industry_id"))){
                continue;
            }
            $params[$k] = $this->$k;
        }
        $res = $stmt->execute(
            $params
        ) or die ("行业信息修改失败, 请联系技术人员");
        //print_r($stmt->errorInfo());
        return true;
    }
    function delete($id) {
        $sql = "UPDATE industry_info SET status=false WHERE id=:id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute(array("id"=>$id));
        return true;
    }
    function get_list($industry_id){
        $sql = "SELECT * FROM industry_info WHERE status=true AND industry_id=:industry_id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute(array("industry_id"=>$industry_id));
        return $stmt->fetchAll();
    }

}

class IndustryFile{
    
    private $dbh;

    function __construct(){
        global $dbh;
        $this->dbh = $dbh;
    }

    public $properties = array(
        "id"=>null,
        "udate"=>null,
        "title"=>null,
        "ftype"=>null,
        "create_dt"=>null,
        "update_dt"=>null,
        "create_user"=>null,
        "last_editor"=>null,
        "industry_id"=>null,
        "status"=>null,
        "file_type"=>null
    );
    
    function delete($id) {
        $sql = "UPDATE industry_file SET status=false WHERE id=:id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute(array("id"=>$id));
        return true;
    }

    function get_list($industry_id, $file_type) {
        $file_obj = new LogFiles();
        $sql = "SELECT id, udate as adate, title, ftype, file_type FROM industry_file WHERE industry_id=:industry_id AND file_type=:file_type AND status=true";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute(array("industry_id"=>$industry_id, "file_type"=>$file_type));
        $rows = array();
        foreach ($stmt->fetchAll() as $row){
            $row["files"] = $file_obj->get_list($row["id"], $file_type + 2);
            array_push($rows, $row); 
        }
        return $rows;
    }


    function update() {
        $sql = "UPDATE industry_file 
                set udate=:udate,
                title=:title,
                ftype=:ftype,
                update_dt=now(),
                last_editor=:last_editor
                WHERE id=:id
            ";
        $stmt = $this->dbh->prepare($sql);
        $params = array();
        foreach($this->properties as $k=>$v){
            if (in_array($k, array("create_dt", "update_dt", "status", "file_type", "create_user", "industry_id"))){
                continue;
            }
            $params[$k] = $this->$k;
        }
        $res = $stmt->execute(
            $params
        ) or die ("资料创建失败, 请联系技术人员");
        //print_r($stmt->errorInfo());
        return true;
    }

    function create() {
        $sql = "INSERT INTO industry_file values
            (
                default,
                :udate,
                :title,
                :ftype,
                now(),
                now(),
                :create_user,
                :last_editor,
                :industry_id,
                true,
                :file_type
            )
            ";
        $stmt = $this->dbh->prepare($sql);
        $params = array();
        foreach($this->properties as $k=>$v){
            if (in_array($k, array("id", "create_dt", "update_dt", "status"))){
                continue;
            }
            $params[$k] = $this->$k;
        }
        $res = $stmt->execute(
            $params
        ) or die ("资料创建失败, 请联系技术人员");
        //print_r($stmt->errorInfo());
        return $this->dbh->lastInsertId("industry_file_id_seq");
    }
}

class IndustryController{
    private $industry;
    private $create_user;
    private $file_obj;

    function __construct(){
        $this->industry = new IndustryObj();
        $this->industry_file_1 = new IndustryFile();
        $this->industry_file_2 = new IndustryFile();
        $this->industry_info = new IndustryInfo();
        $this->create_user = $_SESSION["userinfo"]->username;
        $this->file_obj = new LogFiles();
    }

    function get_industry_id($id) {
        $tpl = "I000000";
        $tpl = substr($tpl, 0, strlen($tpl) - strlen($id));
        return $tpl.$id;
    }
    function get_industry_info($params=array()) {
        extract($params);
        $info = $this->industry->get_info($id); 
        $info["zbera_files"] = $this->industry_file_1->get_list($id, 1);
        $info["files"] = $this->industry_file_1->get_list($id, 2);
        $info["industry_infos"] = $this->industry_info->get_list($id);
        return json_encode($info);
    }
    function get_industry_list() {
        $res = $this->industry->get_list(); 
        $rows = array();
        foreach($res as $row) {
            array_push($rows, array(
                $this->get_industry_id($row["id"]),
                $row["industry_name"],
                $row["sub_industry_name"],
                $row["region"],
                $row["last_update"],
                "<a class='fa-pencil fa' href='#/app/industry/edit/{$row["id"]}'></a>&nbsp;
                <a class='fa-trash-o fa' href='#/app/industry/delete/{$row["id"]}'></a>&nbsp;"
            ));
        }
        return json_encode($rows);
    }
    function delete_industry($params=array()){
        extract($params);
        return $this->industry->delete($id);
    }
    function delete_industry_info($params=array()){
        extract($params);
        return $this->industry_info->delete($id);
    }
    function delete_industry_file($params=array()){
        extract($params);
        return $this->industry_file_1->delete($id);
    }

    function update_industry($params=array()){
        extract($params);
        $this->industry->industry_name = $industry_name; 
        $this->industry->sub_industry_name = $sub_industry_name; 
        $this->industry->region = $region; 
        $this->industry->summary = $summary; 
        $this->industry->release_projects = $release_projects; 
        $this->industry->last_update = $last_update; 
        $this->industry->last_editor = $this->create_user; 
        $this->industry->industry_option = $industry_option; 
        $this->industry->industry_option_free = $industry_option_free; 
        $this->industry->industry_option_text = $industry_option_text; 
        $this->industry->id = $id;
        $this->industry->update();
        foreach($zebra_files as $zebra_file){
            if (empty($zebra_file["title"]))
                continue;
            if (empty($zebra_file["id"])) {
                $this->create_industry_file($zebra_file, 1);
            } else {
                $this->edit_industry_file($zebra_file);
            }
        }
        foreach($files as $sfile){
            if (empty($sfile["title"]))
                continue;
            if (empty($sfile["id"])) {
                $this->create_industry_file($sfile, 2);
            } else {
                $this->edit_industry_file($sfile);
            }
        }
        foreach($industry_infos as $industry_info) {
            if (empty($industry_info["industry_option"]))
                continue;
            if (empty($industry_info["id"])){
                $this->industry_info->industry_option = $industry_info["industry_option"];
                $this->industry_info->industry_option_free = $industry_info["industry_option_free"];
                $this->industry_info->industry_option_text = $industry_info["industry_option_text"];
                $this->industry_info->industry_id= $this->industry->id;
                $this->industry_info->create();
            } else {
                $this->industry_info->id = $industry_info["id"];
                $this->industry_info->industry_option = $industry_info["industry_option"];
                $this->industry_info->industry_option_free = $industry_info["industry_option_free"];
                $this->industry_info->industry_option_text = $industry_info["industry_option_text"];
                $this->industry_info->update();
            }
        }
        return true;
    }
    
    function create_industry_file($zebra_file, $file_type) {
        $this->industry_file_1->udate = $zebra_file["adate"];
        $this->industry_file_1->title = $zebra_file["title"];
        $this->industry_file_1->ftype = $zebra_file["ftype"];
        $this->industry_file_1->create_user = $this->create_user;
        $this->industry_file_1->last_editor = $this->create_user;
        $this->industry_file_1->industry_id = $this->industry->id;
        $this->industry_file_1->file_type = $file_type;
        $this->industry_file_1->id = $this->industry_file_1->create(); 
        foreach ($zebra_file["files"] as $file){
            if (empty($file["file_name"]))
                continue;
            $this->file_obj->create($this->industry_file_1->id,
                $file["file_name"],
                $file["file_path"],
                $file_type + 2
            ); 
        }
    }
    function edit_industry_file($zebra_file) {
        $this->industry_file_2->udate = $zebra_file["adate"];
        $this->industry_file_2->title = $zebra_file["title"];
        $this->industry_file_2->ftype = $zebra_file["ftype"];
        $this->industry_file_2->last_editor = $this->create_user;
        $this->industry_file_2->id = $zebra_file["id"]; 
        @$this->industry_file_2->update(); 
        foreach($zebra_file["files"] as $file){
            if (empty($file["file_name"]))
                continue;
            if (empty($file["id"])) {
                @$this->file_obj->create($zebra_file["id"],
                    $file["file_name"],
                    $file["file_path"],
                    $zebra_file["file_type"] + 2
                );
            }else{
                @$this->file_obj->update(
                    $file["id"],
                    $zebra_file["id"],
                    $file["file_name"],
                    $file["file_path"]
                );
            }
        }
    }

    function create_industry($params=array()){
        extract($params);
        $this->industry->industry_name = $industry_name; 
        $this->industry->sub_industry_name = $sub_industry_name; 
        $this->industry->region = $region; 
        $this->industry->summary = $summary; 
        $this->industry->release_projects = $release_projects; 
        $this->industry->last_update = $last_update; 
        $this->industry->create_user = $this->create_user; 
        $this->industry->last_editor = $this->create_user; 
        $this->industry->industry_option = $industry_option; 
        $this->industry->industry_option_free = $industry_option_free; 
        $this->industry->industry_option_text = $industry_option_text; 
        $this->industry->id = @$this->industry->create();
        foreach($industry_infos as $industry_info) {
            if (empty($industry_info["industry_option"]))
                continue;
            $this->industry_info->industry_option = $industry_info["industry_option"];
            $this->industry_info->industry_option_free = $industry_info["industry_option_free"];
            $this->industry_info->industry_option_text = $industry_info["industry_option_text"];
            $this->industry_info->industry_id= $this->industry->id;
            $this->industry_info->create();
        }
        foreach($zebra_files as $zebra_file){
            if (empty($zebra_file["title"]))
                continue;
            $this->create_industry_file($zebra_file, 1);
        }
        foreach($files as $sfile){
            if (empty($sfile["title"]))
                continue;
            $this->create_industry_file($sfile, 2);
        }
        return true;
    }

}
$controller = new IndustryController();
?>
