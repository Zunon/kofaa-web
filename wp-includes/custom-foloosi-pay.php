<?php
require '../wp-load.php';

$MERCHANT_KEY='live_$2y$10$ibc4DJoPM.nntXeLLFXejuvy6prrPYRftDIM5pABBeuugD3wUuLZO';
$SECRET_KEY='live_$2y$10$8wNno8KYRDUrdJg-YZWXqusYJ.Rx9jPuRWXl6HTywdPBRWBmmoumW';

$fields = array(
"redirect_url" => home_url('/thank-you-for-purchase'),
"transaction_amount" => $_REQUEST['total_course_value'],
"currency" => "AED",
"customer_name" =>  $_REQUEST['user_name'], /*note : auto render in payment popup*/
"customer_email" =>  $_REQUEST['user_email'], /*note : auto render in payment popup*/
"customer_mobile" =>  "", /*note : auto render in payment popup*/
"customer_address" =>  "", /*note : minimize form fields in card detail page*/
"customer_city" =>  "", /*note : minimize form fields in card detail page*/

);
      
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://foloosi.com/api/v1/api/initialize-setup");
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
'merchant_key:' .$MERCHANT_KEY
));

curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    $response = curl_exec($ch);
    curl_close($ch);

    $JSONarr= json_decode($response,true);
    // print_r($JSONarr);

echo "<input type='hidden' id='reference_token' value='".$JSONarr["data"]["reference_token"]."'/>";
echo "<input type='hidden' id='MERCHANT_KEY' value='".$MERCHANT_KEY."'/>";
echo "<input type='hidden' id='SECRET_KEY' value='".$SECRET_KEY."'/>";
echo "<input type='hidden' id='total_value' value='".$_REQUEST['total_course_value']."'/>";
echo "<input type='hidden' id='user_id' value='".$_REQUEST['user_id']."'/>";
echo "<input type='hidden' id='user_name' value='".$_REQUEST['user_name']."'/>";
echo "<input type='hidden' id='user_email' value='".$_REQUEST['user_email']."'/>";
echo "<input type='hidden' id='course_id' value='".$_REQUEST['course_id']."'/>";

?>

<!DOCTYPE html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="https://www.foloosi.com/js/foloosipay.v2.js"></script>
</head>
<body>
<p>click <button onclick="foloosi_open()" >here</button> if you are not redirected to payment page.</p>
<script type="text/javascript">

var homeurl = $('#homeurl').text();
var newReferenceToken=$('#reference_token').val();
var MERCHANT_KEY=$('#MERCHANT_KEY').val();
var total_value=$('#total_value').val();
var user_id=$('#user_id').val();
var user_name=$('#user_name').val();
var user_email=$('#user_email').val();
var course_id=$('#course_id').val();
   var options = {
      "reference_token" : newReferenceToken, //which is get from step2
      "merchant_key" : MERCHANT_KEY
   }
   var fp1 = new Foloosipay(options);

   fp1.open();

   function foloosi_open() {
      fp1.open();
   }

   foloosiHandler(response, function (e) {
      if(e.data.status == 'success'){
         //responde success code
         // alert(e.data.data.transaction_no);
         // alert(e.data.status);
         // alert(e.data.data.payment_status);
      }
      if(e.data.status == 'error'){
         //responde error code
         // alert(e.data.status);
         // alert(e.data.data.payment_status);
         // alert(e.data.data.transaction_no);
      }
      $.ajax({
            type: 'post',
            url: 'custom-foloosi-response.php',
            data: { user_email : user_email, user_id : user_id, user_name : user_name, payment_status : e.data.status, transact_no : e.data.data.transaction_no, total_value : total_value, course_id : course_id },
            success: function (response) {
                if(response == 'true') {
                  var url = homeurl+'thank-you-for-purchase';
                  $(location).attr('href',url);
                }
            }
        });
   });
</script>
</body>
</html>
<?php

// FLSAPI0005f153176793a2   69022351
?>