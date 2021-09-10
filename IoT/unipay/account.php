<?php
   include "./configs/names.php";
   include "./configs/db_connections.php";
   include "./configs/auth.php";
   //code to update user details
   if(isset($_POST['update'])){
     $usr_name_u=$_POST['usr_name'];
     $usr_add_u=$_POST['usr_add'];
     $usr_phno_u=$_POST['usr_phno'];
     $query="UPDATE user_details SET name='$usr_name_u',address='$usr_add_u',phno='$usr_phno_u' WHERE e_mail='$usr_email'";
     if($res=mysqli_query($conn,$query)){
      echo "<script>alert(\"Sucessfully Updated\");</script>";
     }
     header('location:account.php');
   }
   //code to change password
   if(isset($_POST['chgPassU'])){
     $pass_old=md5($_POST['old_pass']);
     $pass_new=md5($_POST['new_pass']);
     $query="SELECT * FROM user_auth WHERE email='$usr_email'";
     if($res=mysqli_query($conn,$query)){
       $r=mysqli_fetch_object($res);
       if($r->password==$pass_old){
         $q="UPDATE user_auth SET password='$pass_new' WHERE email='$usr_email'";
         if($s=mysqli_query($conn,$q)){
           echo "<script> alert(\"Sucessfully changed password\"); </script>";
         }
         else {
           echo "<script> alert(\"Try again later\"); </script>";
         }
       }
       else{
         echo "<script> alert(\"Password Incorrect\"); </script>";
       }
     }
   }
   ?>
<html lang="zxx" class="no-js">
   <head>
      <!-- Mobile Specific Meta -->
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <!-- Favicon-->
      <link rel="shortcut icon" href="img/fav.png">
      <!-- Author Meta -->
      <meta name="author" content="CodePixar">
      <!-- Meta Description -->
      <meta name="description" content="">
      <!-- Meta Keyword -->
      <meta name="keywords" content="">
      <!-- meta character set -->
      <meta charset="UTF-8">
      <!-- Site Title -->
      <title>Account</title>
      <script src="//code.jquery.com/jquery-1.10.2.js"></script>
      <link href="https://fonts.googleapis.com/css?family=Poppins:100,300,500" rel="stylesheet">
      <!-- script to match password -->
      <script>
         function pass_validation()
         {
         		var firstpassword=document.getElementById("p1").value;
         		var secondpassword=document.getElementById("p2").value;

         		if(firstpassword==secondpassword){
         			document.getElementById("sub").disabled = false;
         			document.getElementById("sub").value = "Submit";
         			return true;
         		}
         		else{
         			document.getElementById("sub").disabled = true;
         			document.getElementById("sub").value = "Value Missmatch";
         			alert("Passwords must be same!");
         			return false;
         		}
         }

      </script>
      <!--
         CSS
         ============================================= -->
      <link rel="stylesheet" href="css/linearicons.css">
      <link rel="stylesheet" href="css/owl.carousel.css">
      <link rel="stylesheet" href="css/font-awesome.min.css">
      <link rel="stylesheet" href="css/nice-select.css">
      <link rel="stylesheet" href="css/magnific-popup.css">
      <link rel="stylesheet" href="css/bootstrap.css">
      <link rel="stylesheet" href="css/main.css">
      <style>
         .vl {
         border-left: 2px solid white;
         height:150px;
         margin-left: 40px;
         margin-right: 40px;
         }
         .v2 {
         border-left: 2px solid #e0003b;
         height:500px;
         margin-left: 40px;
         margin-right: 40px;
         }
      </style>
   </head>
   <body class="dup-body">
      <div class="dup-body-wrap">
         <?php include 'pg_h_f/header.php' ?>
         <section class="sample-text-area">
            <div class="container">
               <?php if(!isset($_GET['chgPass'])){ ?>
               <h3 class="text-heading">Account Settings</h3>
               <form action="./account.php" method="post">
                  <div class="mt-10">
                     <h6>Name:</h6>
                     <input type="text" name="usr_name" value="<? echo $usr_name;?>" class="single-input">
                  </div>
                  <div class="mt-10">
                     <h6>Email:</h6>
                     <input type="email" name="usr_email" placeholder="<? echo $usr_email;?>" disabled  class="single-input">
                  </div>
                  <div class="mt-10">
                     <h6>Adahar Number:</h6>
                     <input type="text" name="usr_id" placeholder="<? echo $usr_id;?>" disabled  class="single-input">
                  </div>
                  <div class="mt-10">
                     <h6>Address:</h6>
                     <input type="text" name="usr_add" value="<? echo $usr_add;?>" required class="single-input">
                  </div>
                  <div class="mt-10">
                     <h6>Phone Number:</h6>
                     <input type="text" name="usr_phno" value="<? echo $usr_phno;?>" required class="single-input">
                  </div>
                  <div class="button-group-area mt-40" align=center>
                     <input type="button" style="width:180px;display:inline;" class="genric-btn primary-border circle arrow" value="Change Password" onclick="window.location.href = 'account.php?chgPass';">
                     <div class="v2" style="display:inline"></div>
                     <input type="submit" style="width:180px;display:inline" class="genric-btn primary circle arrow" value="Update" name="update">
                  </div>
               </form>
               <?php } else{?>
               <h3 class="text-heading">Change Password</h3>
               <form action="./account.php" method="post">
                  <div class="mt-10">
                     <input type="password" placeholder="Enter Old Password" name="old_pass" required class="single-input">
                  </div>
                  <div class="mt-10">
                     <input type="password" id="p1" name="new_pass" placeholder="New Password" required class="single-input">
                  </div>
                  <div class="mt-10">
                     <input type="password" id="p2" placeholder="Confirm Password" required class="single-input" onfocusout="pass_validation()">
                  </div>
                  <div class="button-group-area mt-40" align=center>
                     <input type="button" style="width:180px;display:inline;" class="genric-btn primary-border circle arrow" value="< BACK" onclick="window.location.href = 'account.php';">
                     <div class="v2" style="display:inline"></div>
                     <input type="submit" id="sub" style="width:160px;display:inline" class="genric-btn primary circle arrow" value="Update" name="chgPassU">
                  </div>
               </form>
               <?php } ?>
            </div>
         </section>
      </div>
      <script src="js/vendor/jquery-2.2.4.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
      <script src="js/vendor/bootstrap.min.js"></script>
      <script src="js/jquery.ajaxchimp.min.js"></script>
      <script src="js/owl.carousel.min.js"></script>
      <script src="js/jquery.nice-select.min.js"></script>
      <script src="js/jquery.magnific-popup.min.js"></script>
      <script src="js/main.js"></script>
   </body>
</html>
