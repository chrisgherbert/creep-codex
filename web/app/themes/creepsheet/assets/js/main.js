/**
 * MAIN JAVASCRIPT FILE
 * Other javascript files concatenated via Gulp
 * User devvars.js and gulpvars.js files to modify Gulp variables
 */

(function($, global) {

	"use strict";

	var website = {

		init: function() {
			this.mobileMenu();
			this.ui();
		},

		mobileMenu: function() {

			var transitionEnd = "webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend";
			var $drawer = $("#mobileDrawer");
			var $menu = $("#mobileMenu");
			var $trigger = $("#mobileTrigger");
			var $close = $("#mobileMenuClose");
			var settings = { activeClass: "js-active" };

			$trigger.on("click", toggleMenu);

			function toggleMenu(e) {
				e.preventDefault();
				if (!$drawer.hasClass(settings.activeClass)){
					openMenu();
				} else {
					closeMenu();
				}
			}

			function openMenu() {
				$drawer.addClass(settings.activeClass);
				$menu
					.attr({
						"aria-hidden" : "false",
						"tabindex": "-1"
					})
					.on(transitionEnd, function() {
						$(document).on("keydown", onKeyDown);
						$drawer.on("click", onDrawerClick);
						$close.on("click", onCloseClick);
					});
			}

			function closeMenu() {
				$drawer.removeClass(settings.activeClass);
				$menu
					.removeAttr("tabindex")
					.attr("aria-hidden", "true")
					.on(transitionEnd, function() {
						$(document).off("keydown", onKeyDown);
						$drawer.off("click", onDrawerClick);
						$close.off("click", onCloseClick);
					});
			}

			function onDrawerClick(e) {
				if (e.target !== this) {
					return;
				}
				closeMenu();
			}

			function onCloseClick(e) {
				e.preventDefault();
				closeMenu();
			}

			function onKeyDown(e) {
				if (e.keyCode === 27) {
					closeMenu();
				}
			}

		},

		ui: function() {

			/* Smooth scrolling */
			$("a[href^='#']").smoothScroll();

		}

	};

	jQuery(document).ready(function($) {

		website.init();

	});

})(jQuery, window);