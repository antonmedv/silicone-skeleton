<?php

class Application extends Silicone\Application
{
    protected function configure()
    {
        $this['router.resource'] = array(
            $this->getRootDir() . '/src/Controller/',
        );

        $this['twig.path'] = array(
            $this->getRootDir() . '/view/',
        );
    }
}