<?php
/*
 * @copyright (C) 2020 Michiel Keijts, Normit
 *
 */

namespace App\Policy\Resolver;

use Authorization\Policy\OrmResolver as BaseResolver;
use Cake\Datasource\RepositoryInterface;

/**
 * OrmResolver works as the basic OrmResolver, but destinguishes not between entity and tables, so uses less files
 */
class OrmResolver extends BaseResolver {
     /**
     * Get a policy for a table. Overrides, to use the Entity Class, and have one policy for one Table/Entity
     *
     * @param \Cake\Datasource\RepositoryInterface $table The table/repository to get a policy for.
     * @return mixed
     */
    protected function getRepositoryPolicy(RepositoryInterface $table)
    {
        if (!method_exists($table, 'getEntityClass')) {
            return parent::getRepositoryPolicy($table);
        }

        $class = $table->getEntityClass();
        $entityNamespace = '\\Model\Entity\\';
        $namespace = str_replace('\\', '/', substr($class, 0, (int)strpos($class, $entityNamespace)));
        /** @psalm-suppress PossiblyFalseOperand */
        $name = substr($class, strpos($class, $entityNamespace) + strlen($entityNamespace));

        return $this->findPolicy($class, $name, $namespace);
    }
}
