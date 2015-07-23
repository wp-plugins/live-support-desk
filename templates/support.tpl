<?php foreach( $errors as $error ): ?>
<div id="setting-error-invalid-value" class="error settings-error">
    <p><strong><?php echo $error ?></strong></p>
</div>
<?php endforeach; ?>
<script src="https://api.bistri.com/bistri.desk.js" type="text/javascript"></script>
<script type="text/javascript">
    WIDGET_NAME    = "<?php echo $widget ?>";
    WIDGET_OPTIONS = <?php echo $params ?>;
</script>
<div style="padding: 20px 20px 20px 0;">
	<div class="bistri-desk"><?php echo $layout ?></div>
</div>