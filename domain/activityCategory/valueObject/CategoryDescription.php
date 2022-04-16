<?php
namespace app\domain\activityCategory\valueObject;


use app\helper\Assert;

final class CategoryDescription
{
    private $description;

    public static function fromString(string $param): self
    {
        Assert::stringNotEmpty($param, '%s 不能为空');
        Assert::maxLength($param, 225,'%s 不能超过225个字符');

        $categoryDescription = new self();

        $categoryDescription->description = $param;

        return $categoryDescription;
    }

    public function toString(): string
    {
        return $this->description;
    }

    public function __toString(): string
    {
        return $this->description;
    }

    private function __construct()
    {
    }

}