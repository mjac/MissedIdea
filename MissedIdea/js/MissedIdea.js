
function MissedIdea() {
	this.requestList = new MissedIdea.RequestList(10); 
};

MissedIdea.prototype = {
	getCategory: function() {
		var ideaContainer = $('#ideas ol');
		
		if (ideaContainer.is('[data-category]')) {
			return parseInt(ideaContainer.attr('data-category'));
		} else {
			return null;
		}
	},

	submitIdea: function(ideaForm) {
		var inputNode = $(ideaForm).find('input');

		var ideaInput = new MissedIdea.TextInput(inputNode);
		if (!ideaInput.hasText()) {
			return;
		}

		var request = {
			idea: ideaInput.getText()
		};
		
		var category = this.getCategory();
		if (category !== null) {
			request.id = category;
		}

		var successFunc = function (data) {
			var ideaContent = ich.idea(data);
			$('#ideas ol').prepend(ideaContent);

			ideaContent.addClass('hide');
			ideaContent.fadeIn('slow');

			ideaInput.resetText();
			inputNode.blur();
		};

		this.requestList.createRequest(
			'create idea \'' + request.idea + '\'',
			$(ideaForm).attr('action'),
			successFunc,
			request
		);
	},

	submitComment: function(commentForm) {
		var inputNode = $(commentForm).find('input');

		var commentInput = new MissedIdea.TextInput(inputNode);
		if (!commentInput.hasText()) {
			return;
		}

		var request = {
			commentText: commentInput.getText()
		};

		var successFunc = function (commentData) {
			var ideaElem = $(commentForm).closest('li');
			this.showComments(ideaElem, commentData);

			commentInput.resetText();
			inputNode.blur();
		}.bind(this);

		this.requestList.createRequest(
			'submit comment \'' + request.commentText + '\'',
			$(commentForm).attr('action'), 
			successFunc,
			request
		);
	},

	showComments: function(ideaElem, commentData) {
		ideaElem.find('.commentList').remove();

		var commentHtml = ich.comments(commentData);

		var commentContainer = ideaElem.find('.commentContainer');
		commentContainer.append(commentHtml);
	}
};

MissedIdea.RequestList = function(timeoutSeconds) {
	this._timeoutSeconds = timeoutSeconds;
	this._successHandlers = {};
};
MissedIdea.RequestList.prototype = {
	createRequest: function(title, url, success, data) {
		if (title in this._successHandlers) {
			this.showFeedback('Already trying to ' + title);
			return;
		}

		var successWrapperFunc = function() {
			if (title in this._successHandlers && this._successHandlers[title] === successWrapperFunc) {
				delete this._successHandlers[title];
				success.apply(null, arguments);
			}
		}.bind(this);

		this._successHandlers[title] = successWrapperFunc;

		setTimeout(function() {
			if (title in this._successHandlers && this._successHandlers[title] === successWrapperFunc) {
				this.showFeedback('Could not ' + title);
				delete this._successHandlers[title];
			}
		}.bind(this), this._timeoutSeconds * 1000);

		var errorFunc = function() {
			this.showFeedback('Error trying to ' + title);
			delete this._successHandlers[title];
		}.bind(this);

		$.ajax({
			type: 'POST',
			url: url,
			data: data,
			success: successWrapperFunc,
			error: errorFunc,
			dataType: 'json'
		});
	},

	showFeedback: function(message) {
		console.log(message);
	}
};

MissedIdea.TextInput = function(inputNode) {
	this._node = inputNode;
};
MissedIdea.TextInput.prototype = {
	getText: function() {
		return $(this._node).attr('value');
	},

	getOriginalText: function() {
		return this._node.data('original');
	},

	hasText: function() {
		var originalText = this.getOriginalText();
		var currentText = this.getText();

		return typeof originalText !== 'undefined'
			&& originalText !== currentText
			&& currentText.length > 0;
	},

	resetText: function() {
		var originalText = this.getOriginalText();

		if (typeof originalText !== 'undefined') {
			$(this._node).attr('value', originalText);
		}
	}
};

MissedIdea.ErrorMessage = function(message) {
	this._message = message;
};



$(document).ready(function () {
	var MI = new MissedIdea();

	$('#navigation').on('click', 'a', function (ev) {
		var successFunc = function (response) {
			var ideaListElem = $('#ideas ol');

			ideaListElem.empty();

			ideaListElem.attr('data-category', response.category);

			$.each(response.ideas, function (ideaIndex, ideaObject) {
				ideaListElem.append(ich.idea(ideaObject));
			});
		};

		MI.requestList.createRequest(
			'get category \'' + $(ev.target).text() + '\'',
			$(ev.target).attr('href'),
			successFunc
		);

		return false;
	});

	$('#ideas').on('click', 'a.showComments', function (ev) {
		var ideaElem = $(ev.target).closest('li');
		var commentContainer = ideaElem.find('.commentContainer');

		if (!commentContainer.hasClass('hide')) {
			commentContainer.addClass('hide');
			return false;
		}

		var successFunc = function (commentData) {
			MI.showComments(ideaElem, commentData);
			commentContainer.removeClass('hide');
		};

		MI.requestList.createRequest(
			'show comments for idea \'' + ideaElem.find('> p').text() + '\'',
			$(ev.target).attr('href'),
			successFunc
		);

		return false;
	});

	$('#ideas').on('click', '.rating a', function (ev) {
		var successFunc = function (data) {
			var ideaContent = ich.idea(data);
			$(ev.target).closest('.idea').replaceWith(ideaContent);
		};

		MI.requestList.createRequest(
			'vote for idea \'' + $(ev.target).closest('li').find('> p').text() + '\'',
			$(ev.target).attr('href'),
			successFunc
		);

		return false;
	});

	$('#ideas').delegate('input[type=text]', 'focus', function (ev) {
		var originalIdeaValue = $(this).data('original');
		if (typeof originalIdeaValue === 'undefined') {
			$(this).data('original', $(this).attr('value'));
		}

		if ($(this).attr('value') === $(this).data('original')) {
			$(this).attr('value', '');
		}
	});

	$('#ideas').delegate('input[type=text]', 'blur', function (ev) {
		if ($(this).attr('value') === '') {
			$(this).attr('value', $(this).data('original'));
		}
	});

	$('#formSubmitIdea').on('submit', function (ev) {
		MI.submitIdea(this);
		return false;
	});

	$('#ideas').delegate('.commentContainer form', 'submit', function (ev) {
		MI.submitComment(this);
		return false;
	});
});

window.fbAsyncInit = function() {
	FB.init({
		appId: '320148841392206',
		status: true, 
		cookie: true,
		oauth: true,
		xfbml: true,
		logging: true
	});
};

(function(d){
	var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
	js = d.createElement('script'); js.id = id; js.async = true;
	js.src = "//connect.facebook.net/en_US/all.js";
	d.getElementsByTagName('head')[0].appendChild(js);
 }(document));
