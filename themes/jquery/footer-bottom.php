<?php
	$links = array(
		'Learning Center' => array(
			'class' => 'icon-pencil',
			'url' => 'https://learn.jquery.com/'
		),
		'Chat' => array(
			'class' => 'icon-comments',
			'url' => 'https://jquery.com/support/'
		),
		'X' => array(
			'class' => 'icon-twitter',
			'url' => get_option( 'jquery_x_link' ) ?: 'https://x.com/jquery'
		),
		'GitHub' => array(
			'class' => 'icon-github',
			'url' => 'https://github.com/jquery'
		)
	);
?>

<div id="legal" class="legal">
	<ul class="footer-site-links">
	<?php foreach ( $links as $title => $link ) : ?>
		<li><a class="<?php echo $link[ 'class' ]; ?>" href="<?php echo $link[ 'url' ]; ?>"><?php echo $title; ?></a></li>
	<?php endforeach ?></ul>
	<p class="copyright">
		Copyright <?php echo date('Y'); ?> <a href="https://openjsf.org/">OpenJS Foundation</a> and jQuery contributors. All rights reserved. See <a href="https://jquery.com/license/">jQuery License</a> for more information. The <a href="https://openjsf.org/">OpenJS Foundation</a> has registered trademarks and uses trademarks. For a list of trademarks of the <a href="https://openjsf.org/">OpenJS Foundation</a>, please see our <a href="https://trademark-policy.openjsf.org/">Trademark Policy</a> and <a href="https://trademark-list.openjsf.org/">Trademark List</a>. Trademarks and logos not indicated on the <a href="https://trademark-list.openjsf.org/">list of OpenJS Foundation trademarks</a> are trademarks or registered trademarks of their respective holders. Use of them does not imply any affiliation with or endorsement by them. OpenJS Foundation <a href="https://terms-of-use.openjsf.org/">Terms of Use</a>, <a href="https://privacy-policy.openjsf.org/">Privacy</a>, <a href="https://code-of-conduct.openjsf.org/">Code of Conduct</a>, and <a href="https://www.linuxfoundation.org/cookies">Cookie</a> Policies also apply.
	</p>
	<p><a href="https://www.digitalocean.com" class="do-link">Web hosting by Digital Ocean</a> | <a href="https://www.fastly.com/">CDN by Fastly</a> | <a href="https://wordpress.org/" class="wp-link">Powered by WordPress</a></p>
</div>

<?php
unset( $links );
