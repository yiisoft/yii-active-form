<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\TestSupport\Widget;

use Yiisoft\Form\Widget\Attribute\WithoutModelAttribute;
use Yiisoft\Html\Html;
use Yiisoft\Widget\Widget;

final class WithoutModelAttributeWidget extends Widget
{
    use WithoutModelAttribute;

    protected function run(): string
    {
        $new = clone $this;

        $new->attributes['id'] = $new->getId();
        $new->attributes['name'] = $new->getName();
        $new->attributes['value'] = $new->value;

        return '<test' . Html::renderTagAttributes($new->attributes) . '>';
    }
}
