<?php

class Application extends Silicone\Application
{
    protected function configure()
    {
        $app = $this;
        require_once $app->getRootDir() . '/config/dev.php';
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function entityManager()
    {
        return $this['em'];
    }
}