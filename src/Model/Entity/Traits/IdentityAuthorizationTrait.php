<?php
/* 
 * @copyright (C) 2020 Michiel Keijts, Normit
 * 
 */

namespace CakeAuthorizationHelper\Model\Entity\Traits;

use Cake\Utility\Hash;
use Cake\Cache\Cache;
use Cake\Datasource\EntityInterface;
use CakeAuthorizationHelper\Helper\AuthorizationHelper;

/**
 * Extra funtionality to add to a IdentityInterface to check if an identity
 * has access
 */
trait IdentityAuthorizationTrait {
    
    /**
     * Checks if the user hase Authorization for a specific key (defined by $entity)
     * Fall back
     * 
     * @param string $authorization_key
     * @param EntityInterface $entity
     * @param bool $default (FALSE) The default fallback if not defined
     * @return bool
     */
    public function isAuthorized($authorization_key, EntityInterface $entity, bool $default = FALSE): bool
    {
        $mapper = $this->getAuthorizationMapper($this->getAuthorizationLevel());
        
        return $this->mapAuthorization($authorization_key, $mapper, $default || $this->isSuperAdmin());
    }
    
    /**
     * Gets the maximum level of authorization from the underlying usergroups
     * @return int
     */
    public function getAuthorizationLevel(): int
    {
        $authorization_level = 0;
        if (!isset($this->usergroups) || empty($this->usergroups)) {
            return $authorization_level;
        }
        
        /* @var $usergroup \App\Model\Entity\Usergroup */
        foreach ($this->usergroups as $usergroup) {
            if ($usergroup->level > $authorization_level) {
                $authorization_level = $usergroup->level;
            }                
        }
        
        return $authorization_level;
    }
    
    /**
     * Superadmin skips all authority checks
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return $this->getAuthorizationLevel() >= SUPERADMIN_AUTHORIZATION_LEVEL;
    }
    
    /**
     * Maps the $key to the $mapper, in this case an array with boolean
     * @param string $key
     * @param array $mapper
     * @param bool $default
     * @return bool
     */
    protected function mapAuthorization(string $key, array $mapper, bool $default): bool
    {
        if (!array_key_exists($key, $mapper)) {
            return $default;
        }
        
        return (bool)$mapper[$key];
    }
    
    /**
     * Get the mapper for the Authorization. Returns empty mapper if not anything defined. 
     * @param int $level
     * @return array
     */
    protected function getAuthorizationMapper(int $level): array
    {
        $mapper = Cache::remember($key, function() { return AuthorizationHelper::loadAuthorizationMap(); }, 'default');
        
        if (!isset($mapper[$level])) {
            return [];
        }
        
        return $mapper[$level];
    }
}