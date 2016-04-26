<?php
class Email{
    private $smtp_mail;
    private $imap_mail;

    function __construct($is_smtp){
        $this->smtp_mail = new PHPMailer();
        if (!$is_smtp) {
            $imap_link = "{".IMAP_SERVER.":".IMAP_PORT."/imap/ssl}INBOX";
            #print $imap_link;
            $this->imap_mail = imap_open($imap_link, MAIL_USER, MAIL_PWD, OP_READONLY);
        } else {
            $this->smtp_mail->isSMTP();
            $this->smtp_mail->Host = SMTP_SERVER;
            $this->smtp_mail->Port = SMTP_PORT;
            $this->smtp_mail->Username = MAIL_USER;
            $this->smtp_mail->Password = MAIL_PWD;
            $this->smtp_mail->SMTPAuth = true;
            $this->smtp_mail->CharSet = "UTF-8";
            //$this->smtp_mail->SMTPDebug = 2;
            $this->smtp_mail->Debugoutput = 'html';
            $this->smtp_mail->SMTPSecure = "tls";
        }
    }

    function send($to_arr, $subject, $body) {
        foreach ($to_arr as $to) {
            $this->smtp_mail->addAddress($to);
        }
        $this->smtp_mail->setFrom(MAIL_USER);
        $this->smtp_mail->Subject = $subject;
        $this->smtp_mail->msgHTML($body);
        $this->smtp_mail->send();
    }

    function search($id){
        $res = array();
        $search_rows = imap_search($this->imap_mail, 'SUBJECT "['.$id.']"');
        if (!$search_rows)
            return $res;
        foreach($search_rows as $imapid){ 
            $ar = array();
            $ar["headerinfo"] = imap_headerinfo($this->imap_mail, $imapid);
            $ar["headerinfo"]->Subject = imap_utf8($ar["headerinfo"]->Subject);
            #print imap_utf8($info->Subject);
            $mail_body = imap_fetchbody($this->imap_mail, $imapid, 1);
            $ar["body"] = $this->process_msg($mail_body);
            #print $mail_body;
            #print $ar["body"];
            array_push($res, $ar);
        }
        $res = array_reverse($res);
        return $res;
    }

    function process_msg($var){
        /*$var = strip_tags($var);
        $split_var = '<meta http-equiv="Content-Type" content="text/html; charset=us-ascii">';
        $tmp = @split($split_var, $var)[1];
        $tmp = str_replace(array("\n","\r"),"", $tmp);
        $pattern = '/--(\w*)--/';
        $replacement = "";
        return preg_replace($pattern, $replacement, $tmp);
         */
        return $var;
    }

    function close_imap() {
        imap_close($this->imap_mail);
    }
}
?>
