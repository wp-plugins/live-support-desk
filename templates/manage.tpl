<script>
    window.onmessage = function( event ) {
        var data = JSON.parse ( event.data );
        if ( data.label == 'window-height' ) {
            document.querySelector ( '#BOLSD' ).style.height = data.value + 25 + 'px';
        }
        if ( data.label == 'domain' ) {
            var answer = JSON.stringify ( {
                label: 'domain',
                value: document.location.host
            } );
            document.querySelector ( '#BOLSD' ).contentWindow.postMessage( answer, '*' );
        }
    };
</script>
<div class="wrap">
    <iframe id="BOLSD" src="https://backoffice.bistri.com/plugin/applications?<?php if( $api_key ){ echo "api_key=" . $api_key . "&"; } ?>plugin_id=<?php echo $plugin_id; ?>" style="height: 650px; width: 100%;"></iframe>
</div>