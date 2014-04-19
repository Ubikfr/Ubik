<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     modifier.print_tag.php
 * Type:     modifier
 * Name:     print_tag
 * Purpose:  Print tag without "_"
 * -------------------------------------------------------------
 */
function smarty_modifier_print_tag($tag)
{

    return str_replace("_", " ", $tag);
}
