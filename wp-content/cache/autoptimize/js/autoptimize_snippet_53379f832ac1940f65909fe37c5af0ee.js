tribe.events=tribe.events||{};tribe.events.views=tribe.events.views||{};tribe.events.views.datepicker={};(function($,obj){'use strict';var $document=$(document);obj.selectors={datepickerFormClass:'.tribe-events-c-top-bar__datepicker-form',datepickerContainer:'[data-js="tribe-events-top-bar-datepicker-container"]',datepickerDaysBody:'.datepicker-days tbody',input:'[data-js="tribe-events-top-bar-date"]',button:'[data-js="tribe-events-top-bar-datepicker-button"]',buttonOpenClass:'.tribe-events-c-top-bar__datepicker-button--open',dateInput:'[name="tribe-events-views[tribe-bar-date]"]',};obj.state={initialized:false,};obj.options={container:null,daysOfWeekDisabled:[],maxViewMode:'decade',minViewMode:'month',orientation:'bottom left',showOnFocus:false,templates:{leftArrow:'',rightArrow:'',},};obj.keyCode={ENTER:13,};obj.today=null;obj.dateFormatMap={d:'dd',j:'d',m:'mm',n:'m',Y:'yyyy',};obj.observer=null;obj.padNumber=function(number){var numStr=number+'';var padding=numStr.length>1?'':'0';return padding+numStr;};obj.request=function(viewData,$container){var data={view_data:viewData,_wpnonce:$container.data('view-rest-nonce'),};tribe.events.views.manager.request(data,$container);};obj.createDateInputObj=function(value){var $input=$('<input>');$input.attr({type:'hidden',name:'tribe-events-views[tribe-bar-date]',value:value,});return $input;};obj.submitRequest=function($container,value){var viewData={['tribe-bar-date']:value,};obj.request(viewData,$container);};obj.handleChangeDate=function(event){var $container=event.data.container;var date=event.date.getDate();var month=event.date.getMonth()+1;var year=event.date.getFullYear();var paddedDate=obj.padNumber(date);var paddedMonth=obj.padNumber(month);var dateValue=[year,paddedMonth,paddedDate].join('-');obj.submitRequest($container,dateValue);};obj.handleChangeMonth=function(event){var $container=event.data.container;var month,year;if(event.date){month=event.date.getMonth()+1;year=event.date.getFullYear();}else{var date=$container.find(obj.selectors.input).bootstrapDatepicker('getDate');month=date.getMonth()+1;year=date.getFullYear();}
var paddedMonth=obj.padNumber(month);var dateValue=[year,paddedMonth].join('-');obj.submitRequest($container,dateValue);};obj.handleKeyDown=function(event){if(event.keyCode!==obj.keyCode.ENTER){return;}
event.data.input.bootstrapDatepicker().trigger('changeMonth');}
obj.handleShow=function(event){event.data.datepickerButton.addClass(obj.selectors.buttonOpenClass.className());};obj.handleHide=function(event){var $datepickerButton=event.data.datepickerButton
var state=$datepickerButton.data('tribeEventsState');event.data.observer.disconnect();if(state.isTarget){event.data.input.bootstrapDatepicker('show');return;}
$datepickerButton.removeClass(obj.selectors.buttonOpenClass.className()).focus();};obj.handleMousedown=function(event){var $datepickerButton=event.data.target;var state=$datepickerButton.data('tribeEventsState');if('touchstart'===event.type){var method=$datepickerButton.hasClass(obj.selectors.buttonOpenClass.className())?'hide':'show';var tapHide='hide'===method;state.isTarget=false;$datepickerButton.data('tribeTapHide',tapHide).data('tribeEventsState',state).off('mousedown',obj.handleMousedown);return;}
state.isTarget=true;$datepickerButton.data('tribeEventsState',state);};obj.handleClick=function(event){var $input=event.data.input;var $datepickerButton=event.data.target;var state=$datepickerButton.data('tribeEventsState');var method=$datepickerButton.hasClass(obj.selectors.buttonOpenClass.className())?'hide':'show';var tapHide=$datepickerButton.data('tribeTapHide');if(tapHide){return;}
state.isTarget=false;$datepickerButton.data('tribeEventsState',state);$input.bootstrapDatepicker(method);if('show'===method){$input.focus();}};obj.handleMutation=function(data){var $container=data.container;return function(mutationsList,observer){for(var mutation of mutationsList){if('childList'===mutation.type&&$container.find(obj.selectors.datepickerDaysBody).is(mutation.target)&&mutation.addedNodes.length){$container.trigger('handleMutationMonthChange.tribeEvents');}}};};obj.setToday=function(today){var date=today;if(today.indexOf(' ')>=0){date=today.split(' ')[0];}
obj.today=new Date(date);};obj.isSameAsToday=function(date,unit){switch(unit){case'year':return date.getFullYear()===obj.today.getUTCFullYear();case'month':return obj.isSameAsToday(date,'year')&&date.getMonth()===obj.today.getUTCMonth();case'day':return obj.isSameAsToday(date,'month')&&date.getDate()===obj.today.getUTCDate();default:return false;}}
obj.isBeforeToday=function(date,unit){switch(unit){case'year':return date.getFullYear()<obj.today.getUTCFullYear();case'month':return obj.isBeforeToday(date,'year')||(obj.isSameAsToday(date,'year')&&date.getMonth()<obj.today.getUTCMonth());case'day':return obj.isBeforeToday(date,'month')||(obj.isSameAsToday(date,'month')&&date.getDate()<obj.today.getUTCDate());default:return false;}};obj.filterDayCells=function(date){if(obj.isBeforeToday(date,'day')){return'past';}else if(obj.isSameAsToday(date,'day')){return'current';}};obj.filterMonthCells=function(date){if(obj.isBeforeToday(date,'month')){return'past';}else if(obj.isSameAsToday(date,'month')){return'current';}};obj.filterYearCells=function(date){if(obj.isBeforeToday(date,'year')){return'past';}else if(obj.isSameAsToday(date,'year')){return'current';}};obj.convertDateFormat=function(dateFormat){var convertedDateFormat=dateFormat;Object.keys(obj.dateFormatMap).forEach(function(key){convertedDateFormat=convertedDateFormat.replace(key,obj.dateFormatMap[key]);});return convertedDateFormat;};obj.initDateFormat=function(data){var dateFormats=data.date_formats||{};var dateFormat=dateFormats.compact;var convertedDateFormat=obj.convertDateFormat(dateFormat);obj.options.format=convertedDateFormat;};obj.deinit=function(event,jqXHR,settings){var $container=event.data.container;$container.trigger('beforeDatepickerDeinit.tribeEvents',[jqXHR,settings]);var $input=$container.find(obj.selectors.input);var $datepickerButton=$container.find(obj.selectors.button);$input.bootstrapDatepicker('destroy').off();$datepickerButton.off();$container.off('beforeAjaxSuccess.tribeEvents',obj.deinit);$container.trigger('afterDatepickerDeinit.tribeEvents',[jqXHR,settings]);};obj.init=function(event,index,$container,data){$container.trigger('beforeDatepickerInit.tribeEvents',[index,$container,data]);var $input=$container.find(obj.selectors.input);var $datepickerButton=$container.find(obj.selectors.button);var viewSlug=data.slug;var isMonthView='month'===viewSlug;var changeEvent=isMonthView?'changeMonth':'changeDate';var changeHandler=isMonthView?obj.handleChangeMonth:obj.handleChangeDate;var state={isTarget:false,};obj.observer=new MutationObserver(obj.handleMutation({container:$container}));obj.setToday(data.today);obj.initDateFormat(data);obj.options.weekStart=data.start_of_week;obj.options.container=$container.find(obj.selectors.datepickerContainer);obj.options.minViewMode=isMonthView?'year':'month';var tribeL10nDatatables=window.tribe_l10n_datatables||{};var datepickerI18n=tribeL10nDatatables.datepicker||{};var nextText=datepickerI18n.nextText||'Next';var prevText=datepickerI18n.prevText||'Prev';obj.options.templates.leftArrow='<span class="tribe-common-svgicon"></span><span class="tribe-common-a11y-visual-hide">'+prevText+'</span>',obj.options.templates.rightArrow='<span class="tribe-common-svgicon"></span><span class="tribe-common-a11y-visual-hide">'+nextText+'</span>',obj.options.beforeShowDay=obj.filterDayCells;obj.options.beforeShowMonth=obj.filterMonthCells;obj.options.beforeShowYear=obj.filterYearCells;$input.bootstrapDatepicker(obj.options).on(changeEvent,{container:$container},changeHandler).on('show',{datepickerButton:$datepickerButton},obj.handleShow).on('hide',{datepickerButton:$datepickerButton,input:$input,observer:obj.observer},obj.handleHide);if(isMonthView){$input.bootstrapDatepicker().on('keydown',{input:$input},obj.handleKeyDown);}
$datepickerButton.on('touchstart mousedown',{target:$datepickerButton},obj.handleMousedown).on('click',{target:$datepickerButton,input:$input},obj.handleClick).data('tribeEventsState',state);$container.on('beforeAjaxSuccess.tribeEvents',{container:$container,viewSlug:viewSlug},obj.deinit);$container.trigger('afterDatepickerInit.tribeEvents',[index,$container,data]);};obj.initDatepickerI18n=function(){var tribeL10nDatatables=window.tribe_l10n_datatables||{};var datepickerI18n=tribeL10nDatatables.datepicker||{};datepickerI18n.dayNames&&($.fn.bootstrapDatepicker.dates.en.days=datepickerI18n.dayNames);datepickerI18n.dayNamesShort&&($.fn.bootstrapDatepicker.dates.en.daysShort=datepickerI18n.dayNamesShort);datepickerI18n.dayNamesMin&&($.fn.bootstrapDatepicker.dates.en.daysMin=datepickerI18n.dayNamesMin);datepickerI18n.monthNames&&($.fn.bootstrapDatepicker.dates.en.months=datepickerI18n.monthNames);datepickerI18n.monthNamesMin&&($.fn.bootstrapDatepicker.dates.en.monthsShort=datepickerI18n.monthNamesMin);datepickerI18n.today&&($.fn.bootstrapDatepicker.dates.en.today=datepickerI18n.today);datepickerI18n.clear&&($.fn.bootstrapDatepicker.dates.en.clear=datepickerI18n.clear);};obj.initDatepicker=function(){if($.fn.datepicker&&$.fn.datepicker.noConflict){var datepicker=$.fn.datepicker.noConflict();$.fn.bootstrapDatepicker=datepicker;obj.initDatepickerI18n();obj.state.initialized=true;}};obj.ready=function(){obj.initDatepicker();if(obj.state.initialized){$document.on('afterSetup.tribeEvents',tribe.events.views.manager.selectors.container,obj.init);}};$document.ready(obj.ready);})(jQuery,tribe.events.views.datepicker);