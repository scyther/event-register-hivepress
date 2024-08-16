<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;

?>
<button type="button" class="hp-menu__item hp-menu__item--listing-submit button button--secondary" data-component="link"
	data-url="<?php echo esc_url(hivepress()->router->get_url('user_family_panel_page')); ?>"><span><?php echo esc_html('Add Member'); ?></span></button>
<?php

