<?php
/*
 * @copyright (C) 2020 Michiel Keijts, Normit
 * 
 */

namespace CakeAuthorizationHelper\Model\Table\Traits;

use Cake\Event\EventInterface;
use ArrayObject;


/**
 * Description of TranslatePolicyTrait
 *
 * @author michiel
 */
trait TranslatePolicyTrait {
    
    /**
     * Before the $data is merged into entities
     * @param EventInterface $event
     * @param ArrayObject $data
     * @param ArrayObject $options
     */
    public function beforeMarshal(EventInterface $event, ArrayObject $data, ArrayObject $options)
    {
        if (isset($data['locale']) && isset($options['accessibleFields'])) {
            $this->parsePolicyFieldsForLocale($data['locale'], $options);
        }
    }
    
    /**
     * Filter the accessibleFields for this language only. The $options['accessibleFields'] 
     * needs to have the following field:
     * 
     * $locale . SEPARATOR . $field
     * 
     * if the locale matches, the necessary ammendings of the accessible fields are done
     * 
     * @param string $locale
     * @param ArrayObject $options
     */
    protected function parsePolicyFieldsForLocale(string $locale, ArrayObject $options) 
    {
        $accessibleFields = array_filter($options['accessibleFields'], function ($key) use ($locale) {
            return strpos($key, $locale) === 0;
        }, ARRAY_FILTER_USE_KEY);
        
        $newAccessibleFields = [];
        foreach ($accessibleFields as $key => $value) {
            $_key = str_replace($locale . LOCALE_FIELD_SEPARATOR, '', $key);
            $newAccessibleFields[$_key] = $value;
        }
        
        $options['accessibleFields'] = $newAccessibleFields;
    }
}
