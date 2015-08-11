<style>
	.bistri-desk .form-field input {
		width: 25em;
	}
</style>
<div class="wrap bistri-desk">
	<?php foreach( $errors as $error ): ?>
	<div id="setting-error-invalid-value" class="error settings-error">
		<p><strong><?php echo $error ?>.</strong></p>
	</div>
	<?php endforeach; ?>
	<?php if( isset( $id ) ): ?>
	<h2><?php _e( 'Modify role', 'bistridesk' ) ?> "<?php echo $name ?>"</h2>
	<?php else: ?>
	<h2><?php _e( 'Add a new role', 'bistridesk' ) ?></h2>
	<?php endif; ?>
	<form method="post" action="<?php echo $form_action ?>&noheader=true" class="validate" novalidate>
		<input type="hidden" name="action" value="<?php echo isset( $id ) ? "update" : "add" ?>">
		<?php if( isset( $id ) ): ?>
		<input type="hidden" name="id" value="<?php echo $id ?>">
		<?php endif ?>
		<table class="form-table">
			<tbody>
				<tr class="form-field form-required">
					<th scope="row">
						<label for="name"><?php _e( 'Name', 'bistridesk' ) ?> <span class="description"><?php _e( '(required)', 'bistridesk' ) ?></span></label>
					</th>
					<td>
						<input name="name" id="name" type="text" value="<?php echo $name ?>" aria-required="true">
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<input type="submit" class="button button-primary" value="<?php echo isset( $userid ) ? _e( 'Save role', 'bistridesk' ) : _e( 'Add role', 'bistridesk' ); ?>">
		</p>
	</form>
</div>