(function($){
/*
 *@TODO rewrite everything.
 * encapulate functionality into seperate files and load as needed
 * replace CSS Helpers section with theme logic instead
 * fix wasteful selectors
*/
Drupal.behaviors.hks = {
attach: function(context, settings) {

	/*---------------------------------
		MENU Dropdowns
	-----------------------------------*/
	$('ul.menu').each(function(){
		// add the menu toggle
		$(this).prepend('<li class="menu-toggle"><a href="#"><span class="icon" data-icon="Y"></span> Menu</a></li>');

		// find menu items with children.
		$(this).find('li').has('ul').addClass('has-menu')
		.find('a:first').append('<span class="arrow">&nbsp;</span>');
	});

	$('ul.menu li').hover(function(){
		$(this).find('ul:first').stop(true, true).fadeIn('fast');
		$(this).addClass('hover');
	},
	function(){
		$(this).find('ul').stop(true, true).fadeOut('slow');
		$(this).removeClass('hover');
	});

	/*---------------------------------
		Slideshow
	-----------------------------------*/
	$('.slideshow').bxSlider({
		mode: 'horizontal', // 'horizontal', 'vertical', 'fade'
		video: true,
		useCSS: true,
		pager: true,
		speed: 500, // transition time
		startSlide: 0,
		infiniteLoop: true,
		captions: true,
		adaptiveHeight: false,
		touchEnabled: true,
		pause: 4000,
		autoControls: false,
		controls: false,
		autoStart: true,
		auto: true
	});

	/*---------------------------------
		HTML5 Placeholder Support
	-----------------------------------*/
	$('input[placeholder], textarea[placeholder]').placeholder();

	/*---------------------------------
		Fancybox Lightbox
	-----------------------------------*/
	$('.gallery').each(function(i){
		$(this).find('a').attr('rel', 'gallery'+i)
		.fancybox({
			overlayOpacity: 0.2,
			overlayColor: '#000'
		});
	});

	// lightbox links
	$('a.lightbox').fancybox({
		overlayOpacity: 0.2,
		overlayColor: '#000'
	});

	/*---------------------------------
		Tabs
	-----------------------------------*/
	// tab setup
	$('.tab-content').addClass('clearfix').not(':first').hide();
	$('ul.tabs').each(function(){
		var current = $(this).find('li.current');
		if(current.length < 1) { $(this).find('li:first').addClass('current'); }
		current = $(this).find('li.current a').attr('href');
		$(current).show();
	});

	// tab click
	$(document).on('click', 'ul.tabs a[href^="#"]', function(e){
		e.preventDefault();
		var tabs = $(this).parents('ul.tabs').find('li');
		var tab_next = $(this).attr('href');
		var tab_current = tabs.filter('.current').find('a').attr('href');
		$(tab_current).hide();
		tabs.removeClass('current');
		$(this).parent().addClass('current');
		$(tab_next).show();
		history.pushState( null, null, window.location.search + $(this).attr('href') );
		return false;
	});

 	// tab hashtag identification and auto-focus
    	var wantedTag = window.location.hash;
    	if (wantedTag != "")
    	{
        	var allTabs = $("ul.tabs a[href^=" + wantedTag + "]").parents('ul.tabs').find('li');
        	var defaultTab = allTabs.filter('.current').find('a').attr('href');
        	$(defaultTab).hide();
        	allTabs.removeClass('current');
        	$("ul.tabs a[href^=" + wantedTag + "]").parent().addClass('current');
        	$("#" + wantedTag.replace('#','')).show();
    	}

	/*---------------------------------
		Image Caption
	-----------------------------------*/
	$('img.caption').each(function(){
		$(this).wrap('<div class="caption">');
		$(this).parents('div.caption')
			.attr('class', 'caption '+$(this).attr('class'));
		if($(this).attr('title')){
			$(this).parents('div.caption')
			.append('<span>'+$(this).attr('title')+'</span>');
		}
	});

	/*---------------------------------
		Notice
	-----------------------------------*/
	$(document).on('click', '.notice a[class^="icon-remove"]', function(e){
		e.preventDefault();
		var notice = $(this).parents('.notice');
		$(this).hide();
		notice.fadeOut('slow');
	});

	/*---------------------------------
		ToolTip - TipTip
	-----------------------------------*/

	// Standard tooltip
	$('.tooltip, .tooltip-top, .tooltip-bottom, .tooltip-right, .tooltip-left').each(function(){
		// variables
		var tpos = 'top';
		var content = $(this).attr('title');
		var dataContent = $(this).attr('data-content');
		var keepAlive = false;
		var action = 'hover';

		// position
		if($(this).hasClass('tooltip-top')) 	{ tpos = 'top'; 	}
		if($(this).hasClass('tooltip-right')) 	{ tpos = 'right'; 	}
		if($(this).hasClass('tooltip-bottom')) 	{ tpos = 'bottom'; 	}
		if($(this).hasClass('tooltip-left')) 	{ tpos = 'left'; 	}

		// content
		$('.tooltip-content').removeClass('hide').wrap('<div class="hide"></div>');
		if(dataContent) { content = $(dataContent).html(); keepAlive = true; }

		// action (hover or click) defaults to hover
		if($(this).attr('data-action') == 'click') { action = 'click'; }

		// tooltip
		$(this).attr('title','')
		.tipTip({defaultPosition: tpos, content: content, keepAlive: keepAlive, activation: action, delay: 1000});
	});

	/*---------------------------------
		Table Sort
	-----------------------------------*/
	// init
	var aAsc = [];
	$('table.sortable').each(function(){
		$(this).find('thead th').each(function(index){$(this).attr('rel', index);});
		$(this).find('th,td').each(function(){$(this).attr('value', $(this).text());});
	});

	// table click
	$(document).on('click', 'table.sortable thead th', function(e){
		// update arrow icon
		$(this).parents('table.sortable').find('span.arrow').remove();
		$(this).append('<span class="arrow"></span>');

		// sort direction
		var nr = $(this).attr('rel');
		aAsc[nr] = aAsc[nr]=='asc'?'desc':'asc';
		if(aAsc[nr] == 'desc'){ $(this).find('span.arrow').addClass('up'); }

		// sort rows
		var rows = $(this).parents('table.sortable').find('tbody tr');
		rows.tsort('td:eq('+nr+')',{order:aAsc[nr],attr:'value'});

		// fix row classes
		rows.removeClass('alt first last');
		var table = $(this).parents('table.sortable');
		table.find('tr:even').addClass('alt');
		table.find('tr:first').addClass('first');
		table.find('tr:last').addClass('last');
	});

	/*---------------------------------
		CSS Helpers
	-----------------------------------*/
	if($.browser.msie){ $('body').addClass('msie'); }
	$('input[type=checkbox]').addClass('checkbox');
	$('input[type=radio]').addClass('radio');
	$('input[type=file]').addClass('file');
	$('[disabled=disabled]').addClass('disabled');
	$('table').find('tr:even').addClass('alt');
	$('table').find('tr:first-child').addClass('first');
	$('table').find('tr:last-child').addClass('last');
	$('ul').find('li:first-child').addClass('first');
	$('ul').find('li:last-child').addClass('last');
	$('hr').before('<div class="clear">&nbsp;</div>');
	$('[class*="col_"]').addClass('column');
	$('pre').addClass('prettyprint');prettyPrint();


}};

})(jQuery);