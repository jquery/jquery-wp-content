<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <div class="entry-content clearfix">
    <div class="plugin-main">
      <h1 class="entry-title"><?php the_title(); ?></h1>
      <p class="attribution">by <?php echo jq_release_author(); ?></p>
      <div class="block description">
        <?php the_content(); ?>
      </div> <!-- /.description -->
      <?php if ( $keywords = jq_release_keywords() ) { ?>
      <div class="block tags">
        <h2>Tags</h2>
        <?php echo $keywords; ?>
      </div> <!-- /.tags -->
      <?php } ?>
      <div class="block versions">
        <h2>Versions</h2>
        <table>
          <thead>
            <tr>
              <th class="version">Version</th>
              <th class="release-date">Date</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach( jq_plugin_versions() as $version ) : ?>
            <tr>
              <td class="version"><?php echo $version[ 'link' ]; ?></td>
              <td class="release-date"><?php echo date_format(new DateTime($version[ 'date' ]), "M j Y"); ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div><!-- /.versions -->
      <div class="block related-plugins last">
        <h3>Related Plugins</h3>
        <div class="box clearfix">
          <ul>
            <li class="icon-caret-right"><a href="javascript:;">plugin 1</a></li>
            <li class="icon-caret-right"><a href="javascript:;">plugin 2</a></li>
            <li class="icon-caret-right"><a href="javascript:;">plugin 3</a></li>
            <li class="icon-caret-right"><a href="javascript:;">plugin 4</a></li>
            <li class="icon-caret-right"><a href="javascript:;">plugin 5</a></li>
            <li class="icon-caret-right"><a href="javascript:;">plugin 6</a></li>
            <li class="icon-caret-right"><a href="javascript:;">plugin 7</a></li>
            <li class="icon-caret-right"><a href="javascript:;">plugin 8</a></li>
          </ul>
        </div> <!-- /.box -->
      </div> <!-- /.related-plugins -->
    </div> <!-- /.plugin-main -->
    <div class="plugin-metadata">
      <div class="block toolbox">
        <div class="inner">
          <header class="clearfix">
            <div class="version-info">
              <p class="caption">Version</p>
              <p class="version-number"><?php echo jq_release_version(); ?></p>
            </div> <!-- /.version-info -->
            <div class="release-info">
              <p class="caption">Released</p>
              <p class="date"><?php echo jq_release_date(); ?></p>
            </div>
          </header>
          <div class="body">
            <a class="download" href="<?php echo jq_release_download_url(); ?>">
              <span class="inner-wrapper"><span class="icon-download-alt"></span>Download now</span>
            </a>
            <a class="other-link gh-fork" href="<?php echo jq_plugin_repo_url(); ?>"><span class="icon-github"></span>Fork on GitHub</a>
            <a class="other-link view-homepage" href="<?php echo jq_release_homepage(); ?>"><span class="icon-external-link"></span>View homepage</a>
            <a class="other-link demo" href="<?php echo jq_release_demo(); ?>"><span class="icon-eye-open"></span>Try a demo</a>
            <a class="other-link read-docs" href="<?php echo jq_release_docs(); ?>"><span class="icon-file"></span>Read the docs</a>
          </div> <!-- /.body -->
        </div> <!-- /.inner -->
      </div> <!-- /.toolbox -->
      <div class="block github-activity group">
        <h2><span class="icon-github"></span>GitHub Activity</h2>
        <div class="box">
          <div class="info-block watchers">
            <p class="number"><?php echo jq_plugin_watchers(); ?></p>
            <p class="caption">Watchers</p>
          </div> <!-- /.watchers -->
          <div class="info-block forks">
            <p class="number"><?php echo jq_plugin_forks(); ?></p>
            <p class="caption">Forks</p>
          </div> <!-- /.forks -->
        </div> <!-- /.box -->
      </div> <!-- /.github-activity -->
      <div class="block author-info">
        <h2><span class="icon-user"></span>Author</h2>
        <ul>
          <li class="icon-caret-right"><?php echo jq_release_author(); ?></li>
        </ul>
      </div> <!-- /.author-info -->
      <?php if ( $maintainers = jq_release_maintainers() ) { ?>
      <div class="block maintainer-info">
        <h2><span class="icon-wrench"></span>Maintainers</h2>
        <ul>
          <?php echo $maintainers; ?>
        </ul>
      </div> <!-- /.maintainer-info -->
      <?php } ?>
      <div class="block licenses">
        <h2><span class="icon-book"></span>Licenses</h2>
        <ul>
          <?php echo jq_release_licenses(); ?>
        </ul>
      </div> <!-- /.licenses -->
      <div class="block dependencies">
        <h2><span class="icon-sitemap"></span>Dependencies</h2>
        <ul>
          <?php echo jq_release_dependencies(); ?>
        </ul>
      </div> <!-- /.dependencies -->
    </div> <!-- /.plugin-metadata -->
  </div><!-- .entry-content -->
  <footer class="entry-meta">
  </footer><!-- .entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->
