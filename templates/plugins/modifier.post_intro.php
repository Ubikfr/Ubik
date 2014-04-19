<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     modifier.post_intro.php
 * Type:     modifier
 * Name:     post_intro
 * Purpose:  Print post intro
 * -------------------------------------------------------------
 */
function smarty_modifier_post_intro($post)
{
    return substr($post, 0, 100);
}
