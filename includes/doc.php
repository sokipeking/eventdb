<?php
include_once("common.php");
class DocObj{
    private $dbh;
    function __construct(){
        global $dbh;
        $this->dbh = $dbh;
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
    function update(
        $file_name,
        $date_opened,
        $last_updated,
        $author,
        $jurisdiction,
        $region,
        $website,
        $industry,
        $model,
        $product,
        $stage,
        $pre_money,
        $raising_target,
        $zebra_stake,
        $current_status,
        $note,
        $zebra_team,
        $source,
        $contact_note,
        $create_user,
        $next_move,
        $id
    ) {
        $sql = "UPDATE customer SET 
            date_opened=:date_opened,
            last_updated=:last_updated,
            author=:author,
            jurisdiction=:jurisdiction,
            region=:region,
            website=:website,
            industry=:industry,
            model=:model,
            product=:product,
            stage=:stage,
            pre_money=:pre_money,
            raising_target=:raising_target,
            zebra_stake=:zebra_stake,
            current_status=:current_status,
            note=:note,
            update_dt=current_timestamp,
            last_editor=:create_user,
            zebra_team=:zebra_team,
            source=:source,
            contact_note=:contact_note,
            file_name=:file_name,
            next_move=:next_move
            WHERE id=:id";
        $stmt = $this->dbh->prepare($sql);
        $res = $stmt->execute(
            array(
                "date_opened"=>$date_opened,
                "last_updated"=>$last_updated,
                "author"=>$author,
                "jurisdiction"=>$jurisdiction,
                "region"=>$region,
                "website"=>$website,
                "industry"=>$industry,
                "model"=>$model,
                "product"=>$product,
                "stage"=>$stage,
                "pre_money"=>$pre_money,
                "raising_target"=>$raising_target,
                "zebra_team"=>$zebra_team,
                "zebra_stake"=>$zebra_stake,
                "current_status"=>$current_status,
                "note"=>$note,
                "create_user"=>$create_user,
                "source"=>$source,
                "contact_note"=>$contact_note,
                "file_name"=>$file_name,
                "next_move"=>$next_move,
                "id"=>$id
            )
        ) or die ("文档修改失败, 请联系技术人员");

        return true;
    }

    function create(
        $file_name,
        $date_opened,
        $last_updated,
        $author,
        $jurisdiction,
        $region,
        $website,
        $industry,
        $model,
        $product,
        $stage,
        $pre_money,
        $raising_target,
        $zebra_stake,
        $current_status,
        $note,
        $zebra_team,
        $source,
        $contact_note,
        $create_user,
        $next_move
    ) {
        $sql = "insert into customer values(
            default,
            :date_opened,
            :last_updated,
            :author,
            :jurisdiction,
            :region,
            :website,
            :industry,
            :model,
            :product,
            :stage,
            :pre_money,
            :raising_target,
            :zebra_stake,
            :current_status,
            :note,
            current_timestamp,
            current_timestamp,
            :create_user,
            :last_editor,
            :zebra_team,
            :source,
            :contact_note,
            :file_name,
            true,
            :next_move
        )";
        $stmt = $this->dbh->prepare($sql);
        $res = $stmt->execute(
            array(
                "date_opened"=>$date_opened,
                "last_updated"=>$last_updated,
                "author"=>$author,
                "jurisdiction"=>$jurisdiction,
                "region"=>$region,
                "website"=>$website,
                "industry"=>$industry,
                "model"=>$model,
                "product"=>$product,
                "stage"=>$stage,
                "pre_money"=>$pre_money,
                "raising_target"=>$raising_target,
                "zebra_team"=>$zebra_team,
                "zebra_stake"=>$zebra_stake,
                "current_status"=>$current_status,
                "note"=>$note,
                "create_user"=>$create_user,
                "last_editor"=>$create_user,
                "source"=>$source,
                "contact_note"=>$contact_note,
                "file_name"=>$file_name,
                "next_move"=>$next_move
            )
        ) or die ("文档创建失败, 请联系技术人员");

        return $this->dbh->lastInsertId("customer_id_seq");
    }
    
    function get_list(){
        $sql = "SELECT * FROM customer WHERE status=true";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $rows = array();
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
            array_push($rows, array(
                $row["id"],
                "<a href='#/app/doc/show/".$row["id"]."'>".$row["file_name"]."</a>",
                $row["industry"],
                $row["region"],
                $row["product"],
                $row["pre_money"],
                $row["raising_target"],
                $row["current_status"],
                $row["next_move"],
                "<a class='fa-pencil fa' href='#/app/doc/edit/{$row["id"]}'></a>&nbsp;
                <a class='fa-trash-o fa' href='#/app/doc/delete/{$row["id"]}'></a>&nbsp;
                <a class='fa-send fa' href='#/app/doc/{$row["id"]}/mail'></a>"
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
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $customer) {
            $customer["contacts"] = $contact_obj->get_list($customer["id"]);
            $customer["logs"] = $log_obj->get_list($customer["id"]);
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

    function get_list($aid) {
        $sql = "SELECT * FROM activity_log_files WHERE a_id=:aid AND status=true";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute(array("aid"=>$aid));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function create($a_id, $file_name, $file_path) {
        $sql = "INSERT INTO activity_log_files VALUES(
            default, :a_id,:file_name,:file_path,true
        )";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute(array(
            "a_id"=>$a_id,
            "file_name"=>$file_name,
            "file_path"=>$file_path
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

    function update($id, $name, $title, $phone, $email, $create_user) {
        $sql = "UPDATE contact SET
            name=:name,
            title=:title, phone=:phone, email=:email,
            update_dt=current_timestamp, last_editor=:create_user WHERE id=:id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute(
            array("name"=>$name, "title"=>$title, "phone"=>$phone,
            "email"=>$email, "create_user"=>$create_user, "id"=>$id)
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
        $customer_id
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
            true
        )";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute(
            array("name"=>$name, "title"=>$title, "phone"=>$phone, "email"=>$email, "create_user"=>$create_user, "customer_id"=>$customer_id)
        ) or die("创建联系人失败, 请联系技术人员");
        return true;
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
            $log["files"] = $file_obj->get_list($log["id"]);
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
        $stmt->execute(
            array(
                "activity"=>$activity, "document"=>$document, "document_file"=>$document_file, "note"=>$note, "create_user"=>$create_user,
                "adate"=>$adate, "id"=>$id
            )
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
    function __construct(){
        $this->doc = new DocObj();
        $this->contact_obj = new CompanyContact();
        $this->log_obj = new ActivityLog();
        $this->file_obj = new LogFiles();
        $this->create_user = $_SESSION["userinfo"]->username;
    }
    
    function create_contact($params=array()) {
        extract($params);
        if (@$this->contact_obj->create($name,
            $title,
            $phone,
            $email,
            $this->create_user,
            $customer_id))
            return json_encode($this->contact_obj->get_list($customer_id));
    }
    function update_contact($params=array()){
        extract($params);
        if (@$this->contact_obj->update($id, $name, $title, $phone, $email, $this->create_user))
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
        return json_encode($this->doc->get_list()); 
    }

    function update_doc($params=array()){
        extract($params);
        $res = @$this->doc->update(
            $file_name,
            $date_opened,
            $last_updated,
            $author,
            $jurisdiction,
            $region,
            $website,
            $industry,
            $model,
            $product,
            $stage,
            $pre_money,
            $raising_target,
            $zebra_stake,
            $current_status,
            $note,
            $zebra_team,
            $source,
            $contact_note,
            $this->create_user,
            $next_move,
            $id
        );
        foreach($contacts as $contact){
            if (empty($contact["id"])){
                $contact_res = @$this->contact_obj->create(
                    $contact["name"],
                    $contact["title"],
                    $contact["phone"],
                    $contact["email"],
                    $this->create_user,
                    $id
                );
            } else {
                @$this->contact_obj->update(
                    $contact["id"],
                    $contact["name"],
                    $contact["title"],
                    $contact["phone"],
                    $contact["email"],
                    $this->create_user);
            }
        }
        foreach ($logs as $log){
            if (empty($log["id"])) {
                $log_id = $this->log_obj->create(
                    $log["activity"],
                    "",
                    "",
                    $log["note"],
                    $this->create_user,
                    $id,
                    $log["adate"]
                );
            } else {
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
                        $log_id, $file["file_name"], $file["file_path"]
                    );
                } else {
                    $this->file_obj->update(
                        $file["id"], $log_id, $file["file_name"], $file["file_path"]
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
        extract($params);
        //print_r($params);
        $res = @$this->doc->create(
            $file_name,
            $date_opened,
            $last_updated,
            $author,
            $jurisdiction,
            $region,
            $website,
            $industry,
            $model,
            $product,
            $stage,
            $pre_money,
            $raising_target,
            $zebra_stake,
            $current_status,
            $note,
            $zebra_team,
            $source,
            $contact_note,
            $this->create_user,
            $next_move,
            $contacts,
            $logs
        );
        if ($res) {
            foreach ($contacts as $contact){
                $contact_res = @$this->contact_obj->create(
                    $contact["name"],
                    $contact["title"],
                    $contact["phone"],
                    $contact["email"],
                    $this->create_user,
                    $res
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
            }
            $to = array("projects@zebraglobalcap.com");
            $subject = "文档[{$res}]{$file_name}创建成功";
            $message = "testtest";
            $email = new Email(true);
            $email->send($to, $subject, $message);
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
