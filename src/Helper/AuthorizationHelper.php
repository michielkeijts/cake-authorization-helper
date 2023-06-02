<?php
/*
 * @copyright (C) 2020 Michiel Keijts, Normit
 *
 */

namespace CakeAuthorizationHelper\Helper;

use Cake\Collection\Collection;
use Cake\Collection\CollectionInterface;
use Cake\Log\Log;
use CakeAuthorizationHelper\Model\Entity\AuthorizationLevel;
use Exception;
use ArrayIterator;

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

        $contents = file_get_contents(AUTHORIZATION_LEVELS_FILE);

        $decoded = json_decode($contents, TRUE);
        if (json_last_error() != JSON_ERROR_NONE || !is_array($decoded)) {
            throw new Exception("Invalid Authorization Map defined. Please run bin/cake migrations seed --seed=SetupSeed");
        }

        return self::$_map = $decoded;
    }

    /**
     * Save the current Authorizatoin Map as definition
     * @return bool
     */
    public static function saveAuthorizationMap(): bool
    {
        if (empty(self::$_map)) {
            return FALSE;
        }

        return file_put_contents(AUTHORIZATION_LEVELS_FILE, json_encode(self::$_map)) !== FALSE;
    }

    /**
     * Return collection of entities per level
     * @return array
     * @throws Exception
     */
    public static function mapToEntities() : array
    {
        $groups = array_keys(self::loadAuthorizationMap());
        $super_level = max($groups);
        $policies = array_keys(self::loadAuthorizationMap()[$super_level]);
        sort($policies);
        $entities = [];

        foreach ($policies as $policy) {
            $entities[] = self::getEntityForPolicy($policy);
        }

        return $entities;
    }

    public static function getEntityForPolicy(string $policy) : AuthorizationLevel
    {
        $groups = array_keys(self::loadAuthorizationMap());

        $entity = new AuthorizationLevel(['id'=>$policy]);
        foreach ($groups as $group) {
            $entity->set('level_'.$group, self::loadAuthorizationMap()[$group][$policy] ?? FALSE);
        }

        return $entity;
    }

    /**
     * Update the loaded map with the value of $entity
     * Return true if modifications made
     *
     * @param AuthorizationLevel $entity
     * @return bool
     * @throws Exception
     */
    public static function entityToMap(AuthorizationLevel $entity) : bool
    {
        $policy = $entity->id;
        $modified = FALSE;
        foreach ($entity->toArray() as $key=>$value) {
            if ($key == 'id') {
                continue;
            }

            $level = substr($key,6);

            if (isset(self::loadAuthorizationMap()[$level])) {
                self::$_map[$level][$policy] = $value;

                $modified = TRUE;
            }
        }

        return $modified;
    }
}
