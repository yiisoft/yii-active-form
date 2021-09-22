<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\TestSupport\Validator;

use Yiisoft\Validator\Formatter;
use Yiisoft\Validator\ResultSet;
use Yiisoft\Validator\Validator;
use Yiisoft\Validator\ValidatorInterface;

final class ValidatorMock implements ValidatorInterface
{
    private Validator $validator;

    public function __construct()
    {
        $this->validator = new Validator(new Formatter());
    }

    public function validate($dataSet, iterable $rules = []): ResultSet
    {
        return $this->validator->validate($dataSet, $rules);
    }
}
