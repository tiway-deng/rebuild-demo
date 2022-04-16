<?php

namespace app\domain\activityCategory;


use app\domain\activityCategory\specification\CategorySpecificationInterface;
use app\domain\activityCategory\valueObject\CategoryDescription;
use app\domain\activityCategory\valueObject\CategoryName;
use app\domain\activityCategory\valueObject\CategorySort;
use app\helper\Assert;

class ActivityCategory
{
    private $id;
    private $name;
    private $description;
    private $sort;
    private $created_at;
    private $updated_at;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param mixed $sort
     */
    public function setSort(string $sort): void
    {
        $this->sort = $sort;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        if (is_null($this->created_at)) {
            $this->setCreatedAt(time());
        }
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        if (is_null($this->updated_at)) {
            $this->setUpdatedAt(time());
        }
        return $this->updated_at;
    }


    /**
     * @param mixed $updated_at
     */
    public function setUpdatedAt($updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    public static function create(
        CategoryName $name,
        CategoryDescription $categoryDescription,
        CategorySort $sort,
        CategorySpecificationInterface $specification
    ): self
    {
        if($specification->isUniqueName($name)){
            Assert::reportInvalidArgument('分类名称重复');
        }

        $category = new self();
        $category->setName($name);
        $category->setDescription($categoryDescription);
        $category->setSort($sort);

        return $category;
    }

}