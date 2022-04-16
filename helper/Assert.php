<?php


namespace app\helper;


use Webmozart\Assert\InvalidArgumentException;

class Assert extends \Webmozart\Assert\Assert
{
    public static function reportInvalidArgument($message)
    {
        throw new InvalidArgumentException($message);
    }
}