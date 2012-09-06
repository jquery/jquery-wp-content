<div class="menu-top-container">
	<ul id="menu-top" class="menu">
		<?php wp_list_pages(array(
			'title_li' => null,
			'child_of' => get_page_by_path( '/docs/' )->ID
		)); ?>
	</ul>
</div>
