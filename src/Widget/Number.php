<?php

declare(strict_types=1);

namespace Yiisoft\Form\Widget;

use InvalidArgumentException;
use Yiisoft\Form\Helper\HtmlForm;
use Yiisoft\Form\Widget\Attribute\CommonAttributes;
use Yiisoft\Form\Widget\Attribute\ModelAttributes;
use Yiisoft\Html\Tag\Input;
use Yiisoft\Widget\Widget;

/**
 * The input element with a type attribute whose value is "number" represents a precise control for setting the
 * element’s value to a string representing a number.
 *
 * @link https://www.w3.org/TR/2012/WD-html-markup-20120329/input.number.html
 */
final class Number extends Widget
{
    use CommonAttributes;
    use ModelAttributes;

    /**
     * The expected upper bound for the element’s value.
     *
     * @param int $max
     *
     * @return static
     *
     * @link https://www.w3.org/TR/2012/WD-html-markup-20120329/input.number.html#input.number.attrs.max
     */
    public function max(int $value): self
    {
        $new = clone $this;
        $new->attributes['max'] = $value;
        return $new;
    }

    /**
     * The expected lower bound for the element’s value.
     *
     * @param int $min
     *
     * @return static
     *
     * @link https://www.w3.org/TR/2012/WD-html-markup-20120329/input.number.html#input.number.attrs.min
     */
    public function min(int $value): self
    {
        $new = clone $this;
        $new->attributes['min'] = $value;
        return $new;
    }

    /**
     * It allows defining placeholder.
     *
     * @param string $value
     *
     * @return static
     *
     * @link https://www.w3.org/TR/2012/WD-html-markup-20120329/input.email.html#input.email.attrs.placeholder
     */
    public function placeholder(string $value): self
    {
        $new = clone $this;
        $new->attributes['placeholder'] = $value;
        return $new;
    }

    /**
     * @return string the generated input tag.
     */
    protected function run(): string
    {
        $new = clone $this;

        /** @link https://www.w3.org/TR/2012/WD-html-markup-20120329/input.number.html#input.number.attrs.value */
        $value = HtmlForm::getAttributeValue($new->getFormModel(), $new->attribute);

        if (!is_numeric($value)) {
            throw new InvalidArgumentException('Number widget must be a numeric value.');
        }

        return Input::tag()
            ->type('number')
            ->attributes($new->attributes)
            ->id($new->getId())
            ->name(HtmlForm::getInputName($new->getFormModel(), $new->attribute))
            ->value($value)
            ->render();
    }
}
