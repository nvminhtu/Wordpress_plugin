(function($){
	var Admin = $.admin = (function(){
		function init(){
			$('#the-list').find('strong').each(function(){
				var txt = $(this).text();
				if(txt.match(/^\[Blank Page\] /)){
					$(this).html(txt.substr(12));
				}
			});

			$('#post-body-content').find('#title').attr('disabled','disabled');

			$('#normal-sortables').find('.label').each(function(){
				var txt = $(this).text();
				if(txt.match(/^\[Only Administrator\] /)){
					$(this).parent().hide();
				}
			});

			$('#your-profile').find('h3').eq(0).hide()
			.end().end().find('.form-table').eq(0).hide()
			.end().eq(1).find('tr').slice(1,3).hide()
			.end().end().end().eq(2).find('tr').eq(1).hide()
			.end().end().end().eq(3).find('tr').eq(0).hide();
		}

		return {
			init : init
		}
	})();
	$(Admin.init);
})(jQuery);
