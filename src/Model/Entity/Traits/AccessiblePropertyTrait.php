<?php
/*
 * @copyright (C) 2020 Michiel Keijts, Normit
 * 
 */

namespace CakeAuthorizationHelper\Model\Entity\Traits;

/**
 * Add a policy check to the isAccesible option of AccessiblePropertyTrait
 *
 * @author michiel
 */
trait AccessiblePropertyTrait {
    /**
     * Checks if a field is accessible
     *
     * ### Example:
     *
     * ```
     * $entity->isAccessible('id'); // Returns whether it can be set or not
     * ```
     *
     * @param string $field Field name to check
     * @return bool
     */
    public function isAccessible(string $field): bool
    {
        $value = $this->_accessible[$field] ??
            null;

        return ($value === null && !empty($this->_accessible['*'])) || $value;
    }
    
    //public function setAccess
}
