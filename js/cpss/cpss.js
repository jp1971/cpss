//	cpss js module:
//		cpss.js

//	desc:
//		Compatible Post-Sharing System JavaScript.

//	requires:
//		jQuery, Magnific Popup

var cpss = ( function( app, $ ) {

	/* define new module */
	app.js = ( function( $ ) {

		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

		function _init() {

			$( '.cpss-form' ).magnificPopup( {
				type: 'inline',
				preloader: false,

				// When element is focused, some mobile browsers in some cases zoom in.
				// It does not look nice, so we disable it:
				callbacks: {
					beforeOpen: function() {

						if( $( window ).width() < 700 ) {
							this.st.focus = false;
						} else {
							this.st.focus = '.cpss_recipient_name';
						}
					},
					open: function() {						
						
						var ttl = decodeURI( $( this.currItem.el ).attr( 'data-title' ) ),
							url = $( this.currItem.el ).attr( 'data-url' ),
							sub = ( $( '.cpss_subject' ).val() ),
							msg = ( $( '.cpss_message' ).val() );

						$( '.cpss_subject' ).val( sub.replace( '[page_title]', ttl ) );
						msg = msg.replace( '[url]', url );
						$( '.cpss_message' ).val( msg.replace( '[page_title]', ttl ) );
					},
					close: function() {
						$( 'form[id^=cpss-form-]' ).find( 'input[type=text], input[type=email], textarea' ).val( '' );
						$( 'form[id^=cpss-form-]' ).html( $( '#cpss-template' ).html() );						
					}
				}
			} );

			$( 'form[id^=cpss-form-]' ).submit( function( event ) {
				var $this = $( this ),
					nonce = cpss_ajax.nonce,
					to = ( $( '.cpss_recipient_name' ).val() ),
					theirs = ( $( '.cpss_recipient_email' ).val() ),
					from  = ( $( '.cpss_sender_name' ).val() ),
					yours = ( $( '.cpss_sender_email' ).val() ),
					subject = ( $( '.cpss_subject' ).val() ),
					message = ( $( '.cpss_message' ).val() );

			  	$.ajax({
			  	    url: cpss_ajax.url,
			  	    dataType:'json',
			  	    data: ( {action:'cpss_send_email', nonce:nonce, to:to, theirs:theirs, from:from, yours:yours, subject:subject, message:message} ),
			  	    success: function( json ) {
			  	    	$this.find( 'fieldset' ).css( 'display', 'none' );	
			  	    	$this.append( '<div class="cpss_json_msg">' + json + '</div>' );
			  	    },
			  	    error: function( jqXHR, textStatus, errorThrown ) {
			  	    	$this.find( 'fieldset' ).css( 'display', 'none' );	
			  	    	$this.append( '<div class="cpss_json_msg">There was a problem sending your messsage. Please try again.</div>' );
			  	    }
			  	});
			  	event.preventDefault();
			} );		
		}

		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

		/* return public-facing methods and/or vars */
		return {
			init : _init
		};
		
	}( $ ) );
	
	return app; /* return augmented app object */
	
}( cpss || {}, jQuery ) ); /* import app if exists, or create new; import jQuery */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

jQuery( document ).ready( function() {
	cpss.js.init();
} );