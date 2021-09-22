<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\TestSupport\Widget;

use Yiisoft\Form\Widget\Attribute\ModelAttributes;
use Yiisoft\Html\Html;
use Yiisoft\Widget\Widget;

final class ModelAttributesWidget extends Widget
{
    use ModelAttributes;

    protected function run(): string
    {
        $new = clone $this;

        return '<test' . Html::renderTagAttributes($new->attributes) . '>';
    }
}
