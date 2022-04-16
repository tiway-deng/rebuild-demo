<?php

namespace app\modules\v1\services;


use app\domain\activityCategory\ActivityCategory;
use app\domain\activityCategory\specification\CategorySpecificationInterface;
use app\domain\activityCategory\valueObject\CategoryDescription;
use app\domain\activityCategory\valueObject\CategoryName;
use app\domain\activityCategory\valueObject\CategorySort;
use app\repository\activity\ActivityCategoryRepository;

class ActivityCategoryService
{
    private $categorySpecification;
    public function __construct(CategorySpecificationInterface $categorySpecification)
    {
        $this->categorySpecification = $categorySpecification;
    }

    public function create(CategoryName $categoryName, CategoryDescription $categoryDescription, CategorySort $categorySort)
    {
        //是否重命名
        $this->categorySpecification->isUniqueName($categoryName);

        $activityCategoryRepository = new ActivityCategoryRepository();
        $category = ActivityCategory::create($categoryName,$categoryDescription,$categorySort,$this->categorySpecification);
        $categoryDO = $activityCategoryRepository->create($category);
        return $categoryDO->toArray();

    }
}