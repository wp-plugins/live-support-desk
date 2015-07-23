<?php foreach( $errors as $error ): ?>
<div id="setting-error-invalid-value" class="error settings-error">
	<p><strong><?php echo $error ?>.</strong></p>
</div>
<?php endforeach; ?>
<form method="POST" action="<?php echo $action ?>" novalidate="novalidate">
	<input type="hidden" name="action" value="login" />
    <p>
            <label for="agent_login"><?php _e( 'Login' ) ?><br />
            <input type="text" name="agent_login" id="agent_login" class="input" value="<?php echo $login ?>" size="25" /></label>
    </p>
    <p>
            <label for="agent_password"><?php _e( 'Password' ) ?><br />
            <input type="password" name="agent_password" id="agent_password" class="input" value="" size="25" /></label>
    </p>
    <br class="clear" />
	<p class="submit"><input type="submit" name="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e( 'Login' ); ?>" /></p>
</form>