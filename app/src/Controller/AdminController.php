<?php
/**
* Admin controller.
*/
namespace App\Controller;

use App\Entity\Page;
use App\Entity\User;
use App\Repository\PageRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends AbstractController
{
    /**
    * Pages action.
    *
    * @param \App\Repository\PageRepository Page repository
    * @param \Symfony\Component\HttpFoundation\Request HTTP request
    * @param \Knp\Component\Pager\PaginatorInterface Paginator
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
    }

    /**
    * Users action.
    *
    * @return \Symfony\Component\HttpFoundation\Response HTTP response
    * @return \Symfony\Component\Twig
    *
    * @Route(
    *    "/admin/users",
    *     name="admin_users"
    *    )
    */
    public function users(UserRepository $repository, Request $request, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            User::NUMBER_OF_ITEMS
        );

        return $this->render(
            'admin/users.html.twig',
            ['pagination' => $pagination]
        );
    }
}
