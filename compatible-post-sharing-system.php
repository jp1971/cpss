<?php
/*
Plugin Name: Compatible Post-Sharing System
Description: The Compatible Post Sharing System allows site visitors to share posts and pages via email.
Version: 1.2
Author: Jameson Proctor, Athleticsnyc
Author URI: http://jp1971.com/, http://athleticsnyc.com
License: GPL2
*/

/*  Copyright 2014 JP1971 (jameson@jp1971.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/

class KrnlCompatiblePostSharingSystem {

	/*
	Why Compatible Post Sharing System?

	With the introduction of MIT's Compatible Time-Sharing System (CTSS) in 
	1961 multiple users were able to log into a central system from remote
	dial-up terminals, and to store and share files on the central disk. 
	Informal methods of using this to pass messages developed and were 
	expanded to create the first system worthy of the name "email".
	*/

	private static $instance = false;

	public static function get_instance() {
	  if ( ! self::$instance ) {
	    self::$instance = new self();
	  }
	  return self::$instance;
	}

	private function __construct() {

		//Define plugin specific variables

		// add_options_page variables
		$this->options_page_title = 'Compatible Post Sharing System';
		$this->options_page_menu_title = 'CPSS Settings';
		$this->options_page_menu_slug ='cpss';

		// add_settings_section variables
		$this->section_id = 'cpss_section';
		$this->section_title = '';
		$this->section_callback = array( $this, 'cpss_section_callback' );

		// register_setting/register_settings_fields variables
		$this->option_group = 'cpss';

		// Add admin menu and settings action hooks
		add_action( 'admin_menu', array( $this, 'add_plugin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_plugin_settings' ) );

		// Enqueue styles and scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles') );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Add AJAX Actions
		add_action( 'wp_ajax_cpss_send_email', array( $this, 'send_email' ) );
		add_action( 'wp_ajax_nopriv_cpss_send_email', array( $this, 'send_email' ) );

		// Add shortcode
		add_shortcode( 'cpss', array( $this, 'shortcode' ) );

		// Register uninstall hook
		register_uninstall_hook( __FILE__, array( 'KrnlCompatiblePostSharingSystem' , 'uninstall' ) );
	}

	public function add_plugin_menu() {
		// Add options page
		// http://codex.wordpress.org/Function_Reference/add_options_page
		add_options_page( 
			$this->options_page_title,
			$this->options_page_menu_title,
			'manage_options',
			$this->options_page_menu_slug,
			array( $this, 'render_options_page' )
		);
	}

	public function register_plugin_settings() {

		// Add settings section(s)
		// http://codex.wordpress.org/Function_Reference/add_settings_section
		add_settings_section(
			$this->section_id,
			$this->section_title,
			$this->section_callback,
			$this->options_page_menu_slug
		);

		// Add settings field(s)
		// e.g. add_settings_field( $id, $title, $callback, $page, $section, $args );
		// http://codex.wordpress.org/Function_Reference/add_settings_field
		add_settings_field(
			'cpss_subject',
			'Subject',
			array( $this, 'subject_callback' ),
			$this->options_page_menu_slug,
			$this->section_id
		);

		add_settings_field(
			'cpss_message',
			'Message',
			array( $this, 'message_callback' ),
			$this->options_page_menu_slug,
			$this->section_id
		);

		// Register plugin settings
		// register_setting( $option_group, $option_name, $sanitize_callback );
		// http://codex.wordpress.org/Function_Reference/register_setting	
		register_setting ( $this->option_group, 'cpss_subject' );
		register_setting ( $this->option_group, 'cpss_message' );
	}

	public function cpss_section_callback() {
		echo 'The default values of the Subject and Message fields in the CPSS pop-up can be set using the fields below. These fields support two tags, [page_title] and [url], which will be replaced by the page name and url on the front end.';
	}

	public function subject_callback() {
		$subject = esc_attr( get_option( 'cpss_subject' ) );
		echo "<input type='text' size='40' name='cpss_subject' value='$subject'>";
	}

	public function message_callback() {
		$message = esc_attr( get_option( 'cpss_message' ) );
		echo "<textarea name='cpss_message' cols='40' rows='6'>$message</textarea>";
	}

	public function render_options_page() {
	?>
		<div class="wrap">
			<h2><?php echo $this->options_page_title; ?></h2>
			<form action="options.php" method="POST">
				<?php settings_fields( $this->option_group ); ?>
				<?php do_settings_sections( $this->options_page_menu_slug ); ?>
				<?php submit_button(); ?>
			</form>
		</div>
	<?php
	}
	
	public function enqueue_styles() {
		wp_enqueue_style(
			'cpss', //$handle
			plugins_url( '/css/cpss/cpss.css', __FILE__ ),//$src
			false, //$deps (dependencies)
			'1.0', //$ver
			'screen' //$media
	   	);

	   	wp_enqueue_style(
			'magnific-popup', //$handle
			plugins_url( '/css/vendor/magnific-popup/magnific-popup.css', __FILE__ ),//$src
			false, //$deps (dependencies)
			'0.9.9', //$ver
			'screen' //$media
	   	);
	}

	public function enqueue_scripts() {
		wp_enqueue_script(
			'cpss',//$handle
			plugins_url( '/js/cpss/cpss.js', __FILE__ ),//$src
	    	array( 'jquery', 'magnific-popup' ), //$deps (dependencies)
			'1.0', //$ver
			true //$in_footer
		);
		wp_localize_script( 'cpss', 'cpss_ajax', array( 'url' => home_url( 'wp-admin/admin-ajax.php' ), 'nonce' => wp_create_nonce( 'cpss_ajax_nonce' ) ) );

		wp_enqueue_script(
			'magnific-popup',//$handle
			plugins_url( 'js/vendor/magnific-popup/jquery.magnific-popup.min.js', __FILE__),//$src
	    	array( 'jquery' ), //$deps (dependencies)
			'0.9.9', //$ver
			true //$in_footer
		);
	}

	public function prepare_for_akismet( $form ) {
		$form['comment_type'] = 'contact_form';
		$form['user_ip']      = preg_replace( '/[^0-9., ]/', '', $this->get_user_ip() );
		$form['user_agent']   = $_SERVER['HTTP_USER_AGENT'];
		$form['referrer']     = $_SERVER['HTTP_REFERER'];
		$form['blog']         = home_url();
	 
		$ignore = array( 'HTTP_COOKIE' );
	 
		foreach ( $_SERVER as $k => $value ) {
			if ( !in_array( $k, $ignore ) && is_string( $value ) ) {
				$form["$k"] = $value;
			}
		}
	 
		return $form;
	}

	function is_spam_akismet( $form ) {
		global $akismet_api_host, $akismet_api_port;
	 
		if ( ! function_exists( 'akismet_http_post' ) ) {
			return false;
		} 
	 
		$query_string = http_build_query( $form );
	 
		$response = akismet_http_post( $query_string, $akismet_api_host, '/1.1/comment-check', $akismet_api_port );
		$result = false;
	 
		// 'true' is spam
		if ( 'true' == trim( $response[1] ) ) {
			$result = true;
		}
	 
		return $result;
	}

	public function get_user_ip() {
	 
		if ( isset( $_SERVER["HTTP_CLIENT_IP"] ) ) {
			$user_ip = $_SERVER["HTTP_CLIENT_IP"];
		} else if ( isset( $_SERVER["HTTP_X_FORWARDED_FOR"] ) ) {
			$user_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		} else {
			$user_ip = $_SERVER["REMOTE_ADDR"];
		}
	 
		return $user_ip;
	}

	public function send_email() {

		// Get $nonce
		$nonce = sanitize_text_field( $_GET['nonce'] );

		if ( ! wp_verify_nonce( $nonce, 'cpss_ajax_nonce' ) ) {
			echo json_encode( 'Nonce check failed!' );
			die(); 
		} else {

			//Get nonce and fields
			$to = sanitize_text_field( $_GET['to'] );
			$theirs = sanitize_email( $_GET['theirs'] );
			$from = sanitize_text_field( $_GET['from'] );
			$yours = sanitize_email( $_GET['yours'] );
			$subject = sanitize_text_field( $_GET['subject'] );
			$message = wp_kses( $_GET['message'] );

			// Check for spam
			$fields = array(
				$to,
				$theirs,
				$from,
				$yours,
				$message
			);

			$form = $this->prepare_for_akismet( compact( $fields ) );
			if ( ! $this->is_spam_akismet( $form ) ) {
				if( empty( $from ) ) {
					$from = $yours;
				}
				//Set $headers
				$headers[] = "From: $from <$yours>";
				// Send email
				if ( wp_mail( $theirs, $subject, $message, $headers ) ) {
					$json = json_encode( "Your message was successfully sent to $theirs." );
					echo $json;
					die();
				} else {
					$json = json_encode( 'There was a problem sending your messsage. Please try again. If you continue to have issues, please contact the site\'s administrator.' );
					echo $json;
					die();
				}
			} else {
				$json = json_encode( 'The Compatible Post Sharing System thinks this message is spam.' );
				echo $json;
				die();
			}
		}
	}	

	public function shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'link' => 'Email',
			'title' => 'Email This Page'
			), $atts ) );

			global $wp;
			$url = home_url( $wp->request );
			$ttl = get_the_title();
			$form = '<form id="cpss-form" class="mfp-hide cpss-popup">[content]</form>';
			$template = '<form id="cpss-form" class="cpss-popup">[content]</form>';

		ob_start();
		?>
		<h3><?php echo $title; ?></h3>
		<fieldset style="border:0;">
			<div class="form-group inline left">
				<label for="cpss_recipient_name">To Name</label>
			  	<input type="text" class="form-control" id="cpss_recipient_name" placeholder="Recipient Name">
			</div>
			<div class="form-group inline">
				<label for="cpss_recipient_email">To Email Address</label>
			  	<input type="email" class="form-control" id="cpss_recipient_email" placeholder="recipient@something.com">
			</div>
			<div class="form-group inline left">
				<label for="cpss_sender_name">From Name</label>
			  	<input type="text" class="form-control" id="cpss_sender_name" placeholder="Sender Name">
			</div>
			<div class="form-group inline">
				<label for="cpss_sender_email">From Email Address</label>
			  	<input type="email" class="form-control" id="cpss_sender_email" placeholder="sender@something.com">
			</div>
			<div class="form-group">
				<label for="cpss_subject">Subject</label>
			  	<input type="text" class="form-control" id="cpss_subject" value="<?php echo esc_attr( get_option( 'cpss_subject' ) ); ?>">
			</div>
			<div class="form-group">
				<label for="cpss_message">Message</label>
			  	<textarea class="form-control" id="cpss_message" rows="5"><?php echo esc_attr( get_option( 'cpss_message' ) ); ?></textarea>
			</div>
			<button type="submit" id="cpss_submit" class="btn btn-primary">Send</button>
		</fieldset>
		<?php
		$form_contents = ob_get_clean();
		$form = str_replace( '[content]', $form_contents, $form );
		$template = str_replace( '[content]', $form_contents, $template );
		$cpss = "<a class='cpss-form' href='#cpss-form' data-title='' data-url='" . esc_attr( $url ) . "'>$link</a>" . $form . "<script id='cpss-template' type='text/template'>$template</script>";

		return $cpss;
	}

	public function uninstall() {

		// Single site
		if ( !is_multisite() ) 
		{
			// Delete options
		    delete_option( 'cpss_subject' );
		    delete_option( 'cpss_message' );
		} 
		// Multisite
		else 
		{
		    global $wpdb;
		    $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
		    $original_blog_id = get_current_blog_id();
		    foreach ( $blog_ids as $blog_id ) {
	    		// Delete options
	    	    delete_option( 'cpss_subject' );
	    	    delete_option( 'cpss_message' );		     
		    }
		    switch_to_blog( $original_blog_id );
		}
	}
}
$krnl_cpss = KrnlCompatiblePostSharingSystem::get_instance();