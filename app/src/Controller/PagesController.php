<?php
/**
* Pages controller.
*/
namespace App\Controller;

use App\Entity\Page;
use App\Form\PageType;
use App\Repository\PageRepository;
use App\Repository\SettingRepository;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class PagesController
 * @Route("/")
 */
class PagesController extends AbstractController
{
    /**
    * Uploader Service.
    *
    * @var \App\Service\FileUploader $uploaderService
    */
    private $uploaderService = null;

    /**
    * Constructor.
    *
    * @param \App\Service\FileUploader $uploaderService Uploader Service.
    */
    public function __construct(FileUploader $uploaderService)
    {
        $this->uploaderService = $uploaderService;
    }

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

            if (null !== $request->files->get('page')) {
                $file = $request->files->get('page');
                if (isset($file['file'])) {
                    $file = $file['file'];
                }

                if ($file instanceof UploadedFile) {
                    $filename = $this->uploaderService->upload($file);
                    $page->setFile($filename);
                }
            }

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
    public function edit(
        Request $request,
        Page $page,
        PageRepository $repository,
        SettingRepository $settingRepository
    ): Response
    {
        $user = $this->getUser();
        $oldFile = $page->getFile();

        if (!$this->isGranted('ROLE_ADMIN') && $user->getId() !== $page->getAuthor()->getId()) {
            return $this->render(
                'errors/403.html.twig'
            );
        }

        $homepage = $repository->getHomePage();

        if (null !== $page->getFile()) {
            $page->setFile(new File($this->uploaderService->getTargetDirectory() . '/' . $page->getFile()));
        }

        $form = $this->createForm(PageType::class, $page, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->isGranted('ROLE_ADMIN')) {
                $isHomepage = $request->request->get('homepage');
                if ($isHomepage) {
                    $settingRepository->setHomepage($page->getId());
                    $page->setPublished(true);
                }
            }

            if (null !== $request->files->get('page')) {
                $file = $request->files->get('page');
                if (isset($file['file'])) {
                    $file = $file['file'];
                } else {
                    $page->setFile($oldFile);
                }

                if ($file instanceof UploadedFile) {
                    $filename = $this->uploaderService->upload($file);
                    $page->setFile($filename);
                }
            }

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
                'homepage' => $homepage
            ]
        );
    }
}
