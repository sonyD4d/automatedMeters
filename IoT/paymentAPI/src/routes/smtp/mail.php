<?php
    include "PHPMailer/src/PHPMailer.php";
    include "PHPMailer/src/SMTP.php";
    function mailTo($email,$data){
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
      $mail->Subject = "Bill Info";
      $jso=json_decode($data,true);
      $m=nl2br("Hello,\n\t Your bill has been generated for this month hear are it's info: \n\tBill ID:".$jso['bill_id']."\n\tSubscription ID : ".$jso['sub_id']."\n\tProvider :".$jso['provider']."\n\tDate :".$jso['date']."\n\tCost:".$jso['cost']."\n\tTo pay visit our site");
      $mail->Body = $m;
      $mail->AddAddress($email);
       if(!$mail->Send()) {
          echo "Mailer Error: " . $mail->ErrorInfo;
       }

   }

?>
