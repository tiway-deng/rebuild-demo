<?php


namespace app\repository\activity;


use app\domain\activityCategory\specification\CategorySpecificationInterface;
use app\domain\activityCategory\valueObject\CategoryId;
use app\domain\activityCategory\valueObject\CategoryName;
use app\models\activity\ActivityCategory;

class ActivityCategoryRepository extends ActivityCategory implements CategorySpecificationInterface
{
    /**
     * @param CategoryName $name
     * @return bool
     */
    public function isUniqueName(CategoryName $name) : bool
    {
        return self::find()->andWhere(['name'=>$name])->exists();
    }

    /**
     * @param CategoryId $id
     * @return bool
     */
    public function isCategoryExits(CategoryId $id):bool
    {
        return self::find()->andWhere(['id'=>$id])->exists();
    }

    /**
     * @param \app\domain\activity\ActivityCategory $activityCategory
     * @return ActivityCategoryRepository
     */
    public function create(\app\domain\activityCategory\ActivityCategory $activityCategory)
    {
        $category = new self();

        $category->name = (string)$activityCategory->getName();
        $category->description = (string)$activityCategory->getDescription();
        $category->sort = (int)$activityCategory->getSort();
        $category->created_at = (int)$activityCategory->getCreatedAt();
        $category->updated_at = (int)$activityCategory->getUpdatedAt();


        $category->save();
        return $category;
    }

}