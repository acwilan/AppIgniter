			</div><?php if ($show) : ?>
			<footer>
			</footer><?php endif; ?>
		</div>
		<script src="<?php echo site_url('assets/js/jquery-1.8.2.js'); ?>"></script>
		<script src="<?php echo site_url('assets/js/jquery-ui-1.9.1.custom.min.js'); ?>"></script>
		<script src="<?php echo site_url('assets/js/bootstrap.min.js'); ?>"></script>
<?php 
if (!empty($js_files))
	foreach ($js_files as $file): ?>
		<script src="<?php echo site_url("assets/js/$file"); ?>"></script><?php 
	endforeach;
if (!empty($js_scripts)) : ?>
		<script type="text/javascript"><?php
	echo implode(PHP_EOL, $js_scripts); ?>
		</script><?php
endif; ?>
		<script type="text/javascript">
			$('[rel=tooltip]').tooltip();
		</script>
	</body>
</html>