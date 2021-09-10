<?php
   include "./configs/names.php";
   include "./configs/db_connections.php";
	 include "./smtp/mail.php";
   include "./configs/rest.php";
   include "./configs/auth.php";

   if(isset($_GET['pay'])){
	   $bill_id=$_POST['bill_id'];
     $sub_id=$_POST['sub_id'];
     $date=$_POST['date'];
     $cons=$_POST['cons'];
     $unit=$_POST['unit'];
     $cost=$_POST['cost'];
   }
   if(isset($_GET['payProceed'])){
     $bill_id=$_POST['bill_id'];
     $cost=$_POST['cost'];
     $sub_id=$_POST['sub_id'];
   }
   if(isset($_GET['paySucess'])) {
      $ref_no=$_POST['ref_no'];
      $date=date("Y-m-d");
      $id=$_POST['bill_id'];
      $sub_id=$_POST['sub_id'];
      $cost=$_POST['cost'];
      try{
        $jso=array('id'=>$id,'paid_date'=>$date,'paid_ref'=>$ref_no);
        if(!httpPutBill(json_encode($jso))){
          $js_mail=array('id'=>$id,'paid_date'=>$date,'paid_ref'=>$ref_no,'sub_id'=>$sub_id,'cost'=>$cost);
          mailTo(2,$_SESSION['usr'],json_encode($js_mail));
        }
        else{
          header('location:payment.php?payFail');
        }
      }catch(Exception $e){}
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

   </head>
   <body class="dup-body">
      <div class="dup-body-wrap">
         <section class="banner-area relative">

    			<div class="container">
    				<div class="row fullscreen align-items-center justify-content-between">
              <header class="default-header">
                 <div class="header-wrap">
                    <div class="header-top d-flex justify-content-between align-items-center">
                       <div class="logo">
                          <a href="#">
                             <h2 style="color:#000"><?php echo $title; ?></h2>
                          </a>
                       </div>
                       <div class="main-menubar white-menubar d-flex align-items-center">
                         <div class="menu-bar"><span class="lnr lnr-menu"></span></div>
                            <input type="button" style="width:150px;display:inline;margin-left:50px" class="genric-btn primary-border circle arrow" value="< BACK" onclick="window.location.href = 'index.php';">
                       </div>
                    </div>
                 </div>
              </header>
    					<div class="col-md-12" align=center>
    						<div class="content">
    							<h1>Payment</h1><hr>
                  <? if(isset($_GET['pay'])) { ?>
                  <h4 class="about-title" align=center><?php echo $sub_id; ?></h4>
                  <p class="about-title" align=center><em>ID: <?php echo " ".$bill_id ; ?></em></p>
                  <p class="about-title" align=center>Bill Date:<em><?php echo " ".$date ; ?></em></p>
                  <div class="row" align=center>
                     <div class="col-md-6">
                  <h1 class="about-title" align=center><?php echo $cons; ?><sub><?php echo " ".$unit ; ?></sub></h1>
                  <p class="about-title" align=center>Consuption</p>
                </div>
                  <div class="col-md-6">
                  <h1 class="about-title" align=center><?php echo $cost; ?><sup>INR</sup></h1>
                  <p class="about-title" align=center>Amount</p>
                </div>
                </div>
                <hr>
                <form action="./payment.php?payProceed" method="post">
                  <input type="submit" class="genric-btn primary-border circle arrow" value="PAY >">
                  <input type="hidden" name="bill_id" value=<?php echo $bill_id;?>>
                  <input type="hidden" name="cost" value=<?php echo $cost;?>>
                  <input type="hidden" name="sub_id" value=<?php echo $sub_id;?>>
                </form>
                <? } ?>
                <? if(isset($_GET['payProceed'])) { ?>
                <form action="./payment.php?paySucess" method="post">
                  <div class="mt-10">
                    <input type="hidden" name="bill_id" value=<?php echo $bill_id;?>>
                    <input type="hidden" name="cost" value=<?php echo $cost;?>>
                    <input type="hidden" name="sub_id" value=<?php echo $sub_id;?>>
                     <input type="text" name="ref_no" placeholder="ref_no" style="width:530px" style="display:inline;" class="single-input" required>
                     <input type="submit" class="genric-btn primary-border circle arrow" style="display:inline;" value="SUCCESS >">
                  </div>
                </form>
                <form action="./payment.php?payFail" method="post">
                  <input type="submit" class="genric-btn primary-border circle arrow" value="< DECLINED">
                </form>
              <? } ?>
              <? if(isset($_GET['payFail'])) { ?>
                    <h2>Payment Failed Please Try again later</h2>
              <? } ?>
              <? if(isset($_GET['paySucess'])) { ?>
                    <h4>Payment was scuessful thanks for using our services</h4>
                    <div>
                      <?
                        echo "Bill ID : <b>".$id."</b><br>";
                        echo "Subscription ID: <b>".$sub_id."</b><br>";
                        echo "Refrence Number : <b>".$ref_no."</b><br>";
                        echo "Paid Date : <b>".$date."</b><br>";
                        echo "Amount paid : <b>".$cost."</b>";
                      ?>
                    </div>
                    <hr>
                    <p>All the details have been sent to your email.</p>
              <? } ?>
    						</div>
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
