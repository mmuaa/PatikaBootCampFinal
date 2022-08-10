<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Form\OrdersType;
use App\Repository\OrdersRepository;
use App\Repository\SettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/orders')]
class OrdersController extends AbstractController
{
    #[Route('/', name: 'app_orders_index', methods: ['GET'])]
    public function index(OrdersRepository $ordersRepository,SettingRepository $settingRepository): Response
    {

        $categories = $this->fetchCategoryTreeList();
        $categories[0] ='<ul>';
        $setting = $settingRepository->findAll();
        return $this->render('orders/index.html.twig', [
            'orders' => $ordersRepository->findAll(),
            'setting' => $setting,
            'categories' => $categories,
        ]);
    }

    #[Route('/new/{total}', name: 'app_orders_new', methods: ['GET', 'POST'])]
    public function new(Request $request,$total,OrdersRepository $ordersRepository,SettingRepository $settingRepository): Response
    {
        $order = new Orders();
        $form = $this->createForm(OrdersType::class, $order);
        $form->handleRequest($request);

        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $order->setIp($_SERVER['REMOTE_ADDR']);

            $sql = "SELECT * FROM shopcart WHERE userid=".$user->getId();
            $stmt = $em->getConnection()->prepare($sql);
            $result = $stmt->executeQuery()->fetchAllAssociative();
            $createdAt = new \DateTimeImmutable();
            $order->setCreatedAt($createdAt);

            $asd = array();
            foreach ($result as $res)
            {
              array_push($asd,$res["productid"]);
            }

            $order->setProductid(implode(',', $asd));


            $sqldelete = "DELETE FROM shopcart WHERE userid=".$user->getId();
            $stmt2 = $em->getConnection()->prepare($sqldelete);
            $result2 = $stmt2->executeQuery()->fetchAllAssociative();

            $sqldelete2 = "DELETE FROM discount WHERE userid=".$user->getId();
            $stmt3 = $em->getConnection()->prepare($sqldelete2);
            $result3 = $stmt3->executeQuery()->fetchAllAssociative();

            $ordersRepository->add($order, true);
            $this->addFlash('success','Siparişiniz Başarıyla Gerçekleşmiştir.<br>Teşekkür ederiz.');

            return $this->redirectToRoute('app_user_order', [], Response::HTTP_SEE_OTHER);
        }



        $categories = $this->fetchCategoryTreeList();
        $categories[0] ='<ul>';
        $setting = $settingRepository->findAll();
        return $this->renderForm('orders/new.html.twig', [
            'order' => $order,
            'form' => $form,
            'setting' => $setting,
            'categories' => $categories,
            'total' => $total,
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

    #[Route('/{id}', name: 'app_orders_show', methods: ['GET'])]
    public function show(Orders $order,SettingRepository $settingRepository): Response
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $sql = "SELECT * FROM orders WHERE userid=".$user->getId();
        $stmt = $em->getConnection()->prepare($sql);
        $result = $stmt->executeQuery()->fetchAllAssociative();

        $arr=array();
        for ($i=0;$i<ceil(strlen($result[0]["productid"])/2);$i++)
        {
            $first = explode(',',$result[0]["productid"]);
            $sql2 = "SELECT product.title FROM product JOIN orders ON product.id=".$first[$i];
            $stmt2 = $em->getConnection()->prepare($sql2);
            $result2 = $stmt2->executeQuery()->fetchAllAssociative();
            array_push($arr,$result2);
        }

        $categories = $this->fetchCategoryTreeList();
        $categories[0] ='<ul>';
        $setting = $settingRepository->findAll();
        return $this->render('user/userorderdetail.html.twig', [
            'order' => $order,
            'setting' => $setting,
            'categories' => $categories,
            'products' => $arr,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_orders_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Orders $order, OrdersRepository $ordersRepository): Response
    {
        $form = $this->createForm(OrdersType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ordersRepository->add($order, true);

            return $this->redirectToRoute('app_orders_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('orders/edit.html.twig', [
            'order' => $order,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_orders_delete', methods: ['POST'])]
    public function delete(Request $request, Orders $order, OrdersRepository $ordersRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$order->getId(), $request->request->get('_token'))) {
            $ordersRepository->remove($order, true);
        }

        return $this->redirectToRoute('app_orders_index', [], Response::HTTP_SEE_OTHER);
    }
}
