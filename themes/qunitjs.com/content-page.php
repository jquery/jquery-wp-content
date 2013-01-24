<?php
/**
 * The template used for displaying pages
 *
 * the_content() pulls in data from GitHub repositories
 * All content is managed by repositories at: https://github.com/jquery
 */
?>

<?php the_content(); ?>
<?php $column_count = is_front_page() ? "six" : "eight"; ?>
<aside class="github-feedback <?php echo $column_count; ?> columns">
        <h3>Suggestions, Problems, Feedback?</h3>
        <a class="button dark" href="<?php echo jq_get_github_url(); ?>"><i class="icon-github"></i>  Open an Issue or Submit a Pull Request on GitHub</a>
</aside>
