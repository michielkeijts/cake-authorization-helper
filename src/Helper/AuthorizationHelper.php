<?php
/* 
 * @copyright (C) 2020 Michiel Keijts, Normit
 * 
 */

namespace CakeAuthorizationHelper\Helper;

use Cake\Log\Log;
use Exception;

class AuthorizationHelper {
    /**
     * @var array
     */
    public static $_map = [];
    
    /**
     * Loads a file with Authorization definition (JSON)
     * @return array
     */
    public static function loadAuthorizationMap(): array
    {
        if (!empty(self::$_map)) {
            return self::$_map;
        }
        
        $contents = file_get_contents(TMP. 'authorization_levels.json');
        
        $decoded = json_decode($contents, TRUE);
        if (json_last_error() != JSON_ERROR_NONE || !is_array($decoded)) {
            throw new Exception("Invalid Authorization Map defined. Please run bin/cake migrations seed --seed=SetupSeed");
        }
               
        self::$_map = $decoded;
     
        return self::$_map;
    }
}