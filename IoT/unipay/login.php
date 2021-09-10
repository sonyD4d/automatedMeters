<?php
   include "./configs/names.php";
   include "./configs/db_connections.php";
	 include "./smtp/mail.php";
   $loginpg=true;
   $loginErr=false;
   if(isset($_POST['login'])){
	   $usr_email=$_POST['usr_email'];
	   $usr_pass=md5($_POST['usr_pass']);
	   $query="SELECT * FROM user_auth WHERE email='$usr_email' AND password='$usr_pass'";
	   if($res=mysqli_query($conn,$query)){
	   	if(mysqli_num_rows($res)==1){
	   		session_start();
	   		$_SESSION['auth']='true';
	   		$_SESSION['usr']=$usr_email;
	   		header('location:index.php');
	   	}
	   	else{
	   		$loginErr=true;
	   	}
	   }
   }
	 if(isset($_POST['signup'])){
		 $usr_email=$_POST['usr_email'];
	   $usr_pass=md5($_POST['usr_pass']);
		 $usr_name=$_POST['usr_name'];
		 $usr_id=$_POST['usr_id'];
		 $usr_add=$_POST['usr_add'];
		 $usr_phno=$_POST['usr_phno'];
		 $query="INSERT INTO user_details VALUES ('$usr_id','$usr_name','$usr_phno','$usr_email','$usr_add')";
		 if($res=mysqli_query($conn,$query)){
			  $q="INSERT INTO user_auth VALUES ('$usr_email','$usr_pass')";
					if($r=mysqli_query($conn,$q)){
					     mailTo(1,$usr_email,$usr_name);
							 header('location:login.php?sucessReg&fgt');
					}
		 }
	 }
   if(isset($_GET['signupd'])){
   		$loginpg=false;
   }
	 if(isset($_GET['destroy'])){
		 session_start();
		 session_destroy();
		 session_unset();
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
      <title><?php echo $title; ?></title>
      <script src="//code.jquery.com/jquery-1.10.2.js"></script>
      <script src="js/form.js"></script>
      <link href="https://fonts.googleapis.com/css?family=Poppins:100,300,500" rel="stylesheet">
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
         border-left: 6px solid #e0003b;
         height:450px;
         margin-left: 40px;
         margin-right: 40px;
         }
         .v2 {
         border-left: 2px solid #e0003b;
         height:50px;
         margin-left: 10px;
         margin-right: 25px;
         }
      </style>
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
   </head>
   <body class="dup-body">
      <div class="dup-body-wrap">
         <section class="generic-banner element-banner relative" style="height:100px">
            <div class="overlay overlay-bg"></div>
            <div class="container">
               <div class="row fullscreen align-items-center justify-content-between">
                  <header class="default-header">
                     <div class="header-wrap">
                        <div class="header-top d-flex justify-content-between align-items-center">
                           <div class="logo">
                              <a href="./index.php">
                                 <h2 style="color:#FFF"><?php echo $title; ?></h2>
                              </a>
                           </div>
                        </div>
                     </div>
                  </header>
               </div>
            </div>
         </section>
         <section class="sample-text-area">
            <div class="container">
               <div class="row">
                  <div class="col-md-5">
                     <br><br><br>
                     <h3 class="text-heading" align=left >One Service to all your</h3>
                     <h1 class="text-heading" align=right style="color:#e0003b;font-size:60px;font-align:right">Metered<br>Connections</h1>
                  </div>
                  <div class="vl" style="display:inline"></div>
									<div class="col-md-5" id="block">
								<?php if($loginpg){ ?>
                     <br><br><br>
                     <h3 class="text-heading" align=left >Login:</h3>
										 <?php
										 			if(isset($_GET['sucessReg'])){ echo "<p color:red >Registration sucessful you can login";}
													if(isset($_GET['sucessPass'])){ echo "<p color:red >Password reset sucessful you can login";}
													?>
                     <form action="./login.php" method="post">
                        <div class="mt-10">
                           <input type="email" name="usr_email" placeholder="E-Mail" onfocus="this.placeholder = ''" onblur="this.placeholder = 'E-Mail'" required class="single-input">
                        </div>
                        <div class="mt-10">
                           <input type="password" name="usr_pass" placeholder="Password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Password'" required class="single-input">
                        </div>
                        <?php if($loginErr) {echo "<p style=\"color:red\">Wrong email or password</p>";} ?>
                        <div class="button-group-area mt-40" align=center>
                           <input type="button" style="width:160px;display:inline;" class="genric-btn primary-border circle arrow" value="Sign Up" name="signupd" onclick="window.location.href = 'login.php?signupd';">
                           <div class="v2" style="display:inline"></div>
                           <input type="submit" style="width:160px;display:inline" class="genric-btn primary circle arrow" value="Login" name="login">
                        </div>
                     </form>
                  <?php }
                     else {
                     ?>
                     <h3 class="text-heading" align=left >Sign Up:</h3>
                     <form action="./login.php" method="post">
                        <div class="mt-10">
                           <input type="text" name="usr_name" placeholder="Name" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Name'" required class="single-input">
                        </div>
                        <div class="mt-10">
                           <input type="email" name="usr_email" placeholder="E-Mail" onfocus="this.placeholder = ''" onblur="this.placeholder = 'E-Mail'" required class="single-input">
                        </div>
                        <div class="mt-10">
                           <input type="password" id="p1" name="usr_pass" placeholder="Password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Password'" required class="single-input">
                        </div>
                        <div class="mt-10">
                           <input type="password" id="p2" placeholder="Confirm Password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Confirm Password'" required class="single-input" onfocusout="pass_validation()">
                        </div>
                        <div class="mt-10">
                           <input type="text" name="usr_id" placeholder="Adahar No" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Adahar No'" required class="single-input">
                        </div>
                        <div class="mt-10">
                           <input type="text" name="usr_add" placeholder="Address" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Address'" required class="single-input">
                        </div>
                        <div class="mt-10">
                           <input type="text" name="usr_phno" placeholder="Phone Number" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Phone Number'" required class="single-input">
                        </div>
                        <div class="button-group-area mt-40" align=center>
                           <input type="button" style="width:160px;display:inline;" class="genric-btn primary-border circle arrow" value="< Back" onclick="window.location.href = 'login.php';">
                           <div class="v2" style="display:inline"></div>
                           <input type="submit" id="sub" style="width:160px;display:inline" class="genric-btn primary circle arrow" value="Submit" name="signup">
                        </div>
                     </form>
                  <?php
								}
                     	?>
										</div>
               </div>
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
