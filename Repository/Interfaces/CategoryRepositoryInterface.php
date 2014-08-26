<?php

namespace Extension\Shop\Repository\Interfaces;

use BinCMS\Repository\ObjectRepository;
use Extension\Shop\Document\Category;
use Gedmo\Tree\RepositoryInterface;

interface CategoryRepositoryInterface extends ObjectRepository, RepositoryInterface
{
    /**
     * @param $title
     * @param \Extension\Shop\Document\Category $parent
     * @return \Extension\Shop\Document\Category
     */
    public function create($title, Category $parent = null);

    /**
     * @param \Extension\Shop\Document\Category $category
     * @param array $parameters
     * @return \Extension\Shop\Document\Category
     */
    public function update(Category $category, array $parameters = []);

    /**
     * @param \Extension\Shop\Document\Category $category
     * @param \Extension\Shop\Document\Category $destinationCategory
     * @return void
     */
    public function move(Category $category, Category $destinationCategory = null);

    /**
     * @param $categories \Extension\Shop\Document\Category[]
     * @return void
     */
    public function remove($categories);

    /**
     * @param $title
     * @return \Extension\Shop\Document\Category[]|null
     */
    public function findByTitle($title);

    /**
     * @param $externalId
     * @return \Extension\Shop\Document\Category|null
     */
    public function findByExternalId($externalId);
} 