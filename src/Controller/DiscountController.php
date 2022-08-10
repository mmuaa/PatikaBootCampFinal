<?php

namespace App\Controller;

use App\Entity\Discount;
use App\Form\DiscountType;
use App\Repository\DiscountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/discount')]
class DiscountController extends AbstractController
{
    #[Route('/', name: 'app_discount_index', methods: ['GET'])]
    public function index(DiscountRepository $discountRepository): Response
    {
        return $this->render('discount/index.html.twig', [
            'discounts' => $discountRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_discount_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DiscountRepository $discountRepository): Response
    {
        $discount = new Discount();
        $form = $this->createForm(DiscountType::class, $discount);
        $form->handleRequest($request);
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $sql = "SELECT * FROM discount WHERE userid=".$user->getId();
        $stmt = $em->getConnection()->prepare($sql);
        //$stmt->bindValue('userid',);
        $result2 = $stmt->executeQuery()->fetchAllAssociative();
        $check = $request->request->get("discount");

        if (!$result2)
        {
            if ($check["title"]=='ikinci' || $check["title"]=='ayakkabi')
            {
                if ($form->isSubmitted() && $form->isValid()) {
                    $discount->setUserid($user->getId());
                    $discountRepository->add($discount, true);

                    return $this->redirectToRoute('app_shopcart', [], Response::HTTP_SEE_OTHER);
                }
            }else{
                $this->addFlash('warning','Böyle bir kupon bulunmamaktadır.');
                return $this->redirectToRoute('app_shopcart', [], Response::HTTP_SEE_OTHER);
            }
        }else{
            $this->addFlash('warning','Sepette sadece tek kupon kullanılabilir');
            return $this->redirectToRoute('app_shopcart', [], Response::HTTP_SEE_OTHER);
        }


        return $this->renderForm('discount/new.html.twig', [
            'discount' => $discount,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_discount_show', methods: ['GET'])]
    public function show(Discount $discount): Response
    {
        return $this->render('discount/show.html.twig', [
            'discount' => $discount,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_discount_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Discount $discount, DiscountRepository $discountRepository): Response
    {
        $form = $this->createForm(DiscountType::class, $discount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $discountRepository->add($discount, true);

            return $this->redirectToRoute('app_discount_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('discount/edit.html.twig', [
            'discount' => $discount,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_discount_delete', methods: ['POST'])]
    public function delete(Request $request, Discount $discount, DiscountRepository $discountRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$discount->getId(), $request->request->get('_token'))) {
            $discountRepository->remove($discount, true);
        }

        return $this->redirectToRoute('app_shopcart', [], Response::HTTP_SEE_OTHER);
    }
}
