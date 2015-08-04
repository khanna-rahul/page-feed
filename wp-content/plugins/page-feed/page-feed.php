<?php 
/*
Plugin Name: Page Feed
Plugin URI: http://google.com
Description: Get Page Events - Install Advanced Custom Fields before using this plugin.
Version: 1.0.0
Author: Rahul Khanna
Author URI: http://psychocoding.com/
*/


if ( !class_exists( "FBPageEvent" ) ) {
	class FBPageEvent {
		public function __construct() {
			add_action('admin_menu', array($this, 'page_feed_menu'));

			add_action( 'admin_init', array($this, 'page_feed_settings' ));

			add_action( 'admin_enqueue_scripts', array($this, 'load_custom_page_feed_script' ));

			add_action( 'wp_ajax_create_fb_post', array($this, 'create_fb_post_callback' ));

			add_action( 'init', array($this, 'FB_Event_Post' ));

			add_shortcode( 'events', array( $this, 'page_feed_shortcode' ) );
		}

		/*
		 * Add Menu Option in Admin Screen
		*/
		function page_feed_menu() {
			add_menu_page('Page Feed Settings', 'Page Feed Settings', 'administrator', 'page-feed-settings', array($this, 'page_feed_settings_page'), 'dashicons-admin-generic');
		}

		/*
		 * Generate Admin Screen
		*/
		function page_feed_settings_page() {
			?>
			<div class="wrap">
				<?php if(!is_plugin_active('advanced-custom-fields/acf.php')) : ?>
					<div class="alert">This plugin uses Advanced Custom Fields to show post meta data in FB Events.</div>
				<?php endif; ?>
				<h2>Facebook Info</h2>

				<form method="post" action="options.php">
				    <?php settings_fields( 'page-feed-settings-group' ); ?>
				    <?php do_settings_sections( 'page-feed-settings-group' ); ?>
				    <table class="form-table">
				        <tr valign="top">
					        <th scope="row">Facebook App ID</th>
					        <td><input type="text" name="facebook_app_id" value="<?php echo esc_attr( get_option('facebook_app_id') ); ?>" /></td>
				        </tr>

				        <tr valign="top">
					        <th scope="row">Facebook App Secret</th>
					        <td><input type="text" name="facebook_app_secret" value="<?php echo esc_attr( get_option('facebook_app_secret') ); ?>" /></td>
				        </tr>
				        
				        <tr valign="top">
					        <th scope="row">Facebook Page ID</th>
					        <td><input type="text" name="facebook_page_id" value="<?php echo esc_attr( get_option('facebook_page_id') ); ?>" /></td>
				        </tr>
				    </table>
				    
				    <?php submit_button(); ?>

				</form>

				<hr/>
				<div class="alert" id="page-feed-mesage"></div>
				<p>Fetch Facebook Page Events : </p><button class="button button-success" id="page-feed-fetch-btn">Fetch Events</button>

				<div id="fb-root"></div>

			</div>
			<?php
		}

		function page_feed_settings() {
			register_setting( 'page-feed-settings-group', 'facebook_app_id' );
			register_setting( 'page-feed-settings-group', 'facebook_app_secret' );
			register_setting( 'page-feed-settings-group', 'facebook_page_id' );
		}

		/*
		 * Loads style and scripts for plugin
		*/
		function load_custom_page_feed_script() {
			wp_enqueue_style('fb-page-style', plugins_url('css/style.css', __FILE__), [], '', 'all');
			
			wp_register_script( 'fb-page-feed', plugins_url('js/admin.js', __FILE__) );
			// Localize the script with new data
			$translation_array = array(
				'fb_app_id' => get_option('facebook_app_id'),
				'fb_app_secret' => get_option('facebook_app_secret'),
				'fb_page_id' => get_option('facebook_page_id')
			);
			wp_localize_script( 'fb-page-feed', 'fb_option', $translation_array );
			// Enqueued script with localized data.
			wp_enqueue_script( 'fb-page-feed' );
		}

		/*
		 * Ajax function to store fb page events
		*/

		function create_fb_post_callback() {
			global $wpdb;
			$table_name = $wpdb->prefix . 'posts';

			$wpdb->query("DELETE FROM $table_name WHERE post_type = 'fb-event'");

			$posts = $_POST['posts'];
			$ids = [];
			foreach($posts as $post) {
				$new_post = array(
					'post_content' => isset($post['description']) ? $post['description'] : '',
					'post_title' => $post['name'],
					'post_type' => 'fb-event',
					'post_status' => 'publish'
				);
				$post_id = wp_insert_post( $new_post );
				$id[] = $post_id;
				add_post_meta($post_id, 'event_id', $post['id']);
				add_post_meta($post_id, 'start_time', $post['start_time']);
				add_post_meta($post_id, 'end_time', $post['end_time']);
			}
			echo 'Page Events are saved';
			wp_die();
		}

		/*
		 * Create Custom Post Type
		*/
		function FB_Event_Post() {
		    $args = array(
		      'public' => true,
		      'label'  => 'FB Events'
		    );
		    register_post_type( 'fb-event', $args );
		}

		/*
		 * Shortcode Callback Function
		*/
		function page_feed_shortcode($attributes) {
			extract( shortcode_atts( array(
                'number' => 5,
                'upcoming_only' => 0,
            ), $attributes ) );
			$limit = 0;
			if($upcoming_only == 1) {
				$limit = $number;
				$number = 200;
			}
            $events = get_posts(array(
            	'posts_per_page' => $number,
            	'post_type' => 'fb-event',
				'meta_key' => 'start_time',
				'orderby' => 'meta_value_num',
				'order' => 'DESC'
            ));
            $content = '';
            $count = 0;
            if( $events ) {
				$content .= '<ul>';
				$now = new DateTime('NOW');
				foreach( $events as $index => $post ) {
					$date = get_post_meta($post->ID, 'start_time');
					$date = new DateTime($date[0]);
					$diff = date_diff($now, $date);
					$days = $diff->format('%a');
					if($upcoming_only == 1) {
						if($days <= -1  && $count < $limit) {
							$content .= '<li>';
								$content .= '<a href="'.get_permalink($post->ID).'">'.$post->post_title.'</a>';
							$content .= '</li>';
							$count++;		
						}
					} else {
						$content .= '<li>';
							$content .= '<a href="'.get_permalink($post->ID).'">'.$post->post_title.'</a>';
						$content .= '</li>';
					}
				}
				$content .= '</ul>';
			}
			return $content;
		}

	}
}


// Instantiating the Class
if (class_exists("FBPageEvent")) {
	$FBPageEvent = new FBPageEvent();
}

?>