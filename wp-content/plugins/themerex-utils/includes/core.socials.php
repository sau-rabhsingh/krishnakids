<?php
/**
 * ThemeREX Framework: social networks
 *
 * @package	themerex
 * @since	themerex 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('themerex_socials_theme_setup')) {
	add_action( 'themerex_action_before_init_theme', 'themerex_socials_theme_setup' );
	function themerex_socials_theme_setup() {

		if ( !is_admin() ) {
			// Add og:image meta tag for facebook
			add_action( 'wp_head', 					'themerex_facebook_og_tags', 5 );

		} else {
            // Add extra fields in the user profile
            add_action( 'show_user_profile',		'themerex_add_fields_in_user_profile' );
            add_action( 'edit_user_profile',		'themerex_add_fields_in_user_profile' );

            // Save / update additional fields from profile
            add_action( 'personal_options_update',	'themerex_save_fields_in_user_profile' );
            add_action( 'edit_user_profile_update',	'themerex_save_fields_in_user_profile' );
        }

        // Save start values in the views and likes counters when save or publish post
        add_action('publish_post',							'trx_utils_init_post_counters');
        add_action('save_post',								'trx_utils_init_post_counters');

        // AJAX: Set post likes/views count
        add_action('wp_ajax_post_counter', 					'trx_utils_callback_post_counter');
        add_action('wp_ajax_nopriv_post_counter', 			'trx_utils_callback_post_counter');
	}
}


if (!function_exists('themerex_socials_theme_setup1')) {
    add_action( 'themerex_action_before_init_theme', 'themerex_socials_theme_setup1' );
    function themerex_socials_theme_setup1() {
        /* Social Share and Profile links
        -------------------------------------------------------------------------------- */

        // List of social networks for site sharing and user profiles
        $THEMEREX_GLOBALS['share_links'] = array(
            'blogger' =>		'http://www.blogger.com/blog_this.pyra?t&u={link}&n={title}',
            'bobrdobr' =>		'http://bobrdobr.ru/add.html?url={link}&title={title}&desc={descr}',
            'delicious' =>		'http://delicious.com/save?url={link}&title={title}&note={descr}',
            'designbump' =>		'http://designbump.com/node/add/drigg/?url={link}&title={title}',
            'designfloat' =>	'http://www.designfloat.com/submit.php?url={link}',
            'digg' =>			'http://digg.com/submit?url={link}',
            'evernote' =>		'https://www.evernote.com/clip.action?url={link}&title={title}',
            'facebook' =>		'http://www.facebook.com/sharer.php?s=100&p[url]={link}&p[title]={title}&p[summary]={descr}&p[images][0]={image}',
            'friendfeed' =>		'http://www.friendfeed.com/share?title={title} - {link}',
            'google' =>			'http://www.google.com/bookmarks/mark?op=edit&output=popup&bkmk={link}&title={title}&annotation={descr}',
            
            'identi' => 		'http://identi.ca/notice/new?status_textarea={title} - {link}',
            'juick' => 			'http://www.juick.com/post?body={title} - {link}',
            'linkedin' => 		'http://www.linkedin.com/shareArticle?mini=true&url={link}&title={title}',
            'liveinternet' =>	'http://www.liveinternet.ru/journal_post.php?action=n_add&cnurl={link}&cntitle={title}',
            'livejournal' =>	'http://www.livejournal.com/update.bml?event={link}&subject={title}',
            'mail' =>			'http://connect.mail.ru/share?url={link}&title={title}&description={descr}&imageurl={image}',
            'memori' =>			'http://memori.ru/link/?sm=1&u_data[url]={link}&u_data[name]={title}',
            'mister-wong' =>	'http://www.mister-wong.ru/index.php?action=addurl&bm_url={link}&bm_description={title}',
            'mixx' =>			'http://chime.in/chimebutton/compose/?utm_source=bookmarklet&utm_medium=compose&utm_campaign=chime&chime[url]={link}&chime[title]={title}&chime[body]={descr}',
            'moykrug' =>		'http://share.yandex.ru/go.xml?service=moikrug&url={link}&title={title}&description={descr}',
            'myspace' =>		'http://www.myspace.com/Modules/PostTo/Pages/?u={link}&t={title}&c={descr}',
            'newsvine' =>		'http://www.newsvine.com/_tools/seed&save?u={link}&h={title}',
            'odnoklassniki' =>	'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st._surl={link}&title={title}',
            'pikabu' =>			'http://pikabu.ru/add_story.php?story_url={link}',
            'pinterest' => 'json:{"link": "http://pinterest.com/pin/create/button/", "script": "//assets.pinterest.com/js/pinit.js", "style": "", "attributes": {"data-pin-do": "buttonPin", "data-pin-media": "{image}", "data-pin-url": "{link}", "data-pin-description": "{title}", "data-pin-custom": "true","nopopup": "true"}}',
            'posterous' =>		'http://posterous.com/share?linkto={link}&title={title}',
            'postila' =>		'http://postila.ru/publish/?url={link}&agregator=themerex',
            'reddit' =>			'http://reddit.com/submit?url={link}&title={title}',
            'rutvit' =>			'http://rutvit.ru/tools/widgets/share/popup?url={link}&title={title}',
            'stumbleupon' =>	'http://www.stumbleupon.com/submit?url={link}&title={title}',
            'surfingbird' =>	'http://surfingbird.ru/share?url={link}',
            'technorati' =>		'http://technorati.com/faves?add={link}&title={title}',
            'tumblr' =>			'http://www.tumblr.com/share?v=3&u={link}&t={title}&s={descr}',
            'twitter' =>		'https://twitter.com/intent/tweet?text={title}&url={link}',
            'vk' =>				'http://vk.com/share.php?url={link}&title={title}&description={descr}',
            'vk2' =>			'http://vk.com/share.php?url={link}&title={title}&description={descr}',
            'webdiscover' =>	'http://webdiscover.ru/share.php?url={link}',
            'yahoo' =>			'http://bookmarks.yahoo.com/toolbar/savebm?u={link}&t={title}&d={descr}',
            'yandex' =>			'http://zakladki.yandex.ru/newlink.xml?url={link}&name={title}&descr={descr}',
            'ya' =>				'http://my.ya.ru/posts_add_link.xml?URL={link}&title={title}&body={descr}',
            'yosmi' =>			'http://yosmi.ru/index.php?do=share&url={link}'
        );
    }
}

// Add social network
// Example: 1) add_share_link('pinterest', 'url');
//			2) add_share_link(array('pinterest'=>'url', 'dribble'=>'url'));
if (!function_exists('themerex_add_share_link')) {
	function themerex_add_share_link($soc, $url='') {
		if (!is_array($soc)) $soc = array($soc => $url);
		global $THEMEREX_GLOBALS;
		$THEMEREX_GLOBALS['share_links'] = array_merge( $THEMEREX_GLOBALS['share_links'], $soc );
	}
}

// Return (and show) share social links
if (!function_exists('themerex_show_share_links')) {
	function themerex_show_share_links($args) {
		if ( themerex_get_custom_option('show_share')=='hide' ) return '';

		$args = array_merge(array(
			'post_id' => 0,						// post ID
			'post_link' => '',					// post link
			'post_title' => '',					// post title
			'post_descr' => '',					// post descr
			'post_thumb' => '',					// post featured image
			'size' => 'small',					// icons size: tiny|small|big
			'style' => themerex_get_theme_setting('socials_type')=='images' ? 'bg' : 'icons',	// style for show icons: icons|images|bg
			'type' => 'block',					// share block type: list|block|drop
			'popup' => true,					// open share url in new window or in popup window
			'counters' => themerex_get_custom_option('show_share_counters')=='yes',	// show share counters
			'direction' => themerex_get_custom_option('show_share'),				// share block direction
			'caption' => themerex_get_custom_option('share_caption'),				// share block caption
			'share' => themerex_get_theme_option('share_buttons'),					// list of allowed socials
			'echo' => true						// if true - show on page, else - only return as string
			), $args);

		if (count($args['share'])==0) return '';
		$empty = false;
		foreach ($args['share'] as $k=>$v) {
			if (!is_array($v) || implode('', $v)=='')
				$empty = true;
			break;
		}
		if ($empty) return '';
		
		global $THEMEREX_GLOBALS;

		$upload_info = wp_upload_dir();
		$upload_url = $upload_info['baseurl'];

		$output = '<div class="sc_socials sc_socials_size_'.esc_attr($args['size']).' sc_socials_share' . ($args['type']=='drop' ? ' sc_socials_drop' : ' sc_socials_dir_' . esc_attr($args['direction'])) . '">'
			. ($args['caption']!='' ? '<span class="share_caption">'.($args['caption']).'</span>' : '');

		if (is_array($args['share']) && count($args['share']) > 0) {
			foreach ($args['share'] as $soc) {
				$icon = $args['style']=='icons' || themerex_strpos($soc['icon'], $upload_url)!==false ? $soc['icon'] : themerex_get_socials_url(basename($soc['icon']));
				if ($args['style'] == 'icons') {
					$parts = explode('-', $soc['icon'], 2);
					$sn = isset($parts[1]) ? $parts[1] : $parts[0];
				} else {
					$sn = basename($soc['icon']);
					$sn = themerex_substr($sn, 0, themerex_strrpos($sn, '.'));
					if (($pos=themerex_strrpos($sn, '_'))!==false)
						$sn = themerex_substr($sn, 0, $pos);
				}
                $url = empty($soc['url']) && !empty($THEMEREX_GLOBALS['share_links'][$sn]) ? $THEMEREX_GLOBALS['share_links'][$sn] : $soc['url'];
                if (substr($url, 0, 5) == 'json:') {
                    $url = json_decode(substr($url, 5), true);
                    if (is_null($url))
                        continue;
                } else {
                    $url = array('link' => $url);
                }

                if (!isset($url['attributes']))
                    $url['attributes'] = array();
                $url['attributes']['href'] = $url['link'];
                $email = strpos($url['link'], 'mailto:')!==false;
                $popup = !empty($args['popup']) && !$email && empty($url['attributes']['nopopup']);
                if (!empty($popup))
                    $url['attributes']['data-link'] = esc_url($url['link']);
                else
                    $url['attributes']['target'] = '_blank';
                if ($args['counters'])
                    $url['attributes']['data-count'] = $sn;

                if (empty($url)) $url = $THEMEREX_GLOBALS['share_links'][$sn];
			
				$link = str_replace(
					array('{id}', '{link}', '{title}', '{descr}', '{image}'),
					array(
						urlencode($args['post_id']),
						urlencode($args['post_link']),
						urlencode(strip_tags($args['post_title'])),
						urlencode(strip_tags($args['post_descr'])),
						urlencode($args['post_thumb'])
						),
                    is_array($url) ? $url['link'] : $url);
				$output .= '<div class="sc_socials_item' . (!empty($args['popup']) ? ' social_item_popup' : '') . '">'
						. '<a href="'.esc_url($soc['url']).'"'
						. ' class="social_icons social_'.esc_attr($sn).'"'
						. ($args['popup'] ? ' onclick="window.open(\'' . esc_url($link) .'\', \'_blank\', \'scrollbars=0, resizable=1, menubar=0, left=100, top=100, width=480, height=400, toolbar=0, status=0\'); return false;"' : ' target="_blank"')
						. ($args['style']=='bg' ? ' style="background-image: url('.esc_url($icon).');"' : '')

						. ($args['counters'] ? ' data-count="'.esc_attr($sn).'"' : '') ;
                if ( is_array($url)) {
                    foreach($url['attributes'] as $k=>$v) {
                        $v = str_replace(
                            array('{id}', '{link}', '{title}', '{descr}', '{image}'),
                            array(
                                $k=='href' ? urlencode($args['post_id']) : $args['post_id'],
                                $k=='href' ? urlencode($args['post_link']) : $args['post_link'],
                                $k=='href' && !$email ? urlencode(strip_tags($args['post_title'])) : strip_tags($args['post_title']),
                                $k=='href' && !$email ? urlencode(strip_tags($args['post_descr'])) : strip_tags($args['post_descr']),
                                $k=='href' ? urlencode($args['post_thumb']) : $args['post_thumb']
                            ),
                            $v);
                        $output .= " {$k}=\"" . ($k=='href' ? esc_url($v) : esc_attr($v)) . '"';
                    }
                }

                $output .= '>'

					. ($args['style']=='icons' 
						? '<span class="' . esc_attr($soc['icon']) . '"></span>' 
						: ($args['style']=='images' 
							? '<img src="'.esc_url($icon).'" alt="'.esc_attr($sn).'" />' 
							: '<span class="sc_socials_hover" style="background-image: url('.esc_url($icon).');"></span>'
							)
						)
					. '</a>'
					//. ($args['counters'] ? '<span class="share_counter">0</span>' : '') 
					. ($args['type']=='drop' ? '<i>' . trim(themerex_strtoproper($sn)) . '</i>' : '')
					. '</div>';


                if (!empty($url['script']))
                    wp_enqueue_script( "trx_addons_share_{$sn}", $url['script'], array(), null, true );


        }
		}
		$output .= '</div>';
		if ($args['echo']) echo ($output);
		return $output;
	}
}


// Return social icons links
if (!function_exists('themerex_prepare_socials')) {
	function themerex_prepare_socials($list, $style='') {
		if (empty($style)) $style = themerex_get_theme_setting('socials_type')=='images' ? 'bg' : 'icons';
		$output = '';
		$upload_info = wp_upload_dir();
		$upload_url = $upload_info['baseurl'];
		if (is_array($list) && count($list) > 0) {
			foreach ($list as $soc) {
				if (empty($soc['url'])) continue;
				$icon = $style=='icons' || themerex_strpos($soc['icon'], $upload_url)!==false ? $soc['icon'] : themerex_get_socials_url(basename($soc['icon']));
				if ($style == 'icons') {
					$parts = explode('-', $soc['icon'], 2);
					$sn = isset($parts[1]) ? $parts[1] : $parts[0];
				} else {
					$sn = basename($soc['icon']);
					$sn = themerex_substr($sn, 0, themerex_strrpos($sn, '.'));
					if (($pos=themerex_strrpos($sn, '_'))!==false)
						$sn = themerex_substr($sn, 0, $pos);
				}
				$output .= '<div class="sc_socials_item">'
						. '<a href="'.esc_url($soc['url']).'" target="_blank" class="social_icons social_'.esc_attr($sn).'"'
						. ($style=='bg' ? ' style="background-image: url('.esc_url($icon).');"' : '')
						. '>'
						. ($style=='icons' 
							? '<span class="icon-' . esc_attr($sn) . '"></span>' 
							: ($style=='images' 
								? '<img src="'.esc_url($icon).'" alt="" />' 
								: '<span class="sc_socials_hover" style="background-image: url('.esc_url($icon).');"></span>'))
						. '</a>'
						. '</div>';
			}
		}
		return $output;
	}
}
	
	
/* Twitter
-------------------------------------------------------------------------------- */

if (!function_exists('themerex_get_twitter_data')) {
	function themerex_get_twitter_data($cfg) {
        return function_exists('trx_utils_twitter_acquire_data')
            ? trx_utils_twitter_acquire_data(array(
                'mode'            => 'user_timeline',
                'consumer_key'    => $cfg['consumer_key'],
                'consumer_secret' => $cfg['consumer_secret'],
                'token'           => $cfg['token'],
                'secret'          => $cfg['secret']
            ))
            : '';
    }
}

if (!function_exists('themerex_get_twitter_mode_url')) {
	function themerex_get_twitter_mode_url($mode) {
		$url = '/1.1/statuses/';
		if ($mode == 'user_timeline')
			$url .= $mode;
		else if ($mode == 'home_timeline')
			$url .= $mode;
		return $url;
	}
}

if (!function_exists('themerex_prepare_twitter_text')) {
	function themerex_prepare_twitter_text($tweet) {
		$text = $tweet['text'];
		if (!empty($tweet['entities']['urls']) && count($tweet['entities']['urls']) > 0) {
			foreach ($tweet['entities']['urls'] as $url) {
				$text = str_replace($url['url'], '<a href="'.esc_url($url['expanded_url']).'" target="_blank">' . ($url['display_url']) . '</a>', $text);
			}
		}
		if (!empty($tweet['entities']['media']) && count($tweet['entities']['media']) > 0) {
			foreach ($tweet['entities']['media'] as $url) {
				$text = str_replace($url['url'], '<a href="'.esc_url($url['expanded_url']).'" target="_blank">' . ($url['display_url']) . '</a>', $text);
			}
		}
		return $text;
	}
}

// Return Twitter followers count
if (!function_exists('themerex_get_twitter_followers')) {
	function themerex_get_twitter_followers($cfg) {
		$data = themerex_get_twitter_data($cfg); 
		return $data && isset($data[0]['user']['followers_count']) ? $data[0]['user']['followers_count'] : 0;
	}
	// Old version
	/*
	function themerex_get_twitter_followers($account) {
		$tw = get_transient("twitterfollowers");
		if ($tw !== false) return $tw;
		$tw = '?';
		$url = esc_url('https://twitter.com/users/show/'.($account));
		$headers = get_headers($url);
		if (themerex_strpos($headers[0], '200')) {
			$xml = themerex_fgc($url);
			preg_match('/followers_count>(.*)</', $xml, $match);
			if ($match[1] !=0 ) {
				$tw = $match[1];
				set_transient("twitterfollowers", $tw, 60*60);
			}
		}
		return $tw;
	}
	*/
}



/* Facebook
-------------------------------------------------------------------------------- */

if (!function_exists('themerex_get_facebook_likes')) {
	function themerex_get_facebook_likes($account) {
		$fb = get_transient("facebooklikes");
		if ($fb !== false) return $fb;
		$fb = '?';
		$url = esc_url('http://graph.facebook.com/'.($account));
		$headers = get_headers($url);
		if (themerex_strpos($headers[0], '200')) {
			$json = themerex_fgc($url);
			$rez = json_decode($json, true);
			if (isset($rez['likes']) ) {
				$fb = $rez['likes'];
				set_transient("facebooklikes", $fb, 60*60);
			}
		}
		return $fb;
	}
}


// Add facebook meta tags for post/page sharing
function themerex_facebook_og_tags() {
	global $post;
	if ( !is_singular() || themerex_get_global('blog_streampage')) return;
	if (has_post_thumbnail( $post->ID )) {
		$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
		echo '<meta property="og:image" content="' . esc_attr( $thumbnail_src[0] ) . '"/>' . "\n";
	}
	//echo '<meta property="og:title" content="' . esc_attr( strip_tags( get_the_title() ) ) . '" />' . "\n"
	//	.'<meta property="og:description" content="' . esc_attr( strip_tags( strip_shortcodes( get_the_excerpt()) ) ) . '" />' . "\n"
	//	.'<meta property="og:url" content="' . esc_attr( get_permalink() ) . '" />';
}

// Show additional fields in the user profile
if ( ! function_exists( 'themerex_add_fields_in_user_profile' ) ) {
    function themerex_add_fields_in_user_profile( $user ) {
        ?>
        <h3><?php esc_html_e( 'User Position', 'trx_utils' ); ?></h3>
        <table class="form-table">
            <tr>
                <th><label for="user_position"><?php esc_html_e( 'User position', 'trx_utils' ); ?>:</label></th>
                <td><input type="text" name="user_position" id="user_position" size="55"
                           value="<?php echo esc_attr( get_the_author_meta( 'user_position', $user->ID ) ); ?>"/>
                    <span class="description"><?php esc_html_e( 'Please, enter your position in the company', 'trx_utils' ); ?></span>
                </td>
            </tr>
        </table>

        <h3><?php esc_html_e( 'Social links', 'trx_utils' ); ?></h3>
        <table class="form-table">
            <?php
            $socials_type = themerex_get_theme_setting( 'socials_type' );
            $social_list  = themerex_get_theme_option( 'social_icons' );
            if ( is_array( $social_list ) && count( $social_list ) > 0 ) {
                foreach ( $social_list as $soc ) {
                    if ( $socials_type == 'icons' ) {
                        $parts = explode( '-', $soc['icon'], 2 );
                        $sn    = isset( $parts[1] ) ? $parts[1] : $sn;
                    } else {
                        $sn = basename( $soc['icon'] );
                        $sn = themerex_substr( $sn, 0, themerex_strrpos( $sn, '.' ) );
                        if ( ( $pos = themerex_strrpos( $sn, '_' ) ) !== false ) {
                            $sn = themerex_substr( $sn, 0, $pos );
                        }
                    }
                    if ( ! empty( $sn ) ) {
                        ?>
                        <tr>
                            <th>
                                <label for="user_<?php echo esc_attr( $sn ); ?>"><?php themerex_show_layout( themerex_strtoproper( $sn ) ); ?>
                                    :</label></th>
                            <td><input type="text" name="user_<?php echo esc_attr( $sn ); ?>"
                                       id="user_<?php echo esc_attr( $sn ); ?>" size="55"
                                       value="<?php echo esc_attr( get_the_author_meta( 'user_' . ( $sn ), $user->ID ) ); ?>"/>
                                <span class="description"><?php echo sprintf( esc_html__( 'Please, enter your %s link', 'trx_utils' ), themerex_strtoproper( $sn ) ); ?></span>
                            </td>
                        </tr>
                        <?php
                    }
                }
            }
            ?>
        </table>
        <?php
    }
}

// Save / update additional fields
if (!function_exists('themerex_save_fields_in_user_profile')) {
    function themerex_save_fields_in_user_profile( $user_id ) {
        if ( !current_user_can( 'edit_user', $user_id ) )
            return false;
        update_user_meta( $user_id, 'user_position', $_POST['user_position'] );
        $socials_type = themerex_get_theme_setting('socials_type');
        $social_list = themerex_get_theme_option('social_icons');
        if (is_array($social_list) && count($social_list) > 0) {
            foreach ($social_list as $soc) {
                if ($socials_type == 'icons') {
                    $parts = explode('-', $soc['icon'], 2);
                    $sn = isset($parts[1]) ? $parts[1] : $sn;
                } else {
                    $sn = basename($soc['icon']);
                    $sn = themerex_substr($sn, 0, themerex_strrpos($sn, '.'));
                    if (($pos=themerex_strrpos($sn, '_'))!==false)
                        $sn = themerex_substr($sn, 0, $pos);
                }
                update_user_meta( $user_id, 'user_'.($sn), $_POST['user_'.($sn)] );
            }
        }
    }
}

//Return Post Views Count
if (!function_exists('trx_utils_get_post_views')) {
    add_filter('trx_utils_filter_get_post_views', 'trx_utils_get_post_views', 10, 2);
    function trx_utils_get_post_views($default=0, $id=0){
        global $wp_query;
        if (!$id) $id = $wp_query->current_post>=0 ? get_the_ID() : ( ! empty( $wp_query->post->ID )
            ? $wp_query->post->ID
            : ( is_singular() && ! empty( $wp_query->queried_object->ID )
                ? $wp_query->queried_object->ID
                : 0
            )
        );
        $count_key = 'post_views_count';
        $count = get_post_meta($id, $count_key, true);
        if ($count===''){
            delete_post_meta($id, $count_key);
            add_post_meta($id, $count_key, '0');
            $count = 0;
        }
        return $count;
    }
}

//Set Post Views Count
if (!function_exists('trx_utils_set_post_views')) {
    add_action('trx_utils_filter_set_post_views', 'trx_utils_set_post_views', 10, 2);
    function trx_utils_set_post_views($id=0, $counter=-1) {
        global $wp_query;
        if (!$id) $id = $wp_query->current_post>=0 ? get_the_ID() : $wp_query->post->ID;
        $count_key = 'post_views_count';
        $count = get_post_meta($id, $count_key, true);
        if ($count===''){
            delete_post_meta($id, $count_key);
            add_post_meta($id, $count_key, 1);
        } else {
            $count = $counter >= 0 ? $counter : $count+1;
            update_post_meta($id, $count_key, $count);
        }
    }
}

//Return Post Likes Count
if (!function_exists('trx_utils_get_post_likes')) {
    add_filter('trx_utils_filter_get_post_likes', 'trx_utils_get_post_likes', 10, 2);
    function trx_utils_get_post_likes($default=0, $id=0){
        global $wp_query;
        if (!$id) $id = $wp_query->current_post>=0 ? get_the_ID() : ( ! empty( $wp_query->post->ID )
            ? $wp_query->post->ID
            : ( is_singular() && ! empty( $wp_query->queried_object->ID )
                ? $wp_query->queried_object->ID
                : 0
            )
        );
        $count_key = 'post_likes_count';
        $count = get_post_meta($id, $count_key, true);
        if ($count===''){
            delete_post_meta($id, $count_key);
            add_post_meta($id, $count_key, '0');
            $count = 0;
        }
        return $count;
    }
}

//Set Post Likes Count
if (!function_exists('trx_utils_set_post_likes')) {
    add_action('trx_utils_filter_set_post_likes', 'trx_utils_set_post_likes', 10, 2);
    function trx_utils_set_post_likes($id=0, $count=0) {
        global $wp_query;
        if (!$id) $id = $wp_query->current_post>=0 ? get_the_ID() : $wp_query->post->ID;
        $count_key = 'post_likes_count';
        update_post_meta($id, $count_key, $count);
    }
}


// Set post likes/views counters when save/publish post
// Handler of add_action('save_post',		'trx_utils_init_post_counters');
// Handler of add_action('publish_post',	'trx_utils_init_post_counters');
if ( !function_exists( 'trx_utils_init_post_counters' ) ) {
    function trx_utils_init_post_counters($id) {
        trx_utils_get_post_views($id);
        trx_utils_get_post_likes($id);
    }
}


// AJAX: Set post likes/views count
// Handler of add_action('wp_ajax_post_counter', 			'trx_utils_callback_post_counter');
// Handler of add_action('wp_ajax_nopriv_post_counter',	'trx_utils_callback_post_counter');
if ( !function_exists( 'trx_utils_callback_post_counter' ) ) {
    function trx_utils_callback_post_counter() {
        global $_REQUEST;

        if ( !wp_verify_nonce( $_REQUEST['nonce'], 'ajax_nonce' ) )
            wp_die();

        $response = array('error'=>'');

        $id = (int) trx_utils_get_value_gpc('post_id');
        if (isset($_REQUEST['likes'])) {
            $counter = max(0, (int) $_REQUEST['likes']);
            trx_utils_set_post_likes($id, $counter);
        } else if (isset($_REQUEST['views'])) {
            $counter = max(0, (int) $_REQUEST['views']);
            trx_utils_set_post_views($id, $counter);
        }
        echo json_encode($response);
        wp_die();
    }
}

/* Feedburner
-------------------------------------------------------------------------------- */

if (!function_exists('themerex_get_feedburner_counter')) {
	function themerex_get_feedburner_counter($account) {
		$rss = get_transient("feedburnercounter");
		if ($rss !== false) return $rss;
		$rss = '?';
		$url = esc_url('http://feedburner.google.com/api/awareness/1.0/GetFeedData?uri='.($account));
		$headers = get_headers($url);
		if (themerex_strpos($headers[0], '200')) {
			$xml = themerex_fgc($url);
			preg_match('/circulation="(\d+)"/', $xml, $match);
			if ($match[1] != 0) {
				$rss = $match[1];
				set_transient("feedburnercounter", $rss, 60*60);
			}
		}
		return $rss;
	}
}
?>