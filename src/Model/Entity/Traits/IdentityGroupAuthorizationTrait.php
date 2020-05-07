<?php
/* 
 * @copyright (C) 2020 Michiel Keijts, Normit
 * 
 */

namespace CakeAuthorizationHelper\Model\Entity\Traits;

/**
 * Extra funtionality to add to a IdentityInterface to check if an identity
 * has access
 */
trait IdentityGroupAuthorizationTrait {
    
    /**
     * Prevents the level to be set as superadmin
     * @param int $level
     * @return int
     */
    protected function _setLevel($level) {
        if ($level >= SUPERADMIN_AUTHORIZATION_LEVEL) {
            return $level;
        }
        
        return $this->level;
    }
}