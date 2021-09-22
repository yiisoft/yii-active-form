<?php

declare(strict_types=1);

namespace Yiisoft\Form\Widget;

use InvalidArgumentException;
use Yiisoft\Form\Helper\HtmlForm;
use Yiisoft\Form\Widget\Attribute\CommonAttributes;
use Yiisoft\Form\Widget\Attribute\DateAttributes;
use Yiisoft\Form\Widget\Attribute\ModelAttributes;
use Yiisoft\Html\Tag\Input;
use Yiisoft\Widget\Widget;

/**
 * The input element with a type attribute whose value is "datetime" represents a control for setting the element’s
 * value to a string representing a global date and time (with timezone information).
 *
 * @link https://www.w3.org/TR/2012/WD-html-markup-20120329/input.datetime.html#input.datetime
 */
final class DateTime extends Widget
{
    use CommonAttributes;
    use DateAttributes;
    use ModelAttributes;

    /**
     * Generates a datepicker tag together with a label for the given form attribute.
     *
     * @return string the generated checkbox tag.
     */
    protected function run(): string
    {
        /** @link https://www.w3.org/TR/2012/WD-html-markup-20120329/input.date.html#input.date.attrs.value */
        $value = HtmlForm::getAttributeValue($this->getFormModel(), $this->attribute);

        if (!is_string($value)) {
            throw new InvalidArgumentException('DateTime widget requires a string value.');
        }

        return Input::tag()
            ->type('datetime')
            ->attributes($this->attributes)
            ->id($this->getId())
            ->name(HtmlForm::getInputName($this->getFormModel(), $this->attribute))
            ->value($value)
            ->render();
    }
}
