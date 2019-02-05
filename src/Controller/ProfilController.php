<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Entity\Role;
use App\Form\UserType;
use App\Repository\QuestionRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\ReponseRepository;

class ProfilController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register (Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        // On récupère le Role qui doit être associée par défault à un User
        $repository = $this->getDoctrine()->getRepository(Role::class);
        $roleUser = $repository->findRoleUser();

        $user = new user();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        // Si Form est submit :
        if ($form->isSubmitted()) {

            // On verifie si les mot de passe sont identique
            if ($user->getPassword() != $user->getPasswordConfirm()) {
                $this->addFlash(
                    'danger',
                    'La confirmation de votre mot de passe a échoué'
                );
            } else if ($form->isValid()) {
                // Encode du password et passwordConfirm pour l'envoyer en base de donnée
                $passwordEncoded = $encoder->encodePassword($user, $user->getPassword());
                $passwordConfirmEncoded = $encoder->encodePassword($user, $user->getPasswordConfirm());
                $user->setPassword($passwordEncoded);
                $user->setPasswordConfirm($passwordConfirmEncoded);
                $user->setRole($roleUser);
    
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
    
                return $this->redirectToRoute('homepage', ['page' => 1]);
            }
        }

        return $this->render('profil/register.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profil/{id}/edit", name="user_edit", methods="GET|POST")
     */
    public function profil (Request $request, User $user, UserPasswordEncoderInterface $encoder): Response
    {
        if ($this->getUser() === $user)
        {
            // On stock le mot de passe courrant
            $currentPassword = $user->getPassword();
            // Et on le passe à vide
            $user->setPassword('');
            $user->setPasswordConfirm('');

            $form = $this->createForm(UserType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted()) {

                // On verifie si les mots de passe sont identiques
                if ($user->getPassword() != $user->getPasswordConfirm()) {
                    $this->addFlash(
                        'danger',
                        'La confirmation de votre mot de passe a échoué'
                    );
                } else if ($form->isValid()) {
                    // On compare le nouveau (form) et l'ancien (current)
                    if(empty($user->getPassword())) {
                        // Si c'est le même ne fait rien
                        $user->setPassword($currentPassword);
                        $user->setPasswordConfirm($currentPassword);
                    } else {
                        // On encode le mot de passe
                        $passwordEncoded = $encoder->encodePassword($user, $user->getPassword());
                        $passwordConfirmEncoded = $encoder->encodePassword($user, $user->getPasswordConfirm());
                        $user->setPassword($passwordEncoded);
                        $user->setPasswordConfirm($passwordConfirmEncoded);
                    }
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();

                    return $this->redirectToRoute('user_edit', ['id' => $user->getId()]);
                }

            }

            return $this->render('profil/profil.html.twig', [
                'user' => $user,
                'form' => $form->createView(),
            ]);
        }
        else
        {
            $this->addFlash(
                'danger',
                'Vous ne pouvez que modifier votre profil!!!'
            );

            return $this->redirectToRoute('homepage');
        }

    }

    /**
     * @Route("/profil/{id}/question", name="user_question")
     */
    public function questionByAuthor(QuestionRepository $questionRepository, UserInterface $user)
    {
        return $this->render('profil/question.html.twig', [
            'questions' => $questionRepository->findQuestionByAuthor($user->getId()),
        ]);
    }

    /**
     *@Route("/profil/{id}/reponse", name="user_reponse")
     */
    public function reponseByAuthor(ReponseRepository $reponseRepository, UserInterface $user)
    {
        return $this->render('profil/reponse.html.twig', [
            'reponses' => $reponseRepository->findReponseByAuthor($user->getId()),
        ]);
    }
}
                
       
    