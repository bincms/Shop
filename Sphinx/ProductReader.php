<?php


namespace Extension\Shop\Sphinx;

use Extension\Shop\SphinxXml\ReaderInterface;
use Extension\Shop\SphinxXml\SphinxDocument;
use Extension\Shop\Repository\Interfaces\ProductRepositoryInterface;

class ProductReader implements ReaderInterface
{
    /**
     * @var \Extension\Shop\Repository\Interfaces\ProductRepositoryInterface
     */
    private $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAll()
    {
        $documents = [];
        foreach($this->productRepository->findAll() as $product) {
            $documents[] = new SphinxDocument($product->getId(), $product->getTitle(), $product->getTitle(), 'shop.product');
        }
        return $documents;
    }
}