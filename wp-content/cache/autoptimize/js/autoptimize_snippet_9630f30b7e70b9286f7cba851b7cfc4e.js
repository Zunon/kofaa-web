document.addEventListener('wpcf7mailsent',function(event){var currentfid=event.detail.contactFormId;var pid=event.detail.apiResponse.popup_id;if(event.detail.apiResponse.popup_background_option=="bg_color")
{var ccode=event.detail.apiResponse.popup_background_color;}
if(event.detail.apiResponse.popup_background_option==="gradient_color")
{var ccode='linear-gradient('+event.detail.apiResponse.popup_gradient_color+','+event.detail.apiResponse.popup_gradient_color1+')';}
if(event.detail.apiResponse.popup_background_option==="image")
{var ccode='  url("'+event.detail.apiResponse.popup_image_color+'")right center / cover no-repeat';}
if(pid!=null&&pid!=''){swal({background:ccode,title:'<span style="color:'+event.detail.apiResponse.popup_text_color+'">'+event.detail.apiResponse.popup_message+'</span>',confirmButtonColor:event.detail.apiResponse.popup_button_background_color,confirmButtonText:'<span style="color:'+event.detail.apiResponse.popup_text_color+'">'+event.detail.apiResponse.popup_button_text+'</span>',width:event.detail.apiResponse.m_popup_width,timer:event.detail.apiResponse.m_popup_duration,})
jQuery('.swal2-modal').css('border-radius',event.detail.apiResponse.m_popup_radius+"px");}},false);document.addEventListener('wpcf7submit',function(event){var currentfid=event.detail.contactFormId;var popup_id=event.detail.apiResponse.popup_id;var fpid=event.detail.apiResponse.failure_popup_id;var fstatus=event.detail.apiResponse.status;var fmessage=event.detail.apiResponse.message;if(event.detail.apiResponse.failure_popup_background_option=="bg_color")
{var ccode=event.detail.apiResponse.failure_popup_background_color;}
if(event.detail.apiResponse.failure_popup_background_option==="gradient_color")
{var ccode='linear-gradient('+event.detail.apiResponse.failure_popup_gradient_color+','+event.detail.apiResponse.failure_popup_gradient_color1+')';}
if(event.detail.apiResponse.failure_popup_background_option==="image")
{var ccode='  url("'+event.detail.apiResponse.failure_popup_image_color+'")right center / cover no-repeat';}
if((fpid!=null&&fpid!='')&&(fstatus=='validation_failed'||fstatus=='acceptance_missing'||fstatus=='spam'||fstatus=='aborted'||fstatus=='mail_failed')){swal({background:ccode,title:'<span style="color:'+event.detail.apiResponse.failure_popup_text_color+'">'+fmessage+'</span>',confirmButtonColor:event.detail.apiResponse.failure_popup_button_background_color,confirmButtonText:'<span style="color:'+event.detail.apiResponse.failure_popup_text_color+'">'+event.detail.apiResponse.failure_popup_button_text+'</span>',width:event.detail.apiResponse.failure_popup_width,timer:event.detail.apiResponse.failure_popup_duration,})
jQuery('.swal2-modal').css('border-radius',event.detail.apiResponse.failure_popup_radius+"px");}},false);