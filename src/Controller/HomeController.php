<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', []);
    }

    #[Route('/download/{filename}', name: 'home_download', methods: ['GET'])]
    public function download(string $filename): BinaryFileResponse
    {
        return $this->file(new File($filename));
    }

    #[Route('/api/helloword/{name}', name: 'api_helloword')]
    public function apiHelloword(string $name): Response
    {
        return new JsonResponse('Hello ' . $name . ' !');
    }



    //route de modification d'un produit
    #[Route('/api/product/update', name: 'api_product_update', methods: ['PUT'])]
    public function updateProduct(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $productId = $data['id'];
        $productName = $data['name'];
        $productDescription = $data['description'];
        $productPrice = $data['price'];

        $product = $this->getDoctrine()->getRepository(Product::class)->find($productId);
        $product->setName($productName);
        $product->setDescription($productDescription);
        $product->setPrice($productPrice);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($product);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Produit mis à jour']);
    }


    // route de suppression d'un produit 
    #[Route('/api/product/delete/{id}', name: 'api_product_delete', methods: ['DELETE'])]
    public function deleteProduct(int $id): Response
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        $product->deleteProduct();


        return new JsonResponse(['message' => 'Product deleted successfully']);
    }

    
}
