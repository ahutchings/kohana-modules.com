<div id="notice-<?php echo $notice->hash ?>" class="notice <?php echo $notice->type ?><?php echo $notice->is_persistent ? ' notice-persistent' : '' ?>">
	<div class="notice-content">
		<strong class="notice-type"><?php echo ucwords(__($notice->type)) ?>:</strong>
		<?php echo $notice->message ?>
	</div>
	<div class="notice-clear"></div>
</div>
