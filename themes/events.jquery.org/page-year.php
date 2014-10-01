<?php
/*
 * Template Name: Event Year
 */

get_header();
the_post();
?>

<div class="content-full full-width twelve columns">
	<div id="content">
		<h1 class="entry-title"><?php the_title(); ?> Events</h1>
		<hr>

		<?php foreach ( $events[ 'year' ][ get_the_title() ] as $event ) : ?>
			<h2><a href="<?php echo $event->url; ?>"><?php echo $event->title; ?></a></h2>
			<p><?php echo $event->location . ' | ' . $event->date; ?></p>
		<?php endforeach; ?>
	</div>
</div>

<?php get_footer(); ?>
