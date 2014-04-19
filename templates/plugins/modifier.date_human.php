<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     modifier.date_human.php
 * Type:     modifier
 * Name:     date_human
 * Purpose:  print date form YYYYMMDD to human readable
 * -------------------------------------------------------------
 */
function smarty_modifier_date_human($d)
{
    $english_days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
    $french_days = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
    $english_months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'Décember');
    $french_months = array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
    $date = DateTime::createFromFormat('Ymd', $d);
    return str_replace($english_months, $french_months, str_replace($english_days, $french_days, $date->format('l j F Y')));
}
