<?php
/**
* Admin controller.
*/
namespace App\Controller;

use App\Entity\Page;
use App\Entity\Setting;
use App\Entity\User;
use App\Form\SettingType;
use App\Repository\PageRepository;
use App\Repository\UserRepository;
use App\Repository\SettingRepository;
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
    * @param \App\Repository\SettingRepository Setting repository
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
    public function pages(
        PageRepository $repository,
        SettingRepository $settingRepository,
        Request $request,
        PaginatorInterface $paginator
    ): Response
    {
        $homepageId = $settingRepository->getHomepageId();

        $pagination = $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            Page::NUMBER_OF_ITEMS
        );

        return $this->render(
            'admin/pages.html.twig',
            ['pagination' => $pagination, 'homepageId' => $homepageId]
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

    /**
    * Settings action.
    *
    * @return \Symfony\Component\HttpFoundation\Response HTTP response
    * @return \Symfony\Component\Twig
    *
    * @Route(
    *    "/admin/settings",
    *     methods={"GET", "PUT"},
    *     name="admin_settings"
    *    )
    */
    public function settings(Request $request, SettingRepository $repository): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->render(
                'errors/403.html.twig'
            );
        }

        $setting = $repository->getPageTitle();

        $titleForm = $this->createForm(SettingType::class, $setting, ['method' => 'PUT']);
        $titleForm->handleRequest($request);

        if ($titleForm->isSubmitted() && $titleForm->isValid()) {
            $setting->setUpdatedAt(new \DateTime());
            $repository->save($setting);

            $this->addFlash('success', 'message.updated_successfully');
            return $this->redirectToRoute('admin_pages');
        }

        return $this->render(
            'admin/settings.html.twig',
            [
                'titleForm' => $titleForm->createView()
            ]
        );
    }
}
