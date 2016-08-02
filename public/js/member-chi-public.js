(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	(function(_,c,h,i) {
		_.Chi = _.Chi || [];
		i = c.createElement("script");
		i.async = 1;
		i.setAttribute("data-team", options.team_id);
		i.setAttribute("data-api", options.api_key);
		i.setAttribute("data-email", options.email);
		i.setAttribute("data-wp_id", options.wp_id);
		i.src = options.url;
		h = c.getElementsByTagName("script")[0];
		h.parentNode.insertBefore(i, h);

		console.log('email:' + options.email);
	})(window,document);

})( jQuery );
