<?php get_header(); ?>

<div id="banner-secondary" class="large-banner">
	<div class="banner-text">
	<h1>jQuery is better with friends.</h1>
	<p>There are loads of events big and small.</p>
	</div>
</div>

<div class="content-full full-width twelve columns">
	<div id="content">
		<?php if ( count( $events[ 'future' ] ) === 0 ) : ?>
			<p>
			Unfortunately there are no upcoming events right now.
			Check back soon as we're constantly adding new events around the world!
			</p>
		<?php else : ?>
			<h2>Here are a few upcoming jQuery Foundation events:</h2>
			<?php foreach ( $events[ 'future' ] as $event ) : ?>
				<article class="conference-grid six columns"
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
		<?php endif; ?>

		<hr>

		<div class="past-conferences">
			<h2>We had a great time at these past events:</h2>

			<?php foreach ( $events[ 'past' ] as $year => $yearEvents ) : ?>
				<?php echo "<h3>" . $year . "</h3>"; ?>
				<?php foreach ( $yearEvents as $event ) : ?>
					<a href="<?php echo $event->url; ?>" class="past-conference-grid four columns">
						<article>
							<h4><?php echo $event->title; ?></h4>
							<p><?php echo $event->location . ' | ' . $event->date; ?></p>
						</article>
					</a>
				<?php endforeach; ?>
			<?php endforeach; ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
