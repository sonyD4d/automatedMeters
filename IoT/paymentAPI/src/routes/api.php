<?php
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Message\ResponseInterface as Response;

    require '../vendor/autoload.php';
    include "smtp/mail.php";

    $app = new \Slim\App;

    /*-----------------------------API-FOR-PI-------------------------------------------*/
    //get cost for each pulse
    //API link:http://paymentapi/paymentAPI/public/api/cost/[id_of_provider]
    $app->get('/api/cost/{id}', function(Request $request, Response $response){
        $id = $request->getAttribute('id');
        $sql = "SELECT * FROM cost_per_pulse WHERE providerID = '$id'";
        try{
            $db = new db();
            $db = $db->connect();
            $stmt = $db->query($sql);
            $customer = $stmt->fetch(PDO::FETCH_OBJ);
            $db = null;
            echo json_encode($customer);
        } catch(PDOException $e){
            echo '{"error": {"text": '.$e->getMessage().'}';
        }
    });
    /*-----------------------------END-API-PI-------------------------------------------*/

    /*----------------------------------------------------KAFKA-INPUT--------------------------------------------------*/

    function httpPostBill($js,$dbType){

      $ch = curl_init();
      if($dbType==1){
          $url="http://paymentapi/paymentAPI/public/api/postProvider";
          $dataS=$js;
          curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                  'Content-Type: application/json',
                  'Content-Length: ' . strlen($dataS))
              );
      }
      else{
          $url="http://localhost:8086/write?db=iot_meter";
          $data=json_decode($js,true);
          $dataS = "reading,provider_id=".$data['provider']." sub_id=\"".$data['sub_id']."\",cost=".$data['cost'].",cons=".$data['cons'].",type=\"".$data['type']."\" ".$data['date'];
          curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain'));

      }

      curl_setopt($ch,CURLOPT_URL,$url);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $dataS);

      $output=curl_exec($ch);
      curl_close($ch);

    }

    //Add Consumption data from KafkaConsumer to our and provider database and influxDB
    //API link:http://paymentapi/paymentAPI/public/api/addCons
    //{"cons": "0", "pReading": "2583", "cost": 0.0, "provider": "BESCOM06", "id": "ELE001"}
    //{"cons": "33", "pReading": "2583", "cost": 702, "provider": "GASL01", "id": "GAS001"}
    //{"cons": "22", "pReading": "2583", "cost": 190, "provider": "WTR_TMK", "id": "WTR001"}
    $app->post('/api/addCons', function(Request $request, Response $response){

      //Query Parameters
      $sub_id=$request->getParam('id');
      $cost=$request->getParam('cost');
      $cons=$request->getParam('cons');
      $date=date("Y-m-d");
      $dateNano = (int) (microtime(true) * 1000000000);
      $provider_id=$request->getParam('provider');
      $p_reading=$request->getParam('pReading');

      //To add data to our databse
      $addQuery = "INSERT INTO `meter_billing`(`bill_id`, `sub_id`, `cost`, `cons`, `date`, `paid_status`, `pay_date`, `ref_no`) VALUES('',:sub_id,:cost,:cons,:dt,0,NULL,NULL)";
      try{
          $db = new db_iot();
          $db = $db->connect();
          $stmt = $db->prepare($addQuery);
          $stmt->bindParam(':sub_id', $sub_id);
          $stmt->bindParam(':cost', $cost);
          $stmt->bindParam(':cons', $cons);
          $stmt->bindParam(':dt', $date);
          $stmt->execute();
          $db = null;
      } catch(PDOException $e){
          echo '{"error": {"text": '.$e->getMessage().'}';
      }

      //To get ID of just added data to reflect in providers data
      try{
          $db = new db_iot();
          $db = $db->connect();
          $sql = $db->prepare("SELECT `bill_id` FROM `meter_billing` WHERE `sub_id`=\"$sub_id\" AND `date`=\"$date\" AND `cons`=$cons");
          $sql->execute();
          $result = $sql->fetch(PDO::FETCH_ASSOC);
          $id=$result['bill_id'];
          $db = null;
      } catch(PDOException $e){
          echo '{"error": {"text": '.$e->getMessage().'}';
      }

      //send data to  provider database
      $jso=array('bill_id'=>$id,'date'=>$date,'cost'=>$cost,'cons'=>$cons,'sub_id'=>$sub_id,'provider'=>$provider_id,'pReading'=>$p_reading);
      httpPostBill(json_encode($jso),1);

      //to get emial-id and subcritption type for that user_id
      try{
          $db = new db_iot();
          $db = $db->connect();
          $sql = $db->prepare("SELECT `e_mail`,`type` FROM `user_details` JOIN `user_subscription` ON `user_details`.`user_id` = `user_subscription`.`user_id` JOIN `provider_details` ON `user_subscription`.`provider_id` =`provider_details`.`provider_id` WHERE `user_subscription`.`sub_id` = '$sub_id'");
          $sql->execute();
          $result = $sql->fetch(PDO::FETCH_ASSOC);
          $email_id=$result['e_mail'];
          $type=$result['type'];
          $db = null;
      } catch(PDOException $e){
          echo '{"error": {"text": '.$e->getMessage().'}';
      }
      //send email to user
      //mailTo($email_id,json_encode($jso));

      //to INFLUX Data
      //"{"provider":"BESCOM06","sub_id":"ELE001","cost":"201","cons":"25","type":"ELE","date":"1465939835108405663"}"
      $jso=array('date'=>$dateNano,'cost'=>$cost,'cons'=>$cons,'sub_id'=>$sub_id,'provider'=>$provider_id,'type'=>$type);
      httpPostBill(json_encode($jso),2);

    });

    //API Send billing data to provider database
    //API link:http://paymentapi/paymentAPI/public/api/postProvider
    //{"bill_id":"10","date":"2019-03-19","cost":"0","cons":"0","sub_id":"ELE001","provider":"BESCOM06"}
    $app->post('/api/postProvider', function(Request $request, Response $response){
      $sub_id=$request->getParam('sub_id');
      $bill_id=$request->getParam('bill_id');
      $cost=$request->getParam('cost');
      $cons=$request->getParam('cons');
      $date=$request->getParam('date');
      $provider_id=$request->getParam('provider');
      $past=$request->getParam('pReading');
      $query = "INSERT INTO `meter_billing`(`bill_id`, `sub_id`, `cost`, `cons`, `date`, `pay_date`, `ref_no`, `pReading`) VALUES(:id,:sub_id,:cost,:cons,:dt,NULL,NULL,:pR)";
      try{
          $db = new db();
          $db = $db->connect();
          $stmt = $db->prepare($query);
          $stmt->bindParam(':id', $bill_id);
          $stmt->bindParam(':sub_id', $sub_id);
          $stmt->bindParam(':cost', $cost);
          $stmt->bindParam(':cons', $cons);
          $stmt->bindParam(':dt', $date);
          $stmt->bindParam(':pR', $past);
          $stmt->execute();
          $db = null;
      } catch(PDOException $e){
          echo '{"error": {"text": '.$e->getMessage().'}';
      }
    });

    /*----------------------------------------------------END-KAFKA-INPUT-----------------------------------------------------------*/

    /*--------------------TEST-API-FOR-GET-------------------------------------------------------------------*/
    //get subscription details for a subscriber with his/her ID
    //API link:http://paymentapi/paymentAPI/public/api/getSub/[id_of_subscriber]
    $app->get('/api/getSub/{id}', function(Request $request, Response $response){
      $id = $request->getAttribute('id');
      $sql = "SELECT * FROM user_subscription WHERE user_id='$id'";
      try{
          $db = new db_iot();
          $db = $db->connect();
          $stmt = $db->query($sql);
          $count = $stmt->rowCount();
          echo "[";
          while ($count!=0)
          {
              $row=$stmt->fetch(PDO::FETCH_OBJ);
              echo json_encode($row);
              if($count>1){
                echo ",";
              }
              $count--;
          }
          echo "]";
          $db = null;
      } catch(PDOException $e){
          echo '{"error": {"text": '.$e->getMessage().'}';
      }
    });
    /*-------------------------END-TEST-------------------------------------------------------------------*/

    /*--------------------------------------UTILITY-APIS--------------------------------------------------*/

    //post data into user_subscription or add data to user subscription details
    /*
        API link:http://paymentapi/paymentAPI/public/api/postSub
        {
          "user_id":"3ds23",
          "provider_id":"BESCOM06",
          "sub_id":"ELE201"
        }
    */
    $app->post('/api/postSub', function(Request $request, Response $response){

      $qp=$request->getParsedBody();
      $user_id = $request->getParam('user_id');
      $provider_id = $request->getParam('provider_id');
      $sub_id = $request->getParam('sub_id');
      $query = "INSERT INTO `user_subscription`(`user_id`, `provider_id`, `sub_id`) VALUES (:user_id,:provider_id,:sub_id)";
      try{
          $db = new db_iot();
          $db = $db->connect();
          $stmt = $db->prepare($query);
          $stmt->bindParam(':user_id', $user_id);
          $stmt->bindParam(':provider_id', $provider_id);
          $stmt->bindParam(':sub_id', $sub_id);
          $stmt->execute();
          $db = null;
      } catch(PDOException $e){
          echo '{"error": {"text": '.$e->getMessage().'}';
      }
    });

    //put data to update billing info in our and provider database
    /*
        API link:http://paymentapi/paymentAPI/public/api/updateBill
        {
          "id":"1",
          "paid_date":"2019-03-02",
          "paid_ref":"Qt45"
        }
    */
    $app->put('/api/updateBill', function(Request $request, Response $response){
        $id = $request->getParam('id');
        $paid_date = $request->getParam('paid_date');
        $paid_ref = $request->getParam('paid_ref');
        $sql = "UPDATE `meter_billing` SET `paid_status`=1,`pay_date`=:paid_date,`ref_no`=:paid_ref WHERE `bill_id`=:id";
        try{
            $db = new db_iot();
            $db = $db->connect();
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':paid_date', $paid_date);
            $stmt->bindParam(':paid_ref',  $paid_ref);
            $stmt->bindParam(':id',$id);
            $stmt->execute();
        } catch(PDOException $e){
            echo '{"error": {"text": '.$e->getMessage().'}';
        }
        $sql = "UPDATE `meter_billing` SET `pay_date`=:paid_date,`ref_no`=:paid_ref WHERE `bill_id`=:id";
        try{
            $db = new db();
            $db = $db->connect();
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':paid_date', $paid_date);
            $stmt->bindParam(':paid_ref',  $paid_ref);
            $stmt->bindParam(':id',$id);
            $stmt->execute();
        } catch(PDOException $e){
            echo '{"error": {"text": '.$e->getMessage().'}';
        }
    });
    /*-------------------------------------END-UTILITY----------------------------------------------------------------*/
  ?>
