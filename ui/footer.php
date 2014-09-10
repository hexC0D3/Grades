	</div>
	<footer>
	<script>
	<?php require_once(UI_DIR . "js/flexselect/jquery.flexselect.min.js"); ?>
	</script>
	<script>
	<?php require_once(UI_DIR . "js/jquery-ui/jquery-ui.min.js"); ?>
	<?php require_once(UI_DIR . "js/alertify/alertify.min.js"); ?>
	<?php require_once(UI_DIR . "js/chart/Chart.min.js"); ?>
	<?php require_once(AJAX_DIR."nt-ajax.js"); ?>
	var successText = "<?php echo sanitizeOutput(_("Action has successfully been completed!")); ?>";
	alertify.set({ labels: {
	    ok     : "<?php echo sanitizeOutput(_("Yes")); ?>",
	    cancel : "<?php echo sanitizeOutput(_("Cancel")); ?>"
	}});
	jQuery(document).ready(function() {
		$("select").flexselect();
	});
	</script>
	</footer>
</body>