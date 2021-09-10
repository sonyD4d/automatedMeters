<?php
   include "./configs/names.php";
   include "./configs/db_connections.php";
   include "./configs/auth.php";
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
      <title>Home</title>
      <script src="//code.jquery.com/jquery-1.10.2.js"></script>
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
         border-left: 2px solid white;
         height:190px;
         margin-left: 40px;
         margin-right: 40px;
         }
         .v2 {
         border-left: 2px solid #e0003b;
         height:560px;
         margin-left: 40px;
         margin-right: 40px;
         }
      </style>
   </head>
   <body class="dup-body">
      <div class="dup-body-wrap">
         <?php include 'pg_h_f/header.php' ?>
         <section class="about-generic-area">
            <div class="overlay overlay-bg"></div>
            <div class="container border-top-generic">
               <div class="row">
                  <div class="col-md-2">
                     <br>
                     <h3 class="about-title mb-70 text-white" align=right>User Informaition</h3>
                  </div>
                  <div class="col-md-1">
                     <div class="vl" ></div>
                  </div>
                  <div class="col-md-3" align=left>
                     <?php
                        echo "<h1 class=\"about-title  text-white\" align=left>$usr_name</h1>";
                        echo "<h4 class=\"about-title  text-white\" align=left>$usr_email</h4>";
                        echo "<p class=\"about-title  text-white\" align=left>$usr_add</p>";
                        ?>
                  </div>
                  <div class="col-md-1">
                     <div class="vl" ></div>
                  </div>
                  <div class="col-md-5" align=left>
                     <h1 class="about-title  text-white" align=left>Active Connections</h1>
                     <?php
                        $query="SELECT sub_id FROM user_subscription WHERE user_id='$usr_id'";
                        if($res=mysqli_query($conn,$query)){
                            if(mysqli_num_rows($res)>=1){
                        				 while($r=mysqli_fetch_object($res)){
                        				 ?>
                     <p style="color:white;margin:5px;" align=left><?php echo " ".$r->sub_id ; ?></p>
                     <?php
                        }
                        }
                        else {
                          echo "<p style=\"color:white;margin:5px;\" align=left>No Active Connections,Please contact your provider</p>";
                        }
                        }
                        ?>
                  </div>
               </div>
            </div>
         </section>
         <section class="sample-text-area">
            <div class="container">
               <h3 class="text-heading">Current Bills</h3>
               <div class="container">
                  <div class="row" align=center>
                     <?php
                        $today = date("Y-m-01");
                        $query="SELECT `bill_id`,`cons`,`cost`,`date`,`paid_status`,`pay_date`,`ref_no`,`meter_billing`.`sub_id` , `provider_type`.`t_unit`,`provider_details`.`type` FROM `meter_billing` JOIN `user_subscription` ON `meter_billing`.`sub_id`=`user_subscription`.`sub_id` JOIN `provider_details` ON `user_subscription`.`provider_id`=`provider_details`.`provider_id` JOIN `provider_type` ON `provider_type`.`t_name`=`provider_details`.`type` WHERE `user_subscription`.`user_id`='$usr_id' AND `meter_billing`.`date`>'$today'";
                        if($res=mysqli_query($conn,$query)){
                                 if(mysqli_num_rows($res)>=1){?>
                     <div class="col-md-1">
                        <div class="v2" ></div>
                     </div>
                     <?
                        while($r=mysqli_fetch_object($res)){
                        ?>
                     <div class="col-md-2">
                        <br>
                        <h4 class="about-title" align=center><?php echo $r->sub_id; ?></h4>
                        <p class="about-title" align=center><em>ID: <?php echo " ".$r->bill_id ; ?></em></p>
                        <p class="about-title" align=center>Bill Date:<em><?php echo " ".$r->date ; ?></em></p>
                        <img src="img/<?php echo $r->type; ?>logo.png" height="40px" width="40px"></img>
                        <hr>
                        <h1 class="about-title" align=center><?php echo $r->cons; ?><sub><?php echo " ".$r->t_unit ; ?></sub></h1>
                        <p class="about-title" align=center>Consuption</p>
                        <hr>
                        <h1 class="about-title" align=center><?php echo $r->cost; ?><sup>INR</sup></h1>
                        <p class="about-title" align=center>Amount</p>
                        <hr>
                        <p class="about-title" align=center>Bill Status</p>
                        <?php
                           if($r->paid_status==0){
                           ?>
                        <h3 class="about-title" align=center>Not Paid</h3>
                        <hr>
                        <form action="./payment.php?pay" method="post">
                           <input type="submit" class="genric-btn success medium" value="Pay Now">
                           <input type="hidden" name="bill_id" value=<?php echo $r->bill_id;?>>
                           <input type="hidden" name="sub_id" value=<?php echo $r->sub_id;?>>
                           <input type="hidden" name="date" value=<?php echo $r->date;?>>
                           <input type="hidden" name="cons" value=<?php echo $r->cons;?>>
                           <input type="hidden" name="unit" value=<?php echo $r->t_unit;?>>
                           <input type="hidden" name="cost" value=<?php echo $r->cost;?>>
                        </form>
                        <?php }
                           else{
                           ?>
                        <h3 class="about-title" align=center>Paid :)</h3>
                        <p class="about-title" align=center>Date:<em><?php echo " ".$r->pay_date ; ?></em></p>
                        <p class="about-title" align=center>Ref No:<em><?php echo " ".$r->ref_no ; ?></em></p>
                        <?php }?>
                        <hr>
                     </div>
                     <div class="col-md-1">
                        <div class="v2" ></div>
                     </div>
                     <?php
                        }} else{ echo "No bills generated for this month,We are also wating for it ;)";}
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
