<?php
namespace app\domain\activityCategory\specification;

use app\domain\activityCategory\valueObject\CategoryId;
use app\domain\activityCategory\valueObject\CategoryName;

interface CategorySpecificationInterface
{
    public function isUniqueName(CategoryName $name): bool;
    public function isCategoryExits(CategoryId $id): bool;

}
