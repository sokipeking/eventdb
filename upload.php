<?php
session_start();
define("UPLOAD_DIR", "uploads/");
$allow_exts = array("DOC", "DOCX", "PPT", "PPTX", "XLS", "XLSX", "TXT", "JPG", "PDF", "ZIP", "RAR");
if (!isset($_SESSION["userinfo"])){
    header('HTTP/1.0 403 Forbidden');
    exit(1);
} else if($_SERVER['REQUEST_METHOD'] == 'GET') {
?>

<form method="POST" id="upload_form" enctype="multipart/form-data">
    <input type="file" name="upload"/>
</form>
支持上传的文件格式：<?php echo join(",", $allow_exts)?>
<?php } else {
if (isset($_FILES["upload"])) {
    if ($_FILES["upload"]["error"] > 0) {
        $error_msg = "Upload Error ". $_FILES["upload"]["error"];
    } else{ 
        $file_name = $_FILES["upload"]["name"];
        if (!stristr($file_name, ".")) {
            $error_msg = "文件格式不正确";
        } else {
            $tmp = explode(".", $file_name);
            $ext = strtoupper($tmp[count($tmp) - 1]);
            if (!in_array($ext, $allow_exts)) {
                $error_msg = "文件格式不正确";
            } else {
                $save_file_name  = UPLOAD_DIR . md5(time()) . ".". $ext;
                $file_name = $_FILES["upload"]["name"];
                move_uploaded_file($_FILES["upload"]["tmp_name"],
                          $save_file_name);
                $error_msg = "上传成功";
            }
        }
    }
}

if ($error_msg) {
    ?>
<script>
<?php if ($error_msg == "上传成功") { echo "parent.document.getElementById('upload_file_path').value='".$file_name."==".$save_file_name."';";}?>
    alert("<?php echo $error_msg?>");
    location.href="upload.php";    
</script>
<?php
}

}?>
