<?php
/*
 * @copyright (C) 2020 Michiel Keijts, Normit
 *
 */

/**
 * Cake Authorization Helper Bootstrap
 */
if (!defined("SUPERADMIN_AUTHORIZATION_LEVEL")) {
    define("SUPERADMIN_AUTHORIZATION_LEVEL",4294967295);
}

if (!defined("LOCALE_FIELD_SEPARATOR")) {
    define("LOCALE_FIELD_SEPARATOR",".");
}

if (!defined("AUTHORIZATION_LEVELS_FILE")) {
     define("AUTHORIZATION_LEVELS_FILE", TMP. 'authorization_levels.json');
}
