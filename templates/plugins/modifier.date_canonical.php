<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     modifier.date_canonical.php
 * Type:     modifier
 * Name:     date_canonical
 * Purpose:  print date form YYYYMMDD to YYYY-MM-DD
 * -------------------------------------------------------------
 */
function smarty_modifier_date_canonical($d)
{
    $date = DateTime::createFromFormat('Ymd', $d);
    return $date->format('Y-m-d');
}
