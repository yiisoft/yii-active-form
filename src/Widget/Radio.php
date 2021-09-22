<?php

declare(strict_types=1);

namespace Yiisoft\Form\Widget;

use InvalidArgumentException;
use Yiisoft\Form\Helper\HtmlForm;
use Yiisoft\Form\Widget\Attribute\CommonAttributes;
use Yiisoft\Form\Widget\Attribute\ModelAttributes;
use Yiisoft\Html\Tag\Input\Radio as RadioTag;
use Yiisoft\Widget\Widget;

/**
 * The input element with a type attribute whose value is "radio" represents a selection of one item from a list of
 * items (a radio button).
 *
 * @link https://www.w3.org/TR/2012/WD-html-markup-20120329/input.radio.html
 */
final class Radio extends Widget
{
    use CommonAttributes;
    use ModelAttributes;

    private bool $enclosedByLabel = true;
    private string $label = '';
    private array $labelAttributes = [];

    /**
     * If the widget should be enclosed by label.
     *
     * @param bool $value If the widget should be en closed by label.
     *
     * @return static
     */
    public function enclosedByLabel(bool $value = true): self
    {
        $new = clone $this;
        $new->enclosedByLabel = $value;
        return $new;
    }

    /**
     * Label displayed next to the radio.
     *
     * It will NOT be HTML-encoded, therefore you can pass in HTML code such as an image tag. If this is is coming from
     * end users, you should {@see encode()} it to prevent XSS attacks.
     *
     * When this option is specified, the radio will be enclosed by a label tag.
     *
     * @param string $value
     *
     * @return static
     */
    public function label(string $value): self
    {
        $new = clone $this;
        $new->label = $value;
        return $new;
    }

    /**
     * HTML attributes for the label tag.
     *
     * Do not set this option unless you set the "label" option.
     *
     * @param array $value
     *
     * @return static
     */
    public function labelAttributes(array $value = []): self
    {
        $new = clone $this;
        $new->labelAttributes = $value;
        return $new;
    }

    /**
     * Generates a radio button tag together with a label for the given form attribute.
     *
     * @return string the generated radio button tag.
     */
    protected function run(): string
    {
        $new = clone $this;
        $radio = RadioTag::tag();

        /** @var bool|float|int|string|null  */
        $forceUncheckedValue = $new->attributes['forceUncheckedValue'] ?? null;

        unset($new->attributes['forceUncheckedValue']);

        $value = HtmlForm::getAttributeValue($new->getFormModel(), $new->attribute);

        if (is_iterable($value) || is_object($value)) {
            throw new InvalidArgumentException('Radio widget requires a bool|float|int|string|null value.');
        }

        if ($new->enclosedByLabel === true) {
            $label = $new->label !== '' ? $new->label : HtmlForm::getAttributeLabel($new->getFormModel(), $new->attribute);
            $radio = $radio->label($label, $new->labelAttributes);
        }

        return $radio
            ->attributes($new->attributes)
            ->checked((bool) $value)
            ->id($new->getId())
            ->name(HtmlForm::getInputName($new->getFormModel(), $new->attribute))
            ->uncheckValue($forceUncheckedValue)
            ->value((int) $value)
            ->render();
    }
}
