<?php

declare(strict_types=1);

use Yiisoft\Form\ThemeContainer;

/**
 * @var array $params
 */

return [
    static function () use ($params) {
        ThemeContainer::initialize(
            $params['yiisoft/form']['themes'],
            $params['yiisoft/form']['defaultTheme'],
            $params['yiisoft/form']['validationRulesEnricher'],
        );
    },
];
