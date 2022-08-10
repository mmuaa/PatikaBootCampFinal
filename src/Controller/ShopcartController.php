<?php

namespace App\Controller;

use App\Entity\Shopcart;
use App\Form\ShopcartType;
use App\Repository\ShopcartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/shopcart')]
class ShopcartController extends AbstractController
{
    #[Route('/index', name: 'app_shopcart_index', methods: ['GET'])]
    public function index(ShopcartRepository $shopcartRepository ): Response
    {
        return $this->render('shopcart/index.html.twig', [
            'shopcarts' => $shopcartRepository->findAll(),
        ]);
    }

    #[Route('/new/{prodid}', name: 'app_shopcart_new', methods: ['GET', 'POST'])]
    public function new(Request $request,$prodid, ShopcartRepository $shopcartRepository): Response
    {
        $shopcart = new Shopcart();
        $form = $this->createForm(ShopcartType::class, $shopcart);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $user = $this->getUser();
            $shopcart->setUserid($user->getId());
            $shopcart->setProductid($prodid);
            $shopcart->setQuantity($request->request->get("shopcart")["quantity"]+1);

            $em = $this->getDoctrine()->getManager();
            $sql = "SELECT COUNT(*),quantity,s.id,p.price FROM `shopcart` AS s JOIN product AS p ON p.id=productid WHERE productid=".$prodid." AND userid=".$user->getId();
            $stmt = $em->getConnection()->prepare($sql);
            //$stmt->bindValue('parentid',$parent);
            $result = $stmt->executeQuery()->fetchAllAssociative();

            if ($result[0]['COUNT(*)'])
            {
                $shopcart->setQuantity($shopcart->getQuantity()+$result[0]['quantity']);
                $sqldelete = "DELETE FROM `shopcart` WHERE id=".$result[0]['id'];
                $em->getConnection()->prepare($sqldelete)->executeQuery();
                $shopcart->setTotalprice($shopcart->getQuantity()*$result[0]['price']);
                $shopcartRepository->add($shopcart, true);
            }
            $shopcart->setTotalprice($shopcart->getQuantity()*$result[0]['price']);
            $shopcartRepository->add($shopcart, true);

            return $this->redirectToRoute('app_shopcart', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('shopcart/new.html.twig', [
            'shopcart' => $shopcart,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_shopcart_show', methods: ['GET'])]
    public function show(Shopcart $shopcart): Response
    {
        return $this->render('shopcart/show.html.twig', [
            'shopcart' => $shopcart,
        ]);
    }

    /*
    #[Route('/{id}/edit', name: 'app_shopcart_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Shopcart $shopcart, ShopcartRepository $shopcartRepository): Response
    {
        $form = $this->createForm(ShopcartType::class, $shopcart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shopcartRepository->add($shopcart, true);

            return $this->redirectToRoute('app_shopcart_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('shopcart/edit.html.twig', [
            'shopcart' => $shopcart,
            'form' => $form,
        ]);
    }
    */

    #[Route('/{id}/del', name: 'app_shopcart_del', methods: ['POST'])]
    public function del(Request $request, Shopcart $shopcart, ShopcartRepository $shopcartRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$shopcart->getId(), $request->request->get('_token'))) {
            $shopcartRepository->remove($shopcart, true);
        }
        $this->addFlash('success','Ürün Sepetinizden Silindi.');
        return $this->redirectToRoute('app_shopcart', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_shopcart_delete', methods: ['POST'])]
    public function delete(Request $request, Shopcart $shopcart, ShopcartRepository $shopcartRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$shopcart->getId(), $request->request->get('_token'))) {
            $shopcartRepository->remove($shopcart, true);
        }

        return $this->redirectToRoute('app_shopcart_index', [], Response::HTTP_SEE_OTHER);

    }
}
