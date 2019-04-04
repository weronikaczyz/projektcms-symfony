<?php
/**
* Pages controller.
*/
namespace App\Controller;

use App\Entity\Page;
use App\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PagesController
 * @Route("/")
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
    public function index(PageRepository $repository): Response
    {
        return $this->render('pages/index.html.twig', ['page' => $repository->findAll()]);
    }

    /**
    * View action.
    *
    * @param \App\Entity\Page $page Page entity
    *
    * @return \Symfony\Component\HttpFoundation\Response HTTP response
    *
    * @Route(
    *     "/{id}",
    *     name="page_view",
    *     requirements={"id": "[1-9]\d*"},
    * )
    */
    public function view(Page $page): Response
    {
        return $this->render(
            'pages/view.html.twig',
            ['page' => $page]
        );
    }
}
