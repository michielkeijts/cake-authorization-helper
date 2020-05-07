<?php
/* 
 * @copyright (C) 2020 Michiel Keijts, Normit
 * 
 */

namespace CakeAuthorizationHelper\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use DirectoryIterator;
use RegexIterator;
use ReflectionClass;

class ExtractPolicyActionsCommand extends Command
{
    /**
     * 
     * @param Arguments $args
     * @param ConsoleIo $io
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $constants = [];
        foreach ($this->getPolicies(APP . 'Policy') as $policy) {
            $constants = array_merge($constants, $this->parsePolicy($policy));
        }
        
        $io->out("\"" . implode("\" => TRUE,\n\"", $constants) . "\"");
    }
    
    protected function parsePolicy(string $filepath): array
    {
        if (preg_match("/\/([^\/]+)Policy/i", $filepath, $matches) !== 1) {
            return [];
        }
        
        $name = end($matches);
        $policy_class_name = sprintf("App\\Policy\\%sPolicy", $name);
        
        $constants = [];
        foreach ($this->getConstants($policy_class_name) as $constant) {
            $constants[] = strtoupper($name.'::'.$constant);
        }
        
        return $constants;
    }


    /**
     * Get a list of Policy files (filenames)
     * @param string $path
     * @return array
     */
    protected function getPolicies(string $path = './Policy'): array
    {
        $Directory = new DirectoryIterator($path);
       
        $list = [];
        while ($Directory->valid()) {
            if (strpos($Directory->getFilename(), 'Policy') !== FALSE) {
                $list[] = $Directory->getPathname();
            }
            
            $Directory->next();
        }
        
        return $list;
    }
    
    /**
     * Get list of constants for a policy
     * @param type $policy
     * @param string $prefix
     * @return array
     */
    protected function getConstants($policy, string $prefix = 'ACTION_') : array
    {
        $reflection = new ReflectionClass($policy);
        
        $constants = $reflection->getConstants();
        return array_filter($constants, function ($item, $key) use ($prefix) { return strpos($key, $prefix) === 0; }, ARRAY_FILTER_USE_BOTH);
    }
}