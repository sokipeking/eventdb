<?php
include_once("common.php");
class DocObj{
    private $dbh;
    public $properties = array("id"=>null,
    "file_name"=>null,
    "date_opened"=>null,
    "last_updated"=>null,
    "industry"=>null,
    "region"=>null,
    "stage"=>null,
    "company_info"=>null,
    "money_type_1"=>null,
    "raising_target"=>null,
    "money_type_2" =>null,
    "pre_money"=>null,
    "investment_type"=>null,
    "investment_structure"=>null,
    "source"=>null,
    "decision_stage"=>null,
    "interest_level"=>null,
    "next_move"=>null,
    "note"=>null,
    "zebra_team"=>null,
    "company_name"=>null,
    "company_address"=>null,
    "create_dt"=>null,
    "update_dt"=>null,
    "create_user"=>null,
    "last_editor"=>null,
    "status"=>null,
    "stage_free"=>null,
    "money_type_1_free"=>null,
    "money_type_2_free"=>null,
    "investment_type_free"=>null,
    "investment_structure_free"=>null,
    "source_free"=>null,
    "decision_stage_free"=>null,
    "interest_level_free"=>null
    );
    //extract($this->properties);

    function __construct($id=null){
        
        global $dbh;
        $this->dbh = $dbh;
        $this->id = $id;
        $this->obj = $this->get_obj($this->id);
    }

    function set($k, $v){
        $this->$k = $v;
    }

    function delete($id) {
        $sql = "UPDATE customer SET status=false WHERE id=:id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute(array("id"=>$id)) or die("删除资料失败");
        $sql = "UPDATE contact SET status=false WHERE customer_id=:id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute(array("id"=>$id)) or die("删除联系人失败");
        $sql = "UPDATE activity_log SET status=false WHERE customer_id=:id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute(array("id"=>$id)) or die("删除日志失败");
        return true;
    }
    function update() {
        $sql = "UPDATE customer SET 
            file_name=:file_name,
            date_opened=:date_opened,
            last_updated=:last_updated,
            industry=:industry,
            region=:region,
            stage=:stage,
            company_info=:company_info,
            money_type_1=:money_type_1,
            raising_target=:raising_target,
            money_type_2=:money_type_2,
            pre_money=:pre_money,
            investment_type=:investment_type,
            investment_structure=:investment_structure,
            source=:source,
            decision_stage=:decision_stage,
            interest_level=:interest_level,
            next_move=:next_move,
            note=:note,
            zebra_team=:zebra_team,
            company_name=:company_name,
            company_address=:company_address,
            update_dt=now(),
            last_editor=:last_editor,
            stage_free=:stage_free,
            money_type_1_free=:money_type_1_free,
            money_type_2_free=:money_type_2_free,
            investment_type_free=:investment_type_free,
            investment_structure_free=:investment_structure_free,
            source_free=:source_free,
            decision_stage_free=:decision_stage_free,
            interest_level_free=:interest_level_free
            WHERE id=:id";
        $stmt = $this->dbh->prepare($sql);
        $params = array();
        foreach($this->properties as $k=>$v){
            if (in_array($k, array("create_user","create_dt", "update_dt","create_user", "status"))){
                continue;
            }
            $params[$k] = $this->$k;
        }
        //print_r($params);
        $res = $stmt->execute(
            $params
        ) or die ("文档修改失败, 请联系技术人员");
        //print_r($stmt->errorInfo());

        return true;
    }

    function create() {
        $sql = "insert into customer values(
            default,
            :file_name,
            :date_opened,
            :last_updated,
            :industry,
            :region,
            :stage,
            :company_info,
            :money_type_1,
            :raising_target,
            :money_type_2,
            :pre_money,
            :investment_type,
            :investment_structure,
            :source,
            :decision_stage,
            :interest_level,
            :next_move,
            :note,
            :zebra_team,
            :company_name,
            :company_address,
            now(),
            now(),
            :create_user,
            :last_editor,
            true,
            :stage_free,
            :money_type_1_free,
            :money_type_2_free,
            :investment_type_free,
            :investment_structure_free,
            :source_free,
            :decision_stage_free,
            :interest_level_free
        )";
        $stmt = $this->dbh->prepare($sql);
        $params = array();
        foreach($this->properties as $k=>$v){
            if (in_array($k, array("id", "create_dt", "update_dt", "status"))){
                continue;
            }
            $params[$k] = $this->$k;
        }
        //print_r($params);
        $res = $stmt->execute(
            $params
        ) or die ("文档创建失败, 请联系技术人员");
        //print_r($stmt->errorInfo());

        return $this->dbh->lastInsertId("customer_id_seq");
    }
    function get_no($id){
        $tpl = "P000000";
        $tpl = substr($tpl, 0, strlen($tpl) - strlen($id));
        return $tpl.$id;
    }
    function get_list($decision_stage){
        $str = "";
        if (!empty($decision_stage)){
            //print_r($decision_stage);
            $str = " and decision_stage in (".join($decision_stage, ",").")"; 
        }
        $sql = "SELECT * FROM customer WHERE status=true". $str;
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $rows = array();
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
            $money_type_1 = $row["money_type_1"]==999 ? $row["money_type_1_free"] : $row["money_type_1"];
            $money_type_2 = $row["money_type_2"]==999 ? $row["money_type_2_free"] : $row["money_type_2"];
            $investment_type = $row["investment_type"]==999 ? $row["investment_type_free"]:$row["investment_type"];
            $decision_stage = $row["decision_stage"]==999 ? $row["decision_stage_free"]:$row["decision_stage"];
            $interest_level = $row["interest_level"]==999 ? $row["interest_level_free"]:$row["interest_level"];
            array_push($rows, array(
                $this->get_no($row["id"]),
                $row["date_opened"],
                "<a href='#/app/doc/show/".$row["id"]."'>".$row["file_name"]."</a>",
                $row["industry"],
                $row["region"],
                $row["raising_target"]."M".$money_type_1,
                $row["pre_money"]."M".$money_type_2,
                $investment_type,
                $decision_stage,
                $interest_level,
                $row["next_move"],
                "<a class='fa-pencil fa' href='#/app/doc/edit/{$row["id"]}'></a>&nbsp;
                <a class='fa-trash-o fa' href='#/app/doc/delete/{$row["id"]}'></a>&nbsp;
                <!--<a class='fa-send fa' href='#/app/doc/{$row["id"]}/mail'></a>-->"
            ));
        }
        return $rows;
    }

    function get_obj($id){
        $sql = "SELECT * FROM customer WHERE id=:id AND status=true";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute(array("id"=>$id));
        $res = array();
        $contact_obj = new CompanyContact();
        $log_obj = new ActivityLog();
        $doc_file = new CustomerFile();
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $customer) {
            $customer["contacts"] = $contact_obj->get_list($customer["id"]);
            $customer["logs"] = $log_obj->get_list($customer["id"]);
            $customer["files"] = $doc_file->get_list($customer["id"]);;
            return $customer;
        }
    }
}

class LogFiles {
    private $dbh;
    function __construct(){
        global $dbh;
        $this->dbh = $dbh;
    }

    function get_list($aid, $d_type) {
        $sql = "SELECT * FROM activity_log_files WHERE a_id=:aid AND d_type=:d_type AND status=true order by id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute(array("aid"=>$aid, "d_type"=>$d_type));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function create($a_id, $file_name, $file_path, $d_type) {
        $sql = "INSERT INTO activity_log_files VALUES(
            default, :a_id,:file_name,:file_path,true,:d_type
        )";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute(array(
            "a_id"=>$a_id,
            "file_name"=>$file_name,
            "file_path"=>$file_path,
            "d_type"=>$d_type
        ));
        return true;
    }

    function update($id, $a_id, $file_name, $file_path) {
        $sql = "UPDATE activity_log_files SET file_name=:file_name, file_path=:file_path WHERE id=:id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute(array("file_name"=>$file_name, "file_path"=>$file_path, "id"=>$id));
        return true;
    }

    function delete($id) {
        $sql = "UPDATE activity_log_files SET status=false WHERE id=:id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute(array("id"=>$id));
        return true;
    }
}

class CompanyContact {
    private $dbh;
    function __construct(){
        global $dbh;
        $this->dbh = $dbh;
    }
    
    function get_list($customer_id){
        $sql = "SELECT * FROM contact WHERE customer_id=:customer_id AND status=true";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute(array("customer_id"=>$customer_id));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function update($id, $name, $title, $phone, $email, $create_user, $related, $release, $company_name, $weixin,$related_free,$release_free) {
        $sql = "UPDATE contact SET
            name=:name,
            title=:title, phone=:phone, email=:email,
            update_dt=current_timestamp, last_editor=:create_user,related=:related,
            release=:release,company_name=:company_name,weixin=:weixin,related_free=:related_free, release_free=:release_free WHERE id=:id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute(
            array("name"=>$name, "title"=>$title, "phone"=>$phone,
            "email"=>$email, "create_user"=>$create_user, "id"=>$id, "related"=>$related,
            "release"=>$release, "company_name"=>$company_name,"weixin"=>$weixin,
            "release_free"=>$release_free, "related_free"=>$related_free)
        ) or die("修改联系人失败，请联系技术人员");
        return true;
    }

    function delete($id) {
        $sql = "UPDATE contact SET status=false WHERE id=:id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute(array("id"=>$id)) or die("删除联系人失败,请联系技术人员");
        return true;
    }

    function create(
        $name,
        $title,
        $phone,
        $email,
        $create_user,
        $customer_id,
        $related, $release, $company_name, $weixin,$related_free,$release_free
    ){
        $sql = "INSERT INTO contact VALUES(
            default,
            :name,
            :title,
            :phone,
            :email,
            current_timestamp,
            current_timestamp,
            :create_user,
            :create_user,
            :customer_id,
            true,
            :related,
            :release,
            :company_name,
            :weixin,
            :related_free,
            :release_free
        )";
        $stmt = $this->dbh->prepare($sql);
        $params = array("name"=>$name, "title"=>$title, "phone"=>$phone,
                "email"=>$email, "create_user"=>$create_user,
                "customer_id"=>$customer_id,
                "related"=>$related, "release"=>$release, "company_name"=>$company_name, "weixin"=>$weixin,"related_free"=>$related_free,"release_free"=>$release_free);
        //print_r($params);
        $stmt->execute(
            $params
        ) or die("创建联系人失败, 请联系技术人员");
        return true;
    }
}

class CustomerFile {
    private $dbh;
    function __construct(){
        global $dbh;
        $this->dbh = $dbh;
    }

    function get_list($customer_id){
        $sql = "SELECT * FROM customer_file WHERE customer_id=:customer_id AND status=true ORDER BY ID ASC";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute(array("customer_id"=>$customer_id));
        $res = array();
        $file_obj = new LogFiles();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $log) {
            $log["files"] = $file_obj->get_list($log["id"], 2);
            array_push($res, $log);
        }
        return $res;
    }

    function delete($id) {
        $sql = "UPDATE customer_file set status=false WHERE id=:id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute(array("id"=>$id)) or die("删除文档失败，请联系技术人员");
        return true;
    }
    
    function update(
        $ftype,
        $note,
        $create_user,
        $adate,
        $note,
        $id,
        $ftype_free
    ){
        $sql = "UPDATE customer_file SET 
            ftype=:ftype,
            note=:note,
            update_dt=current_timestamp,
            last_editor=:create_user,
            adate=:adate,
            ftype_free=:ftype_free
            WHERE id=:id
        "; 
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute(
            array(
                "ftype"=>$ftype, "note"=>$note,"adate"=>$adate, "id"=>$id,"create_user"=>$create_user,"ftype_free"=>$ftype_free
            )
        ) or die("修改文档失败，请联系技术人员");
        return true;
    }

    function create(
        $ftype,
        $note,
        $create_user,
        $customer_id,
        $adate,
        $ftype_free
    ){
        $sql = "INSERT INTO customer_file VALUES(
            default,
            :adate,
            :ftype,
            :note,
            current_timestamp,
            current_timestamp,
            :create_user,
            :create_user,
            :customer_id,
            true,
            :ftype_free
        )"; 
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute(
            array(
                "ftype"=>$ftype, "note"=>$note, "create_user"=>$create_user, "customer_id"=>$customer_id,
                "adate"=>$adate,"ftype_free"=>$ftype_free
            )
        ) or die ("创建文件失败,请联系技术人员");
            //print_r($stmt->errorInfo());
        return $this->dbh->lastInsertId("customer_file_id_seq");
    }
} 

class ActivityLog {
    private $dbh;
    function __construct(){
        global $dbh;
        $this->dbh = $dbh;
    }

    function get_list($customer_id){
        $sql = "SELECT * FROM activity_log WHERE customer_id=:customer_id AND status=true ORDER BY ID ASC";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute(array("customer_id"=>$customer_id));
        $res = array();
        $file_obj = new LogFiles();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $log) {
            $log["files"] = $file_obj->get_list($log["id"], 1);
            array_push($res, $log);
        }
        return $res;
    }

    function delete($id) {
        $sql = "UPDATE activity_log set status=false WHERE id=:id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute(array("id"=>$id)) or die("删除日志失败，请联系技术人员");
        return true;
    }
    
    function update(
        $activity,
        $document,
        $document_file,
        $note,
        $create_user,
        $customer_id,
        $adate,
        $id
    ){
        $sql = "UPDATE activity_log SET 
            activity=:activity,
            document=:document,
            note=:note,
            update_dt=current_timestamp,
            last_editor=:create_user,
            document_file=:document_file,
            adate=:adate
            WHERE id=:id
        "; 
        $stmt = $this->dbh->prepare($sql);
        $params =  array(
                "activity"=>$activity, "document"=>$document, "document_file"=>$document_file, "note"=>$note, "create_user"=>$create_user,
                "adate"=>$adate, "id"=>$id
            );
        $stmt->execute(
            $params
        ) or die("修改日志失败，请联系技术人员");

        return true;
    }

    function create(
        $activity,
        $document,
        $document_file,
        $note,
        $create_user,
        $customer_id,
        $adate
    ){
        $sql = "INSERT INTO activity_log VALUES(
            default,
            :activity,
            '',
            :note,
            current_timestamp,
            current_timestamp,
            :create_user,
            :create_user,
            :customer_id,
            '',
            true,
            :adate
        )"; 
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute(
            array(
                "activity"=>$activity, "note"=>$note, "create_user"=>$create_user, "customer_id"=>$customer_id,
                "adate"=>$adate
            )
        ) or die ("创建日志失败,请联系技术人员");
        return $this->dbh->lastInsertId("activity_log_id_seq");
    }
} 

class DocController {
    private $doc;
    private $contact_obj;
    private $log_obj;
    private $create_user;
    private $file_obj;
    private $customer_file;
    function __construct(){
        $this->doc = new DocObj();
        $this->contact_obj = new CompanyContact();
        $this->log_obj = new ActivityLog();
        $this->file_obj = new LogFiles();
        $this->create_user = $_SESSION["userinfo"]->username;
        $this->customer_file = new CustomerFile();
    }
    
    function create_contact($params=array()) {
        extract($params);
        if (@$this->contact_obj->create($name,
            $title,
            $phone,
            $email,
            $this->create_user,
            $customer_id,$related, $release, $company_name, $weixin, $related_free, $release_free))
            return json_encode($this->contact_obj->get_list($customer_id));
    }
    function update_contact($params=array()){
        extract($params);
        if (@$this->contact_obj->update($id, $name, $title, $phone, $email, $this->create_user,
            $related, $release, $company_name, $weixin, $related_free, $release_free))
            return true;
    }

    function delete_contact($params=array()){
        extract($params);
        if ($this->contact_obj->delete($id))
            return true;
    }
    
    function delete_log($params=array()){
        extract($params);
        if ($this->log_obj->delete($id))
            return true;
    }

    function delete_document_file($params=array()){
        extract($params);
        if ($this->customer_file->delete($id))
            return true;
    }

    function create_log($params=array()){
        extract($params);
        if($this->log_obj->create(
            $activity,
            $document,
            $document_file,
            $note,
            $this->create_user,
            $customer_id,
            $adate
        ))
            return json_encode($this->log_obj->get_list($customer_id));
    }
    
    function update_log($params=array()){
        extract($params);
        if ($this->log_obj->update(
            $activity,
            $document,
            $document_file,
            $note,
            $create_user,
            $customer_id,
            $adate,
            $id
        ))
            return true;
    }

    function get_obj($params=array()){
        extract($params);
        return json_encode($this->doc->get_obj($id)); 
    }

    function get_list($params=array()){
        extract($params);
        return json_encode($this->doc->get_list($decision_stage)); 
    }

    function update_doc($params=array()){
        //extract($params);
        foreach ($params as $k=>$v){
            if (in_array($k, array("id","contacts", "logs", "files"))) {
                $$k = $v;
                continue;
            }
            $this->doc->set($k, $v);
        }
        $this->doc->set("id", $id);
        $this->doc->set("last_editor", $this->create_user);
        @$this->doc->update();
        foreach($contacts as $contact){
            if (empty($contact["id"])){
                $contact_res = @$this->contact_obj->create(
                    $contact["name"],
                    $contact["title"],
                    $contact["phone"],
                    $contact["email"],
                    $this->create_user,
                    $id,
                    $contact["related"],
                    $contact["release"],
                    $contact["company_name"],
                    $contact["weixin"],
                    $contact["related_free"],
                    $contact["release_free"]
                );
            } else {
                @$this->contact_obj->update(
                    $contact["id"],
                    $contact["name"],
                    $contact["title"],
                    $contact["phone"],
                    $contact["email"],
                    $this->create_user,
                    $contact["related"],
                    $contact["release"],
                    $contact["company_name"],
                    $contact["weixin"],
                    $contact["related_free"],
                    $contact["release_free"]
                    );
            }
        }
        foreach ($logs as $log){
            if (empty($log["id"]) && isset($log["adate"]) && $log['adate']) {
                $log_id = $this->log_obj->create(
                    $log["activity"],
                    "",
                    "",
                    $log["note"],
                    $this->create_user,
                    $id,
                    $log["adate"]
                );
            } else if(!empty($log["id"])){
                $this->log_obj->update(
                    $log["activity"],
                    "",
                    "",
                    $log["note"],
                    $this->create_user,
                    $id,
                    $log["adate"],
                    $log["id"]
                );
                $log_id = $log["id"];
            }

            foreach ($log["files"] as $file) {
                if (empty($file["id"])) {
                    if (empty($file["file_name"])) {
                        continue;
                    }
                    $this->file_obj->create(
                        $log_id, $file["file_name"], $file["file_path"], 1
                    );
                } else {
                    @$this->file_obj->update(
                        $file["id"], $log_id, $file["file_name"], $file["file_path"]
                    ); 
                }
            }

        }
        foreach ($files as $fileobj){
            if (empty($fileobj["id"]) && isset($fileobj["adate"])&& $fileobj["adate"]) {
                $file_id = $this->customer_file->create(
                    $fileobj["ftype"],
                    $fileobj["note"],
                    $this->create_user,
                    $id,
                    $fileobj["adate"],
                    $fileobj["ftype_free"]
                );
            } else if(!empty($fileobj["id"])){
                $this->customer_file->update(
                    $fileobj["ftype"],
                    $fileobj["note"],
                    $fileobj["create_user"],
                    $fileobj["adate"],
                    $fileobj["note"],
                    $fileobj["id"],
                    $fileobj["ftype_free"]
                );
                $file_id = $fileobj["id"];
            }

            foreach ($fileobj["files"] as $file) {
                if (empty($file["id"])) {
                    if (empty($file["file_name"])) {
                        continue;
                    }
                    $this->file_obj->create(
                        $file_id, $file["file_name"], $file["file_path"], 2
                    );
                } else {
                    $this->file_obj->update(
                        $file["id"], $file_id, $file["file_name"], $file["file_path"]
                    ); 
                }
            }
            
        }
        return true;
    }

    function delete_file($params=array()) {
        extract($params);
        $this->file_obj->delete($id);
        return true;
    }

    function delete_doc($params=array()) {
        extract($params);
        $this->doc->delete($id);
        return true;
    }

    function create_doc($params=array()) {
        //extract($params);
        //print_r($params);
        foreach ($params as $k=>$v){
            if (in_array($k, array("contacts", "logs", "files"))) {
                $$k = $v;
                continue;
            }
            $this->doc->set($k, $v);
        }
        $this->doc->set("create_user", $this->create_user);
        $this->doc->set("last_editor", $this->create_user);
        $res = @$this->doc->create();
        if ($res) {
            foreach ($contacts as $contact){
                if (empty($contact["name"])){
                    continue;
                }
                $contact_res = @$this->contact_obj->create(
                    $contact["name"],
                    $contact["title"],
                    $contact["phone"],
                    $contact["email"],
                    $this->create_user,
                    $res,
                    $contact['related'], 
                    $contact['release'], $contact['company_name'], $contact['weixin'],$contact["related_free"], $contact["release_free"]
                );
            }
            foreach ($logs as $log){
                $log_res = @$this->log_obj->create(
                    $log["activity"],
                    $log["document"],
                    $log["document_file"],
                    $log["note"],
                    $this->create_user,
                    $res,
                    $log["adate"]
                );   
                foreach ($log["files"] as $file) {
                    if (empty($file["file_name"])) {
                        continue;
                    }
                    $this->file_obj->create(
                        $log_res, $file["file_name"], $file["file_path"], 1
                    );
                }         
            }
            foreach ($files as $fileobj){
                if (empty($fileobj["adate"])){
                    continue;
                }
                $log_res = @$this->customer_file->create(
                    $fileobj["ftype"],
                    $fileobj["note"],
                    $this->create_user,
                    $res,
                    $fileobj["adate"],
                    $fileobj["ftype_free"]
                );   
                foreach ($fileobj["files"] as $file) {
                    if (empty($file["file_name"])) {
                        continue;
                    }
                    $this->file_obj->create(
                        $log_res, $file["file_name"], $file["file_path"], 2
                    );
                }         
            }
            #$to = array("projects@zebraglobalcap.com");
            #$subject = "文档[{$res}]{$file_name}创建成功";
            #$message = "testtest";
            #$email = new Email(true);
            #$email->send($to, $subject, $message);
            return $res; 
        }
        return false;
    }

    function get_mail_list($params) {
        extract($params);
        $email = new Email(false);
        $res = json_encode($email->search($doc_id));
        $email->close_imap();
        return $res;
    }
}
$controller = new DocController();
?>
