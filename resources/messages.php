<?php

	$BISTRI_DESK_ERROR = array(

		/* Rest service related errors */
		'00001' => __( 'Missing action', 'bistridesk' ),
		'00002' => __( 'Unknown action', 'bistridesk' ),
		'00003' => __( 'Customerid is missing', 'bistridesk' ),
		'00004' => __( 'Message is missing', 'bistridesk' ),

		/* Queue related errors */
		'00101' => __( 'Request already push in queue', 'bistridesk' ),
		'00102' => __( 'Fail to count pending requests', 'bistridesk' ),
		'00103' => __( 'Fail to push request in queue', 'bistridesk' ),
		'00104' => __( 'Fail to mark request as processed', 'bistridesk' ),
		'00105' => __( 'Fail to remove requests from queue', 'bistridesk' ),

		/* Message related errors */
		'00201' => __( 'Fail to store message', 'bistridesk' ),
		'00202' => __( 'Fail to update message(s) status', 'bistridesk' ),

		/* Settings related errors */
		'00301' => __( 'Plugin settings are missing', 'bistridesk' ),
		'00302' => __( 'You have entered an invalid API key.', 'bistridesk' ),
		'00303' => __( 'You have entered an invalid API Key.', 'bistridesk' ),
		'00304' => __( 'There is no API Key set (Settings > Live Support Desk).', 'bistridesk' ),

		/* Agents related errors */
		'00401' => __( 'No support agent defined', 'bistridesk' ),

		/* Session related errors */
		'00501' => __( 'Login is missing', 'bistridesk' ),
		'00502' => __( 'Password is missing', 'bistridesk' ),
		'00503' => __( 'Bad login and/or password', 'bistridesk' ),
		'00504' => __( 'No session found', 'bistridesk' ),
		'00505' => __( 'An error occurred while logout', 'bistridesk' ),

		/* Agent related errors */
		'00601' => __( 'Login is missing', 'bistridesk' ),
		'00602' => __( 'First name is missing', 'bistridesk' ),
		'00603' => __( 'Last name is missing', 'bistridesk' ),
		'00604' => __( 'Password is missing', 'bistridesk' ),
		'00605' => __( 'Id is missing', 'bistridesk' ),
		'00606' => __( 'User id is missing', 'bistridesk' ),
		'00607' => __( 'Password confirmation is missing', 'bistridesk' ),
		'00608' => __( 'Passwords missmatch', 'bistridesk' ),
		'00609' => __( 'No agent selected', 'bistridesk' ),
		'00610' => __( 'No role selected', 'bistridesk' ),
		'00611' => __( 'Fail to remove associated roles', 'bistridesk' ),

		/* Role related errors */
		'00701' => __( 'Name is missing', 'bistridesk' ),
		'00702' => __( 'Id is missing', 'bistridesk' ),
		'00703' => __( 'No role selected', 'bistridesk' ),
		'00704' => __( 'Fail to remove associated agents', 'bistridesk' )

	);

	$BISTRI_DESK_MESSAGE = array(

		/* Agent related message */
		'10601' => __( 'Agent has been successfully added', 'bistridesk' ),
		'10602' => __( 'Agent has been successfully updated', 'bistridesk' ),
		'10603' => __( 'Agents have been successfully deleted', 'bistridesk' ),
		'10604' => __( 'Agent has been successfully deleted', 'bistridesk' ),
		
		/* Role related message */
		'10701' => __( 'Role has been successfully added', 'bistridesk' ),
		'10702' => __( 'Role has been successfully updated', 'bistridesk' ),
		'10703' => __( 'Roles have been successfully deleted', 'bistridesk' ),
		'10704' => __( 'Role has been successfully deleted', 'bistridesk' )

	);

?>