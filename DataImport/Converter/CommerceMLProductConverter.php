<?php

namespace Extension\Shop\DataImport\Converter;

use BinCMS\DataImport\Converter\ConverterInterface;
use BinCMS\FileUploader\FileUploaderFactory;
use Extension\Shop\Document\ProductPropertyValue;
use Extension\Shop\Repository\CategoryRepository;
use Extension\Shop\Repository\Interfaces\ProductPropertyRepositoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CommerceMLProductConverter implements ConverterInterface
{
    /**
     * @var \Extension\Shop\Repository\CategoryRepository
     */
    private $categoryRepository;
    /**
     * @var \Extension\Shop\Repository\Interfaces\ProductPropertyRepositoryInterface
     */
    private $productPropertyRepository;
    /**
     * @var
     */
    private $importPath;
    /**
     * @var \BinCMS\FileUploader\FileUploaderFactory
     */
    private $fileUploaderFactory;

    function __construct(CategoryRepository $categoryRepository, ProductPropertyRepositoryInterface $productPropertyRepository, FileUploaderFactory $fileUploaderFactory, $importPath)
    {
        $this->categoryRepository = $categoryRepository;
        $this->productPropertyRepository = $productPropertyRepository;
        $this->importPath = $importPath;
        $this->fileUploaderFactory = $fileUploaderFactory;
    }

    /**
     * @param \Extension\Shop\CommerceML\Product $data
     * @return array
     */
    public function convert($data)
    {
        $category = null;

        if (($group = $data->getGroup()) !== null) {
            $category = $this->categoryRepository->findByExternalId($group->getId());
        }

        $propertyValues = [];

        foreach ($data->getProperties() as $property) {
            if (null !== $property->getId() && null !== $property->getValue()) {
                if (($productProperty = $this->productPropertyRepository->findByExternalId($property->getId())) !== null) {
                    $value = trim($property->getValue());
                    if(!empty($value)) {
                        $propertyValue = new ProductPropertyValue();
                        $propertyValue->setProperty($productProperty);
                        $propertyValue->setValue($value);
                        $propertyValues[] = $propertyValue;
                    }
                }
            }
        }

        $image = null;

        if (($imageName = $data->getImage()) !== null) {
            $imagePath = $this->importPath . '/' . $imageName;
            if (file_exists($imagePath)) {
                $uploadFile = new UploadedFile($imagePath, pathinfo($imageName, \PATHINFO_BASENAME));
                $image = $this->fileUploaderFactory->upload($uploadFile);
            }
        }

        return [
            'externalId' => $data->getId(),
            'title' => $data->getFullTitle(),
            'sku' => $data->getArticle(),
            'description' => $data->getDescription(),
            'category' => $category,
            'image' => $image,
            'unit' => $data->getUnit(),
            'properties' => $propertyValues
        ];
    }
}