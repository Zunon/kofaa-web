tribe.events=tribe.events||{};tribe.events.views=tribe.events.views||{};tribe.events.views.manager={};(function($,_,obj){'use strict';var $document=$(document);var $window=$(window);obj.selectors={container:'[data-js="tribe-events-view"]',form:'[data-js="tribe-events-view-form"]',link:'[data-js="tribe-events-view-link"]',dataScript:'[data-js="tribe-events-view-data"]',loader:'.tribe-events-view-loader',hiddenElement:'.tribe-common-a11y-hidden',};obj.doingPopstate=false;obj.currentAjaxRequest=null;obj.$lastContainer=$();obj.$containers=$();obj.cleanup=function(container){var $container=$(container);var $form=$container.find(obj.selectors.form);var $data=$container.find(obj.selectors.dataScript);var data={};if($data.length){data=JSON.parse($.trim($data.text()));}
$container.trigger('beforeCleanup.tribeEvents',[$container,data]);$container.find(obj.selectors.link).off('click.tribeEvents',obj.onLinkClick);if($form.length){$form.off('submit.tribeEvents',obj.onSubmit);}
$container.trigger('afterCleanup.tribeEvents',[$container,data]);};obj.setup=function(index,container){var $container=$(container);var $form=$container.find(obj.selectors.form);var $data=$container.find(obj.selectors.dataScript);var data={};if($data.length){data=JSON.parse($.trim($data.text()));}
$container.trigger('beforeSetup.tribeEvents',[index,$container,data]);$container.find(obj.selectors.link).on('click.tribeEvents',obj.onLinkClick);if($form.length){$form.on('submit.tribeEvents',obj.onSubmit);}
$container.trigger('afterSetup.tribeEvents',[index,$container,data]);};obj.getContainer=function(element){var $element=$(element);if(!$element.is(obj.selectors.container)){return $element.parents(obj.selectors.container).eq(0);}
return $element;};obj.getContainerData=function($container){var $data=$container.find(obj.selectors.dataScript);if(!$data.length){return;}
var data=JSON.parse($.trim($data.text()));return data;};obj.shouldManageUrl=function($container){var shouldManageUrl=$container.data('view-manage-url');var tribeIsTruthy=/^(true|1|on|yes)$/;if(typeof shouldManageUrl===typeof undefined){shouldManageUrl=true;}else{shouldManageUrl=tribeIsTruthy.test(String(shouldManageUrl));}
return shouldManageUrl;};obj.updateUrl=function($container){if(obj.doingPopstate){return;}
if(!obj.shouldManageUrl($container)){return;}
var $data=$container.find(obj.selectors.dataScript);if(!$data.length){return;}
var data=JSON.parse($.trim($data.text()));if(!_.isObject(data)){return;}
if(_.isUndefined(data.url)){return;}
if(_.isUndefined(data.title)){return;}
document.title=data.title;window.history.pushState(null,data.title,data.url);};obj.onLinkClick=function(event){var $container=obj.getContainer(this);$container.trigger('beforeOnLinkClick.tribeEvents',event);event.preventDefault();var $link=$(this);var url=$link.attr('href');var currentUrl=window.location.href;var nonce=$link.data('view-rest-nonce');var shouldManageUrl=obj.shouldManageUrl($container);var shortcodeId=$container.data('view-shortcode');if(!nonce){nonce=$container.data('view-rest-nonce');}
var data={prev_url:encodeURI(decodeURI(currentUrl)),url:encodeURI(decodeURI(url)),should_manage_url:shouldManageUrl,_wpnonce:nonce,};if(shortcodeId){data['shortcode']=shortcodeId;}
obj.request(data,$container);$container.trigger('afterOnLinkClick.tribeEvents',event);return false;};obj.onSubmit=function(event){var $container=obj.getContainer(this);$container.trigger('beforeOnSubmit.tribeEvents',event);event.preventDefault();var $form=$(this);var nonce=$container.data('view-rest-nonce');var formData=Qs.parse($form.serialize());var data={view_data:formData['tribe-events-views'],_wpnonce:nonce,};obj.request(data,$container);$container.trigger('afterOnSubmit.tribeEvents',event);return false;};obj.onPopState=function(event){var target=event.originalEvent.target;var url=target.location.href;var $container=obj.getLastContainer();if(!$container){return false;}
if(obj.currentAjaxRequest){obj.currentAjaxRequest.abort();}
obj.doingPopstate=true;$container.trigger('beforePopState.tribeEvents',event);var nonce=$container.data('view-rest-nonce');var data={url:url,_wpnonce:nonce,};obj.request(data,$container);return false;};obj.request=function(data,$container){var settings=obj.getAjaxSettings($container);var shouldManageUrl=obj.shouldManageUrl($container);var containerData=obj.getContainerData($container);if(!data.url){data.url=containerData.url;}
if(!data.prev_url){data.prev_url=containerData.prev_url;}
data.should_manage_url=shouldManageUrl;settings.data=data;obj.currentAjaxRequest=$.ajax(settings);};obj.getAjaxSettings=function($container){var ajaxSettings={url:$container.data('view-rest-url'),accepts:'html',dataType:'html',method:'GET','async':true,beforeSend:obj.ajaxBeforeSend,complete:obj.ajaxComplete,success:obj.ajaxSuccess,error:obj.ajaxError,context:$container,};return ajaxSettings;};obj.ajaxBeforeSend=function(jqXHR,settings){var $container=this;var $loader=$container.find(obj.selectors.loader);$container.trigger('beforeAjaxBeforeSend.tribeEvents',[jqXHR,settings]);if($loader.length){$loader.removeClass(obj.selectors.hiddenElement.className());}
$container.trigger('afterAjaxBeforeSend.tribeEvents',[jqXHR,settings]);};obj.ajaxComplete=function(jqXHR,textStatus){var $container=this;var $loader=$container.find(obj.selectors.loader);$container.trigger('beforeAjaxComplete.tribeEvents',[jqXHR,textStatus]);if($loader.length){$loader.addClass(obj.selectors.hiddenElement.className());}
$container.trigger('afterAjaxComplete.tribeEvents',[jqXHR,textStatus]);if(obj.doingPopstate){obj.doingPopstate=false;}
obj.currentAjaxRequest=null;};obj.ajaxSuccess=function(data,textStatus,jqXHR){var $container=this;$container.trigger('beforeAjaxSuccess.tribeEvents',[data,textStatus,jqXHR]);var $html=$(data);obj.cleanup($container);$container.replaceWith($html);$container=$html;obj.setup(0,$container);obj.selectContainers();obj.updateUrl($container);$container.trigger('afterAjaxSuccess.tribeEvents',[data,textStatus,jqXHR]);if(obj.shouldManageUrl($container)){obj.$lastContainer=$container;}};obj.ajaxError=function(jqXHR,settings){var $container=this;$container.trigger('beforeAjaxError.tribeEvents',[jqXHR,settings]);$container.trigger('afterAjaxError.tribeEvents',[jqXHR,settings]);};obj.selectContainers=function(){obj.$containers=$(obj.selectors.container);};obj.getLastContainer=function(){if(!obj.$lastContainer.length){obj.$lastContainer=obj.$containers.filter('[data-view-manage-url="1"]').eq(0);}
return obj.$lastContainer;}
obj.ready=function(){obj.selectContainers();obj.$containers.each(obj.setup);};$document.ready(obj.ready);$window.on('popstate',obj.onPopState);})(jQuery,window.underscore||window._,tribe.events.views.manager);