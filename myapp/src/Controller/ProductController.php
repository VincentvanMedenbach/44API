<?php


namespace App\Controller;


use App\Entity\Product;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


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
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $entityManager = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent(), true);

        if (empty($data['id'])) {        //GIVE ENTIRE LIST
            $products = $entityManager->getRepository(Product::class)->findAll();
            $jsonContent = $serializer->serialize($products, 'json');
            return new Response($jsonContent);
        } else { // GIVE 1 ITEM
            $product = $entityManager->getRepository(Product::class)->find($data['id']);
            $jsonContent = $serializer->serialize($product, 'json');
            return new Response($jsonContent);
        }
    }
}