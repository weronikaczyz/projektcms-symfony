<?php
/**
* Pages controller.
*/
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PagesController
 */
class PagesController extends AbstractController
{
    /**
    * Index action.
    *
    * @return \Symfony\Component\HttpFoundation\Response HTTP response
    * @return \Symfony\Component\Twig
    *
    * @Route("/")
    */
    public function index(): Response
    {
        return $this->render('pages/index.html.twig');
    }
}
