<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\OrdersRepository;
use App\Repository\SettingRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints\File;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(SettingRepository $settingRepository): Response
    {
        $categories = $this->fetchCategoryTreeList();
        $categories[0] ='<ul>';
        $setting = $settingRepository->findAll();
        return $this->render('user/show.html.twig', [
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

    #[Route('/orders', name: 'app_user_order', methods: ['GET'])]
    public function orders(OrdersRepository $ordersRepository,SettingRepository $settingRepository): Response
    {

        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $sql = "SELECT * FROM shopcart WHERE userid=".$user->getId();
        $stmt = $em->getConnection()->prepare($sql);
        $result = $stmt->executeQuery()->fetchAllAssociative();

        $categories = $this->fetchCategoryTreeList();
        $categories[0] ='<ul>';
        $setting = $settingRepository->findAll();
        return $this->render('user/userorder.html.twig',[
            'setting' => $setting,
            'categories' => $categories,
            'orders' => $ordersRepository->findBy(array('userid'=>$this->getUser()->getId())),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request,UserPasswordHasherInterface $userPasswordHasher, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //************** file upload ***>>>>>>>>>>>>
            /** @var file $file */
            $file = $form['image']->getData();
            if ($file) {
                $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                        $this->getParameter('images_directory'), // in Servis.yaml defined folder for upload images
                        $fileName
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $user->setImage($fileName); // Related upload file name with Hotel table image field
            }
            //<<<<<<<<<<<<<<<<<******** file upload ***********>
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request,$id, User $user,UserPasswordHasherInterface $userPasswordHasher, UserRepository $userRepository,SettingRepository $settingRepository): Response
    {
        $user = $this->getUser();
        if ($user->getId() != $id)
        {
            return $this->redirectToRoute("app_home");
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //************** file upload ***>>>>>>>>>>>>
            /** @var file $file */
            $file = $form['image']->getData();
            if ($file) {
                $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                        $this->getParameter('images_directory'), // in Servis.yaml defined folder for upload images
                        $fileName
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $user->setImage($fileName); // Related upload file name with Hotel table image field
            }
            //<<<<<<<<<<<<<<<<<******** file upload ***********>
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $user->setUpdatedAt(new \DateTimeImmutable());

            $userRepository->add($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        $categories = $this->fetchCategoryTreeList();
        $categories[0] ='<ul>';
        $setting = $settingRepository->findAll();
        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
            'setting' => $setting,
            'categories' => $categories,
        ]);
    }

    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository,TokenStorageInterface $tokenStorage): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $this->get('security.token_storage')->setToken(null);
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
