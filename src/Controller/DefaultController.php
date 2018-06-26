<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\CartServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     * @param ProductRepository $productRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(ProductRepository $productRepository)
    {
        return $this->render('default/home.twig', [
            'articleList' => $productRepository->getAllProducts(),
        ]);
    }

    /**
     * @Route("/add", name="add")
     * @param Request $request
     * @param CartServiceInterface $cartService
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function add(Request $request, CartServiceInterface $cartService)
    {
        $cartService->add($request->get('articleId'));
        return $this->json(['result' => 'OK']);
    }

    /**
     * @Route("/cart", name="cart")
     * @param CartServiceInterface $cartService
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cart(CartServiceInterface $cartService)
    {
        return $this->render('default/cart.twig', [
            'cart' => $cartService->getCart(),
        ]);
    }

    /**
     * @Route("/remove", name="remove")
     * @param Request $request
     * @param CartServiceInterface $cartService
     * @return void
     */
    public function remove(Request $request, CartServiceInterface $cartService)
    {
        $cartService->remove($request->get('articleId'));
        return $this->redirect('/cart');
    }

    /**
     * @Route("/empty-cart", name="emptycart")
     * @param Request $request
     * @param CartServiceInterface $cartService
     * @return void
     */
    public function emptyCart(Request $request, CartServiceInterface $cartService)
    {
        $cartService->resetCart();
        return $this->redirect('/cart');
    }
}
