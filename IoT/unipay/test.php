<?php
   include "./configs/names.php";
   include "./configs/db_connections.php";
   include "./configs/auth.php";
   $year = date('Y');
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
      <title>Analysis</title>
      <script src="//code.jquery.com/jquery-1.10.2.js"></script>
      <link href="https://fonts.googleapis.com/css?family=Poppins:100,300,500" rel="stylesheet">
      <!--
         CSS
         ============================================= -->
         <style>
            .v2 {
            border-left: 1px solid #e0003b;
            height:700px;
            margin-left: 40px;
            margin-right: 40px;
            }
            .v1 {
            border-left: 1px solid ;
            height:170px;
            margin-top: 50px;
            margin-left: 20px;
            margin-right: 40px;
            }
            #tbl {
                font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
                border-collapse: collapse;
                width: 100%;
              }

             #tbl th {
                border: 1px solid #ddd;
                padding: 8px;

              }
              #tbl td {
                 padding: 8px;
                 height: 70px;
               }
              #tbl tr:nth-child(even){background-color: #f2f2f2;}

              #tbl tr:hover {background-color: #ddd;}

              #tbl th {
                padding-top: 12px;
                padding-bottom: 12px;
                text-align: left;
                color: white;
              }
         </style>
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
         <?php include 'pg_h_f/header.php' ?>
         <section class="sample-text-area">
            <div class="container">
               <h2 align=left class="text-heading" style="display:inline;">Yearly Analysis</h2>
               <div align=right>
                 <? if(isset($_GET['show'])){ ?>
               <form method="get" class="subscription relative" style="width:250px;" align=right>
                 <input type="text" name="year" placeholder="<? echo $year; ?>" required>
                 <button type="submit" class="primary-btn" >Year<span class="lnr lnr-arrow-right"></span></button>
               </form>
             <? } ?>
             </div>
             <br>
             <div class="container" align=center>
               <div class="row" align=center>
                  <?
                    if(!isset($_GET['show'])){
                    echo "<div class=\"col-md-10\" align=left> <h5>Please select your connection:</h5><br><br></div>";
                    $query="SELECT `sub_id`, `provider_details`.`type`, `provider_details`.`p_name`, `provider_type`.`t_unit` FROM `user_subscription` JOIN `provider_details` ON `user_subscription`.`provider_id` = `provider_details`.`provider_id` JOIN `provider_type` ON `provider_details`.`type` = `provider_type`.`t_name` WHERE `user_id` = '$usr_id'";
                    if($res=mysqli_query($conn,$query)){
                             while($r=mysqli_fetch_object($res)){
                             ?>
                                    <div class="col-md-4">
                                        <img src="img/<?php echo $r->type; ?>logo.png" height="100px" width="100px"></img>
                                        <p style="margin:10px"><b><?echo $r->p_name;?></b></p>
                                        <form action="./test.php?show" method="post">
                                          <input type="hidden" value="<?php echo $r->type; ?>" name="p_type">
                                          <input type="hidden" value="<?php echo $r->t_unit; ?>" name="p_unit">
                                          <input type="hidden" value="<?php echo $r->p_name ; ?>" name="p_name">
                                          <input type="submit" class="genric-btn primary-border" name ="sub_id" value="<? echo $r->sub_id; ?>">
                                      </form>
                                    </div>
                  <?  }
                    }
                  }
                  else{ ?>
                    <div class="col-md-2">
                        <h4 style="margin:40px"><? echo $_POST['sub_id']; ?></h4>
                        <img src="img/<?php echo $_POST['p_type']; ?>logo.png" height="100px" width="100px"></img>
                        <p style="margin:10px"><b><?echo $_POST['p_name'];?></b></p>
                        <a href="./test.php" class="genric-btn primary-border"> < BACK</a>
                    </div>
                    <div class="col-md-1">
                        <div class="v2" ></div>
                    </div>
                    <div class="col-md-8">
                        <p align=left ><b>Usage Stats*</b></p>
                        <div class="row" align=center>
                          <?
                              $sub_id=$_POST['sub_id'];
                              $q="SELECT avg(`cost`) as \"avg_cost_sub\",avg(`cons`) as \"avg_cons_sub\",max(`cost`) as \"max_cost_sub\",min(`cost`) as \"min_cost_sub\",max(`cons`) as \"max_cons_sub\",min(`cons`) as \"min_cons_sub\" FROM `meter_billing` WHERE `sub_id`='ELE001'";
                              if($innerRes=mysqli_query($conn,$q)){
                                       while($innerR=mysqli_fetch_object($innerRes)){
                                         ?>
                                         <div class="col-md-3" style="margin-top:52px">
                                           <p>Average<br></p><hr>
                                           <h1 class="about-title" align=center><b>UNIT</b></h1>
                                           <p>(factor)</p><hr>
                                           <p>Minimum/Maximum<br>Range</p>
                                         </div>
                                         <div class="col-md-1">
                                             <div class="v1" ></div>
                                         </div>
                                         <div class="col-md-4">
                                           <p>Average</p>
                                           <h3><?echo round($innerR->avg_cost_sub,2);?></h3><hr>
                                           <h1 class="about-title" align=center><b>INR</b></h1>
                                           <p>COST</p><hr>
                                           <h3><p style="color:green;display:inline;"><? echo round($innerR->min_cost_sub,2);?></p>-<p style="color:red;display:inline;"><? echo round($innerR->max_cost_sub,2);?></p></h3>
                                           <p>Range</p>
                                         </div>
                                         <div class="col-md-4">
                                           <p>Average</p>
                                           <h3><?echo round($innerR->avg_cons_sub,2);?></h3><hr>
                                           <h1 class="about-title" align=center><b><? echo $_POST['p_unit'];?></b></h1>
                                           <p>CONSUPTION</p><hr>
                                           <h3><p style="color:green;display:inline;"><? echo round($innerR->min_cons_sub,2);?></p>-<p style="color:red;display:inline;"><? echo round($innerR->max_cons_sub,2);?></p></h3>
                                           <p>Range</p>
                                         </div>
                                  <?
                                       }
                              }
                          ?>
                        </div>
                        <hr>
                        <?
                          $q="SELECT `bill_id`,`cost`, `cons`, `date`, `paid_status`, `pay_date`, `ref_no` FROM `meter_billing` WHERE `sub_id`='$sub_id' AND `date`>'$year-01-01' AND `date`<'$year-12-31'";
                          if($innerRes=mysqli_query($conn,$q)){ ?>
                            <table id="tbl">
                              <tr>
                                <td>Bill Id</td>
                                <td>Bill Month</td>
                                <td>Cost</td>
                                <td>Consumption</td>
                                <td>Payment</td>
                              </tr>
                            <?
                                  $St=array();
                                   while($innerR=mysqli_fetch_object($innerRes)){
                                   ?>
                                    <tr>
                                        <td><?php echo $innerR->bill_id; ?></td>
                                        <td><?php echo $innerR->date; ?></td>
                                        <? $monthDate = date("M", strtotime($innerR->date));?>
                                        <? $St[]=array($monthDate,floatval($innerR->cost),(float)$innerR->cons); ?>
                                        <td><?php echo $innerR->cost; ?></td>
                                        <td><?php echo $innerR->cons; ?></td>
                                        <td align=center><?
                                            if($innerR->paid_status==0){ ?>
                                              <form action="./payment.php?pay" method="post">
                                                <input type="submit" class="genric-btn primary-border small" value="Pay Now">
                                                <input type="hidden" name="bill_id" value=<?php echo $innerR->bill_id;?>>
                                                <input type="hidden" name="sub_id" value=<?php echo $sub_id;?>>
                                                <input type="hidden" name="date" value=<?php echo $innerR->date;?>>
                                                <input type="hidden" name="cons" value=<?php echo $innerR->cons;?>>
                                                <input type="hidden" name="unit" value=<?php echo $_POST['p_unit'];?>>
                                                <input type="hidden" name="cost" value=<?php echo $innerR->cost;?>>
                                              </form>
                                              <?
                                            }
                                            else { ?>
                                                <p>Bill Paid<br>ON:<i><?php echo $innerR->pay_date; ?></i><br>REF NO:<i><?php echo $innerR->ref_no; ?></i></p>
                                            <?}
                                        ?></td>
                                     </tr>

                        <? } ?>
                            </table>
                      <? } ?>
                    </div>
                      <br>
                      <p align=center>*readings are compared with your service provider not as a whole source</p>
                  <? } ?>
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
