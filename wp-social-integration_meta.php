<?php 
/* all meta functions for other integration features */
add_action('wp_head', 'wp_social_integration_add_meta_head', 0);

function wp_social_integration_add_meta_head() {
	echo PHP_EOL . implode(PHP_EOL, wp_scintg_get_metadata_head()) . PHP_EOL . PHP_EOL;
}

function wp_scintg_get_metadata_head() {
	$options = get_option('wp_scintg_plugin_meta_settings');
	$add_metadata = true; $meta_content = array();

	// Check for NOINDEX,FOLLOW on archives.
	// no need to further process metadata as we ask search
	// engines not to index the content.
	
	//later add settings to enable adding following meta for these type of content
	if ( is_archive() || is_search() ) {
		if (
				( is_search())  ||          // Search results
				( is_date())  ||           // Date and time archives
				( is_category() && is_paged())  ||     // Category archives (except 1st page)
				( is_tag() && is_paged())  ||          // Tag archives (except 1st page)
				( is_author() && is_paged())           // Author archives (except 1st page)
		) {
			$meta_content[] = '<meta name="robots" content="NOINDEX,FOLLOW" />';
			$add_metadata = false;   // No need to process metadata
		}
	}

	// Get current post object
	$post = get_queried_object();
	if ( is_null( $post ) ) {
		// Allow metadata on the default front page (latest posts).
		// A post object is not available on that page, but we still need to
		// generate metadata for it. A $post object exists for the "front page"
		// and the "posts page" when static pages are used. No allow rule needed.
		if ( ! wp_scintg_is_default_front_page() ) {
			$add_metadata = false;
		}
	} elseif ( is_singular() ) {
		// The post type check should only take place on content pages.
		// Check if metadata should be added to this content type.
		$post_type = get_post_type( $post );
		if ( ! in_array( $post_type, wp_scintg_get_supported_post_types() ) ) {
			$add_metadata = false;
		}
	}

	// Add Metadata
	if ($add_metadata) {

		// Attachments and embedded media are collected only on content pages.
		if ( is_singular() ) {
			// Get an array containing the attachments
			$attachments = wp_scintg_get_ordered_attachments( $post );
			//var_dump($attachments);

			// Get an array containing the URLs of the embedded media
			$embedded_media = wp_scintg_get_embedded_media( $post );
			//var_dump($embedded_media);
		} else {
			$attachments = array();
			$embedded_media = array();
		}

		// Basic Meta tags
		if($options["wp_scintg_basic_meta"]=="enabled"){ 
		$meta_content = array_merge( $meta_content, wp_scintg_add_basic_metadata_head( $post, $attachments, $embedded_media, $options ) ); }
		//var_dump(wp_scintg_add_basic_metadata());		
	}

	// Allow filtering of the all the generated metatags
	$meta_content = apply_filters( 'wp_scintg_metadata_head', $meta_content );

	// Add our comment
	if ( count( $meta_content ) > 0 ) {
		array_unshift( $meta_content, "<!-- meta data added by wp social int: plugin -->" );
		array_push( $meta_content, "<!-- end of meta data added by wp social int: plugin -->" );
	}

	return $meta_content;
}

/**
 * used in order to append information about the current page to the description or the title of the content.
 Works on :
 * paged archives or main blog page
 * multipage content
 */
function wp_scintg_process_paged( $data ) {

	if ( !empty( $data ) ) {

		$data_to_append = ' | Page ';
		//check if it should be translatable
		
		// allowing filtering of the $data_to_append
		$data_to_append = apply_filters( 'wp_scintg_paged_append_data', $data_to_append );

		// For paginated main page or paginated archives with latest posts.
		if ( is_paged() ) {
			$paged = get_query_var( 'paged' );  // paged
			if ( $paged && $paged >= 2 ) {
				return $data . $data_to_append . $paged;
			}
			// For a Post or PAGE Page that has been divided into pages using the <!--nextpage--> QuickTag
		} else {
			$paged = get_query_var( 'page' );  // page
			if ( $paged && $paged >= 2 ) {
				return $data . $data_to_append . $paged;
			}
		}
	}
	return $data;
}


/**
 * Returns the post's excerpt.
 * This function was written in order to get the excerpt *outside* the loop
 * because the get_the_excerpt() function does not work there any more.
 * This function makes the retrieval of the excerpt independent from the
 * WordPress function in order not to break compatibility with older WP versions.
 *
 * Also, this is even better as the algorithm tries to get text of average
 * length 250 characters, which is more SEO friendly.
 * 
 * should return sanitized text.
 */
function wp_scintg_get_the_excerpt( $post, $excerpt_max_len=300, $desc_avg_length=250, $desc_min_length=150 ) {

	if ( empty($post->post_excerpt) || get_post_type( $post ) == 'attachment' ) {   // In attachments we always use $post->post_content to get a description

		// Get the initial data for the excerpt
		$wp_scintg_excerpt = sanitize_text_field( wp_scintg_sanitize_description( substr($post->post_content, 0, $excerpt_max_len) ) );
		// Remove any URLs that may exist exactly at the beginning of the description.
		$wp_scintg_excerpt = preg_replace( '#^https?:[^\t\r\n\s]+#i', '', $wp_scintg_excerpt );
		$wp_scintg_excerpt = ltrim( $wp_scintg_excerpt );

		// If this was not enough, try to get some more clean data for the description (nasty hack)
		if ( strlen($wp_scintg_excerpt) < $desc_avg_length ) {
			$wp_scintg_excerpt = sanitize_text_field( wp_scintg_sanitize_description( substr($post->post_content, 0, (int) ($excerpt_max_len * 1.5)) ) );
			if ( strlen($wp_scintg_excerpt) < $desc_avg_length ) {
				$wp_scintg_excerpt = sanitize_text_field( wp_scintg_sanitize_description( substr($post->post_content, 0, (int) ($excerpt_max_len * 2)) ) );
			}
		}

		$end_of_excerpt = strrpos($wp_scintg_excerpt, ".");

		if ($end_of_excerpt) {
			// if there are sentences, end the description at the end of a sentence.
			$wp_scintg_excerpt_test = substr($wp_scintg_excerpt, 0, $end_of_excerpt + 1);

			if ( strlen($wp_scintg_excerpt_test) < $desc_min_length ) {
				// don't end at the end of the sentence because the description would be too small
				$wp_scintg_excerpt .= "...";
			} else {
				// If after ending at the end of a sentence the description has an acceptable length, use this
				$wp_scintg_excerpt = $wp_scintg_excerpt_test;
			}
		} else {
			// otherwise (no end-of-sentence in the excerpt) add this stuff at the end of the description.
			$wp_scintg_excerpt .= "...";
		}

	} else {
		// When the post excerpt has been set explicitly, then it has priority.
		$wp_scintg_excerpt = sanitize_text_field( wp_scintg_sanitize_description( $post->post_excerpt ) );

		// note: In attachments $post->post_excerpt is the caption.
		// It is usual that attachments have both the post_excerpt and post_content set.
		// Attachments should never enter here, but be processed above, so that
		// post->post_content is always used as the source of the excerpt.

	}

	/**
	 * In some cases, the algorithm might not work, depending on the content.
	 * In those cases, $wp_scintg_excerpt might only contain ``...``. 
	 */
	if ( trim($wp_scintg_excerpt) == "..." ) {
		$wp_scintg_excerpt = "";
	}

	$wp_scintg_excerpt = apply_filters( 'wp_scintg_get_the_excerpt', $wp_scintg_excerpt, $post );

	return $wp_scintg_excerpt;
}


/**
 * Returns a comma-delimited list of a post's categories.
 */
function wp_scintg_get_keywords_from_post_cats( $post ) {

	$postcats = "";
	foreach((get_the_category($post->ID)) as $cat) {
		$postcats .= $cat->cat_name . ', ';
	}
	// strip final comma
	$postcats = substr($postcats, 0, -2);

	return $postcats;
}


/**
 * Helper function. Returns the first category the post belongs to.
 */
/* function wp_scintg_get_first_category( $post ) {
	$cats = wp_scintg_get_keywords_from_post_cats( $post );
	$bits = explode(',', $cats);
	if (!empty($bits)) {
		return $bits[0];
	}
	return '';
} */


/**
 * Retrieves the post's user-defined tags.
 *
 * This will only work in WordPress 2.3 or newer. On older versions it will
 * return an empty string.
 */
function wp_scintg_get_post_tags( $post ) {

	if ( version_compare( get_bloginfo('version'), '2.3', '>=' ) ) {
		$tags = get_the_tags($post->ID);
		if ( empty( $tags ) ) {
			return false;
		} else {
			$tag_list = "";
			foreach ( $tags as $tag ) {
				$tag_list .= $tag->name . ', ';
			}
			$tag_list = rtrim($tag_list, " ,");
			return $tag_list;
		}
	} else {
		return "";
	}
}


/**
 * Returns a comma-delimited list of all the blog's categories.
 * The built-in category "Uncategorized" is excluded.
 */
function wp_scintg_get_all_categories($no_uncategorized = TRUE) {

	global $wpdb;

	if ( version_compare( get_bloginfo('version'), '2.3', '>=' ) ) {
		$cat_field = "name";
		$sql = "SELECT name FROM $wpdb->terms LEFT OUTER JOIN $wpdb->term_taxonomy ON ($wpdb->terms.term_id = $wpdb->term_taxonomy.term_id) WHERE $wpdb->term_taxonomy.taxonomy = 'category' ORDER BY name ASC";
	} else {
		$cat_field = "cat_name";
		$sql = "SELECT cat_name FROM $wpdb->categories ORDER BY cat_name ASC";
	}
	$categories = $wpdb->get_results($sql);
	if ( empty( $categories ) ) {
		return "";
	} else {
		$all_cats = "";
		foreach ( $categories as $cat ) {
			if ($no_uncategorized && $cat->$cat_field != "Uncategorized") {
				$all_cats .= $cat->$cat_field . ', ';
			}
		}
		$all_cats = rtrim($all_cats, " ,");
		return $all_cats;
	}
}


/**
 * Returns an array of the category names that appear in the posts of the loop.
 * Category 'Uncategorized' is excluded.
 *
 * Accepts the $category_arr, an array containing the initial categories.
 */
function wp_scintg_get_categories_from_loop( $category_arr=array() ) {
	if (have_posts()) {
		while ( have_posts() ) {
			the_post(); // Iterate the post index in The Loop. Retrieves the next post, sets up the post, sets the 'in the loop' property to true.
			$categories = get_the_category();
			if( $categories ) {
				foreach( $categories as $category ) {
					if ( ! in_array( $category->name, $category_arr ) && $category->slug != 'uncategorized' ) {
						$category_arr[] = $category->name;
					}
				}
			}
		}
	}
	rewind_posts(); // Not sure if this is needed.
	return $category_arr;
}


/**
 * This is a helper function that returns the post's or page's description.
 *
 * Important: MUST return sanitized data, unless this plugin has sanitized the data before storing to db.
 *
 */
function wp_scintg_get_content_description( $post, $auto=true ) {

	$content_description = '';

	if ( is_singular() || wp_scintg_is_static_front_page() || wp_scintg_is_static_home() ) {    // TODO: check if this check is needed at all!

		$desc_fld_content = wp_scintg_get_post_meta_description( $post->ID );

		if ( !empty($desc_fld_content) ) {
			// If there is a custom field, use it
			$content_description = $desc_fld_content;
		} else {
			// Else, use the post's excerpt. Valid for Pages too.
			if ($auto) {
				// The generated excerpt should already be sanitized.
				$content_description = wp_scintg_get_the_excerpt( $post );
			}
		}
	}
	return $content_description;
}


/**
 * This is a helper function that returns the post's or page's keywords.
 *
 * Important: MUST return sanitized data, unless this plugin has sanitized the data before storing to db.
 *
 */
function wp_scintg_get_content_keywords($post, $auto=true) {
	$content_keywords = '';
	/*
	 * Custom post field "keywords" overrides post's categories and tags (tags exist in WordPress 2.3 or newer).
	* %cats% is replaced by the post's categories.
	* %tags% us replaced by the post's tags.
	*/
	if ( is_singular() || wp_scintg_is_static_front_page() || wp_scintg_is_static_home() ) {
		$keyw_fld_content = wp_scintg_get_post_meta_keywords( $post->ID );
		// If there is a custom field, use it
		if ( !empty($keyw_fld_content) ) {
			// On single posts, expand the %cats% and %tags% placeholders
			if ( is_single() ) {

				// Here we sanitize the provided keywords for safety
				$keywords_from_post_cats = sanitize_text_field( wp_scintg_sanitize_keywords( wp_scintg_get_keywords_from_post_cats($post) ) );
				$keyw_fld_content = str_replace("%cats%", $keywords_from_post_cats, $keyw_fld_content);

				// Also, the %tags% tag is replaced by the post's tags (WordPress 2.3 or newer)
				if ( version_compare( get_bloginfo('version'), '2.3', '>=' ) ) {
					// Here we sanitize the provided keywords for safety
					$keywords_from_post_tags = sanitize_text_field( wp_scintg_sanitize_keywords( wp_scintg_get_post_tags($post) ) );
					$keyw_fld_content = str_replace("%tags%", $keywords_from_post_tags, $keyw_fld_content);
				}
			}
			$content_keywords .= $keyw_fld_content;

			// Otherwise, generate the keywords from categories and tags
			// Note:
			// Here we use is_singular(), so that pages are checked for categories and tags.
			// By default, pages do not support categories and tags, but enabling such
			// functionality is trivial. See #1206 for more details.
		} elseif ( is_singular() ) {
			if ($auto) {
				/*
				 * Add keywords automatically.
				* Keywords consist of the post's categories and the post's tags (tags exist in WordPress 2.3 or newer).
				*/
				// Here we sanitize the provided keywords for safety
				$keywords_from_post_cats = sanitize_text_field( wp_scintg_sanitize_keywords( wp_scintg_get_keywords_from_post_cats($post) ) );
				if (!empty($keywords_from_post_cats)) {
					$content_keywords .= $keywords_from_post_cats;
				}
				// Here we sanitize the provided keywords for safety
				$keywords_from_post_tags = sanitize_text_field( wp_scintg_sanitize_keywords( wp_scintg_get_post_tags($post) ) );
				if (!empty($keywords_from_post_tags)) {
					$content_keywords .= ", " . $keywords_from_post_tags;
				}
			}
		}
	}

	/**
	 * At last Add the global keywords, if they are set in the administration panel.
	 * If $content_keywords is empty, then no global keyword processing takes place.
	 * 
	 * Note : currently did not keep global keywords setting, will do later
	 */
	
	return $content_keywords;
}
/**
 * Helper function that returns an array of objects attached to the provided
 * $post object.
 */
function wp_scintg_get_ordered_attachments( $post ) {
	// to return IDs:
	// $attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
	return get_children( array(
			'numberposts' => -1,
			'post_parent' => $post->ID,
			'post_type' => 'attachment',
			'post_status' => 'inherit',
			//'post_mime_type' => 'image',
			'order' => 'ASC',
			'orderby' => 'menu_order ID'
	)
	);
}
/**
 * returns the permalink of the provided $post object, taking into account multipage content.
 *
 * only for content.
 * not to use with:
 *  - paged archives
 *  - static page as front page
 *  - static page as posts index page
 *
 * Uses logic from default WordPress function: _wp_link_page
 *   - http://core.trac.wordpress.org/browser/trunk/src/wp-includes/post-template.php#L705
 * furthermore, see: wp-includes/canonical.php line: 227 (Post Paging)
 *
 */
/* function wp_scintg_get_permalink_for_multipage( $post ) {
	$pagenum = get_query_var( 'page' );
	// Content is multipage
	if ( $pagenum && $pagenum > 1 ) {
		// Not using clean URLs -> Add query argument to the URL (eg: ?page=2)
		if ( '' == get_option('permalink_structure') || in_array( $post->post_status, array('draft', 'pending')) ) {
			return add_query_arg( 'page', $pagenum, get_permalink($post->ID) );
			// Using clean URLs
		} else {
			return trailingslashit( get_permalink($post->ID) ) . user_trailingslashit( $pagenum, 'single_paged');
		}
		// Content is not paged
	} else {
		return get_permalink($post->ID);
	}
} */


/**
 *  returns true if a static page is used as the homepage instead of the default posts index page.
 */
function wp_scintg_has_page_on_front() {
	$front_type = get_option('show_on_front', 'posts');
	if ( $front_type == 'page' ) {
		return true;
	}
	return false;
}


/**
 * Helper function that returns true, if the currently displayed page is a
 * page that has been set as the 'posts' page in the 'Reading Settings'.
 * See: http://codex.wordpress.org/Conditional_Tags#The_Main_Page
 *
 * it was written because is_page() is not true for the page that is
 * used as the 'posts' page.
 */
function wp_scintg_is_static_home() {
	if ( wp_scintg_has_page_on_front() && is_home() ) {
		return true;
	}
	return false;
}


/**
 * Helper function that returns true, if the currently displayed page is a
 * page that has been set as the 'front' page in the 'Reading Settings'.
 * See: http://codex.wordpress.org/Conditional_Tags#The_Main_Page
 *
 * it was written because is_front_page() returns true if a static
 * page is used as the front page and also if the latest posts are displayed
 * on the front page.
 */
function wp_scintg_is_static_front_page() {
	if ( wp_scintg_has_page_on_front() && is_front_page() ) {
		return true;
	}
	return false;
}


/**
 * returns true, if the currently displayed page is the main index page of the site that displays the latest posts.
 *
 * This function was written because is_front_page() returns true if a static
 * page is used as the front page and also if the latest posts are displayed
 * on the front page.
 */
function wp_scintg_is_default_front_page() {
	if ( !wp_scintg_has_page_on_front() && is_front_page() ) {
		return true;
	}
	return false;
}

/**
 * Returns an array with URLs to players for some embedded media.
 */
function wp_scintg_get_embedded_media( $post ) {
	// Embeds are grouped by type images/videos/sounds
	// Embedded media are added to any group as an associative array.
	$embedded_media_urls = array(
			'images' => array(),
			'videos' => array(),
			'sounds' => array()
	);

	// Find Videos
	$pattern = '#http:\/\/(?:www.)?youtube.com\/.*v=([a-zA-Z0-9_-]+)#i';
	preg_match_all( $pattern, $post->post_content, $matches );
	//var_dump($matches);
	if ($matches) {
		// $matches[0] contains a list of YT video URLS
		// $matches[1] contains a list of YT video IDs
		// Add matches to $embedded_media_urls
		foreach( $matches[1] as $youtube_video_id ) {
			$item = array(
					'page' => 'http://www.youtube.com/watch?v=' . $youtube_video_id,
					'player' => 'http://youtube.com/v/' . $youtube_video_id,
					// Since we can construct the video thumbnail from the ID, we add it
					'thumbnail' => 'http://img.youtube.com/vi/' . $youtube_video_id . '/0.jpg'
					// TODO: check http://i1.ytimg.com/vi/FTnqYIkjSjQ/maxresdefault.jpg    MAXRES
			);
			array_unshift( $embedded_media_urls['videos'], $item );
		}
	}

	// Vimeo
	// Supported:
	// - http://vimeo.com/VIDEO_ID
	//$pattern = '#vimeo.com/([-|~_0-9A-Za-z]+)#';
	$pattern = '#http:\/\/(?:www.)?vimeo.com\/(\d*)#i';
	preg_match_all( $pattern, $post->post_content, $matches );
	//var_dump($matches);
	if ($matches) {
		// $matches[0] contains a list of Vimeo video URLS
		// $matches[1] contains a list of Vimeo video IDs
		// Add matches to $embedded_media_urls
		foreach( $matches[1] as $vimeo_video_id ) {
			$item = array(
					'page' => 'http://vimeo.com/' . $vimeo_video_id,
					'player' => 'http://player.vimeo.com/video/' . $vimeo_video_id,
					'thumbnail' => ''
			);
			array_unshift( $embedded_media_urls['videos'], $item );
		}
	}

	// Find Sounds
	//
	// Keys:
	// page - URL to a HTML page that contains the object.
	// player - URL to the player that can be used in an iframe.

	// Soundcloud
	// Supported:
	// - https://soundcloud.com/USER_ID/TRACK_ID
	// player:
	// https://w.soundcloud.com/player/?url=https://api.soundcloud.com/tracks/117455833
	$pattern = '#https?:\/\/(?:www.)?soundcloud.com\/[^/]+\/[a-zA-Z0-9_-]+#i';
	preg_match_all( $pattern, $post->post_content, $matches );
	//var_dump($matches);
	if ($matches) {
		// $matches[0] contains a list of Soundcloud URLS
		// Add matches to $embedded_media_urls
		foreach( $matches[0] as $soundcloud_url ) {
			$item = array(
					'page' => $soundcloud_url,
					'player' => 'https://w.soundcloud.com/player/?url=' . $soundcloud_url
			);
			array_unshift( $embedded_media_urls['sounds'], $item );
		}
	}

	// Find Images
	//
	// Keys:
	// page - URL to a HTML page that contains the object.
	// player - URL to the player that can be used in an iframe.
	// thumbnail - URL to thumbnail
	// image - URL to image
	// alt - alt text
	// width - image width
	// height - image height

	// Flickr
	//
	// Supported:
	// Embedded URLs MUST be of Format: http://www.flickr.com/photos/USER_ID/IMAGE_ID/
	//
    // size can be like - 
	// t - Thumbnail (100x)
	// q - Square 150 (150x150)
	// s - Small 240 (140x)
	// n - Small 320 (320x)
	// m - Medium 500 (500x)
    //.... see more 
	// l - Large 1024 (1024x)   DOES NOT WORK
	// h - High 1600 (1600x) DOES NOT WORK
	//
	$pattern = '#https?:\/\/(?:www.)?flickr.com\/photos\/[^\/]+\/[^\/]+\/#i';
	//$pattern = '#https?://(?:www.)?flickr.com/photos/[^/]+/[^/]+/#i';
	preg_match_all( $pattern, $post->post_content, $matches );
	//var_dump($matches);
	if ($matches) {
		// $matches[0] contains a list of Flickr image page URLS
		// Add matches to $embedded_media_urls
		foreach( $matches[0] as $flick_page_url ) {

			// Get cached HTML data for embedded images.
			// Do it like WordPress.
			// See source code:
			// - class-wp-embed.php: line 177 [[ $cachekey = '_oembed_' . md5( $url . serialize( $attr ) ); ]]
			// - media.php: line 1332 [[ function wp_embed_defaults ]]
			// If no attributes have been used in the [embed] shortcode, $attr is an empty string.
			$attr = '';
			$attr = wp_parse_args( $attr, wp_embed_defaults() );
			$cachekey = '_oembed_' . md5( $flick_page_url . serialize( $attr ) );
			$cache = get_post_meta( $post->ID, $cachekey, true );
			//var_dump($cache);

			// Get image info from the cached HTML
			preg_match( '#<img src="([^"]+)" alt="([^"]+)" width="([\d]+)" height="([\d]+)" \/>#i', $cache, $img_info );
			//var_dump($img_info);
			if ( ! empty( $img_info ) ) {
				$item = array(
						'page' => $flick_page_url,
						'player' => $flick_page_url . 'lightbox/',
						'thumbnail' => str_replace( 'z.jpg', 'q.jpg', $img_info[1] ),   // size q   BEFORE CHANGING this check if the 150x150 is hardcoded into any metadata generator. It is in Twitter cards.
						'image' => $img_info[1],    // size z
						'alt' => $img_info[2],
						'width' => $img_info[3],
						'height' => $img_info[4]
				);
				array_unshift( $embedded_media_urls['images'], $item );
			}
		}
	}

	// Allow filtering of the embedded media array
	$embedded_media_urls = apply_filters( 'wp_scintg_embedded_media', $embedded_media_urls, $post->ID );

	//var_dump($embedded_media_urls);
	return $embedded_media_urls;
}

/**
 * Generates basic metadata for the head section.
 *
 */
function wp_scintg_add_basic_metadata_head( $post, $attachments, $embedded_media, $options ) {

	$do_description = (($options["wp_scintg_basic_auto_desc"] == "enabled") ? true : false );
	$do_keywords = (($options["wp_scintg_basic_auto_keywords"] == "enabled") ? true : false );	
	// Array to store metadata
	$meta_content = array();

	// Add NOODP on posts and pages
	//if ( $do_noodp_description && ( is_front_page() || is_singular() ) ) {
	if (( is_front_page() || is_singular() ) ) {
		$meta_content[] = '<meta name="robots" content="NOODP,NOYDIR" />';
	}
	// Default front page displaying latest posts
	if ( wp_scintg_is_default_front_page() ) {

		// Description and Keywords from the Add-Meta-Tags settings override
		// default behaviour.

		// Description
		if ($do_description) {
			// Use the site description from the Add-Meta-Tags settings.
			// Fall back to the blog description.
			$site_description = $options["wp_scintg_frontpg_desc"];
			if ( empty($site_description ) ) {
				// Alternatively, use the blog description
				// Here we sanitize the provided description for safety
				$site_description = sanitize_text_field( wp_scintg_sanitize_description( get_bloginfo('description') ) );
			}
			// If we have a description, use it in the description meta-tag of the front page
			if ( ! empty( $site_description ) ) {
				// Note: Contains multipage information through wp_scintg_process_paged()
				$meta_content[] = '<meta name="description" content="' . esc_attr( wp_scintg_process_paged( $site_description ) ) . '" />';
			}
		}
 
		// Keywords
		if ($do_keywords) {
			// Use the site keywords from the Add-Meta-Tags settings.
			// Fall back to the blog categories.
			$site_keywords = $options["wp_scintg_frontpg_keywords"];
			if ( empty( $site_keywords ) ) {
				// Alternatively, use the blog categories
				// Here we sanitize the provided keywords for safety
				$site_keywords = sanitize_text_field( wp_scintg_sanitize_keywords( wp_scintg_get_all_categories() ) );
			}
			// If we have keywords, use them in the keywords meta-tag of the front page
			if ( ! empty( $site_keywords ) ) {
				$meta_content[] = '<meta name="keywords" content="' . esc_attr( $site_keywords ) . '" />';
			}
		}


		// Attachments
	} elseif ( is_attachment() ) {  // has to be before is_singular() as is_singular() is true for attachments.

		// Description
		if ($do_description) {
			$description = wp_scintg_get_content_description($post, $auto=$do_description);
			if ( ! empty($description ) ) {
				// Note: Contains multipage information through wp_scintg_process_paged()
				$meta_content[] = '<meta name="description" content="' . esc_attr( wp_scintg_process_paged( $description ) ) . '" />';
			}
		}

		// No keywords


		// Content pages and static pages used as "front page" and "posts page"
	} elseif ( is_singular() || wp_scintg_is_static_front_page() || wp_scintg_is_static_home() ) {

		// Description
		if ($do_description) {
			$description = wp_scintg_get_content_description($post, $auto=$do_description);
			if ( ! empty( $description ) ) {
				// Note: Contains multipage information through wp_scintg_process_paged()
				$meta_content[] = '<meta name="description" content="' . esc_attr( wp_scintg_process_paged( $description ) ) . '" />';
			}
		}

		// Keywords
		if ($do_keywords) {
			$keywords = wp_scintg_get_content_keywords($post, $auto=$do_keywords);
			if ( ! empty( $keywords ) ) {
				$meta_content[] = '<meta name="keywords" content="' . esc_attr( $keywords ) . '" />';

				// Static Posts Index Page
				// If no keywords have been set in the metabox and this is the static page,
				// which displayes the latest posts, use the categories of the posts in the loop.
			} elseif ( wp_scintg_is_static_home() ) {
				// Here we sanitize the provided keywords for safety
				$cats_from_loop = sanitize_text_field( wp_scintg_sanitize_keywords( implode( ', ', wp_scintg_get_categories_from_loop() ) ) );
				if ( ! empty( $cats_from_loop ) ) {
					$meta_content[] = '<meta name="keywords" content="' . esc_attr( $cats_from_loop ) . '" />';
				}
			}
		}

		// Category based archives
	} elseif ( is_category() ) {

		if ($do_description) {
			// If set, the description of the category is used in the 'description' metatag.
			// Otherwise, a generic description is used.
			// Here we sanitize the provided description for safety
			$description_content = sanitize_text_field( wp_scintg_sanitize_description( category_description() ) );
			// Note: Contains multipage information through wp_scintg_process_paged()
			if ( empty( $description_content ) ) {
				$meta_content[] = '<meta name="description" content="' . esc_attr( wp_scintg_process_paged( 'Content filed under the ' . single_cat_title( $prefix='', $display=false ) . ' category.' ) ) . '" />';
			} else {
				$meta_content[] = '<meta name="description" content="' . esc_attr( wp_scintg_process_paged( $description_content ) ) . '" />';
			}
		}

		if ($do_keywords) {
			// The category name alone is included in the 'keywords' metatag
			// Here we sanitize the provided keywords for safety
			$cur_cat_name = sanitize_text_field( wp_scintg_sanitize_keywords( single_cat_title($prefix = '', $display = false ) ) );
			if ( ! empty($cur_cat_name) ) {
				$meta_content[] = '<meta name="keywords" content="' . esc_attr( $cur_cat_name ) . '" />';
			}
		}

	} elseif ( is_tag() ) {

		if ($do_description) {
			// If set, the description of the tag is used in the 'description' metatag.
			// Otherwise, a generic description is used.
			// Here we sanitize the provided description for safety
			$description_content = sanitize_text_field( wp_scintg_sanitize_description( tag_description() ) );
			// Note: Contains multipage information through wp_scintg_process_paged()
			if ( empty( $description_content ) ) {
				$meta_content[] = '<meta name="description" content="' . esc_attr( wp_scintg_process_paged( 'Content tagged with ' . single_tag_title( $prefix='', $display=false ) . '.' ) ) . '" />';
			} else {
				$meta_content[] = '<meta name="description" content="' . esc_attr( wp_scintg_process_paged( $description_content ) ) . '" />';
			}
		}

		if ($do_keywords) {
			// The tag name alone is included in the 'keywords' metatag
			// Here we sanitize the provided keywords for safety
			$cur_tag_name = sanitize_text_field( wp_scintg_sanitize_keywords( single_tag_title($prefix = '', $display = false ) ) );
			if ( ! empty($cur_tag_name) ) {
				$meta_content[] = '<meta name="keywords" content="' . esc_attr( $cur_tag_name ) . '" />';
			}
		}

	} elseif ( is_author() ) { 
		// Inside the author archives `$post->post_author` does not contain the author object.
		// In this case the $post (get_queried_object()) contains the author object itself.
		// We also can get the author object with the following code. Slug is what WP uses to construct urls.
		// $author = get_user_by( 'slug', get_query_var( 'author_name' ) );
		// Also, ``get_the_author_meta('....', $author)`` returns nothing under author archives.
		// Access user meta with:  $author->description, $author->user_email, etc
		// $author = get_queried_object();
		$author = $post;

		// If a bio has been set in the user profile, use it in the description metatag of the
		// first page of the author archive *ONLY*. The other pages of the author archive use a generic description.
		// This happens because the 1st page of the author archive is considered the profile page
		// by the other metadata modules.
		// Otherwise use a generic meta tag.
		if ($do_description) {
			// Here we sanitize the provided description for safety
			$author_description = sanitize_text_field( wp_scintg_sanitize_description( $author->description ) );
			if ( empty( $author_description ) || is_paged() ) {
				// Note: Contains multipage information through wp_scintg_process_paged()
				$meta_content[] = '<meta name="description" content="' . esc_attr( wp_scintg_process_paged( 'Content published by ' . $author->display_name . '.' ) ) . '" />';
			} else {
				$meta_content[] = '<meta name="description" content="' . esc_attr( $author_description ) . '" />';
			}
		}

		// For the keywords metatag use the categories of the posts the author has written and are displayed in the current page.
		if ($do_keywords) {
			// Here we sanitize the provided keywords for safety
			$cats_from_loop = sanitize_text_field( wp_scintg_sanitize_keywords( implode( ', ', wp_scintg_get_categories_from_loop() ) ) );
			if ( ! empty( $cats_from_loop ) ) {
				$meta_content[] = '<meta name="keywords" content="' . esc_attr( $cats_from_loop ) . '" />';
			}
		}

	}

	// Add site wide meta tags
	/* if ( ! empty( $options["wp_scintg_add_site_wide_meta"] ) ) {
		$meta_content[] = html_entity_decode( stripslashes( $options["wp_scintg_add_site_wide_meta"] ) );
	} */

	// Filtering of the generated basic metadata
	$meta_content = apply_filters( 'wp_scintg_basic_metadata_head', $meta_content );

	return $meta_content;
}
/**
 * Helper function that returns an array containing the post types that are
 * supported by Add-Meta-Tags. These include: post, page, attachment
 * & also to all public custom post types which have a UI.
 */
function wp_scintg_get_supported_post_types() {
	$supported_builtin_types = array('post', 'page', 'attachment');
	$public_custom_types = get_post_types( array('public'=>true, '_builtin'=>false, 'show_ui'=>true) );
	$supported_types = array_merge($supported_builtin_types, $public_custom_types);

	// Allow filtering of the supported content types.
	$supported_types = apply_filters( 'wp_scintg_supported_post_types', $supported_types );

	return $supported_types;
}


/**
 * returns the value of the custom field that contains
 * the content description.
 * The default field name for the description has changed to ``_wp_scintg_description``.
 * For easy migration this function supports reading the description from the
 * old ``description`` custom field and also from the custom field of other plugins.
 */
function wp_scintg_get_post_meta_description( $post_id ) {
	// Internal fields - order matters
	$supported_custom_fields = array( '_wp_scintg_description', 'description' );
	// External fields - Allow filtering
	$external_fields = array();
	$external_fields = apply_filters( 'wp_scintg_external_description_fields', $external_fields, $post_id );
	// Merge external fields to our supported custom fields
	$supported_custom_fields = array_merge( $supported_custom_fields, $external_fields );

	// Get an array of all custom fields names of the post
	$custom_fields = get_post_custom_keys( $post_id );
	if ( empty( $custom_fields ) ) {
		// Just return an empty string if no custom fields have been associated with this content.
		return '';
	}

	// Try our fields
	foreach( $supported_custom_fields as $sup_field ) {
		// If such a field exists in the db, return its content as the description.
		if ( in_array( $sup_field, $custom_fields ) ) {
			return get_post_meta( $post_id, $sup_field, true );
		}
	}

	//Return empty string if all fail
	return '';
}

/**
 * Helper function that returns the value of the custom field that contains
 * the content keywords.
 * The default field name for the keywords has changed to ``_wp_scintg_keywords``.
 * For easy migration this function supports reading the keywords from the
 * old ``keywords`` custom field and also from the custom field of other plugins.
 */
function wp_scintg_get_post_meta_keywords($post_id) {
	// Internal fields - order matters
	$supported_custom_fields = array( '_wp_scintg_keywords', 'keywords' );
	// External fields - Allow filtering
	$external_fields = array();
	$external_fields = apply_filters( 'wp_scintg_external_keywords_fields', $external_fields, $post_id );
	// Merge external fields to our supported custom fields
	$supported_custom_fields = array_merge( $supported_custom_fields, $external_fields );

	// Get an array of all custom fields names of the post
	$custom_fields = get_post_custom_keys( $post_id );
	if ( empty( $custom_fields ) ) {
		// Just return an empty string if no custom fields have been associated with this content.
		return '';
	}

	// Try our fields
	foreach( $supported_custom_fields as $sup_field ) {
		// If such a field exists in the db, return its content as the keywords.
		if ( in_array( $sup_field, $custom_fields ) ) {
			return get_post_meta( $post_id, $sup_field, true );
		}
	}

	//Return empty string if all fail
	return '';
}


/**
 * Sanitizes text for use in the description and similar metatags.
 *
 * Currently:
 * - removes shortcodes
 * - removes double quotes
 * - convert single quotes to space
 */
function wp_scintg_sanitize_description($desc) {

	// Remove shortcode
	// Needs to be before cleaning double quotes as it may contain quoted settings.
	$pattern = get_shortcode_regex();
	//var_dump($pattern);
	$desc = preg_replace('#' . $pattern . '#s', '', $desc);

	// Clean double quotes
	$desc = str_replace('"', '', $desc);
	$desc = str_replace('&quot;', '', $desc);

	// Convert single quotes to space
	$desc = str_replace("'", ' ', $desc);
	$desc = str_replace('&#039;', ' ', $desc);
	$desc = str_replace("&apos;", ' ', $desc);

	return $desc;
}


/**
 * Sanitizes text for use in the 'keywords' or similar metatags.
 *
 * Currently:
 * - converts to lowercase
 * - removes double quotes
 * - convert single quotes to space
 */
function wp_scintg_sanitize_keywords( $text ) {

	// Convert to lowercase
	if (function_exists('mb_strtolower')) {
		$text = mb_strtolower($text, get_bloginfo('charset'));
	} else {
		$text = strtolower($text);
	}

	// Clean double quotes
	$text = str_replace('"', '', $text);
	$text = str_replace('&quot;', '', $text);

	// Convert single quotes to space
	$text = str_replace("'", ' ', $text);
	$text = str_replace('&#039;', ' ', $text);
	$text = str_replace("&apos;", ' ', $text);

	return $text;
}












