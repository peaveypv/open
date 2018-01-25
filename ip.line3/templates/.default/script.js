$(document).ready(function() {



	$( ".vpalab_ip_line_city_name" ).click(function() {
		cityQuestionSet();
		openCityBox();
	});

	$( ".vpalab_ip_line_default_box .btn_no" ).click(function() {
		openCityBox();
		$('.vpalab_ip_line_default_box .city_box .city_question_box').css("display", "none");
		cityQuestionSet();
	});

	$( ".vpalab_ip_line_default_box .btn_yes" ).click(function() {
		$('.vpalab_ip_line_default_box .city_box .city_question_box').css("display", "none");
		cityQuestionSet();
	});


	$( ".vpalab_ip_line_city_change_box .btn_close" ).click(function() {
		closeCityBox();
	});
	$( ".vpalab_ip_line_city_change_box .bg_box" ).click(function() {
		closeCityBox();
	});

	$( ".search_options_box div .item" ).click(function() {
		if(this.id)
		{
			var name = $(this).attr('name');
			SetcityCookie(this.id, name);
		}
		window.location.reload();
	});

});

function cityQuestionSet(){

	BX.setCookie('VPALAB_IP_QUESTION', '1', {expires: 86400, path: '/'});
}

function SetcityCookie(id, name){

	BX.setCookie('BITRIX_SM_VPALAB_IP_CODE', id, {expires: 86400, path: '/'});
	BX.setCookie('BITRIX_SM_VPALAB_IP_NAME', name, {expires: 86400, path: '/'});
}



function cityQuestionGet(){

	return BX.getCookie('VPALAB_IP_QUESTION');
}

function openCityBox()
{
	$('.vpalab_ip_line_default-box .vpalab_ip_line_city_change_box .main_box').css("display", "block");
	$('.vpalab_ip_line_default-box .vpalab_ip_line_city_change_box .bg_box').css("display", "block");
	$('.vpalab_ip_line_city_change_box').show();
	$('.bg_box').animate({'opacity': '1'}, 300);
	$('.main_box').animate({'opacity': '1'}, 300);
}

function closeCityBox()
{
	$('.vpalab_ip_line_city_change_box').hide();
	$('.bg_box').animate({'opacity': '0'}, 300);
	$('.main_box').animate({'opacity': '0'}, 300);
	$('.vpalab_ip_line_default-box .vpalab_ip_line_city_change_box .main_box').css("display", "none");
	$('.vpalab_ip_line_default-box .vpalab_ip_line_city_change_box .bg_box').css("display", "none");
}

function vpaIpCallback(id, obj){

	var cityName = obj.ctrls.inputs.fake.value;

	SetcityCookie(id, cityName);

	window.location.reload();
}
