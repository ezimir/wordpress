
(function ($) {

	var fbfeed = 'fbfeed',
		fbuser = 'fbuser',
		settings_user = {},
		settings_feed = {},
		defaults_user = {
			params: 'name,picture,link,about',
			template: '#template-' + fbuser
		},
		defaults_feed = {
			items: 3,
			template: '#template-' + fbfeed
		},
		API_BASE = 'https://graph.facebook.com/',
		FB_PAGE_BASE = 'https://www.facebook.com/pages/',
		POST_BASE = 'https://www.facebook.com/permalink.php';

	function showError(message) {
		console.log(message);
	}

	function fbResponseUser(response) {
		if (response.error) {
			return showError(response.error.message);
		}

		$(settings_user.template).tmpl(response).appendTo(settings_user.$target);
	}

	function fbResponseFeed(response) {
		if (response.error) {
			return showError(response.error.message);
		}

		var items = [];
		for (var i = 0; post = response.data[i]; i++) {
			var date = /([\d-]+)T([\d:]+)\+(.+)/.exec(post.created_time),
				ids = /(\d+)_(\d+)/.exec(post.id),
				item = $.extend({}, post, {
					date: date[1],
					time: date[2],
					user_id: ids[1],
					post_id: ids[2],
				});
				item.permalink = POST_BASE + '?id=' + item.user_id + '&story_fbid=' + item.post_id;

				if (typeof item.message === 'undefined') {
					item.message = item.story;
				}

			items.push(item);
		}

		$(settings_feed.template).tmpl({ items: items }).appendTo(settings_feed.$target);
	}

	$.fn[fbuser] = function (options) {
		settings_user = $.extend({}, defaults_user, options);

		settings_user.$target = this;
		settings_user.$target.empty();

		$.ajax({
			url: API_BASE + settings_user.id + '/?fields=' + settings_user.params,
			dataType: 'jsonp',
			success: fbResponseUser
		});
	}

	$.fn[fbfeed] = function (options) {
		settings_feed = $.extend({}, defaults_feed, options);

		settings_feed.$target = this;
		settings_feed.$target.empty();

		$.ajax({
			url: API_BASE + settings_feed.id + '/feed/?limit=' + settings_feed.items + '&access_token=' + settings_feed.token,
			dataType: 'jsonp',
			success: fbResponseFeed
		});
	}

}(jQuery));
