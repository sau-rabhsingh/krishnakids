<?php
//===================================== Post author info =====================================
if (themerex_get_custom_option("show_post_author") == 'yes') {
	$post_author_name = $post_author_socials = '';
	$show_post_author_socials = false;
	if ($post_data['post_type']=='post') {
		$post_author_title = esc_html_e('About Author', 'kidsplanet');
		$post_author_name = $post_data['post_author'];
		$post_author_url = $post_data['post_author_url'];
		$post_author_email = get_the_author_meta('user_email', $post_data['post_author_id']);
		$post_author_avatar = get_avatar($post_author_email, 75*min(2, max(1, themerex_get_theme_option("retina_ready"))));
		$post_author_descr = themerex_do_shortcode(nl2br(get_the_author_meta('description', $post_data['post_author_id'])));
		if ($show_post_author_socials) $post_author_socials = themerex_show_user_socials(array('author_id'=>$post_data['post_author_id'], 'size'=>'tiny', 'echo' => false));
	}
	if (!empty($post_author_name)) {
		?>
		<section class="post_author author vcard" itemprop="author" itemscope itemtype="//schema.org/Person">
			<div class="post_author_avatar"><a href="<?php echo esc_url($post_data['post_author_url']); ?>" itemprop="image"><?php themerex_show_layout($post_author_avatar); ?></a></div>
            <div class="about_author">
                <span class="post_author_title"><?php echo esc_html($post_author_title); ?></span>
                <div class="post_author_name"><span itemprop="name"><a href="<?php echo esc_url($post_author_url); ?>" class="fn"><?php themerex_show_layout($post_author_name); ?></a></span></div>
                <div class="post_author_info" itemprop="description"><?php themerex_show_layout($post_author_descr); ?></div>
                <?php if ($post_author_socials!='') themerex_show_layout($post_author_socials); ?>
            </div>
		</section>
		<?php
	}
}
?>