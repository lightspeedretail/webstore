;
(function ($, window, document) {
	$.fn.smartNotification = function (options) {
		var setting = $.extend({
			mainTitle: '',
			mainContent: '',
			hiddenContent: '',
			toggleSwitch: 'off',
			onlyOne: false,
			timerGo: false,
			timerGoTime: 3000,
			overlayOpacity: 0.7,
			overlayShowSpeed: 500,
			overlayHideSpeed: 1000,
			easingEffect: 'easeOutExpo',
			autoResizeBox: true,
			caseShow: 700
		},
		options);
		var getAttrVal = this.attr('id');
		var $body = $('body');
		var $html = $('html');
		if (!$('#case_press').length) {
			$("<div id='case_press'></div>").appendTo($body)
		};
		if (!$('#general_overlay').length) {
//			$("<div id='general_overlay'><audio id='bepp_on' preload='auto'><source src='/sound/3beeps.ogg' type='audio/ogg'><source src='/sound/3beeps.mp3' type='audio/mp3'></audio></div>").appendTo($body);
			$("<div id='general_overlay'>&nbsp;</div>").appendTo($body);
			$body.on('click.noteOverlay', '#general_overlay', function () {
				var genThis = $(this).siblings('[data-case]');
				removeOverCase(genThis, 'yes')
			})
		};
		var genOverlay = $('#general_overlay');
		var casePress = $('#case_press');
		casePress.off('click.noteToggle').on('click.noteToggle', '[data-toggle]', function () {
			var dataToggle = $(this);
			var ptopHeight = dataToggle.siblings('[data-ptop]').height();
			var attrDataToggle = 'data-toggle';
			var attrDataSwitch = 'data-switch';
			if (!dataToggle.attr(attrDataSwitch) || dataToggle.attr(attrDataSwitch) == 'off') {
				dataToggle.attr(attrDataSwitch, 'on');
				dataToggle.parent('[data-case]').css('height', ptopHeight).addClass('clear-p')
			} else if (dataToggle.attr(attrDataSwitch) == 'on') {
				dataToggle.attr(attrDataSwitch, 'off');
				dataToggle.parent('[data-case]').removeClass('clear-p').css({
					'height': '',
					'visibility': 'visible'
				})
			}
		});
		function removeOverCase(bro, swi) {
			bro.stop(true).fadeTo(500, 0).queue(function () {
				$(this).empty().remove();
				$(this).dequeue()
			}).find('[data-hidcont]').hide();
			var switchClose = swi;
			if (switchClose == 'yes') {
				genOverlay.stop(true).fadeTo(setting.overlayHideSpeed, 0).css('display', 'none');
				$html.css('overflow', 'auto')
			}
		};
		$body.off('click.noteClose').on('click.noteClose', '[data-close]', function () {
			var dataCaseParent = $(this).parent('[data-case]');
			removeOverCase(dataCaseParent, 'yes')
		});
		casePress.off('click.noteOpen').on('click.noteOpen', '[data-open]', function () {
			var dataOpen = $(this);
			var thisParentDataCase = dataOpen.parent('[data-case]');
			dataOpen.siblings('[data-hidcont]').show();
			(setting.autoResizeBox == true) ? thisParentDataCase.appendTo('body').addClass('auto_center').css({
				'width': '30%',
				'height': 'auto'
			}) : thisParentDataCase.appendTo('body').addClass('auto_center').css('height', 'auto');
			$html.css('overflow', 'hidden');
			dataOpen.hide().siblings('[data-toggle]').hide();
			$(window).triggerHandler('resize.noteResize');
			genOverlay.stop(true).fadeTo(setting.overlayShowSpeed, setting.overlayOpacity)
		});
		$(window).off('resize.noteResize').on('resize.noteResize', function () {
			var thWinH = $(window).height();
			var thWinW = $(window).width();
			var xheight = $('.auto_center').innerHeight();
			var ywidth = $('.auto_center').innerWidth();
			var caly = thWinH / 2 - xheight / 2;
			var calx = thWinW / 2 - ywidth / 2;
			$('.auto_center').stop(true).animate({
				'top': caly,
				'right': calx
			},
			1000, setting.easingEffect)
		});
		var myaudio = $("#bepp_on")[0];
		return this.each(function () {
			var $this = $(this);
			$this.click(function () {
				$("<div data-case='" + getAttrVal + "'><div data-ptop='" + getAttrVal + "'>" + setting.mainTitle + "</div>" + setting.mainContent + "<div data-hidcont='" + getAttrVal + "'>" + setting.hiddenContent + "</div></div>").prependTo(casePress).fadeTo(800, 1);
				var dataCase = $('[data-case]');
				var caseHeight = casePress.height();
				var ptopHeight = $('[data-ptop]').height();
				var attrDataToggle = 'data-toggle';
				var attrDataSwitch = 'data-switch',
				thAttrCase = $("[data-case = '" + getAttrVal + "']");
				if (setting.toggleSwitch == 'on') {
					thAttrCase.css('height', ptopHeight).addClass('clear-p').find("[data-toggle = '" + getAttrVal + "']").attr(attrDataSwitch, 'on')
				};
				function remover(e) {
					e.eq( - 1).stop(true).fadeTo(100, 0).queue(function () {
						$(this).empty().remove();
						$(this).dequeue()
					})
				};
				function onlyOne() {
					if (thAttrCase.length > 1) {
						remover(thAttrCase)
					}
				};
				if (caseHeight > $(window).height() && dataCase.length > 1) {
					remover(dataCase)
				};
				if (setting.onlyOne == true) {
					onlyOne()
				};
				if (setting.timerGo == true) {
					setTimeout(function () {
						var timeDat = $('[data-case=' + getAttrVal + ']');
						var swi = 'no';
						if (dataCase.attr('class') == 'auto_center') {
							swi = 'yes'
						};
						removeOverCase(timeDat, swi)
					},
					setting.timerGoTime)
				};
				if ($.support.style == true) {
					myaudio.play()
				}
			})
		})
	}
})(jQuery, window, document);