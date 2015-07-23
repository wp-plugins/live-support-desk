<style>
.fixed .column-id {
	width: 5%;
}
.fixed .column-login,
.fixed .column-firstname,
.fixed .column-lastname {
	width: 15%;
}
</style>
<div class="wrap bistri-desk">
	<h2><?php _e( 'Roles ', 'bistridesk' ) ?><a href="<?php echo $add_role_url; ?>" class="add-new-h2">Add</a></h2>
	<?php foreach( $errors as $error ): ?>
	<div class="error settings-error">
		<p><strong><?php echo $error ?></strong></p>
	</div>
	<?php endforeach; ?>
	<?php foreach( $messages as $message ): ?>
	<div class="updated settings-error">
		<p><strong><?php echo $message ?>.</strong></p>
	</div>
	<?php endforeach; ?>
	<p>
		<?php _e( 'A role is a label you assign to an agent. When requests come in they are sent only to agents with the assigned role designated in the chat window. This ensures that a request won’t be sent to a person who isn’t qualified to handle it.', 'bistridesk' ) ?>
	</p>
	<form method="post" action="<?php echo $form_action ?>" class="validate" novalidate>
		<div class="tablenav top">
			<div class="alignleft actions bulkactions">
				<label for="bulk-action-selector-top" class="screen-reader-text"><?php _e( 'Select bulk action', 'bistridesk' ) ?></label>
				<select name="action">
					<option value="" selected><?php _e( 'Bulk actions', 'bistridesk' ) ?></option>
					<option value="delete"><?php _e( 'Delete', 'bistridesk' ) ?></option>
				</select>
				<input type="submit" class="button action" value="<?php _e( 'Apply', 'bistridesk' ) ?>">
			</div>
			<br class="clear">
		</div>

		<table class="wp-list-table widefat fixed pages">
			<thead>
				<tr>
					<th scope="col" id="cb" class="manage-column column-cb check-column">
						<label class="screen-reader-text" for="cb-select-all-1"><?php _e( 'select all', 'bistridesk' ) ?></label>
						<input id="cb-select-all-1" type="checkbox">
					</th>
					<th scope="col" class="manage-column column-id"><?php _e( 'Id', 'bistridesk' ) ?></th>
					<th scope="col" class="manage-column column-login"><?php _e( 'Name', 'bistridesk' ) ?></th>
					<th scope="col" class="manage-column column-agents"><?php _e( 'Associated agents', 'bistridesk' ) ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th scope="col" id="cb" class="manage-column column-cb check-column">
						<label class="screen-reader-text" for="cb-select-all-1"><?php _e( 'select all', 'bistridesk' ) ?></label>
						<input id="cb-select-all-1" type="checkbox">
					</th>
					<th scope="col" class="manage-column column-id"><?php _e( 'Id', 'bistridesk' ) ?></th>
					<th scope="col" class="manage-column column-login"><?php _e( 'Name', 'bistridesk' ) ?></th>
					<th scope="col" class="manage-column column-agents"><?php _e( 'Associated agents', 'bistridesk' ) ?></th>
				</tr>
			</tfoot>

			<tbody>
				<?php foreach( $roles as $role ): ?>
				<tr class="<?php if( $role[ 'id' ]%2 != 0 ){ echo 'alternate'; } ?>">
					<th scope="row" class="check-column">
						<input id="cb-select-2" type="checkbox" name="id[]" value="<?php echo $role[ 'id' ]; ?>">
					</th>
					<td><?php echo $role[ 'id' ]; ?></td>
					<td class="column-login">
						<strong>
							<a class="row-title" href="<?php echo add_query_arg( array( 'page' => 'bistri_desk_role_add', 'action' => 'edit', 'id' => $role[ 'id' ] ), admin_url( 'admin.php' ) ); ?>"><?php echo $role[ 'name' ]; ?></a>
						</strong>
						<div class="row-actions">
							<span class="edit">
								<a href="<?php echo add_query_arg( array( 'page' => 'bistri_desk_role_add', 'action' => 'edit', 'id' => $role[ 'id' ] ), admin_url( 'admin.php' ) ); ?>" title="<?php _e( 'Edit', 'bistridesk' ) ?>"><?php _e( 'Edit', 'bistridesk' ) ?></a> | 
							</span>
							<span class="trash">
								<a href="<?php echo add_query_arg( array( 'page' => 'bistri_desk_roles', 'action' => 'delete', 'id' => $role[ 'id' ] ), admin_url( 'admin.php' ) ); ?>" title="<?php _e( 'Delete', 'bistridesk' ) ?>" class="submitdelete"><?php _e( 'Delete', 'bistridesk' ) ?></a> 
							</span>
						</div>
					</td>
					<td class="column-agents">
						<?php foreach( $role[ 'agents' ] as $index => $agent ): ?>
							<?php if( $index > 0 ): ?>
							,
							<?php endif ?>
							<?php echo $agent->firstname; ?> <?php echo $agent->lastname; ?> (<?php echo $agent->login; ?>)
						<?php endforeach ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<div class="tablenav top">
			<div class="alignleft actions bulkactions">
				<label for="bulk-action-selector-top" class="screen-reader-text"><?php _e( 'Select bulk action', 'bistridesk' ) ?></label>
				<select name="action2">
					<option value="" selected><?php _e( 'Bulk actions', 'bistridesk' ) ?></option>
					<option value="delete"><?php _e( 'Delete', 'bistridesk' ) ?></option>
				</select>
				<input type="submit" class="button action" value="<?php _e( 'Apply', 'bistridesk' ) ?>">
			</div>
			<br class="clear">
		</div>
	</form>
</div>