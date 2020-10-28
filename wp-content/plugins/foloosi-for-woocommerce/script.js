foloosiHandler(response, function (e) {
  if (e.data.status == 'success') {
   document.getElementById('foloosi_payment_id').value =
      e.data.data.transaction_no;
    document.getElementById("btn-foloosi-submit").click();
  }
  if (e.data.status == 'error') {
    setTimeout(function () {
      fp1.close();
    }, 3000)
  }
  if(e.data.status == 'closed'){
    console.log('Transaction cancelled');
  }
});
document.getElementById('btn-foloosipay').onclick = function(e){
  fp1.open();
  e.preventDefault();
}
var options = {
  "reference_token": foloosi_params.reference_token,
  "merchant_key": foloosi_params.merchant_key
}
var fp1 = new Foloosipay(options);
fp1.open();