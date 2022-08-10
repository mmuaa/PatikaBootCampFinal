<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserType;
use App\Repository\SettingRepository;
use App\Security\AppCustomAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppCustomAuthenticator $authenticator, EntityManagerInterface $entityManager,SettingRepository $settingRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $user->setCreatedAt(new \DateTimeImmutable());
            $user->setStatus('True');

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        $categories = $this->fetchCategoryTreeList();

        $categories[0] ='<ul>';

        $setting = $settingRepository->findAll();
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'categories' => $categories,
            'setting' => $setting,
        ]);
    }

    public function fetchCategoryTreeList($parent=0,$user_tree_array='')
    {
        if (!is_array($user_tree_array))
            $user_tree_array = array();

        $em = $this->getDoctrine()->getManager();

        $sql = "SELECT * FROM category WHERE status='true' AND parentid=".$parent;
        $stmt = $em->getConnection()->prepare($sql);
        //$stmt->bindValue('parentid',$parent);
        $result = $stmt->executeQuery()->fetchAllAssociative();


        if (count($result) > 0)
        {
            $user_tree_array[] = "<ul>";
            foreach ($result as $row)
            {
                $user_tree_array[] = "<li><a class='nav-item nav-link' href='category/".$row['id']."'>".$row["title"]."</a>";
                $user_tree_array = $this->fetchCategoryTreeList($row['id'],$user_tree_array);
            }
            $user_tree_array[] ="</li></ul>";
        }
        return $user_tree_array;
    }

}
