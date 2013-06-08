<?php
namespace Controller;

use Silicone\Route;
use Silicone\Controller;

class Index extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('index.twig');
    }
}