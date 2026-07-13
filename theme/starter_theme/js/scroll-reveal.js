/**
 * Scroll reveal via Intersection Observer.
 */
( function () {
	'use strict';

	const revealSelector =
		'.reveal, .home-section, .service-card, .testimonial-card, .woocommerce ul.products li.product';

	const prefersReducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	/**
	 * Mark elements as visible immediately (AJAX swaps, reduced motion).
	 *
	 * @param {ParentNode} root Root node.
	 */
	function markVisible( root ) {
		root.querySelectorAll( revealSelector ).forEach( function ( el ) {
			el.classList.add( 'is-visible' );
		} );
	}

	/**
	 * Reveal archive content swapped in via AJAX.
	 */
	function revealArchiveContent() {
		document.querySelectorAll( '[data-archive-results]' ).forEach( function ( results ) {
			markVisible( results );
		} );
	}

	if ( prefersReducedMotion ) {
		markVisible( document );
		document.addEventListener( 'starterThemeArchiveUpdated', revealArchiveContent );
		window.starterThemeRevealArchiveContent = revealArchiveContent;
		return;
	}

	const revealElements = document.querySelectorAll( revealSelector );

	if ( ! revealElements.length ) {
		document.addEventListener( 'starterThemeArchiveUpdated', revealArchiveContent );
		window.starterThemeRevealArchiveContent = revealArchiveContent;
		return;
	}

	const observer = new IntersectionObserver(
		function ( entries ) {
			entries.forEach( function ( entry ) {
				if ( entry.isIntersecting ) {
					entry.target.classList.add( 'is-visible' );
					observer.unobserve( entry.target );
				}
			} );
		},
		{
			root: null,
			rootMargin: '0px 0px -8% 0px',
			threshold: 0.12,
		}
	);

	/**
	 * Observe reveal targets inside a container.
	 *
	 * @param {ParentNode} root Root node.
	 */
	function observeElements( root ) {
		root.querySelectorAll( revealSelector ).forEach( function ( el ) {
			if ( el.classList.contains( 'is-visible' ) ) {
				return;
			}

			if ( ! el.classList.contains( 'reveal' ) ) {
				el.classList.add( 'reveal' );
			}

			observer.observe( el );
		} );
	}

	observeElements( document );

	document.addEventListener( 'starterThemeArchiveUpdated', function () {
		revealArchiveContent();

		document.querySelectorAll( '[data-archive-results]' ).forEach( function ( results ) {
			observeElements( results );
		} );
	} );

	window.starterThemeRevealArchiveContent = revealArchiveContent;
}() );
