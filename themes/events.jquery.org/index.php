<?php get_header(); ?>

<div class="content-full full-width twelve columns">
	<div id="content">
		<h2>Here are a few upcoming events:</h2>
		<?php foreach ( $events[ 'future' ] as $event ) : ?>
			<article class="conference-grid clearfix"
				style="background-image: url('<?php echo $event->image; ?>');">
				<section>
					<h3><?php echo $event->title; ?></h3>
					<p><?php echo $event->location . ' | ' . $event->date; ?></p>

					<p class="sponsored">Hosted by:
						<a href="<?php echo $event->host->url; ?>">
							<?php echo $event->host->name; ?>
						</a>
					</p>
				</section>

				<a class="button" href="<?php echo $event->url; ?>">See more &raquo;</a>
			</article>
		<?php endforeach; ?>

		<p style="font-size:80%;clear:left;">
		Photo credits: All images used by CC license or by permission.
		</p>
		<hr>

		<h2>We had a great time at these past events:</h2>
		<?php $currentYear = date( 'Y' ) + 1; ?>
		<?php foreach ( $events[ 'past' ] as $event ) : ?>
			<?php
			$eventYear = date( 'Y', $event->end );
			if ( $eventYear < $currentYear ) {
				$currentYear = $eventYear;
				echo "<h3>$eventYear</h3>\n";
			}
			?>

			<article class="past-conference-grid">
				<h4><?php echo $event->title; ?></h4>
				<p><?php echo $event->location . ' | ' . $event->date; ?></p>
			</article>
		<?php endforeach ?>
	</div>
</div>

<?php get_footer(); ?>
