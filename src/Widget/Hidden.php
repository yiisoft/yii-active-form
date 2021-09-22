<?php

declare(strict_types=1);

namespace Yiisoft\Form\Widget;

use InvalidArgumentException;
use Yiisoft\Form\Helper\HtmlForm;
use Yiisoft\Form\Widget\Attribute\ModelAttributes;
use Yiisoft\Html\Tag\Input;
use Yiisoft\Widget\Widget;

/**
 * The input element with a type attribute whose value is "hidden" represents a value that is not intended to be
 * examined or manipulated by the user.
 *
 * @link https://www.w3.org/TR/2012/WD-html-markup-20120329/input.hidden.html#input.hidden
 */
final class Hidden extends Widget
{
    use ModelAttributes;

    /**
     * Generates a hidden input tag for the given form attribute.
     *
     * @return string the generated input tag.
     */
    protected function run(): string
    {
        $new = clone $this;

        /** @link https://www.w3.org/TR/2012/WD-html-markup-20120329/input.hidden.html#input.hidden.attrs.value */
        $value = HtmlForm::getAttributeValue($new->getFormModel(), $new->attribute);

        if (!is_string($value)) {
            throw new InvalidArgumentException('Hidden widget requires a string value.');
        }

        return Input::hidden($new->getId(), $value)->attributes($new->attributes)->render();
    }
}
