function BD_shortCodeGenerator( conf ){
    this.appId        = conf.appId;
    this.appKey       = conf.appKey;
    this.chromeExtId  = conf.chromeExtId;
    this.firefoxExtId = conf.firefoxExtId;
    this._init();
};

BD_shortCodeGenerator.prototype = {
    _fields: {
        client: {
            "type"    : "select",
            "id"      : "client",
            "name"    : "client",
            "default" : ""
        },
        role: {
            "type"    : "select",
            "id"      : "role",
            "name"    : "role",
            "default" : ""
        },
        layout: {
            "type"    : "select",
            "id"      : "layout",
            "name"    : "layout",
            "default" : ""
        },
        /*user_name: {
            "type"    : "text",
            "id"      : "user_name",
            "name"    : "name",
            "default" : ""
        },
        room_name: {
            "type"    : "text",
            "id"      : "room_name",
            "name"    : "room",
            "default" : "default-room"
        },
        capacity: {
            "type"    : "select",
            "id"      : "capacity",
            "name"    : "capacity",
            "default" : "4"
        },*/
        media: {
            "type"    : "select",
            "id"      : "media",
            "name"    : "device",
            "default" : ""
        },
        controls: {
            "type"    : "checkbox",
            "id"      : "controls",
            "name"    : "media_controls",
            "default" : false
        },
        chat: {
            "type"    : "checkbox",
            "id"      : "chat",
            "name"    : "chat",
            "default" : false
        },
        sharing: {
            "type"    : "checkbox",
            "id"      : "sharing",
            "name"    : "screen_sharing",
            "default" : false
        },
        width: {
            "type"    : "text",
            "id"      : "width",
            "name"    : "width",
            "default" : ""
        },
        height: {
            "type"    : "text",
            "id"      : "height",
            "name"    : "height",
            "default" : ""
        }
    },

    _shortcode: "[bistridesk{client}{role}{layout}{capacity}{media}{controls}{chat}{sharing}{width}{height}]",

    _init: function(){
        for( var field in this._fields ){
            var item = this._fields[ field ];
            var node = this._getNode( item.type, item.id );
            switch( item.type ){
                case "select":
                    node.addEventListener( "change", this._onChange );
                case "text":
                    node.value = item.default;
                    break;
                case "checkbox":
                    node.checked = item.default;
                    break;
            };
        }
        document.querySelector( ".bistri-insert" ).addEventListener( "click", this._getShortCode.bind( this ) );
        document.querySelector( ".bistri-cancel" ).addEventListener( "click", this._closeTB.bind( this ) );
        if( this.chromeExtId || this.firefoxExtId ){
            document.querySelector( "#extid" ).style.display = "block";
        }
        document.querySelector( "input[name=controls]" ).checked = true;
        document.querySelector( "input[name=chat]" ).checked = true;

    },
    _onChange: function( event ){

        var node     = event.target;
        var name     = node.getAttribute( "name" );
        var value    = node[ node.selectedIndex ].value;
        var controls = document.querySelector( "input[name=controls]" );
        var sharing  = document.querySelector( "input[name=sharing]" );

        switch( name ){
            case "client":
                var role = document.querySelector( "select[name=role]" );
                if( value == "agent" ){
                    role.setAttribute( "disabled", "disabled" );
                } else {
                    role.removeAttribute( "disabled", "disabled" );
                }
                break;
            case "media":
                switch( value ){
                    case "none":
                        controls.setAttribute( "disabled", "disabled" );
                    case "pure-audio":
                        if(sharing)
                            sharing.setAttribute( "disabled", "disabled" );
                        break;
                    default:
                        controls.removeAttribute( "disabled" );
                        if(sharing)
                            sharing.removeAttribute( "disabled" );
                        break;
                }
            break;
        }

    },
    _getNode: function( tag, name ){
        var node;
        switch( tag ){
            case "text":
                node = document.querySelector( "input[name=" + name + "]" );
                break;
            case "checkbox":
                node = document.querySelector( "input[name=" + name + "]" );
                break;
            case "select":
                node = document.querySelector( "select[name=" + name + "]" );
                break;
        };
        return node;
    },
    _getValue: function( tag, name, val ){
        var value;
        switch( tag ){
            case "text":
                value = document.querySelector( "input[name=" + name + "]" ).value;
                break;
            case "checkbox":
                var node = document.querySelector( "input[name=" + name + "]" );
                value = node.disabled ? false : node.checked;
                break;
            case "select":
                var node = document.querySelector( "select[name=" + name + "]" );
                var option = document.querySelector( "select[name=" + name + "] option:checked" );
                value = node.disabled ? false : option.value;
                break;
        };
        return value;
    },
    _getConfig: function(){
        var config = {};
        for( var field in this._fields ){
            config[ field ] = this._getValue( this._fields[ field ].type,  this._fields[ field ].id, this._fields[ field ].default )
        }
        return config;
    },
    _getAttributes: function(){
        var options = {
            appId  : " appid=\"" + this.appId + "\"",
            appKey : " appkey=\"" + this.appKey + "\""
        };
        var config = this._getConfig();
        for( var option in config ){
            if( this._fields[ option ].default != config[ option ] || option == "room_name" ){
                options[ option ] =  " " + this._fields[ option ].name + "=\"" + config[ option ] + "\"";
            }
        }
        return options;
    },
    _parse: function( model, data ) {
        return model.replace( /\{[^\}]*\}/g, function( match ){
            var key = match.substr( 1, ( match.length - 2 ) );
            return data[ key ] || "";
        });
    },
    _getShortCode: function(){
        window.parent.send_to_editor( this._parse( this._shortcode, this._getAttributes() ) );
    },
    _closeTB: function(){
        window.parent.tb_remove();
    },
	_resetTB: function(){
        window.parent.document.querySelector( ".bistri-desk-bt" ).href = "#TB_inline?width=600&height=475&inlineId=popup_container";
	}
};

window.hold_tb_remove = window.tb_remove;
window.tb_remove = function(){
    hold_tb_remove();
	var node = window.parent.document.querySelector( ".bistri-desk-bt" );
	if( node ){
		node.href = "#TB_inline?width=600&height=475&inlineId=popup_container";
	}
};