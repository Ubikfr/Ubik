<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     modifier.date_rss.php
 * Type:     modifier
 * Name:     date_rss
 * Purpose:  print date to rss format
 * -------------------------------------------------------------
 */
function smarty_modifier_date_rss($d)
{
    $date = DateTime::createFromFormat('Ymd', $d);
    return $date->format(DateTime::RSS);
}
