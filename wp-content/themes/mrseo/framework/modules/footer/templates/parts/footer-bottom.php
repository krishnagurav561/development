<div class="eltdf-footer-bottom-holder">
	<div class="eltdf-footer-bottom-inner <?php echo esc_attr($footer_bottom_grid_class); ?>">
		<div class="eltdf-grid-row <?php echo esc_attr($footer_bottom_classes); ?>">
			<?php /* for($i = 1; $i <= $footer_bottom_columns; $i++) { ?>
				<div class="eltdf-grid-col-<?php echo esc_attr(12 / $footer_bottom_columns); ?>">
					<?php
					if(is_active_sidebar('footer_bottom_column_'.$i)) {
						dynamic_sidebar('footer_bottom_column_'.$i);
					}
					?>
				</div>
			<?php } */ ?>
			<div class="eltdf-grid-col-4">
				<?php
				if(is_active_sidebar('footer_bottom_column_1')) {
					dynamic_sidebar('footer_bottom_column_1');
				}
				?>
			</div>
			<div class="eltdf-grid-col-8">
				<?php
				if(is_active_sidebar('footer_bottom_column_2')) {
					dynamic_sidebar('footer_bottom_column_2');
				}
				?>
			</div>
			<div class="eltdf-grid-col-12">
				<p class="copyright">Copyright © <?php echo date('Y'); ?> Buzzoid. All Rights Reserved.</p>
			</div>
		</div>
	</div>
</div>