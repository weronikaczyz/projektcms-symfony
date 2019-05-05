<?php
/**
* Pages controller.
*/
namespace App\Controller;

use App\Entity\Page;
use App\Form\PageType;
use App\Repository\PageRepository;
use App\Repository\SettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

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
        $page = $repository->getHomePage();

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
    * Given user's pages action.
    *
    * @param \App\Repository\PageRepository Page repository
    * @param \App\Repository\SettingRepository Setting repository
    * @param \Symfony\Component\HttpFoundation\Request HTTP request
    * @param \Knp\Component\Pager\PaginatorInterface Paginator
    *
    * @return \Symfony\Component\HttpFoundation\Response HTTP response
    * @return \Symfony\Component\Twig
    *
    * @Route(
    *    "/pages/my",
    *     name="pages_my"
    *    )
    */
    public function my_pages(
        PageRepository $repository,
        SettingRepository $settingRepository,
        Request $request,
        PaginatorInterface $paginator
    ): Response
    {
        $user = $this->getUser();
        $homepageId = $settingRepository->getHomepageId();

        $pagination = $paginator->paginate(
            $repository->queryAllByUser($user),
            $request->query->getInt('page', 1),
            Page::NUMBER_OF_ITEMS
        );

        return $this->render(
            'admin/pages.html.twig',
            ['pagination' => $pagination, 'homepageId' => $homepageId]
        );
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
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_USER')) {
            return $this->render(
                'errors/403.html.twig'
            );
        }

        $page = new Page();
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $page->setCreatedAt(new \DateTime());
            $page->setUpdatedAt(new \DateTime());
            $page->setAuthor($this->getUser());
            $repository->save($page);

            $this->addFlash('success', 'message.created_successfully');

            if ($this->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('admin_pages');
            } else {
                return $this->redirectToRoute('pages_my');
            }
        }

        return $this->render(
            'pages/new.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
    * Edit action.
    *
    * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
    * @param \App\Entity\Page                          $page       Page entity
    * @param \App\Repository\PageRepository            $repository Page repository
    *
    * @return \Symfony\Component\HttpFoundation\Response HTTP response
    *
    * @throws \Doctrine\ORM\ORMException
    * @throws \Doctrine\ORM\OptimisticLockException
    *
    * @Route(
    *     "/pages/{id}/edit",
    *     methods={"GET", "PUT"},
    *     requirements={"id": "[1-9]\d*"},
    *     name="page_edit",
    * )
    */
    public function edit(Request $request, Page $page, PageRepository $repository): Response
    {
        $user = $this->getUser();

        if (!$this->isGranted('ROLE_ADMIN') && $user->getId() !== $page->getAuthor()->getId()) {
            return $this->render(
                'errors/403.html.twig'
            );
        }

        $form = $this->createForm(PageType::class, $page, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $page->setUpdatedAt(new \DateTime());
            $repository->save($page);

            $this->addFlash('success', 'message.updated_successfully');

            if ($this->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('admin_pages');
            } else {
                return $this->redirectToRoute('pages_my');
            }
        }

        return $this->render(
            'pages/edit.html.twig',
            [
                'form' => $form->createView(),
                'page' => $page,
            ]
        );
    }
}
