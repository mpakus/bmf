<? if( empty($posts) ) return; ?><? echo '<?xml version="1.0" encoding="utf-8" ?>'."\n"; ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <title>Gems from Hell</title>
    <link>http://gemsfromhell.com</link>
    <atom:link href="http://gemsfromhell.com/blog/feed.rss" rel="self" type="application/rss+xml" />
    <description>Gems from Hell - обзор Ruby gem библиотек</description>
    <language>ru-RU</language>
    <pubDate><?= gmdate(DATE_RSS, strtotime($posts[0]['added_at'])) ?></pubDate>
    <lastBuildDate><?= gmdate(DATE_RSS, strtotime($posts[0]['added_at'])) ?></lastBuildDate>
    <docs>http://gemsfromhell.com</docs>
    <generator>BMFOpenSource</generator>
    <managingEditor>info@mrak7.com (Renat Ibragimov)</managingEditor>
    <webMaster>info@mrak7.com (Renat Ibragimov)</webMaster>

  <? foreach( $posts as $post ){ ?>
    <item>
      <title><?= form_prep( $post['title'] ) ?></title>
      <link>http://gemsfromhell.com<?= post_link( $post ) ?></link>
      <description><?= form_prep( $post['description'] )?></description>
      <pubDate><?= gmdate(DATE_RSS, strtotime($post['added_at'])) ?></pubDate>
      <guid>http://gemsfromhell.com<?= post_link( $post ) ?></guid>
    </item>
  <? } ?>
  </channel>
</rss>