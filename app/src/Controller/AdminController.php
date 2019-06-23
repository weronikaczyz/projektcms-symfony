<?php
/**
* Admin controller.
*/
namespace App\Controller;

use App\Entity\Page;
use App\Entity\Setting;
use App\Entity\User;
use App\Form\SettingType;
use App\Form\AdminEditUserType;
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
     * Edit user action.
     *
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     * @return \Symfony\Component\Twig
     *
     * @Route(
     *     "/admin/users/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="admin_users_edit",
     * )
     */
    public function editUser(Request $request, User $user): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->render(
                'errors/403.html.twig'
            );
        }

        $userForm = $this->createForm(AdminEditUserType::class, $user, ['method' => 'PUT']);

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $userForm->get('admin')->setData(true);
        }

        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $user->setUpdatedAt(new \DateTime());
            if ($userForm->get('admin')->getData()) {
                $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
            } else {
                $user->setRoles(['ROLE_USER']);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'message.updated_successfully');
            return $this->redirectToRoute('admin_users');
        }

        return $this->render(
            'admin/user_edit.html.twig',
            [
                'form' => $userForm->createView(),
                'user' => $user
            ]
        );
    }

    /**
    * Delete user action.
    *
    *
    * @return \Symfony\Component\HttpFoundation\Response HTTP response
    * @return \Symfony\Component\Twig
    *
    * @Route(
    *     "/admin/users/{id}/delete",
    *     methods={"GET"},
    *     requirements={"id": "[1-9]\d*"},
    *     name="admin_users_delete",
    * )
    */
    public function deleteUser(Request $request, User $user): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->render(
                'errors/403.html.twig'
            );
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();


        $this->addFlash('success', 'message.deleted_successfully');
        return $this->redirectToRoute('admin_users');
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
