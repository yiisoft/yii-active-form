<?php

declare(strict_types=1);

namespace Yiisoft\Form\Widget;

use Yiisoft\Html\Html;
use Yiisoft\Widget\Widget;

final class CheckBoxList extends Widget
{
    use Options\Common;
    use Options\Input;

    private array $items = [];

    /**
     * Generates a list of checkboxes.
     *
     * A checkbox list allows multiple selection, like {@see ListBox}.
     *
     * @return string the generated checkbox list.
     */
    public function run(): string
    {
        $new = clone $this;

        if (!empty($new->addId())) {
            $new->options['id'] = $new->addId();
        }

        return Html::checkBoxList($new->addName(), $new->addValue(), $new->items, $new->options);
    }

    public function items(array $value): self
    {
        $new = clone $this;
        $new->items = $value;
        return $new;
    }
}
