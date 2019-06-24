<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\AccountEditType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * Edit account action.
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     * @return \Symfony\Component\Twig
     *
     * @Route("/account", name="app_account")
     */
    public function accountEdit(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        UserRepository $userRepository
    ): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->render(
                'errors/403.html.twig'
            );
        }

        $form = $this->createForm(AccountEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($passwordEncoder->isPasswordValid($user, $form->get('password')->getData())) {
                $user->setName($form->get('name')->getData());
                $user->setUpdatedAt(new \DateTime());

                // encode the plain password
                if ($form->get('newPassword')->getData()) {
                    $user->setPassword(
                        $passwordEncoder->encodePassword(
                            $user,
                            $form->get('newPassword')->getData()
                        )
                    );
                }

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'message.updated_successfully');
                return $this->redirectToRoute('page_index');
            } else {
                $this->addFlash('error', 'message.invalid_password');
            }
        }

        return $this->render('account/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
