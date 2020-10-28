<?php
if ( ! defined( 'ABSPATH' ) ) {
     exit;
 }                                         
 ?>
<div class="vx_div">
      <div class="vx_head">
<div class="crm_head_div"> <?php _e('3. Map Form Fields to Zoho Fields.',  'woo-zoho' ); ?></div>
<div class="crm_btn_div"><i class="fa crm_toggle_btn fa-minus" title="<?php _e('Expand / Collapse','woo-zoho') ?>"></i></div>
<div class="crm_clear"></div> 
  </div>

  <div class="vx_group  fields_div" style="padding: 0px; border-width: 0px; background: transparent;">
<?php
 $req_span=" <span class='vx_red vx_required'>(".__('Required','woo-zoho').")</span>";
 $req_span2=" <span class='vx_red vx_required vx_req_parent'>(".__('Required','woo-zoho').")</span>";
  foreach($map_fields as $k=>$v){
    if(isset($skipped_fields[$k])){
        continue;
    }
      
  $req=$this->post('req',$v);
  $v['type']=ucfirst($v['type']);

  $sel_val=isset($map[$k]['field']) ? $map[$k]['field'] : ""; 
    $val_type=isset($map[$k]['type']) && !empty($map[$k]['type']) ? $map[$k]['type'] : "field";  
  $options=$this->wc_select($sel_val);  
  $display="none"; $btn_icon="fa-plus";
  if(isset($map[$k][$val_type]) && !empty($map[$k][$val_type])){
    $display="block"; 
    $btn_icon="fa-minus";   
  }

  $req_html=$req == "true" ? $req_span : "";
 ?> 
<div class="crm_panel crm_panel_100">
<div class="crm_panel_head2 ">
<div class="crm_head_div"><span class="crm_head_text"> <?php echo $v['label']?></span>
<?php echo $req_html; ?>
</div>
<div class="crm_btn_div">
<?php
 if(isset($v['name_c']) || ($api_type != 'web' && $req != 'true')){   
?>
<i class="vx_icons vx_remove_btn vx_remove_custom fa fa-trash-o" title="<?php _e('Delete','woo-zoho'); ?>"></i>
<?php } ?>
<i class="fa crm_toggle_btn vx_btn_inner <?php echo $btn_icon ?>" title="<?php _e('Expand / Collapse','woo-zoho') ?>"></i>

</div>
<div class="crm_clear"></div> </div>
<div class="more_options crm_panel_content " style="display: <?php echo $display ?>;">
  <?php if(!isset($v['name_c'])){ ?>

  <div class="crm-panel-description">
  <span class="crm-desc-name-div"><?php echo __('Name:','woo-zoho')." ";?><span class="crm-desc-name"><?php echo $v['name']; ?></span> </span>
  <?php if($this->post('type',$v) !=""){ ?>
    <span class="crm-desc-type-div">, <?php echo __('Type:','woo-zoho')." ";?><span class="crm-desc-type"><?php echo $v['type'] ?></span> </span>
<?php
   }
  if($this->post('maxlength',$v) !=""){ 
   ?>
   <span class="crm-desc-len-div">, <?php echo __('Max Length:','woo-zoho')." ";?><span class="crm-desc-len"><?php echo $v['maxlength']; ?></span> </span>
  <?php 
  }
   if($this->post('eg',$v) !=""){ 
   ?>
   <span class="crm-eg-div">, <?php echo __('e.g:','woo-zoho')." ";?><span class="crm-eg"><?php echo $v['eg']; ?></span> </span>
  <?php 
  }
  ?>
   </div> 
  <?php
  }
  ?>
<div class="vx_margin">
<?php
    if(isset($v['name_c'])){
?>
<div class="entry_row">
<div class="entry_col1 vx_label"><?php _e('Field API Name','woo-zoho') ?></div>
<div class="entry_col2">
<input type="text" name="meta[map][<?php echo $k ?>][name_c]" value="<?php echo $v['name_c'] ?>" placeholder="<?php _e('Field API Name','woo-zoho') ?>" class="vx_input_100">
</div>
<div class="crm_clear"></div>
</div> 
<?php             
    }
?>
<div class="entry_row">
<div class="entry_col1 vx_label"><label for="vx_type_<?php echo $k ?>"><?php _e('Field Type','woo-zoho') ?></label></div>
<div class="entry_col2">
<select name='meta[map][<?php echo $k ?>][type]' id="vx_type_<?php echo $k ?>"  class='vxc_field_type vx_input_100'>
<?php
  foreach($sel_fields as $f_key=>$f_val){
  $select="";
  if($this->post2($k,'type',$map) == $f_key)
  $select='selected="selected"';
  ?>
  <option value="<?php echo $f_key ?>" <?php echo $select ?>><?php echo $f_val?></option>    
  <?php } ?> 
</select>
</div>
<div class="crm_clear"></div>
</div>  
 
<div class="entry_row entry_row2">
<div class="entry_col1 vx_label">

<div class="vx_label vxc_fields vxc_field_" style="<?php if($this->post2($k,'type',$map) != ''){echo 'display:none';} ?>">
<label for="vx_field_<?php echo $k ?>"><?php _e('Select Field','woo-zoho') ?></label>
</div>

<div class="vxc_fields vxc_field_custom" style="<?php if($this->post2($k,'type',$map) != 'custom'){echo 'display:none';} ?>">
<label for="vx_custom_<?php echo $k ?>"> <?php _e('Custom Field','woo-zoho') ?></label>
</div>

<div class="vxc_fields vxc_field_value" style="<?php if($this->post2($k,'type',$map) != 'value'){echo 'display:none';} ?>">
<label for="vx_value_<?php echo $k ?>"> <?php _e('Custom Value','woo-zoho') ?></label>
</div>

</div>

<div class="entry_col2">


<div class="vxc_fields vxc_field_custom" style="<?php if($this->post2($k,'type',$map) != 'custom'){echo 'display:none';} ?>">
<input type="text" name='meta[map][<?php echo $k?>][custom]' id="vx_custom_<?php echo $k ?>"  value='<?php echo $this->post2($k,'custom',$map)?>' placeholder='<?php _e("Custom Field Name",'woo-zoho')?>' class='vx_input_100' >
</div>

<div class="vxc_fields vxc_field_value" style="<?php if($this->post2($k,'type',$map) != 'value'){echo 'display:none';} ?>">
<input type="text" name='meta[map][<?php echo $k?>][value]'  id="vx_value_<?php echo $k ?>" value='<?php echo $this->post2($k,'value',$map)?>' placeholder='<?php _e("Custom Value",'woo-zoho')?>' class='vx_input_100 vxc_field_input'>
<div class="howto"><?php echo sprintf(__('You can add a form field %s in custom value from following form fields','woo-zoho'),'<code>{field_id}</code>')?></div>
</div>

<div class="vxc_fields vxc_field_ vxc_field_standard" style="<?php if($this->post2($k,'type',$map) == 'custom'){echo 'display:none';} ?>">
<select name="meta[map][<?php echo $k ?>][field]"  id="vx_field_<?php echo $k ?>" class="vxc_field_option vx_input_100">
<?php echo $options ?>
</select>
</div>


</div> 

<div class="crm_clear"></div>
</div> 




  </div></div>
  <div class="clear"></div>
  </div>
  <?php
  }

  ?>
<div id="vx_field_temp" style="display:none">
<div class="crm_panel crm_panel_100 vx_fields">
<div class="crm_panel_head2">
<div class="crm_head_div"><span class="crm_head_text">  <label class="crm_text_label"><?php _e('Custom Field','woo-zoho');?></label></span></div>
<div class="crm_btn_div">
<i class="vx_icons vx_remove_btn vx_remove_custom fa fa-trash-o" data-tip="<?php _e('Delete','woo-zoho'); ?>"></i>
<i class="fa crm_toggle_btn vx_btn_inner fa-minus " title="<?php _e('Expand / Collapse','woo-zoho') ?>"></i>
</div>
<div class="crm_clear"></div> </div>
<div class="more_options crm_panel_content" style="display: block;">


  <div class="crm-panel-description">
  <span class="crm-desc-name-div"><?php echo __('Name:','woo-zoho')." ";?><span class="crm-desc-name"></span> </span>
  <span class="crm-desc-type-div">, <?php echo __('Type:','woo-zoho')." ";?><span class="crm-desc-type"></span> </span>
  <span class="crm-desc-len-div">, <?php echo __('Max Length:','woo-zoho')." ";?><span class="crm-desc-len"></span> </span>
  <span class="crm-eg-div">, <?php echo __('e.g:','woo-zoho')." ";?><span class="crm-eg"></span> </span>

   </div> 


<div class="vx_margin">

<div class="entry_row">
<div class="entry_col1 vx_label"><?php _e('Field Type','woo-zoho') ?></div>
<div class="entry_col2">
<select name='type' class='vxc_field_type vx_input_100'>
<?php
  foreach($sel_fields as $f_key=>$f_val){
  ?>
  <option value="<?php echo $f_key ?>"><?php echo $f_val?></option>    
  <?php } ?> 
</select>
</div>
<div class="crm_clear"></div>
</div>  

<div class="entry_row entry_row2">
<div class="entry_col1 vx_label">

<div class="vx_label vxc_fields vxc_field_">
<label><?php _e('Select Field','woo-zoho') ?></label>
</div>

<div class="vxc_fields vxc_field_custom" style="display:none;">
<label> <?php _e('Custom Field','woo-zoho') ?></label>
</div>

<div class="vxc_fields vxc_field_value" style="display:none;">
<label> <?php _e('Custom Value','woo-zoho') ?></label>
</div>

</div>

<div class="entry_col2">

<div class="vxc_fields vxc_field_custom" style="display:none;">
<input type="text" name='custom'   value='' placeholder='<?php _e("Custom Field Name",'woo-zoho')?>' class='vx_input_100' >
</div>

<div class="vxc_fields vxc_field_value" style="display:none">
<input type="text" name="value"  value="" placeholder='<?php _e("Custom Value",'woo-zoho')?>' class='vx_input_100 vxc_field_input'>
<div class="howto"><?php echo sprintf(__('You can add a form field %s in custom value from following form fields','woo-zoho'),'<code>{field_id}</code>')?></div>
</div>

<div class="vxc_fields vxc_field_ vxc_field_standard">
<select name="field" class="vxc_field_option vx_input_100">
<?php echo $this->wc_select();  ?>
</select>
</div>


</div> 

<div class="crm_clear"></div>
</div>   
 
  </div></div>
  <div class="clear"></div>
  </div>
  
  </div>

<div class="crm_panel crm_panel_100 vx_fields">
<div class="crm_panel_head2">
<div class="crm_head_div"><span class="crm_head_text">  <label class="crm_text_label"><?php _e('Add New Field','woo-zoho');?></label></span></div>
<div class="crm_btn_div"><i class="fa crm_toggle_btn vx_btn_inner fa-minus" style="display: none;" title="<?php _e('Expand / Collapse','woo-zoho') ?>"></i></div>
<div class="crm_clear"></div> </div>
<div class="more_options crm_panel_content" style="display: block;">

<div class="vx_margin">

<div class="vx_tr">
<div class="vx_td">
<select id="vx_add_fields_select" class="vx_input_100" style="width: 100%" autocomplete="off">
<option value=""></option>
<?php
$json_fields=array();
 foreach($fields as $k=>$v){
     $v['type']=ucfirst($v['type']);
     if(!empty($v['options'])){
       $op=array(); 
       foreach($v['options'] as $o){
           $op[$o['value']]=isset($o['label']) ? $o['label'] :  $o['value'];
       } 
       $v['options']=$op; 
     }
     $json_fields[$k]=$v;
   $disable='';
   if(isset($map_fields[$k]) || isset($skipped_fields[$k])){ 
    $disable='disabled="disabled"';   
   } 
echo "<option value='{$k}' {$disable} >{$v['label']}</option>";   
} ?>
</select>
</div>
<div class="vx_td2">
 <button type="button" class="button button-default" style="vertical-align: middle;" id="xv_add_custom_field"><i class="fa fa-plus-circle" ></i> <?php _e('Add Field','woo-zoho')?></button>
 </div>
</div> 
<div class="entry_row vxc_fields vxc_field_custom" style="text-align: center;">
 
</div> 

<i class="vx_icons-h  vx vx-bin-2" data-tip="Delete"></i>    
 
  </div></div>
  <div class="clear"></div>
</div>
<script type="text/javascript">
var crm_fields=<?php echo json_encode($json_fields); ?>;
</script> 

  </div> 
 <!---fields end--->
  </div>
<div class="vx_div ">
    <div class="vx_head ">
<div class="crm_head_div"> <?php _e('4. When to Send the Order to Zoho.',  'woo-zoho' ); ?></div>
<div class="crm_btn_div"><i class="fa crm_toggle_btn fa-minus" title="<?php _e('Expand / Collapse','woo-zoho') ?>"></i></div>
<div class="crm_clear"></div> 
  </div> 
  <div class="vx_group ">
  <div class="vx_row">
  <div class="vx_col1">
  <label for="vxc_event"><?php _e('Select Event','woo-zoho'); $this->tooltip($tooltips['manual_export']); ?></label>
  </div>
  <div class="vx_col2">
  <select id="vxc_event" name="meta[event]" class="vx_sel" autocomplete="off">
  <?php  
  foreach($events as $f_key=>$f_val){
  $select="";
  if($feed['event'] == $f_key)
  $select='selected="selected"';
  echo '<option value="'.$f_key.'" '.$select.'>'.$f_val.'</option>';    
  }    
  ?>
  </select> 
</div>
<div class="clear"></div>
</div>
  <div style="margin-top: 10px; padding-top: 10px; border-top: 1px dashed #ccc">
  <div class="vx_row">
  <div class="vx_col1">
  <label for="crm_optin"><?php _e('Custom Filter', 'woo-zoho'); $this->tooltip($tooltips['optin_condition']);?></label>
  </div>
  <div class="vx_col2">
  <input type="checkbox" style="margin-top: 0px;" id="crm_optin" class="crm_toggle_check" name="meta[optin_enabled]" value="1" <?php echo !empty($feed['optin_enabled']) ? 'checked="checked"' : ''?> autocomplete="off"/>
    <label for="crm_optin"><?php _e('Enable', 'woo-zoho'); ?></label>
  
  </div>
  <div class="clear"></div>
  </div>
  <div id="crm_optin_div" style="margin: 10px auto; width: 90%;<?php echo empty($feed['optin_enabled']) ? 'display:none' : ''?>">
  
        <div>
            <?php
            $sno=0; 
                foreach($filters as $filter_k=>$filter_v){
  $sno++;
                    ?>
  <div class="vx_filter_or" data-id="<?php echo $filter_k ?>"> 
  <?php if($sno>1){ ?>
  <div class="vx_filter_label">OR</div>
  <?php } ?>                 
  <div class="vx_filter_div">
  <?php
  if(is_array($filter_v)){
  $sno_i=0;
  foreach($filter_v as $s_k=>$s_v){   
  $sno_i++;
  
  ?> 
      <div class="vx_filter_and">
      <?php if($sno_i>1){ ?>
  <div class="vx_filter_label">AND</div>
  <?php } ?>   
     <div class="vx_filter_field vx_filter_field1">    
     <select id="crm_optin_field" name="meta[filters][<?php echo $filter_k ?>][<?php echo $s_k ?>][field]"><?php 
  echo $this->wc_select($this->post('field',$s_v));
      ?></select></div>
       <div class="vx_filter_field vx_filter_field2">   
    <select name="meta[filters][<?php echo $filter_k ?>][<?php echo $s_k ?>][op]" >
    <?php
       foreach($vx_op as $k=>$v){
  $sel="";
  if($this->post('op',$s_v) == $k)
  $sel='selected="selected"';
         echo "<option value='".$k."' $sel >".$v."</option>";
     } 
    ?>
            </select></div>
             <div class="vx_filter_field vx_filter_field3">    
           <input type="text" class="vxc_filter_text" placeholder="<?php _e('Value','woo-zoho') ?>" value="<?php echo $this->post('value',$s_v) ?>" name="meta[filters][<?php echo $filter_k ?>][<?php echo $s_k ?>][value]"> 
            </div>
                <?php if( $sno_i>1){ ?> 
  <div class="vx_filter_field vx_filter_field4"><i class="vx_icons-h vx_trash_and fa fa-trash-o"></i></div>
           <?php } ?>
           <div style="clear: both;"></div> 
           </div>
           <?php
  } }
           ?>
           <div class="vx_btn_div">
           <button class="button button-default button-small vx_add_and"><i class="vx_trash_and fa fa-hand-o-right"></i> <?php _e('Add AND Filter','woo-zoho') ?></button>
           <?php if($sno>1){ ?>
  <i class="vx_icons-h fa fa-trash-o vx_trash_or"></i>
  <?php } ?> 
        
           </div>
        </div>
        </div>
                    <?php
                }
            ?>
  
          <div class="vx_btn_div">
  <button class="button button-default  vx_add_or"><i class="vx_trash_and fa fa-check"></i> <?php _e('Add OR Filter','woo-zoho') ?></button></div>
        </div>
    </div>
  <div style="display: none;" id="vx_filter_temp">
  <div class="vx_filter_or"> 
  <div class="vx_filter_label">OR</div>
  <div class="vx_filter_div"> 
      <div class="vx_filter_and">  
      <div class="vx_filter_label vx_filter_label_and">AND</div> 
     <div class="vx_filter_field vx_filter_field1">    
     <select id="crm_optin_field" name="field" class='optin_selecta'><?php 
    echo $this->wc_select($this->post('field',$s_v));
      ?></select></div>
       <div class="vx_filter_field vx_filter_field2">    
    <select name="op" >
    <?php
       foreach($vx_op as $k=>$v){
  
         echo "<option value='".$k."' >".$v."</option>";
     } 
    ?>
            </select></div>
             <div class="vx_filter_field vx_filter_field3">    
           <input type="text" class="vxc_filter_text" placeholder="<?php _e('Value','woo-zoho') ?>" name="value"> 
            </div>
           <div class="vx_filter_field vx_filter_field4"><i class="vx_icons-h vx_trash_and fa fa-trash-o"></i></div>
           <div style="clear: both;"></div> 
           </div>
           <div class="vx_btn_div">
           <button class="button button-default button-small vx_add_and"><i class=" vx_trash_and fa fa-hand-o-right"></i> <?php _e('Add AND Filter','woo-zoho') ?></button>
           <i class="vx_icons-h vx_trash_and fa fa-trash-o vx_trash_or"></i>
           </div>
        </div>
        </div>
        </div>
  <?php

             $settings=get_option($this->type.'_settings',array());
         if(!empty($settings['notes']) && $type == ''){ 
  ?>
    <div style="margin-top: 10px; padding-top: 10px; border-top: 1px dashed #ccc">
  <div class="vx_row">
  <div class="vx_col1">
  <label for="vx_notes"><?php _e('Order Notes', 'woo-zoho'); $this->tooltip($tooltips['vx_order_notes']);?></label>
  </div>
  <div class="vx_col2">
  <input type="checkbox" style="margin-top: 0px;" id="vx_notes" class="crm_toggle_check" name="meta[order_notes]" value="1" <?php echo !empty($feed['order_notes']) ? 'checked="checked"' : ''?> autocomplete="off"/>
    <label for="vx_notes"><?php _e('Add / Delete Notes to Zoho when added / deleted in WooCommerce', 'woo-zoho'); ?></label>
  
  </div>
  <div class="clear"></div>
  </div></div>
  <?php
         } 
  ?>
      
  </div>  
  </div>  
  </div>  
  <?php
  $panel_count=4;
  $panel_count++;
  ?>     
  <div class="vx_div "> 
  <div class="vx_head ">
<div class="crm_head_div"> <?php  echo sprintf(__('%s. Choose Primary Key.',  'woo-zoho' ),$panel_count); ?></div>
<div class="crm_btn_div"><i class="fa crm_toggle_btn fa-minus" title="<?php _e('Expand / Collapse','woo-zoho') ?>"></i></div>
<div class="crm_clear"></div> 
  </div>                    
  <div class="vx_group ">
  <div class="vx_row">
  <div class="vx_col1">
  <label for="crm_primary_field"><?php _e('Select Primary Key','%dd%') ?></label>
  </div><div class="vx_col2">
  <select id="crm_primary_field" name="meta[primary_key]" class="vx_sel" autocomplete="off">
  <?php echo $this->crm_select($fields,$feed['primary_key']); ?>
  </select> 
  <div class="description" style="float: none; width: 90%"><?php _e('If you want to update a pre-existing object, select what should be used as a unique identifier ("Primary Key"). For example, this may be an email address, lead ID, or address. When a new order comes in with the same "Primary Key" you select, a new object will not be created, instead the pre-existing object will be updated.', '%dd%'); ?></div>
  </div>
  <div class="clear"></div>
  </div>
  
  <div class="vx_row">
  <div class="vx_col1">
  <label for="vx_update">
  <?php _e('Update Entry', '%dd%'); ?>
  </label>
  </div>
  <div class="vx_col2">
  <input type="checkbox" style="margin-top: 0px;" id="vx_update" class="crm_toggle_check" name="meta[update]" value="1" <?php echo !empty($feed['update']) ? "checked='checked'" : ""?>/>
  <label for="vx_update">
  <?php _e('Do not update entry, if already exists', '%dd%'); ?>
  </label>
  </div>
  <div style="clear: both;"></div>
  </div>
<?php
      if($module == 'Leads'){
          ?>
             <div class="vx_row">
  <div class="vx_col1">
  <label for="vx_convert">
  <?php _e('Convert Lead ', 'woo-zoho'); ?>
  </label>
  </div>
  <div class="vx_col2">
  <input type="checkbox" style="margin-top: 0px;" id="vx_convert" class="crm_toggle_check" name="meta[convert]" value="1" <?php echo !empty($feed['convert']) ? "checked='checked'" : ""?>/>
  <label for="vx_update">
  <?php _e('If Lead found, convert to Contact, otherwise ignore the lead.', 'woo-zoho'); ?>
  </label>
  </div>
  <div style="clear: both;"></div>
  </div>
          <?php
      }
  ?>  
  
    <div class="vx_row">
  <div class="vx_col1">
  <label for="vx_update">
  <?php _e('Repeat Feed ', 'woo-zoho'); ?>
  </label>
  </div>
  <div class="vx_col2">
  <input type="checkbox" style="margin-top: 0px;" id="vx_update" class="crm_toggle_check" name="meta[each_line]" value="1" <?php echo !empty($feed['each_line']) ? "checked='checked'" : ""?>/>
  <label for="vx_update">
  <?php _e('Repeat this feed for each line item of an Order', 'woo-zoho'); ?>
  </label>
  </div>
  <div style="clear: both;"></div>
  </div>
  
  </div>

  </div>
<?php
if($type == ''){
?>
<div class="vx_div">
     <div class="vx_head">
<div class="crm_head_div"> <?php echo sprintf(__('%s. Add Note.', 'woo-zoho'),$panel_count+=1); ?></div>
<div class="crm_btn_div" title="<?php _e('Expand / Collapse','woo-zoho') ?>"><i class="fa crm_toggle_btn fa-minus"></i></div>
<div class="crm_clear"></div> 
  </div>


  <div class="vx_group">

    <div class="vx_row">
  <div class="vx_col1">
  <label for="crm_note">
  <?php _e("Add Note", 'woo-zoho'); ?>
  <?php $this->tooltip($tooltips["vx_entry_note"]) ?>
  </label>
  </div>
  <div class="vx_col2">
  <input type="checkbox" style="margin-top: 0px;" id="crm_note" class="crm_toggle_check" name="meta[note_check]" value="1" <?php echo !empty($feed['note_check']) ? "checked='checked'" : ""?>/>
  <label for="crm_note_div">
  <?php _e("Enable", 'woo-zoho'); ?>
  </label>
  </div>
  <div style="clear: both;"></div>
  </div>
  <div id="crm_note_div" style="margin-top: 16px; <?php echo empty($feed["note_check"]) ? "display:none" : ""?>">
  <div class="vx_row">
  <div class="vx_col1">
  <label for="crm_note_fields">
  <?php _e( 'Note Fields', 'woo-zoho' ); $this->tooltip($tooltips["vx_note_fields"]) ?>
  </label>
  </div>
   <div class="vx_col2 entry_col2" style="width: 70%;">
  <textarea name="meta[note_val]"  placeholder="<?php _e("{field-id} text",'woo-zoho')?>" class="vx_input_100 vxc_field_input" style="height: 60px"><?php
  if(!empty($feed['note_fields']) && is_array($feed['note_fields'])){
          $feed['note_val']='{'.implode("}\n{",$feed['note_fields'])."}";
}
   echo $this->post('note_val',$feed); ?></textarea>
<div class="howto"><?php echo sprintf(__('You can add a form field %s in custom value from following form fields','woo-zoho'),'<code>{field_id}</code>')?></div>

<select name="field"  class="vxc_field_option vx_input_100">
<?php echo $options ?>
</select>
   </div>
  <div style="clear: both;"></div>
  </div>
  
  <div class="vx_row">
  <div class="vx_col1">
  <label for="crm_disable_note">
  <?php _e( 'Disable Note', 'woo-zoho' ); $this->tooltip($tooltips["vx_disable_note"]) ?>
  </label>
  </div>
  <div class="vx_col2">
  
  <input type="checkbox" style="margin-top: 0px;" id="crm_disable_note" class="crm_toggle_check" name="meta[disable_entry_note]" value="1" <?php echo !empty($feed['disable_entry_note']) ? "checked='checked'" : ""?>/>
  <label for="crm_disable_note">
  <?php _e('Do not Add Note if entry already exists in Zoho', 'woo-zoho'); ?>
  </label>
    
   </div>
  <div style="clear: both;"></div>
  </div>
  
  </div>
  
  </div>
  </div>  
  <!-------------------------- lead owner -------------------->
<?php 
    }
$file=vxc_zoho::$path.'pro/pro-mapping.php';
if(vxc_zoho::$is_pr && file_exists($file)){
include_once($file);
}  
 do_action('vx_plugin_upgrade_notice_plugin_'.$this->type);