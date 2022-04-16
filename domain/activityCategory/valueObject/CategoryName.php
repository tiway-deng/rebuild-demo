<?php
namespace app\domain\activityCategory\valueObject;


use app\helper\Assert;

final class CategoryName
{
    private $name;

    public static function fromString(string $param): self
    {
        Assert::stringNotEmpty($param, '%s 不能为空');
        Assert::maxLength($param, 12,'%s 不能超过12个字符');

        $CategoryName = new self();

        $CategoryName->name = $param;

        return $CategoryName;
    }

    public function toString(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    private function __construct()
    {
    }

}