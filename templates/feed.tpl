<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:content="http://purl.org/rss/1.0/modules/content/">
<channel xml:lang="fr">
    <title>Titre</title>
    <link>http://localhost</link>
    <description>Description</description>
    <language>fr</language>
    <generator>UBIK</generator>
    <image>
        <title>Titre</title>
        <url>http://localhost/assets/img/ubik.png</url>
        <link>http://localhost</link>
        <height>128</height>
        <width>128</width>
    </image>
{foreach $items as $item}
    <item xml:lang="fr">
        <title>{$item.titre}</title>
        <link>http://localhost/blog/{$item.slug}</link>
        <guid isPermaLink="true">http://localhost/blog/{$item.slug}</guid>
        <dc:date>{$item.date|date_rss}</dc:date>
        <dc:format>text/html</dc:format>
        <dc:language>fr</dc:language>
{foreach $item.tag|split_tags as $one_tag}
        <dc:subject>{$one_tag|print_tag}</dc:subject>
{/foreach}
    </item>
{/foreach}
</channel>
</rss>
