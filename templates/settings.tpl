<div class="wrap">
	<h2><?php _e('Live Support Desk Settings', 'bistridesk' ) ?></h2>
	<form method="POST" action="options.php" novalidate="novalidate">
        <?php settings_fields( 'bistri_desk' ); ?>
        <?php do_settings_sections( 'bistri_desk' ); ?>
		<table class="form-table">
			<tr>
				<th colspan="2">
					<h3><?php _e('Account Keys' ) ?></h3>
				</th>
			</tr>
			<tr>
				<th scope="row"><label for="apiKey"><?php _e('API Key', 'bistridesk' ) ?></label></th>
				<td>
					<input type="text" id="apiKey" name="bistri_desk_settings[api_key]" value="<?php echo $api_key; ?>" class="regular-text"/>
					<p class="description"><?php echo _e( 'Enter your API Key here. To get your API Key go to <strong>Live Support Desk > <a href="admin.php?page=bistri_desk_manage_plan">Manage Account</a></strong>', 'bistridesk' ); ?>.
				</td>
			</tr>
			<tr>
				<th colspan="2">
					<h3><?php _e('Request Management Settings' ) ?></h3>
				</th>
			</tr>
			<tr>
				<th scope="row"><label for="closedSupportPage"><?php _e('Unavailable Message', 'bistridesk' ) ?></label></th>
				<td>
					<select id="closedSupportPage" name="bistri_desk_use_page"> 
						<option value="0"><?php echo _e( 'Use default message' ); ?></option> 
						<?php foreach( $pages as $page ): ?>
					  	<option value="<?php echo $page->ID ?>" <?php if( $use_page == $page->ID ){ echo 'selected'; } ?>><?php echo $page->post_title ?></option>';
						<?php endforeach ?>
					</select>
					<p class="description"><?php echo _e( 'This is what appears in place of a chat window when nobody is available for support. You can show a default message or chose a custom page to display.', 'bistridesk' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e('Request Management Style', 'bistridesk' ) ?></th>
				<td>
					<p>
						<input type="radio" id="queueEnabled" name="bistri_desk_use_queue" value="1" class="tog" <?php if( $use_queue == '1' ){ echo 'checked'; } ?>/><label for="queueEnabled"><?php _e('Waiting Queue', 'bistridesk' ) ?></label>
					</p>
					<p class="description"><?php echo _e( 'All incoming chat requests will be stacked into a queue. Connected agents will receive requests as they become available. Requests are managed on a first come, first served basis.', 'bistridesk' ); ?></p>
					<p>
						<input type="radio" id="queueDisabled" name="bistri_desk_use_queue" value="0" class="tog" <?php if( $use_queue == '0' ){ echo 'checked'; } ?>/><label for="queueDisabled"><?php _e('Direct Connect', 'bistridesk' ) ?></label>
					</p>
					<p class="description"><?php echo _e( 'All incoming chat requests are directly sent to all connected agents. An agent can choose to accept or reject a request like a traditional call.', 'bistridesk' ); ?></p>
					</td>
			</tr>
			<tr>
				<th colspan="2">
					<h3><?php _e('Screen Sharing Configuration (optional)' ) ?></h3>
				</th>
			</tr>
			<tr>
				<th scope="row"><label for="chromeExtensionId"><?php _e('Chrome screen-sharing extension ID', 'bistridesk' ) ?></label></th>
				<td>
					<input type="text" id="chromeExtensionId" name="bistri_desk_settings[chrome_extension_id]" value="<?php echo $chrome_extension_id; ?>" class="regular-text"/>
					<p class="description"><?php echo _e( 'This is the Google Chrome "screen sharing" extension ID. To use the Screen-sharing feature you need to create an extension for your website domain. See explanation about how to build your extension here:', 'bistridesk' ); ?> <a href="https://github.com/bistri/screensharing-extensions/tree/master/chrome-screensharing-extension" target="_blank"><?php echo _e( 'build your own Screen-Sharing extension', 'bistridesk' ); ?></a></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="firefoxExtensionId"><?php _e('Firefox screen-sharing extension ID', 'bistridesk' ) ?></label></th>
				<td>
					<input type="text" id="firefoxExtensionId" name="bistri_desk_settings[firefox_extension_id]" value="<?php echo $firefox_extension_id; ?>" class="regular-text"/>
					<p class="description"><?php echo _e( 'This is the Mozilla Firefox "screen sharing" extension ID. To use the Screen-sharing feature you need to build an extension specificaly for your website domain. See explanation about how to build your extension here:', 'bistridesk' ); ?> <a href="https://github.com/bistri/screensharing-extensions/tree/master/firefox-screensharing-extension" target="_blank"><?php echo _e( 'build your own Screen-Sharing extension', 'bistridesk' ); ?></a></p>
				</td>
			</tr>
		</table>
		<?php submit_button( __( 'Save Changes', 'bistridesk' ), 'primary', 'Update' );?>
	</form>
</div>