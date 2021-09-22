<?php

declare(strict_types=1);

namespace Yiisoft\Form\Widget;

use Yiisoft\Arrays\ArrayHelper;
use Yiisoft\Form\Helper\HtmlForm;
use Yiisoft\Form\Widget\Attribute\ModelAttributes;
use Yiisoft\Html\Tag\Label as LabelTag;
use Yiisoft\Widget\Widget;

/**
 * Generates a label tag for the given form attribute.
 *
 * @link https://www.w3.org/TR/2012/WD-html-markup-20120329/label.html
 */
final class Label extends Widget
{
    use ModelAttributes;

    private string $label = '';

    /**
     * The id of a labelable form-related element in the same document as the tag label element.
     *
     * The first element in the document with an id matching the value of the for attribute is the labeled control for
     * this label element, if it is a labelable element.
     *
     * @param string $value
     *
     * @return static
     *
     * @link https://www.w3.org/TR/2012/WD-html-markup-20120329/label.html#label.attrs.for
     */
    public function for(string $value): self
    {
        $new = clone $this;
        $new->attributes['for'] = $value;
        return $new;
    }

    /**
     * This specifies the label to be displayed.
     *
     * @param string $value The label to be displayed.
     *
     * @return static
     *
     * Note that this will NOT be encoded.
     * - If this is not set, {@see \Yiisoft\Forms\FormModel::getAttributeLabel() will be called to get the label for
     * display (after encoding).
     */
    public function label(string $value): self
    {
        $new = clone $this;
        $new->label = $value;
        return $new;
    }

    /**
     * @return string the generated label tag.
     */
    protected function run(): string
    {
        $new = clone $this;

        $label = $new->label !== '' ? $new->label : HtmlForm::getAttributeLabel($new->getFormModel(), $new->attribute);

        /** @var bool|string */
        $attributeLabel = ArrayHelper::remove($new->attributes, 'label', '');

        /** @var bool */
        $encode = $new->attributes['encode'] ?? false;

        if (is_string($attributeLabel) && $attributeLabel !== '') {
            $label = $attributeLabel;
        }

        /** @var string */
        $for = $new->attributes['for'] ?? $new->getId();

        return $attributeLabel !== false
            ? LabelTag::tag()->attributes($new->attributes)->content($label)->encode($encode)->forId($for)->render()
            : '';
    }
}
