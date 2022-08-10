<?php

namespace App\Controller;

use App\Repository\Admin\CategoryRepository;
use App\Repository\SettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            $user = $this->getUser(); // Get login User data
            if ($user->getRoles()[0]=='ROLE_ADMIN')
                return $this->redirectToRoute('admin');
            else
                return $this->redirectToRoute('app_home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/adminlogin.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/loginuser', name: 'app_user_login')]
    public function loginuser(AuthenticationUtils $authenticationUtils,SettingRepository $settingRepository): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $categories = $this->fetchCategoryTreeList();

        $categories[0] ='<ul>';
        $setting = $settingRepository->findAll();
        return $this->render('security/userlogin.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'setting' => $setting,
            'categories' => $categories,
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

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
