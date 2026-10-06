/**
 * plugins/themes/revista/js/navbar.js
 *
 * Dropdown behaviour for the navbar blocks (#sidebarUser, #sidebarNavigation).
 *
 * Loaded by RevistaThemePlugin::activate() through
 * PKPTemplateManager::addJavaScript(), which appends the tag to
 * {$additionalHeadData} — no template change required.
 *
 * Plain ES5-compatible DOM code, no jQuery dependency, so it works even if the
 * CDN jQuery block in common/header.tpl fails.
 *
 * Markup contract (see plugins/blocks/user/block.tpl and
 * plugins/blocks/navigation/block.tpl):
 *
 *   <div class="block" id="sidebarUser">
 *     <h2 class="blockTitle">
 *       <button type="button" class="blockToggle" aria-expanded="false">…</button>
 *     </h2>
 *     …panel contents…
 *   </div>
 *
 * Styling the trigger as the visible title and everything else as the panel is
 * done in css/dropdown.css.
 */
(function () {
	'use strict';

	var BAR_ID = 'navbarBar';

	function eachBlock(root, fn) {
		var blocks = root.querySelectorAll('#leftSidebar > .block');
		var i;
		for (i = 0; i < blocks.length; i++) {
			if (blocks[i].querySelector('.blockToggle')) {
				fn(blocks[i]);
			}
		}
	}

	function setOpen(block, open) {
		var toggle = block.querySelector('.blockToggle');
		if (!toggle) return;

		if (open) {
			block.classList.add('is-open');
		} else {
			block.classList.remove('is-open');
		}
		toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
	}

	function closeAll(except) {
		eachBlock(document, function (block) {
			if (block !== except) setOpen(block, false);
		});
	}

	function init() {
		var bar = document.getElementById(BAR_ID);
		if (!bar) return;

		// Only one dropdown open at a time
		eachBlock(bar, function (block) {
			var toggle = block.querySelector('.blockToggle');
			if (!toggle) return;

			toggle.addEventListener('click', function (event) {
				event.preventDefault();
				event.stopPropagation();

				var willOpen = toggle.getAttribute('aria-expanded') !== 'true';
				closeAll(block);
				setOpen(block, willOpen);
			});

			// Pointer users expect hover on a navbar dropdown. Skip when the
			// device reports coarse pointer (touch) so a tap does not leave the
			// panel stuck open.
			if (window.matchMedia && window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
				block.addEventListener('mouseenter', function () {
					closeAll(block);
					setOpen(block, true);
				});

				block.addEventListener('mouseleave', function () {
					// Defer so a click on a link inside the panel still lands
					window.setTimeout(function () {
						if (!block.contains(document.activeElement)) setOpen(block, false);
					}, 120);
				});
			}
		});

		// Escape closes and returns focus to the trigger
		document.addEventListener('keydown', function (event) {
			if (event.key !== 'Escape' && event.keyCode !== 27) return;

			eachBlock(bar, function (block) {
				var toggle = block.querySelector('.blockToggle');
				if (block.classList.contains('is-open')) {
					setOpen(block, false);
					if (toggle) toggle.focus();
				}
			});
		});

		// Click anywhere else closes
		document.addEventListener('click', function (event) {
			if (bar.contains(event.target)) return;
			closeAll(null);
		});

		// Tabbing out of an open panel closes it
		bar.addEventListener('focusout', function (event) {
			if (bar.contains(event.relatedTarget)) return;
			closeAll(null);
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
}());