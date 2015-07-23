<div id="bistri_desk_configurator" style="display:none;" class="builder">
    <div class="builder-head">
        <h1><?php _e(  'Create Chat Window', 'bistridesk' ) ?></h1>
    </div>
    <div class="builder-content">
        <div class="fl cl cell"><?php _e( 'Type', 'bistridesk' ) ?>:</div>
        <div class="fr cell field">
            <select name="client" tabindex="1">
                <option value="customer" selected><?php _e( 'Customer', 'bistridesk' ) ?></option>
                <option value="agent"><?php _e( 'Agent', 'bistridesk' ) ?></option>
            </select>
            <a href="#" label='<?php _e( 'Choose "Agent" to set up an agent login window. This will let agents receive requests. Choose "Customer" to set up a window that customers can click to start a chat.', 'bistridesk' ) ?>' class="tooltip right"><span>?</span></a>
        </div>

        <div class="fl cl cell"><?php _e( 'Role', 'bistridesk' ) ?>:</div>
        <div class="fr cell field">
            <select name="role" tabindex="2">
                <?php foreach( $roles as $role ): ?>
                <option value="<?php echo $role[ 'id' ] ?>" selected><?php echo $role[ 'name' ] ?></option>
            <?php endforeach ?>
            </select>
            <a href="#" label="<?php _e( 'Select which roles this window will serve. Requests from this chat window will be sent to agents with the role you select here.', 'bistridesk' ) ?>" class="tooltip right"><span>?</span></a>
        </div>

        <div class="fl cl cell"><?php _e( 'Layout', 'bistridesk' ) ?>:</div>
        <div class="fr cell field">
            <select name="layout" tabindex="3">
                <option value="conference-bar-right" selected><?php _e( 'Side to side – right', 'bistridesk' ) ?></option>
                <option value="conference-bar-left"><?php _e( 'Side to side – left', 'bistridesk' ) ?></option>
                <option value="one-2-one"><?php _e( 'Vertical', 'bistridesk' ) ?></option>
                <option value="chat-only"><?php _e( 'Text Chat Only', 'bistridesk' ) ?></option>
            </select>
            <a href="#" label="<?php _e( 'Choose between side-to-side, vertical, or text chat only. This determines the way the chat window will look on the page. Choose the layout that is best suited for the type of chat you want to host.', 'bistridesk' ) ?>" class="tooltip right"><span>?</span></a>
        </div>

        <!--
        <div class="fl cl cell"><?php _e( 'User name', 'bistridesk' ) ?>:</div>
        <div class="fr cell field">
            <input type="text" name="user_name" value="" tabindex="4">
            <a href="#" label="<?php _e( 'This is the user name. This value is different for each user, we recommend to use a "dynamically generated" string', 'bistridesk' ) ?>" class="tooltip right"><span>?</span></a>
        </div>
        <div class="fl cl cell"><?php _e( 'Conference room name', 'bistridesk' ) ?>:</div>
        <div class="fr cell field">
            <input type="text" name="room_name" value="conference-room" tabindex="5">
            <a href="#" label="<?php _e( 'This is the virtual meeting room where the conference will take place', 'bistridesk' ) ?>" class="tooltip right"><span>?</span></a>
        </div>
        <div class="fl cl cell"><?php _e( 'Maximum room capacity', 'bistridesk' ) ?>:</div>
        <div class="fr cell field">
            <select name="capacity" tabindex="6">
                <option value="2"><?php _e( '2 persons', 'bistridesk' ) ?></option>
                <option value="3"><?php _e( '3 persons', 'bistridesk' ) ?></option>
                <option value="4"><?php _e( '4 persons', 'bistridesk' ) ?></option>
                <option value="4"><?php _e( '4 persons', 'bistridesk' ) ?></option>
                <option value="5"><?php _e( '5 persons', 'bistridesk' ) ?></option>
                <option value="6"><?php _e( '6 persons', 'bistridesk' ) ?></option>
                <option value="7"><?php _e( '7 persons', 'bistridesk' ) ?></option>
                <option value="8"><?php _e( '8 persons' , 'bistridesk') ?></option>
                <option value="9"><?php _e( '9 persons' , 'bistridesk') ?></option>
                <option value="10"><?php _e( '10 persons', 'bistridesk' ) ?></option>
            </select>
            <a href="#" label="<?php _e( 'This is the maximum number of participants allowed to enter the conference at a same time', 'bistridesk' ) ?>" class="tooltip right"><span>?</span></a>
        </div>
        -->
        <div class="fl cl cell"><?php _e( 'Media source', 'bistridesk' ) ?>:</div>
        <div class="fr cell field">
            <select name="media" tabindex="7">
                <option value="640x480" selected><?php _e( 'Audio & Video 640x480', 'bistridesk' ) ?></option>
                <option value="160x120"><?php _e( 'Audio & Video 160x120', 'bistridesk' ) ?></option>
                <option value="320x240"><?php _e( 'Audio & Video 320x240', 'bistridesk' ) ?></option>
                <option value="1280x720"><?php _e( 'Audio & Video 1280x720', 'bistridesk' ) ?></option>
                <option value="pure-audio"><?php _e( 'Audio', 'bistridesk' ) ?></option>
                <option value="none"><?php _e( 'None', 'bistridesk' ) ?></option>
            </select>
            <a href="#" label="<?php _e( 'A high resolution require more CPU to encode/decode videos and more bandwith', 'bistridesk' ) ?>" class="tooltip right"><span>?</span></a>
        </div>
        <div class="fl cl cell"><?php _e( 'Enable Text Chat', 'bistridesk' ) ?>:</div>
        <div class="fr cell field">
            <input type="checkbox" name="chat" tabindex="8">
            <a href="#" label="<?php _e( 'You can disable the text chat feature in this chat window by unchecking this box', 'bistridesk' ) ?>" class="tooltip left"><span>?</span></a>
        </div>
        <div class="fl cl cell"><?php _e( 'Enable Media Controls', 'bistridesk' ) ?>:</div>
        <div class="fr cell field">
            <input type="checkbox" name="controls" tabindex="9">
            <a href="#" label="<?php _e( 'Choose whether or not people using this chat window have the ability to cut their camera, microphone, or sound.', 'bistridesk' ) ?>" class="tooltip left"><span>?</span></a>
        </div>
        <div id="extid" style="display: none;">
            <div class="fl cl cell"><?php _e( 'Enable Screen Sharing', 'bistridesk' ) ?>:</div>
            <div class="fr cell field">
                <input type="checkbox" name="sharing" tabindex="10">
                <a href="#" label="<?php _e( 'Allows participants to share their screen. This is a Chrome only feature which require an extension', 'bistridesk' ) ?>" class="tooltip left"><span>?</span></a>
            </div>
        </div>
        <div class="fl cl cell"><?php _e( 'Chat Window Width', 'bistridesk' ) ?>:</div>
        <div class="fr cell field">
            <input type="text" name="width" value="" tabindex="11">
            <a href="#" label="<?php _e( 'Select a number (measured in pixels or percent) to set the width of the chat window. Eg: 60% or 400px.', 'bistridesk' ) ?>" class="tooltip right"><span>?</span></a>
        </div>
        <div class="fl cl cell"><?php _e( 'Chat Window Height', 'bistridesk' ) ?>:</div>
        <div class="fr cell field">
            <input type="text" name="height" value="" tabindex="12">
            <a href="#" label="<?php _e( 'Select a number (measured in pixels or percent) to set the height of the chat window. Eg: 60% or 400px.', 'bistridesk' ) ?>" class="tooltip right"><span>?</span></a>
        </div>
    </div>
    <div class="builder-footer">
        <input type="button" value="<?php _e( 'Insert Shortcode', 'bistridesk' ) ?>" class="bistri-insert button-primary button-large" tabindex="13">
        <input type="button" value="<?php _e( 'Cancel', 'bistridesk' ) ?>" class="bistri-cancel button-secondary button-large" tabindex="14">
    </div>
</div>