<style>
	.bistri-desk .form-field input[type=text],
	.bistri-desk .form-field input[type=password] {
		width: 25em;
	}
</style>
<div class="wrap bistri-desk">
	<?php foreach( $errors as $error ): ?>
	<div id="setting-error-invalid-value" class="error settings-error">
		<p><strong><?php echo $error ?>.</strong></p>
	</div>
	<?php endforeach; ?>
	<?php if( isset( $uid ) ): ?>
	<h2><?php _e( 'Modify', 'bistridesk' ) ?> "<?php echo $login ?>"</h2>
	<?php else: ?>
	<h2><?php _e( 'Add a new agent', 'bistridesk' ) ?></h2>
	<?php endif; ?>
	<form method="post" action="<?php echo $form_action ?>&noheader=true" class="validate" novalidate>
		<input type="hidden" name="action" value="<?php echo isset( $id ) ? "update" : "add" ?>">
		<?php if( isset( $id ) ): ?>
		<input type="hidden" name="id" value="<?php echo $id ?>">
		<?php endif ?>
		<?php if( isset( $uid ) ): ?>
		<input type="hidden" name="uid" value="<?php echo $uid ?>">
		<?php endif ?>
		<table class="form-table">
			<tbody>
				<tr class="form-field form-required">
					<th scope="row">
						<label for="login"><?php _e( 'Login', 'bistridesk' ) ?> <!--span class="description"><?php _e( '(required)', 'bistridesk' ) ?></span--></label>
					</th>
					<td>
						<input name="login" type="text" value="<?php echo $login ?>" aria-required="true" <?php if( isset( $userid ) ): ?>readonly<?php endif ?>>
					</td>
				</tr>
				<tr class="form-field form-required">
					<th scope="row">
						<label for="firstname"><?php _e( 'First name', 'bistridesk' ) ?> <!--span class="description"><?php _e( '(required)', 'bistridesk' ) ?></span--></label>
					</th>
					<td>
						<input name="firstname" type="text" value="<?php echo $firstname ?>" aria-required="true">
					</td>
				</tr>
				<tr class="form-field form-required">
					<th scope="row">
						<label for="lastname"><?php _e( 'Last name', 'bistridesk' ) ?> <!--span class="description"><?php _e( '(required)', 'bistridesk' ) ?></span--></label>
					</th>
					<td>
						<input name="lastname" type="text" value="<?php echo $lastname ?>" aria-required="true">
					</td>
				</tr>
        		<tr class="form-field form-required">
                	<th scope="row">
                		<label for="pass1"><?php _e( 'Password', 'bistridesk' ); ?> <!--span class="description"><?php _e( '(required)', 'bistridesk' ) ?></span--></label>
                	</th>
                	<td>
                        <input name="password" type="password" id="pass1" autocomplete="off" />
                	</td>
        		</tr>
        		<tr class="form-field form-required">
                	<th scope="row">
                		<label for="pass2"><?php _e( 'Repeat Password', 'bistridesk' ); ?> <!--span class="description"><?php _e( '(required)', 'bistridesk' ) ?></span--></label>
                	</th>
               		<td>
	                	<input name="re_password" type="password" id="pass2" autocomplete="off" />
                	</td>
        		</tr>
				<tr class="form-field form-required">
					<th scope="row">
						<?php _e( 'Roles', 'bistridesk' ) ?> <!--span class="description"><?php _e( '(required)', 'bistridesk' ) ?></span-->
					</th>
					<td>
						<?php foreach( $all_roles as $key => $role ): ?>
						<input id="role_<?php echo $role[ 'id' ] ?>" name="roles[]" type="checkbox" value="<?php echo $role[ 'id' ] ?>" aria-required="true" 
						<?php if( ( !isset( $id ) and $key == "0" ) ): ?>
							checked
						<?php endif ?>
						<?php foreach( $agent_roles as $agent_role ): ?>
							<?php if( $role[ 'id' ] == $agent_role[ 'roleid' ] ): ?>
							checked
							<?php endif ?>
						<?php endforeach; ?>
						><label for="role_<?php echo $role[ 'id' ] ?>"><?php echo $role[ 'name' ] ?></label><br/>
						<?php endforeach; ?>
						<p class="description"><?php echo _e( 'An agent can have multiple roles, but an agent cannot have zero roles. All agents must have at least one role.', 'bistridesk' ); ?></p>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<input type="submit" class="button button-primary" value="<?php echo isset( $uid ) ? _e( 'Save user', 'bistridesk' ) : _e( 'Add user', 'bistridesk' ); ?>">
		</p>
	</form>
</div>