<?php
/**
* Admin controller.
*/
namespace App\Controller;

use App\Entity\Page;
use App\Repository\PageRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends AbstractController
{
    /**
    * Index action.
    *
    * @return \Symfony\Component\HttpFoundation\Response HTTP response
    * @return \Symfony\Component\Twig
    *
    * @Route(
    *    "/admin",
    *     name="admin_pages"
    *    )
    */
    public function pages(PageRepository $repository, Request $request, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            Page::NUMBER_OF_ITEMS
        );

        return $this->render(
            'admin/pages.html.twig',
            ['pagination' => $pagination]
        );
        // return $this->render('admin/index.html.twig', ['pages' => $repository->findAll()]);
    }
}
