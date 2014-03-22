/**
 *		krnl js module:
 *			cpss.js
 *
 *		desc:
 *			Compatible Post-Sharing System JavaScript.
 *
 *		requires:
 *			jQuery
 */

var krnl = ( function( app, $ ) {

	/* define new module */
	app.cpss = ( function( $ ) {

		// private vars

		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

		function _init() {

			$( 'a.cpss-form' ).attr( 'data-title',
				function() {
					return encodeURI( document.title );
				});

			$( '.cpss-form' ).magnificPopup( {
				type: 'inline',
				preloader: false,
				focus: '#their_email',

				// When element is focused, some mobile browsers in some cases zoom in.
				// It does not look nice, so we disable it:
				callbacks: {
					beforeOpen: function() {

						if( $( window ).width() < 700 ) {
							this.st.focus = false;
						} else {
							this.st.focus = '#cpss_recipient_name';
						}
					},
					open: function() {						
						
						var ttl = decodeURI( $( 'a.cpss-form' ).attr( 'data-title' ) ),
							url = $( 'a.cpss-form' ).attr( 'data-url' ),
							sub = ( $( '#cpss_subject' ).val() ),
							msg = ( $( '#cpss_message' ).val() );

						$( '#cpss_subject' ).val( sub.replace( '[page_title]', ttl ) );
						$( '#cpss_message' ).val( msg.replace( '[url]', url ) );
					},
					close: function() {
						$( '#cpss-form' ).find( 'input[type=text], input[type=email], textarea' ).val( '' );
						$( '#cpss-form' ).html( $( '#cpss-template' ).html() );						
					}
				}
			} );

			$( '#cpss-form' ).submit( function( event ) {
				var nonce = cpss_ajax.nonce,
					to = ( $( '#cpss_recipient_name' ).val() ),
					theirs = ( $( '#cpss_recipient_email' ).val() ),
					from  = ( $( '#cpss_sender_name' ).val() ),
					yours = ( $( '#cpss_sender_email' ).val() ),
					subject = ( $( '#cpss_subject' ).val() ),
					message = ( $( '#cpss_message').val() );

			  	$.ajax({
			  	    url: cpss_ajax.url,
			  	    dataType:'json',
			  	    data: ( {action:'cpss_send_email', nonce:nonce, to:to, theirs:theirs, from:from, yours:yours, subject:subject, message:message} ),
			  	    success: function( json ) {
			  	    	$( '#cpss-form fieldset' ).css( 'display', 'none' );	
			  	    	$( '#cpss-form').append( '<div class="cpss_json_msg">' + json + '</div>' );
			  	    },
			  	    error: function( jqXHR, textStatus, errorThrown ) {
			  	    	$( '#cpss-form fieldset' ).css( 'display', 'none' );	
			  	    	$( '#cpss-form').append( '<div class="cpss_json_msg">There was a problem sending your messsage. Please try again.</div>' );
			  	    }
			  	});
			  	event.preventDefault();
			  	// $.magnificPopup.close();
			} );		
		}

		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

		/* return public-facing methods and/or vars */
		return {
			init : _init
		};
		
	}( $ ) );
	
	return app; /* return augmented app object */
	
}( krnl || {}, jQuery ) ); /* import app if exists, or create new; import jQuery */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

// register this module for initialization
krnl.bootstrap.register( krnl.cpss.init );