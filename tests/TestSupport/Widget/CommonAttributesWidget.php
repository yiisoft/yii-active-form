<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\TestSupport\Widget;

use Yiisoft\Form\Widget\Attribute\CommonAttributes;
use Yiisoft\Html\Html;
use Yiisoft\Widget\Widget;

final class CommonAttributesWidget extends Widget
{
    use CommonAttributes;

    protected function run(): string
    {
        $new = clone $this;

        return '<test' . Html::renderTagAttributes($new->attributes) . '>';
    }
}
