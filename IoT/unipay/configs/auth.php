<?php
  session_start();
  //check if auth>ed by auth session variable
  if(!$_SESSION['auth']){
    //if not then redirect to login page
   header('location:login.php');
  }
  else{
    //store the related information of user in a variable
   $usr_email=$_SESSION['usr'];
   $query="SELECT * FROM user_details WHERE e_mail='$usr_email'";
   if($res=mysqli_query($conn,$query)){
    $r=mysqli_fetch_object($res);
    $usr_id = $r->user_id;
    $usr_name = $r->name;
    $usr_email = $r->e_mail;
    $usr_add = $r->address;
    $usr_phno = $r->phno;
   }
  }
?>
