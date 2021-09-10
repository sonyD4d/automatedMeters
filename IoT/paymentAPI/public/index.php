<?php
  use \Psr\Http\Message\ServerRequestInterface as Request;
  use \Psr\Http\Message\ResponseInterface as Response;
  require '../vendor/autoload.php';
  require '../src/config/db.php';

  //Routes
  require '../src/routes/api.php';

  $app->run();
