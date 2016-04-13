<?php
include_once("common.php");
class DocObj{
    private $dbh;
    function __construct(){
        global $dbh;
        $this->dbh = $dbh;
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
                $row["file_name"],
                $row["industry"],
                $row["region"],
                $row["product"],
                $row["pre_money"],
                $row["raising_target"],
                $row["current_status"],
                $row["next_move"],
                "<a href='#/app/doc/edit/{$row["id"]}'>编辑</a>"
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
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            :document,
            :note,
            current_timestamp,
            current_timestamp,
            :create_user,
            :create_user,
            :customer_id,
            :document_file,
            true,
            :adate
        )"; 
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute(
            array(
                "activity"=>$activity, "document"=>$document, "document_file"=>$document_file, "note"=>$note, "create_user"=>$create_user, "customer_id"=>$customer_id,
                "adate"=>$adate
            )
        ) or die ("创建日志失败,请联系技术人员");
        return true;
    }
} 

class DocController {
    private $doc;
    private $contact_obj;
    private $log_obj;
    private $create_user;
    function __construct(){
        $this->doc = new DocObj();
        $this->contact_obj = new CompanyContact();
        $this->log_obj = new ActivityLog();
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
        $res = $this->doc->update(
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
        return true;
    }

    function create_doc($params = array()) {
        extract($params);
        //print_r($params);
        $res = $this->doc->create(
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
            $next_move
        );
        if ($res) {
            foreach ($contacts as $contact){
                $contact_res = $this->contact_obj->create(
                    $contact["name"],
                    $contact["title"],
                    $contact["phone"],
                    $contact["email"],
                    $this->create_user,
                    $res
                );
            }
            foreach ($logs as $log){
                $log_res = $this->log_obj->create(
                    $log["activity"],
                    $log["document"],
                    $log["document_file"],
                    $log["note"],
                    $this->create_user,
                    $res,
                    $log["adate"]
                );            
            }
            return $res; 
        }
        return false;
    }
}
$controller = new DocController();
?>
