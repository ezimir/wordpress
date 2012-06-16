//Main Menu dropdown
$(function(){

	//Hide SubLevel Menus
	$('#main_menu ul li ul').hide();

 	//Original height of #main_header
 	var main_menu_height = $('#main_header').height();

	//OnHover Show SubLevel Menus
	$('#main_menu div ul li').not($('#main_menu ul li ul li')).hover(

		//OnHover
		function(){

			//Hide Other Menus
			$('#main_menu ul li').not($('ul', this)).stop();

			//Calc height of sub menu
			var this_menu = $('ul', this);
			var this_menu_has_how_many_lis = $('ul li', this).length;
			var height_to_add = this_menu_has_how_many_lis * 35;

			$('#main_header').animate({
				height: '+='+height_to_add
			}, 200, function(){
				var new_height = $(this).height();

				this_menu.stop(true, true).fadeIn('fast');
			});

		},
		//OnOut
		function(){

			var this_menu = $('ul', this);

			this_menu.stop(true, true).fadeOut('fast');

			$('#main_header').animate({
				height: main_menu_height
			}, 200, function(){

			});

		}
	);

});

//Home Page default nive slider
$(window).load(function() {
    $('#slider').nivoSlider();
});

//Home Page blog posts
$(window).load(function() {
    $('.blog_home_slider').nivoSlider({manualAdvance: true, effect: 'fade'});
});

//Single Portfolio Main
$(window).load(function() {
    $('#single_portfolio_slider').nivoSlider({pauseTime: 5000});
});

//Home page clients slider
$('#clients').anythingSlider({
	showMultiple : 3,
	changeBy     : 1,
	startStopped : true,
	delay : 9000,
	appendControlsTo: '#clients_controls',
	onInitialized: function(e, slider) {
        slider.$controls
            .prepend('<ul id="prev_client"><li><a class="prev_client">&larr;</a></li></ul>')
            .append('<ul id="next_client"><li><a class="next_client">&rarr;</a></li></ul>')
            .find('.prev_client, .next_client').click(function(){
                if ($(this).is('.prev_client')) {
                    slider.goBack();
                } else {
                    slider.goForward();
                }
            });
    }
});

function clearText(field){
	if (field.defaultValue == field.value) field.value = '';
	else if (field.value == '') field.value = field.defaultValue;
}

$('#searchform').submit(function (e) {
	var $term = $('#s'),
		val = $term.val().trim();

	if (val === defaultSearchValue || val === ''){
		$term.val(defaultSearchValue).focus();
		return e.preventDefault();
	}
});

$('a img.size-medium, a img.size-small, a img.size-thumbnail, a img.size-full, a img.size-large, a img.topup').parent().attr("rel","colorbox");

$(document).ready(function(){
	$('a[rel="colorbox"]').colorbox();
});

