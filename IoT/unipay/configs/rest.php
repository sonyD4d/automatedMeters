<?php
  //restAPI call to update bill on bill payment(our and provider db)
  function httpPutBill($js){
    $ch = curl_init();
    $url="http://paymentapi/paymentAPI/public/api/updateBill";
    $dataS=$js;
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($dataS))
            );

    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataS);

    $output=curl_exec($ch);
    curl_close($ch);

  }
?>
