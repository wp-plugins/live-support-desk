<div class="bistri-container">
	<div class="bistri-chat" data-attach="visible:isChatEnabled">
	    <div class="bistri-chat-messages inactive" data-attach="onChatMessage:insertMessage, onResize:resizeChat, onFirstChatUserJoined:removeInactive, onLastChatUserLeft:setInactive"></div>
	    <textarea type="text" class="bistri-chat-input inactive" readonly="readonly" data-attach="keydown:sendChatMessage, onFirstChatUserJoined:removeReadOnly, onLastChatUserLeft:addReadOnly"></textarea>
	</div>
    <div class="bistri-end-call">
        <span class="bistri-end-call-button" data-attach="click:endCall" title="{end-call}"></span>
    </div>
</div>