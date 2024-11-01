<?php
	/*
	Plugin Name: SocialShare
	Description: Give the option to share code snippets or download links before allowing access.
	Version: 1.30
	Author: Dylan Pollard [Noble Creative]
	Author URI: http://www.withnoble.com
	License: GPL2
	*/

	add_action('admin_menu', 'socialshare_plugin_settings');
	function socialshare_plugin_settings() {
		add_menu_page('SocialShare Settings', 'SocialShare', 'administrator', 'socialshare_settings', 'socialshare_display_settings', plugins_url('socialshare-basic/img/share-balloon.png'));
	}
	
	add_filter( 'plugin_action_links_' . plugin_basename(__FILE__),  'socialshare_plugin_link' );
	function socialshare_plugin_link($links) {
		$url = get_admin_url().'admin.php?page=socialshare_settings';
		$settings_link = '<a href="'.$url.'">'.esc_html(__('Settings')).'</a>';
		array_unshift($links, $settings_link);
		return $links;
	}


	function socialshare_display_settings() {

		$html = "";
		$selected_text = " checked='checked'";

		$theme_sunburst = (get_option('socialshare_pretty_theme') == 'sunburst') ? ' selected="selected"' : '';
		$theme_desert = (get_option('socialshare_pretty_theme') == 'desert') ? ' selected="selected"' : '';

		$text = (get_option('socialshare_text') != '') ? get_option('socialshare_text') : 'Example Text';

		if (get_option('socialshare_facebook')=="") { add_option('socialshare_facebook', 'enabled', '', 'yes'); }
		if (get_option('socialshare_twitter')=="") { add_option('socialshare_twitter', 'enabled', '', 'yes'); }
		if (get_option('socialshare_googleplus')=="") { add_option('socialshare_googleplus', 'enabled', '', 'yes'); }

		$facebook_enabled  = (get_option('socialshare_facebook') == 'enabled') ? $selected_text : '' ;
		$facebook_disabled  = (get_option('socialshare_facebook') == 'disabled'||get_option('socialshare_facebook') == '') ? $selected_text : '' ;
		$twitter_enabled  = (get_option('socialshare_twitter') == 'enabled') ? $selected_text : '' ;
		$twitter_disabled  = (get_option('socialshare_twitter') == 'disabled'||get_option('socialshare_twitter') == '') ? $selected_text : '' ;
		$googleplus_enabled  = (get_option('socialshare_googleplus') == 'enabled') ? $selected_text : '' ;
		$googleplus_disabled  = (get_option('socialshare_googleplus') == 'disabled'||get_option('socialshare_googleplus') == '') ? $selected_text : '' ;

		$html .= '<style>
			form hr { display:block;margin: 15px 0;border: 0;border-bottom:1px solid #DDD; }
			.socialshare-options-wrapper { max-width: 960px; border: 1px solid #DDD; padding:20px 30px 30px 30px; margin-top:16px; border-radius:4px; box-shadow:0 1px 3px rgba(0,0,0,0.1); }
			.socialshare-options-wrapper .options-title { margin-top:0;padding:0 0 12px 0; }
			.socialshare-options-wrapper .options-items { margin:0;padding:0;height:0px;overflow:hidden; }
			.socialshare-options-wrapper .options-items.active { height:auto }
			.socialshare-options-wrapper .options-items li { margin:12px 0;padding:8px;border:1px solid #DDD;border-radius:4px;box-shadow:0 1px 3px rgba(0,0,0,0.1); }
			.socialshare-options-wrapper .options-items li h4 { margin:0;padding:0px; }
			.socialshare-options-wrapper .options-items li .example { margin:0;padding:2px;font-weight:bold;font-family:monospace;color:#666; }
			.socialshare-options-wrapper .options-items li .example-option { color:#888; }
			.socialshare-options-wrapper .label-on { font-weight:bold;color:green; }
			.socialshare-options-wrapper .label-off { font-weight:bold;color:maroon; }
			
			.socialshare-created-by { color:#444;text-align:center;margin-top:20px; }
			.socialshare-created-by img.noble-logo { margin-top:20px;width:80px;height:50px; }
			.socialshare-created-by p { margin:0 0 1px 0;font-size:1em; }
			.socialshare-created-by p.sub-p { font-size:0.9em; }
		</style>';
		$html .= '<div class="wrap socialshare-options-wrapper">
		<form action="options.php" method="post" name="options">
			<h2 class="options-title">SocialShare Support and Settings</h2>';

			if ($_REQUEST['settings-updated'] == 'true') {
				$html .= '<div id="socialshare-settings-message" class="updated below-h2"><p>Settings updated.</p></div>';
			}

			$html .= wp_nonce_field('update-options').'<h3 style="margin:0;">How to use SocialShare</h3>
			<p>SocialShare was created to make it easy to distribute various types of media while keeping it simple for the end user. Instructions for using each feature are listed below, if you have any questions feel free to drop us a line.</p>
			<a href="#" class="view-shortcode-options">View Shortcode Examples</a>
			<ul class="options-items">
				<li>
					<h4>Code or Text Snippets</h4>
					<p><span class="example">[socialshare-snippet]</span>This is an example of the snippet shortcode<span class="example">[/socialshare-snippet]</span></p>
					<p>You can wrap any amount of text in the snippet shortcode, from a single line to a multi-page example of HTML/CSS code. The text snippet will be automatically color coded based on it\'s detected language(HTML, PHP, CSS, etc...). After the link has been shared, the user will have the option to copy the content to the clipboard or to view the code without copying it.</p>
					<p><em>Examples: Code Samples, Literary Quotes, Directions</em></p>
				</li>
				<li>
					<h4>Download Links</h4>
					<p><span class="example">[socialshare-download <span class="example-option">href="http://www.yoursite.com/download-this-file.zip"</span>]</span>Example File Download<span class="example">[/socialshare-download]</span></p>
					<p>With this shortcode, you can share a download link for any sort of file (zip, jpg, pdf, etc...). The content of the shortcode should be the title of the download, and the URL of the file should be included using the <strong>href</strong> option. If the href option is not specified, the link will be displayed but will take the user anywhere.</p>
					<p><em>Examples: PDF Forms, Audio Files, Screencasts</em></p>
				</li>
			</ul>

			<hr>

			<table>
			<tr>
				<td style="width:140px;"><strong>Social Networks</strong></td>
				<td style="width:40px;text-align:center;"><span class="label-on">On</span></td>
				<td style="width:40px;text-align:center;"><span class="label-off">Off</span></td>
			</tr>
			<tr>
				<td style="text-align:right;">Facebook</td>
				<td style="text-align:center;"><input type="radio" name="socialshare_facebook" value="enabled"'.$facebook_enabled.'></td>
				<td style="text-align:center;"><input type="radio" name="socialshare_facebook" value="disabled"'.$facebook_disabled.'></td>
			</tr>
			<tr>
				<td style="text-align:right;">Twitter</td>
				<td style="text-align:center;"><input type="radio" name="socialshare_twitter" value="enabled"'.$twitter_enabled.'></td>
				<td style="text-align:center;"><input type="radio" name="socialshare_twitter" value="disabled"'.$twitter_disabled.'></td>
			</tr>
			<tr>
				<td style="text-align:right;">Google+</td>
				<td style="text-align:center;"><input type="radio" name="socialshare_googleplus" value="enabled"'.$googleplus_enabled.'></td>
				<td style="text-align:center;"><input type="radio" name="socialshare_googleplus" value="disabled"'.$googleplus_disabled.'></td>
			</tr>
			</table>';
			/*
			<br><br>
			<strong>Code Highlighter Theme</strong><br>
			<select name="socialshare_pretty_theme">
				<option value="sunburst"'.$theme_sunburst.'>Sunburst</option>
				<option value="desert"'.$theme_desert.'>Desert</option>
			</select>
			<br><br>
			<strong>Example Text</strong><br>
			<input type="text" name="socialshare_text" value="'.$text.'">
			*/
			$html .= '<br><br>

			<input type="hidden" name="action" value="update">
			<input type="hidden" name="page_options" value="socialshare_facebook,socialshare_twitter,socialshare_googleplus">
			<input type="submit" name="Submit" value="Update SocialShare Settings">
		
		</form>
		</div>

		<div class="socialshare-created-by">
			<p>SocialShare is brought to you by the fine folks at <a href="http://www.withnoble.com" target="_blank" title="Noble Creative">Noble Creative</a>, a turn-key marketing department.</p>
			<p class="sub-p">Hire us to run your entire marketing strategy or to fill the gaps in your current team. <a href="http://www.withnoble.com" target="_blank">Click here to learn more.</a></p>
			<a href="http://www.withnoble.com" title="Noble Creative"><img src="'.plugin_dir_url('socialshare-basic/img/').'img/noble.jpg" alt="Noble Creative" class="noble-logo"></a>
		</div>

		<script>
		jQuery(document).ready(function(){
			var btn_view_shortcodes = jQuery(".view-shortcode-options"),
				shortcode_list = jQuery(".options-items");

			btn_view_shortcodes.on("click",function(){

				if (shortcode_list.hasClass("active")) {
					shortcode_list.removeClass("active");
					btn_view_shortcodes.text("View Shortcode Examples");
				} else {
					shortcode_list.addClass("active");
					btn_view_shortcodes.text("Close Shortcode Examples");
				}
				return false;
			});
		});
		</script>';

		echo $html;

	}



	function socialshare_snippet_function($atts, $content = null) {
		extract(shortcode_atts(array(
			'style' => 1,
		), $atts));
		
		$droid_sans = "<link href='http://fonts.googleapis.com/css?family=Droid+Sans+Mono' rel='stylesheet' type='text/css'>";

		$page_title = get_the_title();
		$page_url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

		$return_string = $droid_sans;

		$return_string .= '<div class="socialshare-wrapper">';

			$return_string .= '<div class="socialshare-copy-snippet">copy</div>';
			$return_string .= '<div class="socialshare-overlay">';
				
				$return_string .= '<div class="socialshare-share-continue">';
					$return_string .= '<a href="#" class="socialshare-continue-button">Click Here to Copy Snippet</a>';
					$return_string .= '<a href="#" class="socialshare-close-overlay">or click here to view the snippet without copying it</a>';
				$return_string .= '</div>';
				$return_string .= '<div class="socialshare-share-options">';
				$return_string .= '<strong>Share This Snippet</strong> to <strong>copy</strong> it to your clipboard<div class="clear"></div>';
				$return_string .= '<ul class="social-likes" data-url="'.$page_url.'" data-title="'.$page_title.'">';
					
					$text = (get_option('socialshare_text') == '') ? "" : get_option('socialshare_text');

					if (get_option('socialshare_facebook')!="" || get_option('socialshare_twitter')!="" || get_option('socialshare_googleplus')!="") {
						$enabled_facebook = (get_option('socialshare_facebook') == 'enabled') ? true : false;
						$enabled_twitter = (get_option('socialshare_twitter') == 'enabled') ? true : false;
						$enabled_googleplus = (get_option('socialshare_googleplus') == 'enabled') ? true : false;
					} else {
						$enabled_facebook = true;
						$enabled_twitter = true;
						$enabled_googleplus = true;
					}

					if ($enabled_facebook) { $return_string .= '<li class="facebook" title="Share link on Facebook">Facebook</li>'; }
					if ($enabled_twitter) { $return_string .= '<li class="twitter" title="Share link on Twitter">Twitter</li>'; }
					if ($enabled_googleplus) { $return_string .= '<li class="plusone" title="Share link on Google+">Google+</li>'; }

				$return_string .= '</ul>';
				$return_string .= '<div class="socialshare-progress-bar"><span></span></div>';
				$return_string .= '</div>';

			$return_string .= '</div>';

		$return_string .= '<pre class="socialshare prettyprint">';
		$return_string .= $content;
		$return_string .= '</pre>';

		$return_string .= '</div>';

		return $return_string;
	}

	function socialshare_download_function($atts, $content = null) {
		extract(shortcode_atts(array(
			'href' => '#',
		), $atts));

		$page_title = get_the_title();
		$page_url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		
		$return_string = "";

		$return_string .= '<div class="socialshare-wrapper socialshare-download-wrapper">';

			$return_string .= '<div class="socialshare-overlay">';
				
				$return_string .= '<div class="socialshare-share-continue">';
					$return_string .= '<a href="'.$href.'" target="_blank" class="socialshare-continue-button">Click Here to Download</a>';
				$return_string .= '</div>';
				$return_string .= '<div class="socialshare-share-options">';
				$return_string .= '<span class="socialshare-download-text"><strong>Share This Page</strong> to download '.$content.'</span>';
				$return_string .= '<ul class="social-likes" data-url="'.$page_url.'" data-title="'.$page_title.'">';
					
					$enabled_facebook = (get_option('socialshare_facebook') == 'enabled') ? true : false;
					$enabled_twitter = (get_option('socialshare_twitter') == 'enabled') ? true : false;
					$enabled_googleplus = (get_option('socialshare_googleplus') == 'enabled') ? true : false;
					$text = (get_option('socialshare_text') == '') ? "" : get_option('socialshare_text');

					if ($enabled_facebook) { $return_string .= '<li class="facebook" title="Share link on Facebook">Facebook</li>'; }
					if ($enabled_twitter) { $return_string .= '<li class="twitter" title="Share link on Twitter">Twitter</li>'; }
					if ($enabled_googleplus) { $return_string .= '<li class="plusone" title="Share link on Google+">Google+</li>'; }

				$return_string .= '</ul>';
				$return_string .= '<div class="socialshare-progress-bar"><span></span></div>';
				$return_string .= '</div>';

			$return_string .= '</div>';

		$return_string .= '<a class="socialshare-download" href="'.$href.'">'.$content.'</a>';

		$return_string .= '</div>';
	

		return $return_string;
	}

	// Register the shortcode for use when plugin is active
	function socialshare_register_shortcodes(){
		add_shortcode('socialshare-snippet', 'socialshare_snippet_function');
		add_shortcode('socialshare-download', 'socialshare_download_function');
	}
	add_action( 'init', 'socialshare_register_shortcodes');

	// Register socialshare styles
	function socialshare_register_styles()
	{
		wp_register_style( 'socialshare-style', plugins_url( '/socialshare.css', __FILE__ ), array(), date('Ymd',time()), 'all' );
		wp_enqueue_style( 'socialshare-style' );
	}
	add_action( 'wp_enqueue_scripts', 'socialshare_register_styles' );

	// Register socialshare Scripts
	function socialshare_register_scripts()
	{
		wp_register_script( 'run-pretty-code', plugins_url( '/google-code-prettify/run_prettify.js?skin=desert', __FILE__), false, NULL );
		wp_enqueue_script( 'run-pretty-code' );

		wp_register_script( 'social-likes', plugins_url( '/js/jquery-social-likes.js', __FILE__), false, NULL );
		wp_enqueue_script( 'social-likes' );

		wp_register_script( 'zclip', plugins_url( '/js/zclip.min.js', __FILE__), false, NULL );
		wp_enqueue_script( 'zclip' );
	}
	add_action( 'wp_enqueue_scripts', 'socialshare_register_scripts' );


?>