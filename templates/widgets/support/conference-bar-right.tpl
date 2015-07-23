<div class="bistri-container">
    <div class="bistri-videos" data-attach="onRemoteStreamAdded:insertMedia, onResize:resizeContainer"></div>
    <div class="bistri-bar">
        <div class="bistri-pending-requests" data-attach="visible:useQueue"></div>
        <div class="bistri-videos-feedback" data-attach="onLocalStreamAdded:insertMedia, onResize:resizeContainer"></div>
        <div class="bistri-controls" data-attach="visible:isShown">
            <div class="button-wrapper">
                <a type="button" class="bistri-mute-mic bistri-button on" title="{mute-mic}" data-attach="click:toggleMicrophone, visible:isControlsEnabled"><span></span></a>
                <a type="button" class="bistri-mute-webcam bistri-button on" title="{mute-webcam}" data-attach="click:toggleWebcam, visible:isVideoShown"><span></span></a>
                <a type="button" class="bistri-mute-sound bistri-button on" title="{mute-sound}" data-attach="click:toggleSound, visible:isControlsEnabled"><span></span></a>
                <a type="button" class="bistri-screen-sharing bistri-button on" title="{screen-sharing}" data-attach="click:toggleScreenSharing, visible:isScreenSharingEnabled, onConferenceReady:isScreenSharingCompatible, onScreenSharingStateChange:updateButton"><span></span></a>
            </div>
        </div>
        <div class="bistri-end-call">
            <span class="bistri-end-call-button" data-attach="click:endCall" title="{end-call}"></span>
        </div>
        <div class="bistri-chat" data-attach="visible:isChatEnabled">
            <div class="bistri-chat-messages inactive" data-attach="onChatMessage:insertMessage, onResize:resizeChat, onFirstChatUserJoined:removeInactive, onLastChatUserLeft:setInactive"></div>
            <textarea type="text" class="bistri-chat-input inactive" placeholder="{type-here}" readonly="readonly" data-attach="keydown:sendChatMessage, onFirstChatUserJoined:removeReadOnly, onLastChatUserLeft:addReadOnly"></textarea>
        </div>
    </div>
</div>