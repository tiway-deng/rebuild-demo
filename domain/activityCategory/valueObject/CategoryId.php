<?php
namespace app\domain\activityCategory\valueObject;

use app\domain\AbstractId;
use Webmozart\Assert\Assert;

final class CategoryId extends AbstractId
{

    public static function fromString(int $id)
    {
        Assert::notEmpty($id, 'id 不能为空');
        Assert::integer($id, 'id 必须为整型');


        $idObject = new self();
        $idObject->id = $id;

        return $idObject;
    }

}