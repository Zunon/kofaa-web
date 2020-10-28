$(document).ready(function(){
	var homeurl = $('#homeurl').text();
    
    $('#loginform').on('submit', function (e) {
		e.preventDefault();
		var lang = getUrlVars()["lang"];
		
			var url = window.location.href;
			var a = url.indexOf("?");
			var b =  url.substring(a);
			var c = url.replace(b,"");
			
		var homeurl = $('#homeurl').text();
		
		$.ajax({
        type: 'post',
        url: homeurl + '/wp-content/themes/wp-bootstrap-starter/login-register/custom-login.php',
        data: $('#loginform').serialize(),
        success: function (response) {
			console.log(response);
			if($.trim(response) == 'true') {
                window.location.href = $('#current_lang_url').text();
            }
            else {
				var invalid_error = $("#login-invalid-error").val();
                $("#diverror").text(invalid_error);
            }
        }
      });
    });

    $('#lostpasswordform').on('submit', function (e) {
        e.preventDefault();
		var lang = getUrlVars()["lang"];
		
			var url = window.location.href;
			var a = url.indexOf("?");
			var b =  url.substring(a);
			var c = url.replace(b,"");
			var homeurl = $('#homeurl').text();
		
		var lang = $("#site_lang").val();
        $.ajax({
            type: 'post',
            url: homeurl + '/wp-content/themes/wp-bootstrap-starter/login-register/custom-login.php?lang=' + lang +'&siteurl=' + homeurl,
            data: $('#lostpasswordform').serialize(),
            success: function (response) {
                if($.trim(response) == 'true') {
                    $("#forgoterror").text("");
					var forgotinfo = $("#forgot-info").val();
                    $("#forgotinfo").text(forgotinfo);
                }
                else {
                    $("#forgotinfo").text("");
					var forgoterror = $("#forgot-error").val();
                    $("#forgoterror").text(forgot-error);
                }
            }
        });
    });

    $('#registerform').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: homeurl + '/wp-content/themes/wp-bootstrap-starter/login-register/custom-login.php',
            data: $('#registerform').serialize(),
            success: function (response) {
                if($.trim(response) == 'true') {
                // $("#registererror").text("");
                // $("#registerinfo").text("مسجل بنجاح");
                // $("#registerform").hide();
                $('#signupModal').modal('hide');
                $('#succesfullsignupModal').modal();
                    setTimeout(
                        function() 
                        {
                            $('#signupModal').find('form').trigger('reset');
                            $('#succesfullsignupModal').modal('hide');
                            $('#loginModal').modal();
                            $("#registererror").html('');
                        }, 4000);
                }
                else {
                    $("#registerinfo").text("");
                    $("#registererror").html(response);
                }
            }
        });
    });
    
    $('#contactus').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
          type: 'post',
          url: homeurl + 'wp-includes/custom_contactus.php',
          data: $('#contactus').serialize(),
          success: function (response) {
            if($.trim(response) == 'true') {
              $('#contactus').trigger('reset');
              $('#reachyou').modal();
            }
          }
        });
    });
	
	$('.courese-by, .cart-courese').owlCarousel({
		loop:true,
		margin:10,
        nav:true,
        rtl: true,
		responsive:{
			0:{
				items:1
			},
			600:{
				items:3
			},
			1000:{
				items:5
			}
		}
	});
	$('.owl-carousel-review').owlCarousel({
		loop:true,
		margin:10,
        nav:true,
        rtl: true,
		responsive:{
			0:{
				items:1
			},
			600:{
				items:1
			},
			1000:{
				items:1
			}
		}
	})

    $('.close-div').on('click', function(){
        $(this).closest("#clients-edit-wrapper").remove();
    });
	if($("#site_lang").val() == "ar")
	{
		 $(".tnp-email").attr("placeholder", "أدخل بريدك الإلكتروني");
		 $('.tnp-field-email').find('label').text('البريد الإلكتروني');
		 $('.tnp-field-button').find('input').val('الإشتراك');
		 $(".sub_confirmed").text("تم تأكيد اشتراكك");
		 $(".blog-more-link").text("قراءة المزيد");
		 $(".search-course-input").attr("placeholder", "دورة البحث....");
		$('ul.learn-press-tabs').find('li.certificates').find('a').text('الشهادات');
		$('ul.learn-press-tabs').find('li.wishlist').find('a').text('قائمة الرغبات');
		$('ul.lp-tab-sections').find('li.publicity').find('a').text('شهره اعلاميه');
		$('ul.profile-tab-sections').find('li.publicity').find('a').text('شهره اعلاميه');
		$('ul.lp-sub-menu').find('li.all').find('a').text('الكل');
		$('ul.lp-sub-menu').find('li.completed').find('a').text('منجز');
		$('ul.lp-sub-menu').find('li.passed').find('a').text('تم الاجتياز بنجاح');
		$('ul.lp-sub-menu').find('li.failed').find('a').text('فشل');
		$('#profile-content-wishlist').children('.profile-heading').text('قائمة الرغبات');
		$('.mailchimp-newsletter').children('label').children('span').text('اشترك في نشرتنا الإخبارية');
		$('ul.course-nav-tabs').find('li.course-nav-tab-overview').find('a').text('نظرة عامة');
		$('ul.course-nav-tabs').find('li.course-nav-tab-instructor').find('a').text('مدرب');
		$('ul.course-nav-tabs').find('li.course-nav-tab-reviews').find('a').text('المراجعات');
		$('.course-tab-panel-instructor').find('.course-author').find('h3').text('مدرب');
		$('#foloosi_checkout_place_order').text('مكان الامر');
		$('#reply-title').text('اترك رد ');
		$('#commentform').find('textarea').attr("placeholder", "ابدأ الطباعة...");
		$('#commentsubmit').val("أضف تعليقا");
		$(".tribe-events-c-view-selector__list > li > a").click(function() {
			var homeurl = $('#homeurl').text();
			var menu_text = $(this).children(".tribe-events-c-view-selector__list-item-text").text();
			window.location.href = $(this).attr("href");
		});
		$(".tribe-events-c-view-selector__list").children('.tribe-events-c-view-selector__list-item--list').children('.tribe-events-c-view-selector__list-item-link').children(".tribe-events-c-view-selector__list-item-text").text("قائمة");
		$(".tribe-events-c-view-selector__list").children('.tribe-events-c-view-selector__list-item--month').children('.tribe-events-c-view-selector__list-item-link').children(".tribe-events-c-view-selector__list-item-text").text("شهر");
		$(".tribe-events-c-view-selector__list").children('.tribe-events-c-view-selector__list-item--day').children('.tribe-events-c-view-selector__list-item-link').children(".tribe-events-c-view-selector__list-item-text").text("يوم");
		$("#tribe-events-events-bar-keyword").attr("placeholder", "ابحث عن الأحداث");
		$(".tribe-events-c-ical__link").text("أحداث التصدير");
	}
	else
	{
		 $(".tnp-email").attr("placeholder", "Enter your email address");
		 $(".sub_confirmed").text("Your subscription has been confirmed");
		 $(".Thankyou_page").text("Thanks for purchasing the course, your request will be processed soon");
		 $(".search-course-input").attr("placeholder", "Research Course ...");
		 $('ul.learn-press-tabs').find('li.wishlist').find('a').text('Wishlist');
		$('.learn-press-course-wishlist').text('Add to wishlist');
		$('#reply-title').text('Leave a response');
		$('#commentform').find('textarea').attr("placeholder", "write a comment ...");
		$('#commentsubmit').val("Add a comment");
		$('.learn_back').find("a").text('Back to the courses');
		$(".tribe-events-c-view-selector__list > li > a").click(function() {
			var list_class = $(this).parent("li").attr("class");
			if(list_class == "tribe-events-c-view-selector__list-item tribe-events-c-view-selector__list-item-link")
			{
				var find_text = "list";
			}
			else if(list_class == "tribe-events-c-view-selector__list-item tribe-events-c-view-selector__list-item--month")
			{
				var find_text = "month";
			}
			else if(list_class == "tribe-events-c-view-selector__list-item tribe-events-c-view-selector__list-item--day")
			{
				var find_text = "today";
			}
			else
			{
				var find_text = "list";
			}
			var homeurl = $('#homeurl').text();
			var language = $('#current_lanaguage').text();
			var menu_text = $(this).children(".tribe-events-c-view-selector__list-item-text").text();
			//window.location.href = $(this).attr("href");
			window.location.href = homeurl+'/'+language+'/events/'+ find_text;
		});
		$(".tribe-events-c-search__button").click(function() {
			
			if($(".tribe-events-c-search__input").val() != "")
			{
				var homeurl = $('#homeurl').text();
				var language = $('#current_lanaguage').text();
				window.location.href = homeurl+'/'+language+ "/events?tribe-bar-search="+$(".tribe-events-c-search__input").val();
			}
		});
		
	}
   
$(".tribe_events-template-default").find("#comments").css("display","none");
	 $('#sub_sc').val('الإشتراك')

    

    
    $('ul.learn-press-tabs').find('li.owned').find('a').text('مملوكة');
    
    $('li.section-tab.owned').find('span').text('مملوكة');
    $('li.section-tab.owned').find('a').text('مملوكة');
    // var owned_changed = $("body").html().replace(/owned/gi,'مملوكة');
    // $("body").html(owned_changed);
    // var wishlist_changed = $("body").html().replace(/wishlist/gi,'قائمة الرغبات');
    // $("body").html(wishlist_changed);
    // var certificates_changed = $("body").html().replace(/certificates/gi,'الشهادات');
    // $("body").html(certificates_changed);

    var currLoc = $(location).attr('href'); 
    console.log(currLoc);
      
        $(".close-div").click(function(){
            $(".fixed-top").css("top", "0");
        });
      


  });
function getUrlVars()
{
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}