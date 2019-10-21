<?php

namespace Console;

use EasySwoole\EasySwoole\Command\CommandContainer;
use EasySwoole\EasySwoole\Command\CommandInterface;
use EasySwoole\EasySwoole\Command\Utility;

class CommandMakeCommand implements CommandInterface
{

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/Aux/stubs/service.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Service';
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        return $this->laravel['path'].'/'.str_replace('\\', '/', $name).'Service.php';
    }


    public function commandName(): string
    {
        return 'make:command';
    }

    public function exec(array $args): ?string
    {
        // TODO: Implement exec() method.
        if (!isset($args[0])) {
            return '请输入要生成命令名称!';
        } else {
            return $args[0];
        }
    }

    public function help(array $args): ?string
    {

    }
}
