<?php

namespace App\Controller;

use App\Entity\Admin\Message;
use App\Entity\Admin\Product;
use App\Entity\Discount;
use App\Entity\Shopcart;
use App\Form\Admin\MessageType;
use App\Repository\Admin\CategoryRepository;
use App\Repository\Admin\ImageRepository;
use App\Repository\Admin\MessageRepository;
use App\Repository\Admin\ProductRepository;
use App\Repository\DiscountRepository;
use App\Repository\SettingRepository;
use App\Repository\ShopcartRepository;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Driver\Result;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(SettingRepository $settingRepository,CategoryRepository $categoryRepository,ProductRepository $productRepository): Response
    {
        $setting = $settingRepository->findAll();
        $category = $categoryRepository->findBy(array('parentid'=>0,"status"=>'true'));
        $product = $productRepository->findBy(array("status"=>'true'),array('id'=>'DESC'),8);

        $categories = $this->fetchCategoryTreeList();

        $categories[0] ='<ul>';

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'setting' => $setting,
            'category' => $category,
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    #[Route('/search', name: 'ajax_search', methods: ['GET'])]
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $requestString = $request->get('q');

        $entities =  $em->getRepository(Product::class)->findEntitiesByString($requestString);

        if(!$entities) {
            $result['entities']['error'] = "Bulunamadı";
        } else {
            $result['entities'] = $this->getRealEntities($entities);
        }

        return new Response(json_encode($result));
    }

    public function getRealEntities($entities){

        foreach ($entities as $entity){
            $realEntities[$entity->getId()] = $entity->getTitle();
        }

        return $realEntities;
    }


    #[Route('/product/{id}', name: 'app_product_show', methods: ['GET','POST'])]
    public function productshow(Product $product,$id,ImageRepository $imageRepository,SettingRepository $settingRepository): Response
    {
        $categories = $this->fetchCategoryTreeList();
        $categories[0] ='<ul>';
        $setting = $settingRepository->findAll();
        $images = $imageRepository->findBy(array('product'=>$id));
        return $this->render('home/productshow.html.twig', [
            'setting' => $setting,
            'product' => $product,
            'images' => $images,
            'categories' => $categories,
        ]);
    }

    #[Route('/product', name: 'app_allproduct_show', methods: ['GET','POST'])]
    public function allproductshow(ProductRepository $productRepository,SettingRepository $settingRepository): Response
    {
        $categories = $this->fetchCategoryTreeList();

        $categories[0] ='<ul>';
        $product = $productRepository->findAll();
        $setting = $settingRepository->findAll();
        return $this->render('home/allproductshow.html.twig', [
            'setting' => $setting,
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    #[Route('/category/{catid}', name: 'app_category', methods: ['GET'])]
    public function categoryshow(SettingRepository $settingRepository,$catid,CategoryRepository $categoryRepository): Response
    {
        $data = $categoryRepository->findBy(array('id'=>$catid));

        $em = $this->getDoctrine()->getManager();

        $sql = "SELECT * FROM product JOIN product_category ON id=product_id WHERE status='true' AND category_id=".$catid;
        $stmt = $em->getConnection()->prepare($sql);
        //$stmt->bindValue('parentid',$parent);
        $products = $stmt->executeQuery()->fetchAllAssociative();

        $categories = $this->fetchCategoryTreeList();

        $categories[0] ='<ul>';
        $selectedcategory = $categoryRepository->findBy(array('id'=>$catid));
        $setting = $settingRepository->findAll();
        return $this->render('home/productshowbycategory.html.twig', [
            'setting' => $setting,
            'products' => $products,
            'categories' => $categories,
            'selectedcategory' => $selectedcategory,
        ]);
    }

    #[Route('/shopcart', name: 'app_shopcart', methods: ['GET'])]
    public function shopcart(ShopcartRepository $shopcartRepository,SettingRepository $settingRepository,DiscountRepository $discountRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        // CART
        $sql = "SELECT p.title,p.price,s.*,p.image FROM shopcart s,product p WHERE s.productid=p.id AND userid=".$user->getId()." ORDER BY p.price";
        $stmt = $em->getConnection()->prepare($sql);
        //$stmt->bindValue('userid',);
        $result2 = $stmt->executeQuery()->fetchAllAssociative();
        $result = $result2;

        //Total quantity of Shopcart
        $sqltotal = "SELECT SUM(quantity) FROM shopcart WHERE userid=".$user->getId();
        $stmt3 = $em->getConnection()->prepare($sqltotal);
        $result3 = $stmt3->executeQuery()->fetchAllAssociative();

        //Coupon code
        $sqldiscount = "SELECT title FROM discount WHERE userid=".$user->getId();
        $stmt4 = $em->getConnection()->prepare($sqldiscount);
        $result4 = $stmt4->executeQuery()->fetchAllAssociative();

        //Charttaki Ayakkabı Kategorisine ait ürünleri bulur
        $sqlshoes = "SELECT * FROM product 
    JOIN shopcart on product.id=shopcart.productid 
    JOIN product_category ON product.id=product_category.product_id 
         WHERE userid=".$user->getId()." AND product_category.category_id=13 ORDER BY product.price;";
        $stmt5 = $em->getConnection()->prepare($sqlshoes);
        $result5 = $stmt5->executeQuery()->fetchAllAssociative();

        //Charttaki Ayakkabı Kategorisine ait ürün sayısını bulur
        $sqlshoecount = "SELECT SUM(shopcart.quantity) FROM product 
    JOIN shopcart on product.id=shopcart.productid 
    JOIN product_category ON product.id=product_category.product_id 
         WHERE userid=".$user->getId()." AND product_category.category_id=13;";
        $stmt6 = $em->getConnection()->prepare($sqlshoecount);
        $result6 = $stmt6->executeQuery()->fetchAllAssociative();

        $i = 0;
        if ($result3[0]["SUM(quantity)"]==0 && empty($result4))
        {
            $indirimsonrasitoplam[0] = 0;

        }elseif($result3[0]["SUM(quantity)"]==0)
        {
            $indirimsonrasitoplam[0] = 0;
        }
        elseif($result3[0]["SUM(quantity)"]==1)
        {
            $indirimsonrasitoplam[$i] = $result2[$i]["price"];
        }
        elseif(empty($result4))
        {
            $lastkeyindex = array_key_last($result2);
            for ($i=0;$i<=$lastkeyindex;$i++){
                $indirimsonrasitoplam[$i] = $result2[$i]["quantity"]*$result2[$i]["price"];
            }
        }
        elseif($result4[0]["title"]=="ikinci")
        {
            if ($result3[0]["SUM(quantity)"]/2>1)
            {
                $indirimsayisi = floor(($result3[0]["SUM(quantity)"])/2);
                $lastitemindex = 0;
                $lastkeyindex = array_key_last($result2);
                while ($indirimsayisi>0)
                {
                    $result[$lastitemindex]["quantity"]--;
                    $indirimsayisi--;
                    if ($indirimsayisi == 0 && $result[$lastitemindex]["quantity"]==0 )
                    {
                        $indirimsonrasitoplam[$i]= $result2[$i]["price"]/2*$result2[$i]["quantity"];
                        $i++;
                        for ($i;$i<=$lastkeyindex;$i++)
                        {
                            $indirimsonrasitoplam[$i] = $result2[$i]["quantity"]*$result2[$i]["price"];
                        }

                    }elseif ($result[$lastitemindex]["quantity"]==0)
                    {
                        $indirimuygulancakitem = $result2[$i]["quantity"];
                        $indirimsonrasitoplam[$i] = $result2[$i]["price"]/2*$indirimuygulancakitem;
                        $lastitemindex++;
                        $i++;
                    }elseif($indirimsayisi == 0){

                        $indirimuygulancakitem = $result2[$i]["quantity"]-$result[$lastitemindex]["quantity"];
                        $indirimsonrasitoplam[$i] = ($result2[$i]["price"]/2*$indirimuygulancakitem)+($result[$lastitemindex]["quantity"]*$result2[$i]["price"]);
                        $i++;
                        for ($i;$i<=$lastkeyindex;$i++)
                        {
                            $indirimsonrasitoplam[$i] = $result2[$i]["quantity"]*$result2[$i]["price"];
                        }
                    }
                    else
                    {
                        $indirimuygulancakitem = $result2[$i]["quantity"]-$result[$lastitemindex]["quantity"];
                        $indirimsonrasitoplam[$i] = ($result2[$i]["price"]/2*$indirimuygulancakitem)+($result[$lastitemindex]["quantity"]*$result2[$i]["price"]);
                    }
                }
            }else
            {
                $indirimsonrasitoplam[$i] = $result2[$i]["price"];
            }
        }
        elseif($result4[0]["title"]=="ayakkabi" && $result6[0]["SUM(shopcart.quantity)"]>=3)
        {
            $indirimsonrasitoplam[0] = $result5[0]["totalprice"]-$result5[0]["price"];
            $i++;
            $lastkeyindex = array_key_last($result2);
            for ($i;$i<=$lastkeyindex;$i++){
                $indirimsonrasitoplam[$i] = $result2[$i]["quantity"]*$result2[$i]["price"];
            }
        }
        else
        {
            $lastkeyindex = array_key_last($result2);
            for ($i=0;$i<=$lastkeyindex;$i++){
                $indirimsonrasitoplam[$i] = $result2[$i]["quantity"]*$result2[$i]["price"];
            }
        }

        $categories = $this->fetchCategoryTreeList();
        $categories[0] ='<ul>';

        $setting = $settingRepository->findAll();
        return $this->render('shopcart/index.html.twig', [
            'setting' => $setting,
            'shopcarts' => $result2,
            'categories' => $categories,
            'toplam' => $indirimsonrasitoplam,
            'item' => $result3[0]["SUM(quantity)"],
            'discount' => $discountRepository->findBy(array('userid'=>$user->getId())),
        ]);
    }

    #[Route('/aboutus', name: 'app_aboutus', methods: ['GET'])]
    public function about(SettingRepository $settingRepository): Response
    {
        $categories = $this->fetchCategoryTreeList();
        $categories[0] ='<ul>';

        $setting = $settingRepository->findAll();
        return $this->render('home/aboutus.html.twig', [
            'setting' => $setting,
            'categories' => $categories,
        ]);
    }

    #[Route('/contact', name: 'app_contact', methods: ['GET', 'POST'])]
    public function contact(Request $request,SettingRepository $settingRepository,MessageRepository $messageRepository): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setStatus("New");
            $message->setIp($_SERVER['REMOTE_ADDR']);
            $create = new \DateTimeImmutable();
            $message->setCreatedAt($create);

            $messageRepository->add($message, true);

            $this->addFlash('success', 'Your message has been sent successfuly');

            return $this->redirectToRoute('app_contact', [], Response::HTTP_SEE_OTHER);
        }

        $categories = $this->fetchCategoryTreeList();
        $categories[0] ='<ul>';

        $setting = $settingRepository->findAll();
        return $this->render('home/contact.html.twig', [
            'setting' => $setting,
            'message' => $message,
            'form' => $form->createView(),
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

}
