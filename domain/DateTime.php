<?php

namespace app\domain;


use Webmozart\Assert\Assert;

class DateTime
{
    public const FORMAT = 'Y-m-d\TH:i:s.uP';

    /**
     * @throws DateTimeException
     */
    public static function now(): self
    {
        return self::create();
    }

    public static function fromString(string $dateTime): self
    {
        return self::create($dateTime);
    }

    private static function create(string $dateTime = ''): self
    {
        $self = new self();

        try {
            $self->dateTime = new \DateTimeImmutable($dateTime);
        } catch (\Exception $e) {
            Assert::reportInvalidArgument('参数错误');
        }

        return $self;
    }

    public function toString(): string
    {
        return $this->dateTime->format(self::FORMAT);
    }

    public function toNative(): \DateTimeImmutable
    {
        return $this->dateTime;
    }

    /** @var \DateTimeImmutable */
    private $dateTime;
}
