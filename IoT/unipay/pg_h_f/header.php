<?php
  include './configs/names.php';
?>
<header class="default-header" style="margin:10px">
  <div class="header-wrap">
    <div class="header-top d-flex justify-content-between align-items-center">
      <div class="logo">
        <h2 style="color:white" href="index.html"><?php echo $title; ?></h2>
      </div>
      <div class="main-menubar white-menubar d-flex align-items-center">
        <nav class="hide">
          <a href="index.php">Home</a>
          <a href="account.php">Account</a>
          <a href="history.php">Analysis</a>
        </nav>
        <div class="menu-bar"><span class="lnr lnr-menu"></span></div>
           <input type="button" style="width:150px;display:inline;margin-left:50px" class="genric-btn primary-border circle arrow" value="< Logout" onclick="window.location.href = 'login.php?destroy';">
      </div>
    </div>
  </div>
</header>
<section class="generic-banner element-banner relative">
  <div class="container" style="height:120px">

  </div>
</section>
