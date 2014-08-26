<?php

namespace Extension\Shop\Repository;

use BinCMS\RepositoryTrait\RepositoryExtendTrait;
use BinCMS\RepositoryTrait\RepositoryMaterializedPathFilteredTrait;
use Extension\Shop\Document\Category;
use Extension\Shop\Repository\Interfaces\CategoryRepositoryInterface;
use Gedmo\Tree\Document\MongoDB\Repository\MaterializedPathRepository;

class CategoryRepository extends MaterializedPathRepository implements CategoryRepositoryInterface
{
    use RepositoryExtendTrait;
    use RepositoryMaterializedPathFilteredTrait;

    /**
     * @param $title
     * @param Category $parent
     * @return Category
     */
    public function create($title, Category $parent = null)
    {
        $category = new Category();
        $category->setTitle($title);
        $category->setParent($parent);
        $this->saveEntity($category);
        return $category;
    }

    /**
     * @param Category $category
     * @param array $parameters
     * @return Category
     */
    public function update(Category $category, array $parameters = [])
    {
        $category->fillParameters($parameters);
        $this->saveEntity($category);
        return $category;
    }

    /**
     * @param Category $category
     * @param Category $destinationCategory
     */
    public function move(Category $category, Category $destinationCategory = null)
    {
        $category->setParent($destinationCategory);
        $this->saveEntity($category);
    }

    /**
     * @param \Extension\Shop\Document\Category[] $categories
     */
    public function remove($categories)
    {
        if(!is_array($categories)) {
            $categories = [$categories];
        }

        foreach($categories as $category) {
            $this->removeEntity($category);
        }
        $this->flushSafe();
    }

    /**
     * @param $externalId
     * @return \Extension\Shop\Document\Category|null
     */
    public function findByExternalId($externalId)
    {
        return $this->findOneBy(['externalId' => $externalId]);
    }

    /**
     * @param $title
     * @return \Extension\Shop\Document\Category[]|null
     */
    public function findByTitle($title)
    {
        if(empty($title)) {
            return [];
        }

        $criteriaRegex = '/^'.$title.'/i';

        return $this
            ->createQueryBuilder()
            ->field('title')
            ->equals(new \MongoRegex($criteriaRegex))
            ->getQuery()
            ->execute()
            ->toArray(false)
            ;
    }
}