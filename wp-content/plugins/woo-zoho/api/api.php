<?php
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if(!class_exists('vxc_zoho_api')){
    
class vxc_zoho_api extends vxc_zoho{
  
         public $token='' ; 
    public $info=array() ; // info
    public $url='';
    public $ac_url='https://accounts.zoho.com/';
    public $error= "";
    public $timeout= "30";
    public static $address=array();

function __construct($info) {
     
    if(isset($info['data'])){ 
       $this->info= $info['data'];
     
       $domain='com';
       if(isset($this->info['dc'])){
       $domain=$this->info['dc'];    
       }
       $this->ac_url='https://accounts.zoho.'.$domain.'/';
    }
      if(!isset($this->info['type'])){
           $this->info['type']='';
       }
}
public function get_token(){
    $users=$this->get_users();
 
    $info=$this->info;
    if(is_array($users) && count($users)>0){
    $info['valid_token']='true';    
    }else{
        $info['zoho_error']=$users;
      unset($info['valid_token']);  
    }
return $info;
}
/**
  * Get New Access Token from infusionsoft
  * @param  array $form_id Form Id
  * @param  array $info (optional) Infusionsoft Credentials of a form
  * @param  array $posted_form (optional) Form submitted by the user,In case of API error this form will be sent to email
  * @return array  Infusionsoft API Access Informations
  */
public function refresh_token($info=""){
  if(!is_array($info)){
  $info=$this->info;
  }

  if(!isset($info['refresh_token']) || empty($info['refresh_token'])){
   return $info;   
  }
    $ac_url=$this->ac_url(); 
  $client=$this->client_info(); 
  ////////it is oauth    
  $body=array("client_id"=>$client['client_id'],"client_secret"=>$client['client_secret'],"redirect_uri"=>$client['call_back'],"grant_type"=>"refresh_token","refresh_token"=>$info['refresh_token']);
  $re=$this->post_crm($ac_url.'oauth/v2/token','token',$body);

  if(isset($re['access_token']) && $re['access_token'] !=""){ 
  $info["access_token"]=$re['access_token'];
 // $info["refresh_token"]=$re['refresh_token'];
 // $info["org_id"]=$re['id'];
  $info["class"]='updated';
  $info["token_time"]=time(); 
  $info['valid_token']='true'; 
  }else{
      $info['valid_token']=''; 
  $info['error']=$re['error'];
  $info['access_token']="";
   $info["class"]='error';
  } 
  //api validity check
  $this->info=$info;
  //update infusionsoft info 
  //got new token , so update it in db
  $this->update_info( array("data"=> $info),$info['id']); 
  return $info; 
  }
public function handle_code(){
      $info=$this->info;
      $id=$info['id'];
 
        $client=$this->client_info();
  $log_str=array(); $token=array();
  $ac_url=$this->ac_url(); 
  if(isset($_REQUEST['code'])){
  $code=$this->post('code'); 
  
  if(!empty($code)){

     
  $body=array("client_id"=>$client['client_id'],"client_secret"=>$client['client_secret'],"redirect_uri"=>$client['call_back'],"grant_type"=>"authorization_code","code"=>$code);
  $token=$this->post_crm($ac_url.'oauth/v2/token','token',$body);
  }
  if(isset($_REQUEST['error'])){
   $token['error']=$this->post('error');   
  }
  if(empty($token['refresh_token'])){
      $token['access_token']='';
      $dc=!empty($info['dc']) ? $info['dc'] : 'com';
      if(empty($token['error'])){
      $token['error']='You can connect one Zoho account to one location only. if you want to connect one zoho account to multiple locations then please use <b>own zoho App</b> for each location. If you want to dissconnect from other locations then Go to <a href="'.$ac_url.'u/h#sessions/userconnectedapps" target="_blank">accounts.zoho.'.$dc.' -> Sessions -> Connected Apps</a> and remove "CRM Perks" app'; 
  } }
  
  }else if(!empty($info['refresh_token'])){
        $token=$this->post_crm($ac_url.'oauth/v2/token/revoke','token',array('token'=>$info['refresh_token']));
  }

  $url='';
  if(!empty($token['api_domain'])){
  $url=$token['api_domain'];  
  }

  $info['instance_url']=$url;
  $info['access_token']=$this->post('access_token',$token);
  $info['token_exp']=$this->post('expires_in_sec',$token);
  $info['client_id']=$client['client_id'];
  $info['_id']=$this->post('id',$token);
  $info['refresh_token']=$this->post('refresh_token',$token);
  $info['token_time']=time();
  $info['_time']=time();
  $info['error']=$this->post('error',$token);
  $info['api']="api";
  $info["class"]='error';
  $info['valid_token']=''; 
  $info['api_check']=''; 
  if(!empty($info['access_token'])){
  $info["class"]='updated';
  $info['valid_token']='true'; 
  }

  $this->info=$info;

  $this->update_info( array('data'=> $info) , $id); //var_dump($info); die();
  return $info;
  }

public function ac_url(){
    $dc='com';
    if(!empty($this->info['dc'])){
    $dc=$this->info['dc'];    
    }
    $this->ac_url='https://accounts.zoho.'.$dc.'/';
  return $this->ac_url;  
}
public function get_crm_objects(){
    $type=$this->info['type']; 
if( in_array($type,array('invoices','books','inventory'))){
    if($type == 'inventory'){
  $objs=array('contacts'=>'Contacts','invoices'=>'Invoices','customerpayments'=>'Customer Payments','creditnotes'=>'Credit Notes');      
    }else{
$objs=array('contacts'=>'Contacts','invoices'=>'Invoices','estimates'=>'Estimates','customerpayments'=>'Customer Payments','creditnotes'=>'Credit Notes','recurringinvoices'=>'Recurring Invoices'); //,'contactpersons'=>'Contact Persons'
    }
if( in_array($type, array('books','inventory'))){
    $objs['purchaseorders']='Purchase Orders';
    $objs['salesorders']='Sales Orders';
}
$objs['items']='Items';  
return $objs;
}
$arr= $this->post_crm('settings/modules');
//var_dump($arr);
if(!empty($arr['modules'])){
$objects=$arr['modules'];  
  $objects_f="";
  if(is_array($objects)){
        $objects_f=array();
     foreach($objects as $object){
         if(isset($object['editable']) && $object['editable'] == true ){
             if($object['generated_type'] == 'custom'){
            $object['plural_label'].=' (Custom)';     
             }
    $objects_f[$object['api_name']]=$object['plural_label'];   
         }
     }    
  }
 return $objects_f;   
}else if(isset($arr['error'])){
 return $arr['error'];   
}

}
public function get_layouts($module){
$arr= $this->post_crm('settings/layouts?module='.$module);

if(!empty($arr['layouts'])){
        $objects_f=array();
foreach($arr['layouts'] as $object){
    if(!empty($object['visible'])){
    $objects_f[$object['id']]=$object['name'];
    }   
}    
 return $objects_f;   
}else if(isset($arr['error'])){
 return $arr['error'];   
}

}
public function get_fields_crm($module){
  $fields=array();
  $arr=$this->post_crm('settings/fields?module='.$module);

if(isset($arr['fields']) && is_array($arr['fields'])){
foreach($arr['fields'] as $field){
 
if( isset($field['field_read_only']) && $field['field_read_only'] === false && !in_array($field['data_type'],array('fileupload')) ){ //visible = true
            $name=$field['api_name'];
            if(in_array($name,array('Product_Details'))){
                continue;
            }
        $v=array('label'=>$field['field_label'],'name'=>$field['api_name'],'type'=>$field['data_type']);
       if(isset($field['custom_field']) && $field['custom_field'] === true){
       $v['custom']='yes';    
       }
       if( $v['type'] == 'lookup' ){
           if(!empty($field['lookup']['module'])){
          $v['module']=$field['lookup']['module'];     
           }   
       }else  if($v['type'] == 'multiselectlookup'){
           if(!empty($field['multiselectlookup']['connected_module'])){
          $v['module']=$field['multiselectlookup']['connected_module'];   
          $v['linking_module']=$field['multiselectlookup']['linking_module'];    
          $ar=$this->post_crm('settings/fields?module='.$v['linking_module']);
          if(!empty($ar['fields']) && is_array($ar['fields'])){
           foreach($ar['fields'] as $f){ 
             if($f['data_type'] == 'lookup' && $f['lookup']['module'] == $module){
             $v['module_field']=$f['api_name'];    
             }  
           }   
          }
         
} 
if(empty($v['module_field'])){ continue; }  
}
       
//$v['req']=$required;
if(isset($field['length'])){
$v["maxlength"]=$field['length'];
}
       if(!empty($field['pick_list_values'])){
         $ops=$eg=array();
         foreach($field['pick_list_values'] as $op){
         $ops[]=array('value'=>$op['display_value'],'label'=>$op['display_value']);
         $eg[]=$op['display_value'].'='.$op['display_value'];
         }  
       $v['options']=$ops;
       $v['eg']=implode(', ',array_slice($eg,0,10));
       }  
$fields[$name]=$v;   
        }         
}
$fields['tags']=array('label'=>'Tags','name'=>'tags','type'=>'tags','maxlength'=>'0'); 
$fields['GCLID']=array('label'=>'GCLID','name'=>'GCLID','type'=>'text','maxlength'=>'100'); 
if(isset($fields['Grand_Total'])){
  //  $currency_symbol
 $fields['currency_symbol']=array('label'=>'Currency Symbol','name'=>'currency_symbol','type'=>'text','maxlength'=>'100');    
}
//if(in_array($module,array('Leads','Contacts'))){
$fields['vx_attachments']=array('label'=>'Attachments - Related List','name'=>'vx_attachments','type'=>'files','maxlength'=>'0','custom'=>'yes');  
$fields['vx_attachments2']=array('label'=>'Attachments - Related List 2','name'=>'vx_attachments2','type'=>'files','maxlength'=>'0','custom'=>'yes');  
$fields['vx_attachments3']=array('label'=>'Attachments - Related List 3','name'=>'vx_attachments3','type'=>'files','maxlength'=>'0','custom'=>'yes');  
$fields['vx_attachments4']=array('label'=>'Attachments - Related List 4','name'=>'vx_attachments4','type'=>'files','maxlength'=>'0','custom'=>'yes');  
$fields['vx_attachments5']=array('label'=>'Attachments - Related List 5','name'=>'vx_attachments5','type'=>'files','maxlength'=>'0','custom'=>'yes');  
}
else if(!empty($arr['message'])){
 $fields=$arr['message'];   
}

return $fields;  
}

public function get_fields_invoice($module){
    
$json['invoices']='["reference_number","place_of_supply","gst_treatment","gst_no","template_id","date","payment_terms","payment_terms_label","due_date","discount","tax_total","shipping_charge","is_discount_before_tax","discount_type","is_inclusive_tax","exchange_rate","recurring_invoice_id","invoiced_estimate_id","salesperson_name","project_id","allow_partial_payments","notes","terms","adjustment","adjustment_description","reason","tax_authority_id","tax_exemption_id","invoice_number","tax_id","tax_treatment"]'; //recurringinvoices
 
 $json['salesorders']='["salesorder_number","reference_number","shipment_date","date","notes","terms","discount","shipping_charge","shipping_charge","is_discount_before_tax","discount_type","delivery_method","adjustment","adjustment_description","pricebook_id","salesperson_id","salesperson_name","is_inclusive_tax","exchange_rate","template_id","place_of_supply","gst_treatment","gst_no","tax_id","tax_treatment"]';
 
 $json['purchaseorders']='["vendor_id","purchaseorder_number","reference_number","place_of_supply","source_of_supply","destination_of_supply","gst_treatment","tax_treatment","gst_no","template_id","date","delivery_date","discount","tax_total","is_discount_before_tax","is_inclusive_tax","exchange_rate","billing_address_id","discount_account_id","salesorder_id","notes","terms","adjustment","adjustment_description"]';
 
 $json['contacts']='["contact_name","company_name","contact_type","salutation","first_name","last_name","email","phone","mobile","skype","designation","department","website","billing_attention","billing_address","billing_street2","billing_state_code","billing_city","billing_state","billing_zip","billing_country","billing_fax","billing_phone","shipping_attention","shipping_address","shipping_street2","shipping_state_code","shipping_city","shipping_state","shipping_zip","shipping_country","shipping_fax","shipping_phone","contact_persons","language_code","notes","place_of_contact","gst_no","gst_treatment","tax_treatment","tax_exemption_id","tax_authority_id","tax_id","payment_terms","payment_terms_label","is_portal_enabled","facebook","twitter","currency_code","currency_id"]';

 
 $json['contactpersons']='["salutation","first_name","last_name","email","phone","mobile","skype","designation","department","enable_portal"]';
   
 $json['estimates']='["contact_persons","template_id","place_of_supply","gst_treatment","tax_treatment","gst_no","estimate_number","reference_number","date","expiry_date","exchange_rate","discount","is_discount_before_tax","discount_type","is_inclusive_tax","salesperson_name","notes","terms","shipping_charge","adjustment","adjustment_description","tax_id","tax_exemption_id","tax_authority_id"]';
    
 $json['customerpayments']='["payment_mode","amount","date","reference_number","description","exchange_rate","bank_charges","account_id","tax_account_id"]';
 $json['items']='["name","sku","rate","description","description","unit","product_type","item_type","initial_stock","initial_stock_rate","is_taxable","tax_id","hsn_or_sac"]';
  
  $module_a=$module;
   if( in_array($module, array('recurringinvoices','creditnotes'))){
       $module_a='invoices';
   }
$fields=array(); $req=array('contact_name'); $dates=array('date'); $bool=array('is_discount_before_tax','is_taxable');
$ops=array('is_portal_enabled'=>array('1'=>'true','0'=>'false'),'contact_type'=>array('customer','business'),'product_type'=>array('goods','service'),'item_type'=>array('sales','purchases','sales_and_purchases','inventory'));


if(isset($json[$module_a])){
$arr=json_decode($json[$module_a],true);
 if(!empty($arr)){
foreach($arr as $v){
    $label=ucwords(str_replace('_',' ',$v));
   $field=array('label'=>$label,'type'=>'Text','name'=>$v);
   if(isset($ops[$v])){
       $op=array();
       foreach($ops[$v] as $c){
       $op[]=array('value'=>$c);    
       }
   $field['options']=$op;    
   $field['type']='list';    
   }
   if($v == 'tax_id'){
   $res=$this->post_crm('settings/taxes'); 
   if(!empty($res['taxes'])){
    $ops=array();
      foreach($res['taxes'] as $vv){
   $ops[]=array('label'=>$vv['tax_name'],'value'=>$vv['tax_id']);       
      }
     $field['options']=$ops;    
   $field['type']='list';   
   }    
   }
   if($v == 'currency_id'){
   $res=$this->post_crm('settings/currencies'); 
   if(!empty($res['currencies'])){
    $ops=array();
      foreach($res['currencies'] as $vv){
   $ops[]=array('label'=>$vv['currency_code'].' - '.$vv['currency_name'],'value'=>$vv['currency_id']);      // 
      }
     $field['options']=$ops;    
   $field['type']='list';   
   }    
   }
    if(in_array($v,$req)){
        $field['req']='1';
    }
    if(in_array($v,$dates)){
        $field['type']='date';
    }
    if(in_array($v,$bool)){
        $field['type']='bool';
    }
    $fields[$v]=$field;
}
if(!empty( $fields['tax_id']) && $module == 'contacts'){
$field=$fields['tax_id'];
$field['name']='tax_id_new';
$field['label']='Tax ID (Apply if already not set)';
    $fields['tax_id_new']=$field;    
}     
}    } 
$custom=array('invoices','contacts','estimates','purchaseorders','salesorders','items'); 
if(in_array($module,$custom)){
$module=rtrim($module,'s');   
$arr=$this->post_crm('settings/customfields/'.$module); 
if(!empty($arr['customfields'])){
  foreach($arr['customfields'] as $v){ 
     $id=$v['index'];
     // $id=$this->info['type'] == 'books' ? $v['index'] : $v['customfield_id'];
      $field=array('label'=>$v['label'],'type'=>$v['data_type'],'name'=>$id,'is_custom'=>'1');
      if(!empty($v['values'])){
          $ops=$eg=array();
          foreach($v['values'] as $op){
         $ops[]=array('value'=>$op['name'],'label'=>$op['name']);
         $eg[]=$op['name'];
         }  
       $field['options']=$ops;
       $field['eg']=implode(', ',array_slice($eg,0,10));
      }
    $fields[$id]=$field;  
  }  
}
}  

return $fields;
}

public function get_crm_fields($module,$fields_type=""){
 if( in_array($this->info['type'],array('invoices','books','inventory'))){
 $fields=$this->get_fields_invoice($module);   //get_fields_invoice 
 }else{ 
$fields=$this->get_fields_crm($module);
 } 
if(is_array($fields)){
     if(!empty($fields['shipping_charge'])){
  $fields['vx_ship_entry']=array('name'=>'vx_ship_entry',"type"=>'text','label'=>'Zoho Item ID - for Shipping as line item');
  }
 
    /*    if(in_array($module,array('SalesOrders','PurchaseOrders'))){
      $fields['sub_total']=array('label'=>'Sub Total','name'=>'sub_total','type'=>'text','maxlength'=>'100');  
      $fields['grand_total']=array('label'=>'Grand Total','name'=>'grand_total','type'=>'text','maxlength'=>'100');  
      $fields['tax']=array('label'=>'Tax','name'=>'tax','type'=>'text','maxlength'=>'100');  
      $fields['adjustment']=array('label'=>'Adjustment','name'=>'adjustment','type'=>'text','maxlength'=>'100');
    }*/
/*
$arr=$this->post_crm('settings/related_lists?module='.$module);
 if(!empty($arr['related_lists'])){
     foreach($arr['related_lists'] as $field){ 
      $v=array('label'=>$field['display_label'].' - Related List','name'=>$field['api_name'],'type'=>'related_list'); 
  $fields[$field['api_name']]=$v;       
     }
 }*/   
if($fields_type =="options"){
$field_options=array();
if(is_array($fields)){
foreach($fields as $k=>$f){
if(isset($f['options']) && is_array($f['options']) && count($f['options'])>0){
$field_options[$k]=$f;         
}
}    
}
return $field_options;
}    
}

return $fields; 
}
/**
  * Get campaigns from salesforce
  * @return array Salesforce campaigns
  */
public function get_campaigns(){ 

   $arr= $this->post_crm('Campaigns');
  ///seprating fields
  $msg='No Campaign Found';
$fields=array();
if(!empty($arr['data'])){
foreach($arr['data'] as $val){
$fields[$val['id']]=$val['Campaign_Name'];
}
   
}else if(isset($arr['message'])){
 $msg=$arr['message'];   
}

  return empty($fields) ? $msg : $fields;
}
/**
  * Get users from zoho
  * @return array users
  */
public function get_users(){ 
if(in_array($this->info['type'], array('invoices','books','inventory') )){
    return $this->get_users_invoices();    
}
$arr=$this->post_crm('users?type=AllUsers');
$users=array();    
  ///seprating fields
  $msg='No User Found';
if(!empty($arr['users'])){
if(is_array($arr['users']) && isset($arr['users'][0])){
  foreach($arr['users'] as $k=>$v){
   $users[$v['id']]=$v['full_name'];   
  }  
}
}else if(isset($arr['message'])){
 $msg=$arr['message'];   
}

return empty($users) ? $msg : $users;
}
public function get_users_invoices(){ 

$arr=$this->post_crm('users');

$users=array();    
  ///seprating fields
  $msg='No User Found';
if(!empty($arr['users'])){
if(is_array($arr['users']) && isset($arr['users'][0])){
  foreach($arr['users'] as $k=>$v){
   $users[$v['user_id']]=$v['name'];   
  }  
}
}else if(isset($arr['message'])){
 $msg=$arr['message'];   
}

return empty($users) ? $msg : $users;
}
/**
  * Get users from zoho
  * @return array users
  */
public function get_price_books(){ 

$arr=$this->post_crm('Price_Books');

  ///seprating fields
  $msg=__('No Price Book Found','woocommerce-salesforce-crm');
$fields=array();
if(!empty($arr['data'])){
foreach($arr['data'] as $val){
$fields[$val['id']]=$val['Price_Book_Name'];
}
   
}else if(isset($arr['message'])){
 $msg=$arr['message'];   
}
  return empty($fields) ? $msg : $fields;
}

public function push_object($module,$fields,$meta){

 /*   $path='Invoices/135465000000197020/Products/135465000000197001';
    $path='Sales_Orders/135465000000213001';
    $path='Leads/55427000036833113';
    $post=json_encode(array('data'=>array(array('Quantity'=>10))) );
$res=$this->post_crm($path,'get'); 
var_dump($res); die();

$res=$this->get_entry('Sales_Orders','3595657000000400001');
var_dump($res); die(); 
//$this->get_crm_objects();
//die();  Drivers_X_Contacts= LinkingModule1 ,d_accounts=LinkingModule2
$p='Drivers/3779612000000197323';
//$p='Contacts/3703799000000313001/CustomModule1';
//$p='Contacts/3703799000000209001';
$post=json_decode($json,true);
//$post=array('file'=>'@'.realpath(__DIR__.'/banner9.png'));
$post=array('attachmentUrl'=>'https://www.express.com.pk/images/NP_ISB/20181225/Sub_Images/1105997112-1.jpg','File_Name'=>'exp.jpg','Size'=>'10');
$post=array('multi_contact'=>array(array('id'=>'3703799000000313001')),'Name'=>'Updated 2');
$post=array('Name'=>'Updated 4','Secondary_Email'=>'admin@local.com','multi_contact'=>''); //,
$post=array('D_Contacts'=>array('id'=>'3779612000000215002'),'mutl_contact'=>array('id'=>'3779612000000197323'));
$post=array('data'=>array($post));
$p='Drivers_X_Contacts';
//$p='d_accounts';
$post=json_encode($post);
$post='{"data":[{"D_Contacts":{"id":"3779612000000215008"},"mutl_contact":{"id":"3779612000000220016"}}]}';

$r=$this->post_crm($p,'post',$post);
//$r=$this->post_crm($p,'get');
var_dump($r); die();

$post=array('Last_Name'=>'lewiss','URL_1'=>'http://google.com','File_Upload_1'=>array(array('entity_Id' => 3.7037990000002E+18)));
//$post=http_build_query($post);
$p='Sales_Orders/149964000000152015/Products/149964000000152001';
$p='Contacts/149964000000140007/Products/149964000000152001';
$p='Sales_Orders/149964000000152015';
$p='Sales_Orders';
$json='{"Subject":"touseefcccdd","Description":"ahmadhcccsdd","Billing_City":"houston","Billing_State":"TA","Billing_Country":"PK","Billing_Code":"","Owner":"149964000000132011","Product_Details":[{"product":{"id":"149964000000151009"},"quantity":2},{"product":{"id":"149964000000152001"},"quantity":5}]}';
$p='Products/149964000000152180/Price_Books/149964000000148008';
$post=json_encode(array('data'=>array(array('qty'=>'1'))));
$post=json_encode(array('data'=>array(array('list_price'=>558))));
//$post=json_encode(array('data'=>array(json_decode($json,true))));
*/
$type=$this->info['type'];
if( in_array($type,array('invoices','books','inventory'))){
    return $this->push_object_invoice($module,$fields,$meta);
}
if( in_array($type,array('books'))){
  //  return $this->push_object_books($module,$fields,$meta);
}
//check primary key
 $extra=array();

     $files=array();
  for($i=1; $i<6; $i++){
$field_n='vx_attachments';
if($i>1){ $field_n.=$i; }
  if(isset($fields[$field_n]['value'])){
    $files=$this->verify_files($fields[$field_n]['value'],$files);
    unset($fields[$field_n]);  
  }
}
  $debug = isset($_GET['vx_debug']) && current_user_can('manage_options');
  $event= isset($meta['event']) ? $meta['event'] : '';
  $custom_fields= isset($meta['fields']) ? $meta['fields'] : array();
  $id= isset($meta['crm_id']) ? $meta['crm_id'] : '';

  if($debug){ ob_start();}
if(isset($meta['primary_key']) && $meta['primary_key']!="" && isset($fields[$meta['primary_key']]['value']) && $fields[$meta['primary_key']]['value']!=""){    
$search=$fields[$meta['primary_key']]['value'];
$field=$meta['primary_key'];
$field_type= isset($custom_fields[$field]['type']) ? $custom_fields[$field]['type'] : '';
if(!in_array($field_type,array('email','phone1'))){
$field_type='criteria'; 
$search=str_replace(array('(',')'),array('\(','\)'),$search);
$search='(('.$field.':equals:'.$search.')and('.$field.':starts_with:'.$search.'))'; // start_with is required for phones , without this zoho macthes short/invalid phones to long correct ones  
}
    //search object
$path=$module.'/search?'.$field_type.'='.urlencode($search);
$search_response=$this->post_crm($path);

$extra["body"]=$path;
$extra["search"]=$search;
$extra["response"]=$search_response;
      
  if($debug){
  ?>
  <h3>Search field</h3>
  <p><?php print_r($field) ?></p>
  <h3>Search term</h3>
  <p><?php print_r($search) ?></p>
    <h3>POST Body</h3>
  <p><?php print_r($body) ?></p>
  <h3>Search response</h3>
  <p><?php print_r($search_response) ?></p>  
  <?php
  }
      if(is_array($search_response) && !empty($search_response['data']) ){
          $search_response=$search_response['data'];
      if( count($search_response)>5){
       $search_response=array_slice($search_response,count($search_response)-5,5);   
      }
      $extra["response"]=$search_response;
      $id=$search_response[0]['id'];
  }

}



$post=array(); $status=$action=$method=''; $send_body=true;
 $entry_exists=false;
 $link=""; $error=""; 
 $path='';
 $arr=array();
if($id == ""){
if(empty($meta['new_entry'])){
$method='post';
}else{
    $error='Entry does not exist';
}
$action="Added";  $status="1";
}
else{
 $entry_exists=true;
if($event == 'add_note'){ 
$module='Notes';
$action="Added";
$status="1"; 
$send_body=false;
$post=array('Title'=>$fields['Title']['value'],'Body'=>$fields['Body']['value'],'Parent_Id'=>$fields['ParentId']['value']);   
$arr=$this->post_note($post,$meta['related_object']);
if(isset($arr['data'][0]['details']['id'])){
$id=$arr['data'][0]['details']['id']; 
}
}
else if(in_array($event,array('delete','delete_note'))){
 $send_body=false;
     if($event == 'delete_note'){ 
   $module='Notes';
     }
     $method="delete";
     $action="Deleted";
  $status="5";  
  $path=$module.'?ids='.$id;
}
else{
    //update object
$status="2"; $action="Updated";
if(empty($meta['update'])){
$method='put';
$path=$module.'/'.$id;
}
} }
if(!empty($meta['convert'])){
    if(!empty($id)){
     $path='Leads/'.$id.'/actions/convert';
    $post=array(array('overwrite'=>true,'notify_lead_owner'=>true,'notify_new_entity_owner'=>true));
    $post=json_encode(array('data'=>$post));
    $extra['convert lead']=$res=$this->post_crm($path,'post',$post);
    if(!empty($res['data'][0]['Contacts'])){
       $id=$res['data'][0]['Contacts']; $module='Contacts'; 
    } 
    }else{
$status='';  $error='Lead Does not Exist'; 
    }
    
}else if(!empty($method)){
$zoho_products=$related=array();
$module_products=false;
$multi_lookup=$tags=array(); $product_img='';
if($send_body){
foreach($fields as $k=>$v){
   $type=isset($custom_fields[$k]['type']) ? $custom_fields[$k]['type'] : ''; 
  if( in_array($type, array('textarea','text','picklist') ) && is_array($v['value'])){
      $v['value']=trim(implode(' ',$v['value']));  
    }
    if( in_array($type, array('files','tags') )){
     $related[$type]=$v['value'];   
    }else if( in_array($type, array('fileupload') )){
//this field is not supported in zoho API  
    }else if(in_array($type, array('datetime','date'))){
        // to do , change time offset from+00:00 to real
        $offset=get_option('gmt_offset');
     $offset=$offset*3600; 
     $date_val=strtotime(str_replace(array("/"),"-",$v['value']));
     if( $type == 'datetime' && strpos($v['value'],'+') === false){ // convert to utc if no timezone(+) does not exist with time string
     $date_val-= $offset;   
     }
        // Y-m-d\TH:i:s-08:00  
     if($type == 'date'){
     $post[$k]=date('Y-m-d',$date_val);  
    }else{
     $post[$k]=date('c',$date_val);   
    }
$fields[$k]['value']=$post[$k];
    }else if( in_array($type,array('multiselectpicklist')) ){
          if(is_string($v['value'])){ $v['value']=array($v['value']); }
      $post[$k]=$v['value'];  
    }else if($type == 'multiselectlookup'){
     $multi_lookup[$k]=$v['value'];   
    }else if($type == 'boolean'){
      $post[$k]=!empty($v['value']) ? true : false;  
    }else if($type == 'currency'){
      $post[$k]=floatval($v['value']);  
    }else if($type == 'integer'){
      $post[$k]=intval($v['value']);  
    }else if($type == 'text'){
      $post[$k]=strval($v['value']);  
    }else if($k == 'Tag'){
     $tags=explode(',',$v['value']);   
    }else if($k == 'Record_Image'){
 $product_img=$v['value'];
    }else if($k == 'currency_symbol'){
        $k='$currency_symbol';
    }else{
        if($k == 'GCLID'){ $k='$gclid'; }
    $post[$k]=$v['value']; }
}
if(!empty($tags)){
    $tag=array();
    foreach($tags as $v){
    $tag[]=array('name'=>trim($v));    
    }
 $post['Tag']=$tag;   
}
if($module != 'Contacts'){
//var_dump($multi_lookup,$post); die('-------');
}
//var_dump($post); die();
 //change owner id
  if(isset($meta['owner']) && $meta['owner'] == "1"){
   $post['Owner']=$meta['user'];   
   $fields['Owner']=array('label'=>'Owner','value'=>$meta['user']);
  }  
  if(!empty($meta['add_layout'])){
   $post['Layout']=array('id'=>$meta['layout']);     
      $fields['Layout']=array('label'=>'Layout','value'=>$meta['layout']);
  }

  if(!empty($meta['order_items'])){
   $order_res=$this->get_zoho_products($meta);  
  $zoho_products=$order_res['res'];
  //var_dump($order_res); die();
  if(is_array($order_res['extra'])){
  $extra=array_merge($extra, $order_res['extra']);
  } 

 if(is_array($zoho_products) && count($zoho_products)>0){
if(in_array($module,array('Sales_Orders','Purchase_Orders','Invoices','Quotes'))){
 foreach($zoho_products as $v){ 
     $item_arr=array('product'=>array('id'=>$v['id']),'quantity'=>$v['qty'],'list_price'=>floatval($v['cost']),'Tax'=>$v['tax']); //list_price ,unit_price
     //$v['cost'] = total after discounts , no need to add seprate discount
     if(!empty($meta['item_price']) ){
         if($meta['item_price'] == 'dis'){
         $item_arr['list_price']=floatval($v['price']);
      if( $v['price'] > $v['cost']){ 
         $item_arr['Discount']=floatval($v['price']-$v['cost']);
      //   $item_arr['list_price']=floatval($v['price']);
     }   }else if($meta['item_price'] == 'cost'){
       $item_arr['list_price']=floatval($v['cost_woo']); 
      }
     }

  
$post['Product_Details'][]=$item_arr; //Discount , Tax  
}   
  }else{
  $module_products=true;    
  }

 }

}
//if($module == 'purchaseorders'){
 //var_dump($post,$meta['order_items']); die('----------');   
//}

//var_dump($post); die();
$post=array('data'=>array($post));
if(!empty($meta['assign_rule'])){
    $post['lar_id']=$meta['assign_rule'];
}
}

if(!empty($method)){
if(empty($path)){  $path=$module; }
$arr=$this->post_crm( $path, $method,json_encode($post));
//var_dump($arr,$post); die();
}
if(!empty($arr['data'])){
    if(isset($arr['data'][0]['details']['id'])){
$id=$arr['data'][0]['details']['id']; 

    }else if(isset($arr['data'][0]['message'])){
$error=$arr['data'][0]['code'].' : '.$arr['data'][0]['message'];   
$status='';       
}

}
else if(isset($arr['message'])){
$error=$arr['code'].' : '.$arr['message'];   
$status='';       
}

if(!empty($id)){
//add to campaign
if(isset($meta['add_to_camp']) && $meta['add_to_camp'] == "1"){
   $extra['Campaign Path']=$camp_path=$module.'/'.$id.'/Campaigns/'.$meta['campaign'];
   $camp_post=array('data'=>array(array('Member_Status'=>'active')));
   $extra['Add Campaign']=$this->post_crm($camp_path,'put',json_encode($camp_post));   
  }
if(!empty($product_img)){
$url='Products/'.$id.'/photo';
$arr=array('attachments_v2'=>array('image.png'=>$product_img));
$extra['Product Img']=$this->post_crm($url,'post',$arr); 
  }  
//add tags  
if(!empty($related['tags'])){ 
if(is_array($related['tags'])){ $related['tags']=implode(',',$related['tags']); }
$camp_path=$module.'/'.$id.'/actions/add_tags?tag_names='.urlencode($related['tags']);
$extra['Add Tags']=$this->post_crm($camp_path,'post'); 
}
if(!empty($files)){ 
 $camp_path=$module.'/'.$id.'/Attachments';    
foreach($files as $k=>$file){
//  $file=str_replace($upload['baseurl'],$upload['basedir'],$file);
$extra['Add Files '.$k]=$this->post_crm($camp_path,'post',array('attachmentUrl'=>$file)); 

} 
 

}


if($module_products){
foreach($zoho_products as $k=>$v){
$extra['Add Product Path '.$k]=$path=$module.'/'.$id.'/Products/'.$v['id'];
$post=json_encode(array('data'=>array(array('Quantity'=>$v['qty']))) );
$extra['Add Products '.$k]=$this->post_crm($path,'put',$post);   
}
}
if($multi_lookup){
foreach($multi_lookup as $k=>$v){
$field=isset($custom_fields[$k]) ? $custom_fields[$k] : array(); 
if(!empty($field['module_field'])){
$extra['Multilookup Path '.$k]=$path=$field['linking_module'];
$extra['Multilookup post '.$k]=$post=array('data'=>array(array($field['module_field']=>array('id'=>$id),$k=>array('id'=>$v))));
$extra['Multilookup res '.$k]=$this->post_crm($path,'post',json_encode($post) );   
} }
}
 
}

}
if(!empty($id)){
   $domain=!empty($this->info['dc']) ? $this->info['dc'] : 'com'; 
   $type= empty($this->info['type']) ? 'crm' : $this->info['type'];
   // $link='https://crm.zoho.'.$domain.'/crm/EntityInfo.do?module='.$module."&id=".$id; 
    $link='https://'.$type.'.zoho.'.$domain.'/crm/tab/'.str_replace('_','',$module).'/'.$id; 
}
  if($debug){
  ?>
  <h3>Account Information</h3>
  <p><?php //print_r($this->info) ?></p>
  <h3>Data Sent</h3>
  <p><?php print_r($post) ?></p>
  <h3>Fields</h3>
  <p><?php echo json_encode($fields) ?></p>
  <h3>Response</h3>
  <p><?php print_r($response) ?></p>
  <h3>Object</h3>
  <p><?php print_r($module."--------".$action) ?></p>
  <?php
 echo  $contents=trim(ob_get_clean());
  if($contents!=""){
  update_option($this->id."_debug",$contents);   
  }
  }
       //add entry note
 if(!empty($status) && !empty($meta['__vx_entry_note']) && !empty($id)){
 $disable_note=$this->post('disable_entry_note',$meta);
   if(!($entry_exists && !empty($disable_note))){
       $entry_note=$meta['__vx_entry_note'];
       $entry_note['Parent_Id']=$id;
   

$note_response=$this->post_note($entry_note,$module);
  $extra['Note Body']=$entry_note;
  $extra['Note Response']=$note_response;
 
   }  
 }


return array("error"=>$error,"id"=>$id,"link"=>$link,"action"=>$action,"status"=>$status,"data"=>$fields,"response"=>$arr,"extra"=>$extra);
}
public function is_address($field){
 $is_address=false;
 if(!in_array($field,array('shipping_charge'))){
 $is_address= strpos($field,'billing_') !== false || strpos($field,'shipping_') !== false ;
 }
return $is_address;
}
public function verify_files($files,$old=array()){
        if(!is_array($files)){
        $files_temp=json_decode($files,true);
     if(is_array($files_temp)){
    $files=$files_temp;     
     }else if (!empty($files)){ //&& filter_var($files,FILTER_VALIDATE_URL)
      $files=array_map('trim',explode(',',$files));   
     }else{
      $files=array();    
     }   
    }
    if(is_array($files) && is_array($old) && !empty($old)){
   $files=array_merge($old,$files);     
    }
  return $files;  
}
public function push_object_invoice($module,$fields,$meta){

   //  $res=$this->post_crm('salesorders/93987000000055121');
   // $res=$this->post_crm('salesorders/95902000000062171');

  // $res=$this->post_crm('contacts/118114000000032119/address');
 ///  $res=$this->post_crm('salespersons');
  //  var_dump($res); die();
 /*
$post=array('JSONString'=>'{"contact_persons":[{"first_name":"johnxx","last_name":"lewisxx","email":"bioinfo38@gmail.com","phone":"8104763057"}]}');
$post=array('JSONString'=>'{"invoices":[{"invoice_id":"109158000000032286"}],"order_status":"closed","status":"closed","invoiced_status":"invoiced","date":"2020-01-01"}');
$post=array('JSONString'=>'{"salesorder_id":"109158000000032204","salesorder_number":"SO-00001","date":"2020-01-01"}');
//$res=$this->post_crm('contacts/1638246000000106001','put',$post);
//$res=$this->post_crm('invoices/1639733000000076134/email','post');
//$res=$this->post_crm('salesorders/109158000000032204/status/open','post');
//$res=$this->post_crm('salesorders/109158000000032204','put',$post);
$res=$this->post_crm('invoices/fromsalesorder?salesorder_id=95902000000065001','post',$post);
var_dump($res);
die();*/
//check primary key
 $extra=array();

  $event= isset($meta['event']) ? $meta['event'] : '';
  $custom_fields= isset($meta['fields']) ? $meta['fields'] : array();
  $id= isset($meta['crm_id']) ? $meta['crm_id'] : '';
  if($module == 'customerpayments'){
   $module_single='payment';   
  }else{
$module_single=rtrim($module,'s');
  }

if(isset($meta['primary_key']) && $meta['primary_key']!="" && isset($fields[$meta['primary_key']]['value']) && $fields[$meta['primary_key']]['value']!=""){    
$search=$fields[$meta['primary_key']]['value'];
$field=$meta['primary_key'];
$field_type= isset($custom_fields[$field]['type']) ? $custom_fields[$field]['type'] : '';
if(!in_array($field,array('email','phone','contact_name','company_name','first_name','last_name'))){
if($this->is_address($field)){
  $field='address';   
}else{
    $field='search_text'; 
} 
}

$path=$module.'?'.$field.'='.urlencode($search);
if($module == 'contacts'){
    $path.='&contact_type=customer';
}
$search_response=$this->post_crm($path);
//var_dump($search_response); die();
$extra["body"]=$path;
$extra["search"]=$search;
$extra["response"]=$search_response;

      if(is_array($search_response) && !empty($search_response[$module]) ){
          $search_response=$search_response[$module];
      if( count($search_response)>5){
       $search_response=array_slice($search_response,count($search_response)-5,5);   
      }
      $extra["response"]=$search_response;
      $id=$search_response[0][$module_single.'_id'];
  }
}



$post=array(); $status=$action=$method=$contact_person_id=''; $send_body=true;
 $entry_exists=false;
 $link=""; $error=""; 
 $path=''; $q=array(); $disable_items=false;
 $arr=array();
if($id == ""){
if(empty($meta['new_entry'])){
$method='post';
}else{
    $error='Entry does not exist';
}
$action="Added";  $status="1";
$path=$module;
if(!empty($meta['order_check']) && !empty($meta['object_order']) &&  !empty(self::$feeds_res[$meta['object_order']]['id']) && $module == 'invoices'){
  $path.='/fromsalesorder';  
  $q['salesorder_id']= self::$feeds_res[$meta['object_order']]['id'];
  $disable_items=true;
}
}
else{
 $entry_exists=true;
if(in_array($event,array('delete','delete_note'))){
 $send_body=false;
     if($event == 'delete_note'){ 
   $module='Notes';
     }
     $method="delete";
     $action="Deleted";
  $status="5";  
  $path=$module.'/'.$id;
}
else{
    //update object
$status="2"; $action="Updated";
if(empty($meta['update'])){
$method='put';
$path=$module.'/'.$id;
 if($module == 'contacts'){
 $person=$this->post_crm('contacts/'.$id.'/contactpersons');
  if(!empty($person['contact_persons'][0]['contact_person_id'])){
  $contact_person_id=$person['contact_persons'][0]['contact_person_id'];    
  }
 }
}
}
}

if(!empty($method)){
$zoho_products=$related=$custom=array();
$contact_fields=array("salutation","first_name","last_name","email","phone","mobile","skype","designation","department","enable_portal","is_portal_enabled");
if($send_body){
foreach($fields as $k=>$v){
    $field=isset($custom_fields[$k]) ? $custom_fields[$k] : array(); 

    if(empty($field['type'])){ continue; }

    $type=$field['type']; 
       if($type == 'check_box'){
        // to do , change time offset from+00:00 to real
     $v['value']=!empty($v['value']) ? true : false; 
    }else if($type == 'date'){
     $v['value']=date('Y-m-d',strtotime(str_replace('/', '-',$v['value'])));   
    }else if($type == 'bool'){
     $v['value']=(bool)$v['value'];   
    }
   if(!empty($field['is_custom'])){ 
      
  $cust_field=array('value'=>$v['value']);
  if($this->info['type'] == 'books'){
    $cust_field['index']=$field['name'];  
  }else{
   $cust_field['label']=$field['label'];     
  }
    $post['custom_fields'][]=$cust_field;
   }else if($this->is_address($k)){ 
       if(strpos($k,'shipping_') !== false){
       $id_key=substr($k,0,8); 
       $k=substr($k,9);
       }else{
        $id_key=substr($k,0,7); 
       $k=substr($k,8);     
       } 
       $post[$id_key.'_address'][$k]=$v['value']; 
    }else if(in_array($k,$contact_fields)){
      $related['contacts'][$k]=$v['value'];  

    }else if($k == 'invoice_id'){
        $inv=array('invoice_id'=>$v['value']);
        if(!empty($fields['amount']['value'])){
        $inv['amount_applied']=$fields['amount']['value'];  
        }
      $post['invoices']=array($inv);  
    }else if($k == 'tax_id_new'){
        if(!empty($id)){
       $con=$this->post_crm($path); 
       if(empty($con['contact']['tax_id'])){
       $post['tax_id']=$v['value'];    
       }    
        }   
    }else if($k == 'currency_code'){
if(!empty($meta['fields']['currency_id']['options'])){
    foreach($meta['fields']['currency_id']['options'] as $op){ 
    $v['value']=strtolower($v['value']);
    $op['label']=strtolower($op['label']);
        if( strpos($op['label'],$v['value']) === 0 ){
      $post['currency_id']=$op['value'];        
        }
    }
}
}else{
     $post[$k]=$v['value'];    
    }
}

if(!empty($related['contacts']) ){
    $person=$related['contacts'];
    if(!empty($contact_person_id)){
      $person['contact_person_id']=$contact_person_id;
    }
    $post['contact_persons']=array($person);
}
$addresses=array('billing','shipping');
if($module == 'contacts' && $status == '2' && !empty($path)){
    $address_res=$this->post_crm('contacts/'.$id.'/address'); 
    if(!empty($address_res['addresses'])){
        
        foreach($addresses as $addr_id){
        if(!empty($post[$addr_id.'_address'])){ 

            foreach($address_res['addresses'] as $v){
                if(!empty($post[$addr_id.'_address']['address']) && $post[$addr_id.'_address']['address'] == $v['address'] && !empty($post[$addr_id.'_address']['city']) && $post[$addr_id.'_address']['city'] == $v['city']){
                self::$address[$addr_id.'_address']=$v['address_id'];  
                unset($post[$addr_id.'_address']); //address matched , so remove it from post  
                }
            }

if(empty(self::$address[$addr_id.'_address'])){ // no address matched , add as new address
    $addr=$post[$addr_id.'_address']; //$addr['update_existing_transactions_address']=true;
      $extra[$addr_id.' address']=$addr_res=$this->post_crm('contacts/'.$id.'/address','post',array('JSONString'=>json_encode($addr)));  
      unset($post[$addr_id.'_address']);
      if(!empty($addr_res['address_info'])){
      self::$address[$addr_id.'_address']=$addr_res['address_info']['address_id'];    
      }
}
            
        } 
        }
    }
}
if( in_array($module,array('salesorders','invoices')) && !$disable_items ){
  foreach($addresses as $addr_id){
   if(!empty(self::$address[$addr_id.'_address'])){
       $post[$addr_id.'_address_id']=self::$address[$addr_id.'_address'];
       $fields[$addr_id.'_address']=array('value'=>self::$address[$addr_id.'_address'],$addr_id.' address');
   }   
  }  
} 
 //change owner id
  if(isset($meta['owner']) && $meta['owner'] == "1"){
   $post['owner_id']=$meta['user'];   
   $fields['owner_id']=array('label'=>'Owner','value'=>$meta['user']);
  } 
    if(!empty($meta['contact_check']) && !empty($meta['object_contact']) &&  !empty(self::$feeds_res[$meta['object_contact']]['id']) ){
     $customer_key='customer_id'; 
    if($module == 'purchaseorders'){
      $customer_key='delivery_customer_id';   
    }    
   $post[$customer_key]=self::$feeds_res[$meta['object_contact']]['id'];   
   $fields[$customer_key]=array('label'=>'Customer ID','value'=>$post[$customer_key]);
 }
   
if(!empty($meta['email_check']) && !empty($post['customer_id']) ){
  $persons=array();
  $person=$this->post_crm('contacts/'.$post['customer_id'].'/contactpersons');
  if(!empty($person['contact_persons'])){
  foreach($person['contact_persons'] as $p){
      $persons[]=$p['contact_person_id'];
  }  
 $post['contact_persons']=$persons;
 if(!empty($meta['email_subject'])){
     $post['custom_subject']=$meta['email_subject'];
 }
  if(!empty($meta['email_body'])){
     $post['custom_body']=$meta['email_body'];
 }
   $fields['contact_id']=array('label'=>'Contact Person','value'=>$persons);
  }
}

    if(!empty($meta['invoice_check']) && !empty($meta['object_invoice']) &&  !empty(self::$feeds_res[$meta['object_invoice']]['id']) ){
    $inv=array('invoice_id'=>self::$feeds_res[$meta['object_invoice']]['id']);
        if(!empty($fields['amount']['value'])){
        $inv['amount_applied']=$fields['amount']['value'];  
        }
      $post['invoices']=array($inv);   
   $fields['invoice_id']=array('label'=>'Invoice ID','value'=>self::$feeds_res[$meta['object_invoice']]['id']);
} 

if(!empty($meta['order_items']) && !$disable_items){
   $order_res=$this->get_zoho_products_invoice($meta);  
  $zoho_products=$order_res['res'];
  if(is_array($order_res['extra'])){
  $extra=array_merge($extra, $order_res['extra']);
  }

 if(is_array($zoho_products) && count($zoho_products)>0){

     
 foreach($zoho_products as $v){
 $line_item=array('item_id'=>$v['id'],'quantity'=>$v['qty'],'rate'=>$v['cost']);
// $line_item['rate']=1.98;
  if(!empty($meta['warehouse'])){
    $line_item['warehouse_id']=$meta['warehouse'];   
  }
  if(!empty($meta['item_price'])){
      if($meta['item_price'] == 'dis'){
  $line_item['rate']=$v['price'];
   if( $v['price'] > $v['cost']){ 
         $line_item['discount']=floatval($v['price']-$v['cost']);
     }
  
      }else if($meta['item_price'] == 'cost'){
       $line_item['rate']=$v['cost_woo'];   
      }
  }
  $line_item['rate']=floatval($line_item['rate']);
 if(!empty($v['tax_id'])){
     $line_item['tax_id']=$v['tax_id'];
 }
if($module == 'purchaseorders' && !empty($v['purchase_rate'])){
  $line_item['rate']=$v['purchase_rate'];  
}     
$post['line_items'][]= $line_item;  
}   
if(!empty($post['vx_ship_entry'])){
 $post['line_items'][]=array('item_id'=>$post['vx_ship_entry'],'quantity'=>1,'rate'=>floatval($post['shipping_charge'])); 
  
}
$fields['line_items']=$post['line_items'];  
 }
 if(!empty($post['vx_ship_entry'])){
    unset($post['vx_ship_entry']);  
 unset($post['shipping_charge']);  
 }
}

if($module == 'salesorders' ){ 
  //  var_dump($post); die();
}
if($module == 'invoices' ){ //customerpayments
if(!empty($post['discount']) && empty($post['discount_type'])){
$post['discount_type']='entity_level';    
}
if(isset($post['adjustment'])){
$post['adjustment']=(float)$post['adjustment'];
}
if(isset($post['tax_total'])){
$adj= isset($post['adjustment']) ? $post['adjustment'] : 0; 
$tax=(float)$post['tax_total'];
$post['adjustment']=$tax+$adj;
}

//$post['discount_amount']=12;
//$post['discount_applied_on_amount']=48;
//$post['is_discount_before_tax']=true;
}
if(!empty($post['discount']) && !isset($post['discount_type'])){
       $post['discount_type']='entity_level'; //discount on zoho books needs this 
}
if($module == 'purchaseorders'){
  // var_dump($post); die();
// var_dump($post,$meta['order_items'],$extra); die('----------');   
}
if(isset($post['is_inclusive_tax'])){
  $post['is_inclusive_tax']= !empty($post['is_inclusive_tax']) ? true : false;  
}
//$post['currency_id']='113194000000000059';
//$post['currency_code']='USD';
//$post['currency_symbol']='$';
//$post['is_taxable']=true;
//unset($post['tax_id']);
if( $module == 'salesorders'){
//var_dump($post); die();
}
//$post['shipping_address']=array(array('address'=>'abc road lahore','city'=>'lahore','state'=>'New York','country'=>'Pakistan'));
$post=array('JSONString'=>json_encode($post));
}

if(!empty($method)){

if(!empty($meta['email_check'])){ $q['send']='true'; }
if(!empty($q)){ $path.='?'.http_build_query($q); }
$arr=$this->post_crm( $path, $method,$post);
//if($module == 'invoices'){
//var_dump($arr,$post,$path,$extra); die();
//} 
}
if(!empty($arr[$module_single][$module_single.'_id'])){
    if(isset($arr[$module_single][$module_single.'_id'])){
$id=$arr[$module_single][$module_single.'_id']; 
}
if($disable_items){
    $post=array();
     foreach($addresses as $addr_id){
   if(!empty(self::$address[$addr_id.'_address'])){
       $post[$addr_id.'_address_id']=self::$address[$addr_id.'_address'];
   }   
  }
  if(!empty($post)){
$post=array('JSONString'=>json_encode($post));
 $extra['Updating address']=$this->post_crm( 'invoices/'.$id, 'put',$post);
  }   
}
}
else if(isset($arr['message'])){
$error=$arr['message'].' - '.$arr['code'];   
$status='';       
}


}
if(!empty($id)){
   $domain=!empty($this->info['dc']) ? $this->info['dc'] : 'com'; 
   // $link='https://crm.zoho.'.$domain.'/crm/EntityInfo.do?module='.$module."&id=".$id; 
   $type=$this->info['type'] == 'invoices' ? 'invoice' : $this->info['type'];
   $module_url=str_replace('_','',$module);
   if($module == 'customerpayments'){
     $module_url='paymentsreceived';  
   }
    if($module == 'estimates'){
     $module_url='quotes';  
   }
    $link='https://'.$type.'.zoho.'.$domain.'/app#/'.$module_url.'/'.$id; 
}



return array("error"=>$error,"id"=>$id,"link"=>$link,"action"=>$action,"status"=>$status,"data"=>$fields,"response"=>$arr,"extra"=>$extra);
}

public function post_note($post,$module){
  $re=array('Title'=>'Note_Title','Body'=>'Note_Content');
    foreach($post as $k=>$v){
  if(isset($re[$k])){
   $post[$re[$k]]=$v;
   unset($post[$k]);   
  }
  }
     $post['se_module']=$module; 
return $this->post_crm('Notes','POST', json_encode(array('data'=>array($post))) );  
}
public function get_wc_items($meta){
      $_order=self::$_order;
    //  $fees=$_order->get_shipping_total();
    //  $fees=$_order-> get_total_discount();
    //  $fees=$_order-> get_total_tax();
 
     $items=$_order->get_items(); 
     $products=array();  $order_items=array(); 
if(is_array($items) && count($items)>0 ){
foreach($items as $item_id=>$item){

$sku=$img_id=$cat=''; $qty=$unit_price=$tax=$total=$cost=$cost_woo=$stock=0;
if(method_exists($item,'get_product')){
  // $p_id=$v->get_product_id();  
   $product=$item->get_product();
   if(!$product){ continue; } //product deleted but exists in line items of old order
   $total=$item->get_total();
   $total=round($total,2);
   $qty = $item->get_quantity();
   
   $tax = $item->get_total_tax();

   $title=$product->get_title();
  // $title=$item->get_name();

   $sku=$product->get_sku();     
   $unit_price=$product->get_price(); 
   $unit_price=round($unit_price,2);  
    $parent_id=$product->get_parent_id();
    $product_id=$product->get_id();
    if(method_exists($_order,'get_item_total')){
       $cost=$_order->get_item_total($item,false,true); //including woo coupon discuont
       $cost_woo=$_order->get_item_subtotal($item, false, true); // does not include coupon discounts
       $cost=round($cost,2);
       $cost_woo=round($cost_woo,2);
    }
    if(method_exists($product,'get_stock_quantity')){
   $stock=$product->get_stock_quantity();
  $img_id=$product->get_image_id(); //
  $terms = get_the_terms( $product->get_id() , 'product_cat' );
  if(!empty($terms[0]->name)){
   $cat=$terms[0]->name;   
  }
}
    
    if(empty($sku)){
        $sku='wc-'.$product_id;
    }
   if(!empty($parent_id)){
         $product_simple=new WC_Product($parent_id);
         $parent_sku=$product_simple->get_sku(); 
         if($parent_sku == $sku){
             $sku.='-'.$product_id;
         }
     
     // append variation names ,  $item->get_name() does not support more than 3 variation names
          $attrs=$product->get_attributes(); //$item->get_formatted_meta_data( '' )
            $var_info=array();
             if(is_array($attrs) && count($attrs)>0){
                 foreach($attrs as $attr_key=>$attr_val){
                    // $att_name=wc_attribute_label($attr_key,$product);
                     $term = get_term_by( 'slug', $attr_val, $attr_key );
                 if ( taxonomy_exists( $attr_key ) ) {
                $term = get_term_by( 'slug', $attr_val, $attr_key );
                if ( ! is_wp_error( $term ) && is_object( $term ) && $term->name ) {
                    $attr_val = $term->name;
                }    
            }
            if(!empty($attr_val)){
            $var_info[]=$attr_val;
            }    
                 }
             }
          if(!empty($var_info)){
          $title.=' '.implode(', ',$var_info);    
          }    
   }
   if(empty($total)){ $unit_price=0; } 
 }
 else{ //version_compare( WC_VERSION, '3.0.0', '<' )  , is_array($item) both work
          $line_item=$this->wc_get_data_from_item($item); 
   $p_id= !empty($line_item['variation_id']) ? $line_item['variation_id'] : $line_item['product_id'];
        $line_desc=array();
        if(!isset($products[$p_id])){
        $product=new WC_Product($p_id);
        }else{
         $product=$products[$p_id];   
        }
        $qty=$line_item['qty'];
        $products[$p_id]=$product;
        $sku=$product->get_sku(); 
        if(empty($sku) && !empty($line_item['product_id'])){ 
            //if variable product is empty , get simple product sku
            $product_simple=new WC_Product($line_item['product_id']);
            $sku=$product_simple->get_sku(); 
        }
        $unit_price=$product->get_price();
       // $title=$product->get_title();
       $title=$item['name'];
          }
  $temp=array('sku'=>$sku,'unit_price'=>$unit_price,'title'=>$title,'qty'=>$qty,'tax'=>$tax,'total'=>$total,'cost'=>$cost,'cost_woo'=>$cost_woo,'qty_stock'=>$stock,'img_id'=>$img_id,'cat'=>$cat);
          if(method_exists($product,'get_stock_quantity')){
   $temp['stock']=$product->get_stock_quantity();
   if(!empty($meta['tax_id'])){
if($meta['tax_id'] == 'map'){    
$item_tax=$item->get_taxes(); 
if(!empty($item_tax['total']) && !empty($meta['tax_map'])){
    foreach($item_tax['total'] as $tax_id=>$tax_val){
        $tax_rate=array_search($tax_id,$meta['tax_map']);
        if($tax_rate){ 
         $temp['tax_id']=$tax_rate;   
            break;
        }
    }   
} }else{
 $temp['tax_id']=$meta['tax_id'];   
}
}

} 

     $order_items[]=$temp;     
      }
     } 
     
   return $order_items;       
}
public function get_zoho_products($meta){ 

     $sales_response=array();  $extra=array();
     $items=$this->get_wc_items($meta);
     if(is_array($items) && count($items)>0 ){
         $n=0;  
      foreach($items as $item){
          $n++; //var_dump($item); continue;
          extract($item);
    
 $product_detail=array('price'=>$unit_price,'qty'=>$qty,'tax'=>$tax,'total'=>$total,'cost'=>$cost,'cost_woo'=>$cost_woo);
 $url='Products/search?criteria='.urlencode('((Product_Code:equals:'.$sku.'))');
 $search_response=$this->post_crm($url); 
 
 $product_id='';
 $extra['Search SKU - '.$n]=$sku; 
if(!empty($search_response['data'][0]['id'])){
  $product_id=$search_response['data'][0]['id'];  
  $extra['Search Product - '.$n]=$search_response['data'][0];
}else{
  $extra['Search Product - '.$n]=$search_response;  
}

if(empty($product_id)){ //create new product  
$path='Products';
$fields=array('Product_Name'=>$title,'Product_Code'=>$sku,'Unit_Price'=>$unit_price);  
if(!empty($qty_stock)){
   $fields['Qty_in_Stock']=$qty_stock;
} 
if(!empty($cat)){
 $fields['Product_Category']=$cat;   
}
$post=json_encode(array('data'=>array($fields)));
$arr=$this->post_crm('Products','post',$post); 

///var_dump($arr,$fields); die();
$extra['Product Post - '.$n]=$fields;
$extra['Create Product - '.$n]=$arr;

if(isset($arr['data'][0]['details']['id'])){
$product_id=$arr['data'][0]['details']['id'];


if(!empty($img_id)){
$p_url=wp_get_attachment_url( $img_id );
$url='Products/'.$product_id.'/photo';
$arr=array('attachments_v2'=>array('image.png'=>$p_url));
$extra['Product Img - '.$n]=$this->post_crm($url,'post',$arr);
}
}
if(!empty($meta['price_book']) && !empty($product_id)){ // add to price book
$price_book=$meta['price_book'];
$path='Products/'.$product_id.'/Price_Books/'.$meta['price_book']; 
$post=array('list_price'=>(float)$unit_price); 
$post=json_encode(array('data'=>array($post)));
$arr=$this->post_crm($path,'put',$post); 

$extra['Add PriceBook - '.$n]=$post.'----'.$path;
$extra['PriceBook Redult - '.$n]=$arr;  
}

//var_dump($post,$product_id,$book_post); die('--------------');
}
if(!empty($product_id)){ //create order here
$product_detail['id']=$product_id;
$sales_response[$product_id]=$product_detail;
}
 
      }
     }
   //  die('----');
     return array('res'=>$sales_response,'extra'=>$extra);
}  
      
public function get_zoho_products_invoice($meta){ 

     $sales_response=array();  $extra=array();
     $items=$this->get_wc_items($meta);
if(is_array($items) && count($items)>0 ){
foreach($items as $item){
extract($item);
///var_dump($sku,$p_id); die('------die-------');
$product_detail=array('price'=>$unit_price,'qty'=>$qty,'cost'=>$cost,'cost_woo'=>$cost_woo,'purchase_rate'=>'');

if(!empty($tax_id)){
    $product_detail['tax_id']=$tax_id;
}
//$this->info['type'] == 'books' &&
if( empty($meta['search_items_sku'])){ //books support sku search but sku is not enabled by default in books
 $url='items?name='.urlencode($title);
}else{
 $url='items?sku='.urlencode($sku);
}
 $search_response=$this->post_crm($url); 

//var_dump($search_response,$url); die();
 $product_id='';
if(!empty($search_response['items'][0]['item_id'])){
  $product_id=$search_response['items'][0]['item_id'];  
  if(!empty($search_response['items'][0]['purchase_rate'])){
   $product_detail['purchase_rate']=$search_response['items'][0]['purchase_rate']; 
  }
  $extra['Search Product - '.$sku]=$search_response['items'][0];
}else{
  $extra['Search Product - '.$sku]=$search_response;  
}

if(empty($product_id)){ //create new product
$path='Products';
$fields=array('name'=>$title,'sku'=>$sku,'rate'=>$unit_price,'product_type'=>'goods');  
if(!empty($meta['product_type'])){
   $fields['product_type']=$meta['product_type']; 
}
$post=array('JSONString'=>json_encode($fields));
$arr=$this->post_crm('items','post',$post); 

//var_dump($arr,$fields); die();
$extra['Product Post - '.$sku]=$fields;
$extra['Create Product - '.$sku]=$arr;

if(isset($arr['item']['item_id'])){
$product_id=$arr['item']['item_id']; 
}

//var_dump($post,$product_id,$book_post); die('--------------');
}
if(!empty($product_id)){ //create order here
$product_detail['id']=$product_id;
$sales_response[$product_id]=$product_detail;
}

     }
 }
  return array('res'=>$sales_response,'extra'=>$extra);
  }

public function client_info(){
      $info=$this->info;
  $client_id='1000.VFO2QGIQUKMK66057CVLZ8OM1RU9JT';
  $client_secret='feddae1bd7831d4b69e2e4d26ad2057dc8d2d1685a';
  $call_back="https://www.crmperks.com/google_auth/";
  $dc= !empty($info['dc']) ? $info['dc'] : '';
  if($dc == 'com.cn' ){
  $client_id='1000.A84IJNXYRY2U85669SF4LF76AXW9TP';
  $client_secret='817d63c5dfffa01fcc16841f9ad4f6354c017dc1e3';
  }
  if($dc == 'com.au' ){
  $client_id='1000.60USE7OKHPQO9I1QFAUF71YRRB8CIN';
  $client_secret='c009db7e715a587ca585b9beb0ceca90d4d3bc0423';
  }    
  $secret=array('eu'=>'a4e8d2c2284766a748674911a1f5ecbb0a1d7da460','in'=>'d944e3292b8377374725017d934e301f4d2f126f98');
  

  if($this->id == 'vxc_zoho'){
  
  $client_id='1000.JIR7NH735QWJ15857WRBLPYZQ96LZJ';
  $client_secret='ee5194c9cb5876a2133a03657ef01f7490529bfff4';  
   if($dc == 'com.cn' ){
  $client_id='1000.NLQL8QA4ZBPG48016W4FAJ1DDBZ5PP';
  $client_secret='0e6ad76e4ebd6bae6660bcc3908a421143644ddca0';
  }
   if($dc == 'com.au' ){
  $client_id='1000.7Y0LTS21560E41BQPS1EW24R87FOUN';
  $client_secret='a922b07758b1820c00da07448c7db801f09a5b1272';
  }
  
  $secret=array('eu'=>'f659dba19a084551da0d3d34080ac4b06b23e5b976','in'=>'09e03e8e5ead546bbd8932368cf8b2d0a9fdda2f7e');
  }else if($this->id == 'vxg_zoho'){
      
  $client_id='1000.5X3DYKDO3XDH837304FOWEEUQRIYLM';
  $client_secret='91eaa6878b6d0c77644c26a5c4c9b9da394a353e78';  
   if($dc == 'com.cn' ){
  $client_id='1000.RE0ZEM75FBOG52882KNP8GPJTGEUQP';
  $client_secret='cedf6f4dcf2d4952be21558cfbe83d1db66f12ed98';
  }
   if($dc == 'com.au' ){
  $client_id='1000.6SPXIIHITEA64DKY1YR5EQUUHA2LHN';
  $client_secret='815a7be23d3a04d7e815d17c989f17d7b79286538e';
  }
  $secret=array('eu'=>'cf65bd821349873353d3c75c747e951fb87706991a','in'=>'703fd2dd6384cdaa8fd648ba7dc63f199866fe12f0');
  }
  //custom app
  if(is_array($info)){
      
      if(!empty($info['dc']) && isset($secret[$info['dc']])){
        $client_secret=$secret[$info['dc']];  
      }
      if($this->post('custom_app',$info) == "yes" && $this->post('app_id',$info) !="" && $this->post('app_secret',$info) !="" && $this->post('app_url',$info) !=""){
     $client_id=$this->post('app_id',$info);     
     $client_secret=$this->post('app_secret',$info);     
     $call_back=$this->post('app_url',$info);     
      }
  }
  return array("client_id"=>$client_id,"client_secret"=>$client_secret,"call_back"=>$call_back);
}
public function post_crm($path,$method='get',$body=""){
$header=array();   //'content-type'=>'application/x-www-form-urlencoded' ;   

$is_file=false;
if($method == 'token'){
$method='post';   

}else{
  if($method == 'file'){
$method='get';   
$is_file=true;
}
$dc=isset($this->info['dc'])  ? $this->info['dc'] : 'com';
if(!empty($this->info['type']) && !empty($this->info['zoho_org'])){
$concat='?';
if(strpos($path,'?') !== false){
$concat='&';    
}
$path.=$concat.'organization_id='.$this->info['zoho_org'];
}
//var_dump($path);
if($this->info['type'] =='invoices'){
 $path='https://invoice.zoho.'.$dc.'/api/v3/'.$path;   
}else if($this->info['type'] =='books'){
 $path='https://books.zoho.'.$dc.'/api/v3/'.$path;   
}else if($this->info['type'] == 'inventory'){
 $path='https://inventory.zoho.'.$dc.'/api/v1/'.$path;   
}else{

$path='https://www.zohoapis.'.$dc.'/crm/v2/'.$path;     
}

$token_time=!empty($this->info['token_time']) ? $this->info['token_time'] :'';
$time=time();
$expiry=intval($token_time)+3500;   //86400
if($expiry<$time){
    $this->refresh_token(); 
}  
$access_token=!empty($this->info['access_token']) ? $this->info['access_token'] :'';
$header['Authorization']='Zoho-oauthtoken ' .$access_token; 
//$header['Content-Type']='application/json'; 
//$header[]='Authorization: Zoho-oauthtoken ' .$access_token; 
}
//var_dump($header,$path); die();

 if(!empty($body) && is_array($body)){
     $files = array(); $file_name='attachments[]';
if(!empty($body['attachments'])){
$files=$body['attachments'];
unset($body['attachments']);
}
if(!empty($body['attachments_v2'])){
$files=$body['attachments_v2'];
unset($body['attachments_v2']);
$file_name='file';
}
$boundary = wp_generate_password( 24 );
$delimiter = '-------------' . $boundary;
$header['Content-Type']='multipart/form-data; boundary='.$delimiter;
$body = $this->build_data_files($boundary, $body, $files,$file_name);
}

$args=array(
  'method' => strtoupper($method),
  'timeout' => $this->timeout,
  'headers' => $header,
 'body' => $body
  );
$response = wp_remote_request( $path , $args); 

$body = wp_remote_retrieve_body($response);
//var_dump($body,$path);

  if(is_wp_error($response)) { 
  $error = $response->get_error_message();
  return array('error'=>$error);
  }else{
 if($is_file){
$body=array('file'=>$body);
if(!empty($response['headers']['content-disposition'])){
$pos=strpos($response['headers']['content-disposition'],"''");
$body['title']=substr($response['headers']['content-disposition'],$pos+2);     
}
 }else{     
 $body=json_decode($body,true);     
  } }
  return $body;
}
public function build_data_files($boundary, $fields, $files, $file_name='attachments[]'){
    $data = '';
    $eol = "\r\n";

    $delimiter = '-------------' . $boundary;

    foreach ($fields as $name => $content) {
        $data .= "--" . $delimiter . $eol
            . 'Content-Disposition: form-data; name="' . $name . "\"".$eol.$eol
            . $content . $eol;
    }

    foreach ($files as $name => $file) {
    $name=basename($file);
   $content = file_get_contents($file);
        $data .= "--" . $delimiter . $eol
            . 'Content-Disposition: form-data; name="'.$file_name.'"; filename="'.$name.'"' . $eol
            //. 'Content-Type: image/png'.$eol
            . 'Content-Transfer-Encoding: binary'.$eol;

        $data .= $eol;
        $data .= $content . $eol;
    }
    $data .= "--" . $delimiter . "--".$eol;


    return $data;
}
  
public function get_entry($module,$id){
$arr=$this->post_crm($module.'/'.$id);
 $entry=array();
if(!empty($arr['data'][0]) && is_array($arr['data'][0])){
    $entry=$arr['data'][0];
}
return $entry;     
}

}
}
?>