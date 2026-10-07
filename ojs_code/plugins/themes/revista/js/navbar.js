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
 *     <div class="blockPanel">…</div>
 *   </div>
 *
 * Two open modes, deliberately distinct:
 *
 *   .is-open   hover. The panel overlays the page, absolutely positioned, and
 *              the navbar keeps its height.
 *   .is-docked click. The panel becomes a full-width second row inside
 *              #navbarBar, so the bar grows and pushes the content down.
 *
 * Only one block is open at a time. An explicit click wins over hover until it
 * is dismissed.
 *
 * Styling: plugins/themes/revista/css/dropdown.css
 */
(function () {
	'use strict';

	var BAR_ID = 'navbarBar';
	var DOCKED_CLASS = 'navbarBar--docked';

	function eachBlock(root, fn) {
		var blocks = root.querySelectorAll('#leftSidebar > .block');
		var i;
		for (i = 0; i < blocks.length; i++) {
			if (blocks[i].querySelector('.blockToggle')) {
				fn(blocks[i]);
			}
		}
	}

	function toggleOf(block) {
		return block.querySelector('.blockToggle');
	}

	/*
	 * Docking moves the .blockPanel element itself.
	 *
	 * To become a full-width row inside #navbarBar the panel has to be a direct
	 * child of the bar, and CSS cannot promote a nested element to that
	 * position without `display: contents` on both of its ancestors — which
	 * leaves them box-less, makes DevTools highlight the entire page when one is
	 * selected, and shifts the row metrics by a few pixels. Reparenting keeps
	 * every ancestor a real box.
	 *
	 * Each block keeps its own comment anchor. A single shared anchor would be
	 * moved out of one block when docking another, so the first block's panel
	 * would be restored into the wrong place.
	 */
	function movePanel(block, intoBar) {
		var bar = document.getElementById(BAR_ID);
		var panel = block.panel;
		if (!bar || !panel) return;

		var anchor = block.panelAnchor;
		if (!anchor) {
			anchor = document.createComment('blockPanel');
			block.panelAnchor = anchor;
		}

		if (intoBar) {
			if (panel.parentNode === bar) return;

			panel.parentNode.insertBefore(anchor, panel);
			bar.appendChild(panel);
		} else {
			if (panel.parentNode !== bar) return;

			if (anchor.parentNode) {
				anchor.parentNode.replaceChild(panel, anchor);
			} else {
				block.appendChild(panel);
			}
		}
	}

	function setState(block, mode) {
		var toggle = toggleOf(block);
		var panel = block.panel;
		if (!toggle) return;

		movePanel(block, mode === 'dock');

		/*
		 * The state classes go on the panel as well as the block: once docked the
		 * panel is no longer a descendant of .block, so CSS keyed on
		 * `.block.is-docked .blockPanel` would stop matching it.
		 */
		block.classList.toggle('is-open', mode === 'hover');
		block.classList.toggle('is-docked', mode === 'dock');

		if (panel) {
			panel.classList.toggle('is-open', mode === 'hover');
			panel.classList.toggle('is-docked', mode === 'dock');
		}

		toggle.setAttribute('aria-expanded', mode ? 'true' : 'false');

		syncBar();
	}

	/* The bar only needs the docked modifier while something is docked */
	function syncBar() {
		var bar = document.getElementById(BAR_ID);
		if (!bar) return;

		var any = false;
		eachBlock(document, function (block) {
			if (block.classList.contains('is-docked')) any = true;
		});

		if (any) {
			bar.classList.add(DOCKED_CLASS);
		} else {
			bar.classList.remove(DOCKED_CLASS);
		}
	}

	function closeAll(except) {
		eachBlock(document, function (block) {
			if (block !== except) setState(block, null);
		});
	}

	function init() {
		var bar = document.getElementById(BAR_ID);
		if (!bar) return;

		eachBlock(bar, function (block) {
			var toggle = toggleOf(block);
			if (!toggle) return;

			/*
			 * Cache the panel and give it a stable id, so aria-controls keeps
			 * pointing at the right element after it is reparented into the bar.
			 */
			block.panel = block.querySelector('.blockPanel');
			if (block.panel) {
				if (!block.panel.id) block.panel.id = 'blockPanel-' + block.id;
				toggle.setAttribute('aria-controls', block.panel.id);
			}

			toggle.addEventListener('click', function (event) {
				event.preventDefault();
				event.stopPropagation();

				/* Toggle the docked panel, and drop any hover-open panel */
				var wasDocked = block.classList.contains('is-docked');
				closeAll(block);
				setState(block, wasDocked ? null : 'dock');
			});

			/*
			 * Pointer users expect hover. Skip when the device reports a coarse
			 * pointer so a tap does not leave the panel stuck open, and never
			 * override an explicit click.
			 */
			if (window.matchMedia && window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
				block.addEventListener('mouseenter', function () {
					if (block.classList.contains('is-docked')) return;

					closeAll(block);
					setState(block, 'hover');
				});

				block.addEventListener('mouseleave', function () {
					/* Defer so a click on a link inside the panel still lands */
					window.setTimeout(function () {
						if (!block.classList.contains('is-open')) return;
						if (!block.contains(document.activeElement)) setState(block, null);
					}, 120);
				});
			}
		});

		/* Escape closes everything and returns focus to the trigger */
		document.addEventListener('keydown', function (event) {
			if (event.key !== 'Escape' && event.keyCode !== 27) return;

			eachBlock(bar, function (block) {
				if (!block.classList.contains('is-open') && !block.classList.contains('is-docked')) return;

				var toggle = toggleOf(block);
				setState(block, null);
				if (toggle) toggle.focus();
			});
		});

		/* Click anywhere else closes */
		document.addEventListener('click', function (event) {
			if (bar.contains(event.target)) return;
			closeAll(null);
		});

		/* Tabbing out of an open panel closes it */
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