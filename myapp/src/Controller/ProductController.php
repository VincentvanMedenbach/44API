<?php


namespace App\Controller;


use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="Post_product", methods={"POST"})
     */
    public function createProduct(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $product = new product();
        $product->setBarcode($data['barcode']);
        $product->setName($data['name']);
        $product->setHeight($data['height']);
        $product->setWidth($data['width']);

        if (empty($product->getBarcode()) || empty($product->getHeight()) || empty(
            $product->getName()
            ) || empty($product->getWidth())) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($product); //TODO PROPERLY CATCH ERRORS!


        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response(var_dump($product));
    }

    /**
     * @Route("/product", name="Patch_product", methods={"PATCH"})
     */
    public function editProduct(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $id = $data['id'];
        if (empty($id)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);
        $product->setBarcode($data['barcode']);
        $product->setName($data['name']);
        $product->setHeight($data['height']);
        $product->setWidth($data['width']);
        $entityManager->flush();
    }

    /**
     * @Route("/product", name="Delete_product", methods={"DELETE"})
     */
    public function deleteProduct(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $id = $data['id'];
        if (empty($id)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);
        $entityManager->remove($product);
        $entityManager->flush();
    }

    /**
     * @Route("/product", name="Get_product", methods={"GET"})
     */
    public function viewProduct(Request $request)
    {
//        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return new \Symfony\Component\HttpFoundation\Response(var_dump($this->getUser()));
        //TODO Implement GET functionality

    }
}