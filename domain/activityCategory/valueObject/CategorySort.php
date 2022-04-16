<?php
namespace app\domain\activityCategory\valueObject;


use app\helper\Assert;

final class CategorySort
{
    private $sort;

    public static function fromString(string $param): self
    {
        Assert::stringNotEmpty($param, '%s 不能为空');
        Assert::integerish($param, '%s 必须为整型');

        $categorySort = new self();

        $categorySort->sort = $param;

        return $categorySort;
    }

    public function toString(): string
    {
        return $this->sort;
    }

    public function __toString(): string
    {
        return $this->sort;
    }

    private function __construct()
    {
    }

}