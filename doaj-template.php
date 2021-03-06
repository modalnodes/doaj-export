<?php
header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>'."\n"; ?>
<records>
  <!--
    Generated by the DOAJ Export WordPress plugin.
    http://wordpress.org/extend/plugins/doaj-export/
  -->
<?php if ( have_posts() ): while ( have_posts() ): the_post(); ?>
  <record>
    <language>eng<?php /*bloginfo('language');*/ ?></language>
    <?php if ( get_option('doaj_publisher') ) : ?>
      <publisher><?php echo get_option('doaj_publisher'); ?></publisher>
    <?php endif; ?>
    <journalTitle><?php bloginfo('name'); ?></journalTitle>
    <?php if ( get_option('doaj_issn') ) : ?>
      <issn><?php echo get_option('doaj_issn'); ?></issn>
    <?php endif; ?>
    <?php if ( get_option('doaj_eissn') ) : ?>
      <eissn><?php echo get_option('doaj_eissn'); ?></eissn>
    <?php endif; ?>
    <publicationDate><?php the_time('Y-m-d'); ?></publicationDate>
    <?php foreach( (get_the_category()) as $cat ) {
      $parent = get_category($cat->category_parent);
      if ( $parent && ( $parent->category_nicename == "issues" || strpos($parent->category_nicename, 'volume') !== FALSE ) ) { // category should be child of Issues or a volume
        echo '<issue>';
        echo preg_replace('/.*?(\d+).*/', '$1', $cat->cat_name);
        echo "</issue>\n";
        if ( $parent->category_nicename != "issues" ) {
          echo '<volume>';
          echo preg_replace('/.*?(\d+).*/', '$1', $parent->cat_name);
          echo "</volume>\n";
        }
        break; // only do this once. Something's wrong if it's in multiple issues
      }
    }?>
    <?php if ( get_post_meta(get_the_ID(), "doi", TRUE) ) : ?>
      <doi><?php get_post_meta(get_the_ID(), "doi", TRUE); ?></doi>
    <?php endif; ?>
    <publisherRecordId><?php the_ID(); ?></publisherRecordId>
    <documentType>article</documentType>
    <title language="eng<?php /*bloginfo('language');*/ ?>"><?php the_title_rss(); ?></title>

    <authors>
      <author>
        <name><?php the_author(); ?></name>
      </author>
    </authors>
    <abstract language="eng<?php /*bloginfo('language');*/ ?>"><?php the_excerpt_rss(); ?></abstract>

    <fullTextUrl format="html"><?php the_permalink(); ?></fullTextUrl>
    <?php $tags = get_the_tags();
    if ( !empty( $tags ) ): ?>
      <keywords language="eng<?php /*bloginfo('language');*/ ?>">
      <?php foreach ( $tags as $tag ): ?>
        <keyword><?php echo $tag->name; ?></keyword>
      <?php endforeach; ?>
      </keywords>
    <?php endif; ?>
  </record>
<?php endwhile; endif; ?>
</records>
