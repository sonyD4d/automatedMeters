<?php
    include "./PHPMailer/src/PHPMailer.php";
    include "./PHPMailer/src/SMTP.php";
    include "./configs/db_connections.php";
    function mailTo($action,$email,$data){
      $mail = new PHPMailer\PHPMailer\PHPMailer();
      $mail->IsSMTP(); // enable SMTP
      $mail->SMTPAuth = true; // authentication enabled
      $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
      $mail->Host = "smtp.gmail.com";
      $mail->Port = 465; // or 587
      $mail->IsHTML(true);
      $mail->Username = "unipay52@gmail.com";
      $mail->Password = "Cit@1234";
      $mail->SetFrom("unipay52@gmail.com");
      if($action==1){
        $mail->Subject = "Registration Sucessful ";
        $mail->Body = nl2br("Hello, $data \n You have been sucessfully registered :)");
        $mail->AddAddress($email);
         if(!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
         }
      }
      if($action==2){
        $mail->Subject = "Payment Info";
        $jso=json_decode($data,true);
        $m=nl2br("Hello,\n\t Your bill has been sucessfully paid hear are it's info: \n\tBill ID:".$jso['id']."\n\tSubscription ID : ".$jso['sub_id']."\n\tRefrence no :".$jso['paid_ref']."\n\tCost:".$jso['cost']."\n\tPaid on:".$jso['paid_date']);
        $mail->Body = $m;
        $mail->AddAddress($email);
         if(!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
         }
   }
 }
?>
