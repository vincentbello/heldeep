(function() {

	var Module = function() {
		return {

			ALL_CONTENT_LOADED: false,
			embeddedPlayers: {},

			el: {},

			getElements: function() {
				this.el.$window = $(window);

				this.el.$tooltips = $('.tooltip');
				this.el.$hashLinks = $('a[href^="#"]');
				this.el.$backToTop = $('#return-to-top');

				this.el.$searchField = $('#track-search');
				this.el.$resultsContainer = $('#results-container');
				this.el.$searchResults = $('#search-results');
				this.el.$resultsCount = $('#results-count');

				this.el.$tracks = $('li');

				this.el.$heldeepHeaders = $('.heldeep-header');
				this.el.$loadThresholdElem = this.el.$heldeepHeaders.eq(3);
			},

			bindEvents: function() {

				this.el.$window.on('scroll', this._loadContent.bind(this));

				this.el.$hashLinks.on('click', this._scrollToId);
				this.el.$searchField.on('keyup', this._search.bind(this));
				this.el.$heldeepHeaders.on('click', this._revealSoundCloud.bind(this));
			},

			buildPage: function() {

				this.el.$tooltips.tooltipster();
			},

			_scrollToId: function(evt) {
				var target = this.hash,
						$target = $(target);

				evt.preventDefault();

				$('html, body').stop().animate({
					'scrollTop': $target.offset().top
				}, 300, 'swing', function() {
					window.location.hash = target;
				});
			},

			_search: function(evt) {
				var search = evt.target.value,
						bangers = [],
						bangerCount,
						results;

				if (search.length) {
					this.el.$resultsContainer.show();
					this.el.$searchResults.html("<i class='fa fa-circle-o-notch fa-pulse'></i>");

					this.el.$tracks.each(function() {
						var $li = $(this),
								content = $li.text(),
								re = new RegExp(search, 'gi'),
								heldeepNumber;
						if (content.toLowerCase().indexOf(search.toLowerCase()) > -1) {
							content = content.replace(re, "<span class='highlighted'>$&</span>");
							heldeepNumber = $li.parent().attr('id').slice(-3);
							content += "<a class='more-info' href='#heldeep-" + heldeepNumber + "'>From Heldeep #" + heldeepNumber + " <i class='fa fa-caret-down'></i></a>";
							bangers.push(content);
						}
					});

					bangerCount = bangers.length;

					if (bangerCount) {
						this.el.$resultsCount.html("(" + bangerCount + " track" + ((bangerCount == 1) ? "" : "s") + "):");
						results = "<ul>";
						bangers.forEach(function(banger) {
							results += "<li>" + banger + "</li>";
						});
						results += "</ul>";
						this.el.$searchResults.html(results);
					} else {
						this.el.$searchResults.html("Sorry, couldn't find anything.");
					}
				} else {
					this.el.$resultsContainer.hide();
				}
			},

			_loadContent: function() {
				if (this.el.$window.scrollTop() > 0) {
					this.el.$backToTop.fadeIn();
				} else {
					this.el.$backToTop.fadeOut();
				}

				if (!this.ALL_CONTENT_LOADED &&
					this.el.$window.scrollTop() >= this.el.$loadThresholdElem.position().top) {

					$.get('ajax/all_episodes.php', function(data) {
						if (data) {
							data = JSON.parse(data);
							Object.keys(data).forEach(function(idAttr) {
								$('#' + idAttr).html(data[idAttr]);
							});
						}
					});

					this.ALL_CONTENT_LOADED = true;
				}
			},

			_revealSoundCloud: function(evt) {
				var $header = $(evt.target),
						trackId = $header.data('trackid'),
						iframe = '<iframe width="100%" height="166" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/' + trackId + '&amp;color=ff6600&amp;auto_play=true&amp;show_artwork=true"></iframe>';

				if (!this.embeddedPlayers[trackId]) {
					$header.after(iframe).find('i').fadeOut();
				}

				this.embeddedPlayers[trackId] = true;
			},

			init: function() {
				this.getElements();
				this.bindEvents();
				this.buildPage();
			}
		};
	};

	var mod = new Module();
	mod.init();

}());