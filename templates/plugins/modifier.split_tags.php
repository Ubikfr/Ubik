<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     modifier.split_tags.php
 * Type:     modifier
 * Name:     split_tags
 * Purpose:  Split tags into tag
 * -------------------------------------------------------------
 */
function smarty_modifier_split_tags($tag)
{
    return explode(',', $tag);
}
