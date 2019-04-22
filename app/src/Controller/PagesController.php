<?php
/**
* Pages controller.
*/
namespace App\Controller;

use App\Entity\Page;
use App\Form\PageType;
use App\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

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
    * @Route(
    *    "/",
    *     name="page_index"
    *    )
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
    public function view(Page $page = null): Response
    {
        if (empty($page)) {
            return $this->render(
                'errors/404.html.twig'
            );
        }

        return $this->render(
            'pages/view.html.twig',
            ['page' => $page]
        );
    }

    /**
    * New action.
    *
    * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
    * @param \App\Repository\PageRepository            $repository Task repository
    *
    * @return \Symfony\Component\HttpFoundation\Response HTTP response
    *
    * @throws \Doctrine\ORM\ORMException
    * @throws \Doctrine\ORM\OptimisticLockException
    *
    * @Route(
    *     "/pages/new",
    *     methods={"GET", "POST"},
    *     name="page_new",
    * )
    */
    public function new(Request $request, PageRepository $repository): Response
    {
        $page = new Page();
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($page);

            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('admin_index');
        }

        return $this->render(
            'pages/new.html.twig',
            ['form' => $form->createView()]
        );
    }
}
