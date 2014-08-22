<?php get_header(); ?>

<div class="content-full full-width twelve columns">
	<div id="content">
		<h2>Here are a few upcoming events:</h2>

		<?php
		$events = json_decode( file_get_contents( GW_RESOURCE_DIR . '/events.json' ) );
		$currentYear = date( 'Y' ) + 1;
		$firstPastEvent = true;
		?>

		<?php foreach ( $events as $event ) : $eventEnd = strtotime( $event->end ); ?>
			<?php if ( $eventEnd - time() > 0 ) : ?>

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

			<?php else : ?>

				<?php if ( $firstPastEvent ) : $firstPastEvent = false; ?>
					<p style="font-size:80%;clear:left;">
					Photo credits: All images used by CC license or by permission.
					</p>
					<hr>
					<h2>We had a great time at these past events:</h2>
				<?php endif ?>

				<?php
				$eventYear = date( 'Y', $eventEnd );
				if ( $eventYear < $currentYear ) {
					$currentYear = $eventYear;
					echo "<h3>$eventYear</h3>\n";
				}
				?>

				<article class="past-conference-grid">
					<h4><?php echo $event->title; ?></h4>
					<p><?php echo $event->location . ' | ' . $event->date; ?></p>
				</article>

			<?php endif ?>
		<?php endforeach ?>
	</div>
</div>

<?php get_footer(); ?>
