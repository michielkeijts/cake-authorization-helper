<?php
/* 
 * @copyright (C) 2020 Michiel Keijts, Normit
 * 
 */

namespace CakeAuthorizationHelper\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;

class HelloCommand extends Command
{
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $io->out('Hello world.');
    }
}