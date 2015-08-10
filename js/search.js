$(document).ready(function() {
   $('.tooltip').tooltipster();

   	$('a[href^="#"]').on('click',function (e) {
	    e.preventDefault();

	    var target = this.hash;
	    var $target = $(target);

	    $('html, body').stop().animate({
	        'scrollTop': $target.offset().top
	    }, 300, 'swing', function () {
	        window.location.hash = target;
	    });
	});

});

$("#track-search").keyup( function () {
	var search = $(this).val();
	if (search.length == 0) {
		$('#results-container').hide();
	} else {
		$('#results-container').show();
		$('#search-results').html("<i class='fa fa-circle-o-notch fa-pulse'></i>");
		var bangers = new Array();

		$('li').each( function() {
			var li = $(this);
			var content = li.html();
			var re = new RegExp(search, 'gi');
			var toDelete = content.indexOf("<h4>");
			if (toDelete > -1) {
				content = content.substring(0, toDelete);
			}
			if (content.toLowerCase().indexOf(search.toLowerCase()) > -1) {
				content = content.replace(re, "<span class='highlighted'>$&</span>");
				var heldeepNumber = li.parent().attr('id').slice(-3);
				content += "<a class='more-info' href='#heldeep-" + heldeepNumber + "'>From Heldeep #" + heldeepNumber + " <i class='fa fa-caret-down'></i></a>";
				bangers.push(content);
			}
		});

		var bangerCount = bangers.length;
		if (bangerCount == 0) {
			$('#search-results').html("Sorry, couldn't find anything.");
		} else {
			$('#results-count').html("(" + bangerCount + " track" + ((bangerCount == 1) ? "" : "s") + "):");
			var results = "<ul>";
			bangers.forEach( function (banger) {
				results += "<li>" + banger + "</li>";
			})
			results += "</ul>";
			$('#search-results').html(results);
		}
	}
});

$( window ).scroll( function () {
	var win = $( this );
	if (win.scrollTop() > 0) {
		$('#return-to-top').fadeIn();
	} else {
		$('#return-to-top').fadeOut();
	}
});

$('.heldeep-header').click( function () {
	var that = $(this);
	var trackID = that.data('trackid');
	var iFrame = '<iframe width="100%" height="166" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/' + trackID + '&amp;color=ff6600&amp;auto_play=true&amp;show_artwork=true"></iframe>';
	that.after(iFrame).find('i').fadeOut();
})