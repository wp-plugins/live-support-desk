<style>
.fixed .column-login,
.fixed .column-firstname,
.fixed .column-lastname {
	width: 15%;
}
</style>
<div class="wrap bistri-desk">
	<h2><?php _e( 'Agents', 'bistridesk' ) ?><a href="<?php echo $add_agent_url; ?>" class="add-new-h2">Add</a></h2>
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
		<?php _e( 'Agents are the people in charge of responding to incoming requests. Create agent logins and modify their roles. An agent’s role will determine which types of requests are sent their way. If an agent is marked as “Support” then they will get requests from designated “Support” chats. Make sure to create an agent access page with an agent-facing chat so that your agents can log in. You just do this when creating a new page by selecting “Agent” in the Live Support Desk chat set up. Here are more detailed instructions if you need them (link to how to)', 'bistridesk' ) ?>
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
					<th scope="col" class="manage-column column-login"><?php _e( 'Login', 'bistridesk' ) ?></th>
					<th scope="col" class="manage-column column-firstname"><?php _e( 'First Name', 'bistridesk' ) ?></th>
					<th scope="col" class="manage-column column-lastname"><?php _e( 'Last Name', 'bistridesk' ) ?></th>
					<th scope="col" class="manage-column column-roles"><?php _e( 'Associated roles', 'bistridesk' ) ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th scope="col" id="cb" class="manage-column column-cb check-column">
						<label class="screen-reader-text" for="cb-select-all-1"><?php _e( 'select all', 'bistridesk' ) ?></label>
						<input id="cb-select-all-1" type="checkbox">
					</th>
					<th scope="col" class="manage-column column-login"><?php _e( 'Login', 'bistridesk' ) ?></th>
					<th scope="col" class="manage-column column-firstname"><?php _e( 'First Name', 'bistridesk' ) ?></th>
					<th scope="col" class="manage-column column-lastname"><?php _e( 'Last Name', 'bistridesk' ) ?></th>
					<th scope="col" class="manage-column column-roles"><?php _e( 'Associated roles', 'bistridesk' ) ?></th>
				</tr>
			</tfoot>

			<tbody>
				<?php foreach( $agents as $agent ): ?>
				<tr class="<?php if( $agent[ 'id' ]%2 != 0 ){ echo 'alternate'; } ?>">
					<th scope="row" class="check-column">
						<input id="cb-select-2" type="checkbox" name="id[]" value="<?php echo $agent[ 'id' ]; ?>">
					</th>
					<td class="column-login">
						<strong>
							<a class="row-title" href="<?php echo add_query_arg( array( 'page' => 'bistri_desk_agent_add', 'action' => 'edit', 'id' => $agent[ 'id' ] ), admin_url( 'admin.php' ) ); ?>"><?php echo $agent[ 'login' ]; ?></a>
						</strong>
						<div class="row-actions">
							<span class="edit">
								<a href="<?php echo add_query_arg( array( 'page' => 'bistri_desk_agent_add', 'action' => 'edit', 'id' => $agent[ 'id' ] ), admin_url( 'admin.php' ) ); ?>" title="<?php _e( 'Edit', 'bistridesk' ) ?>"><?php _e( 'Edit', 'bistridesk' ) ?></a> | 
							</span>
							<span class="trash">
								<a href="<?php echo add_query_arg( array( 'page' => 'bistri_desk_agents', 'action' => 'delete', 'id' => $agent[ 'id' ] ), admin_url( 'admin.php' ) ); ?>" title="<?php _e( 'Delete', 'bistridesk' ) ?>" class="submitdelete"><?php _e( 'Delete', 'bistridesk' ) ?></a>
							</span>
						</div>
					</td>
					<td class="column-firstname"><?php echo $agent[ 'firstname' ]; ?></td>
					<td class="column-lastname"><?php echo $agent[ 'lastname' ]; ?></td>
					<td class="column-roles">
						<?php foreach( $agent[ 'roles' ] as $index => $role ): ?>
							<?php if( $index > 0 ): ?>
							,
							<?php endif ?>
							<?php echo $role->name; ?>
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