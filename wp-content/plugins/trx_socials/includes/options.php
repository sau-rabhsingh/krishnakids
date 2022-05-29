<div id="wpbody">
	<div id="wpbody-content">
		<form method="post" action="options.php">
			<div class="wrap">
				<h2><?php esc_html_e("Socials API: Instagram", "trx_socials"); ?></h2>
				<table class="form-table">
					<?php settings_fields( 'trx_socials_settings_instagram' ); ?>
					<tr valign="top">
						<th scope="row"><?php esc_html_e("Client ID", "trx_socials"); ?></th>
						<td>
							<input type="text" name="trx_socials_api_instagram_client_id" value="<?php echo get_option('trx_socials_api_instagram_client_id'); ?>" size="50" id="trx_socials_api_instagram_client_id" class="trx_socials_option_field" />
							<div class="trx_socials_option_description"><?php esc_html_e("Client ID from Instagram Application", "trx_socials"); ?></div>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e("Client Secret", "trx_socials"); ?></th>
						<td>
							<input type="text" name="trx_socials_api_instagram_client_secret" value="<?php echo get_option('trx_socials_api_instagram_client_secret'); ?>" size="50" id="trx_socials_api_instagram_client_secret" class="trx_socials_option_field" />
							<div class="trx_socials_option_description"><?php esc_html_e("Client Secret from Instagram Application", "trx_socials"); ?></div>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e("Get Access Token", "trx_socials"); ?></th>
						<td>
							<input type="button" class="button trx_socials_api_instagram_get_access_token" value="<?php esc_html_e('Get Access Token', 'trx_socials'); ?>" />
							<div class="trx_socials_option_description"><?php esc_html_e("Send request for Access Token to Instagram.", "trx_socials"); ?></div>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e("Access Token", "trx_socials"); ?></th>
						<td>
							<input type="text" name="trx_socials_api_instagram_access_token" value="<?php echo get_option('trx_socials_api_instagram_access_token'); ?>" size="50" id="trx_socials_api_instagram_access_token" class="trx_socials_option_field" />
							<div class="trx_socials_option_description"><?php esc_html_e("Access Token from Instagram Application", "trx_socials"); ?></div>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e("User ID", "trx_socials"); ?></th>
						<td>
							<input type="text" name="trx_socials_api_instagram_user_id" value="<?php echo get_option('trx_socials_api_instagram_user_id'); ?>" size="50" id="trx_socials_api_instagram_user_id" class="trx_socials_option_field" />
							<div class="trx_socials_option_description"><?php esc_html_e("Instagram User ID to show photos in the widget (leave field 'Hash tag' empty in the widget params)", "trx_socials"); ?></div>
						</td>
					</tr>
				</table>
				<p class="submit"><input type="submit" class="button-primary" value="<?php esc_html_e('Save Changes', "trx_socials") ?>" /></p>
			</div>
		</form>
	</div>
</div>