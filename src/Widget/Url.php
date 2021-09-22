<?php

declare(strict_types=1);

namespace Yiisoft\Form\Widget;

use InvalidArgumentException;
use Yiisoft\Form\Helper\HtmlForm;
use Yiisoft\Form\Widget\Attribute\ModelAttributes;
use Yiisoft\Html\Tag\Input;
use Yiisoft\Widget\Widget;

/**
 * The input element with a type attribute whose value is "url" represents a control for editing an absolute URL given
 * in the element’s value.
 *
 * @link https://www.w3.org/TR/2012/WD-html-markup-20120329/input.url.html
 */
final class Url extends Widget
{
    use ModelAttributes;

    /**
     * The maxlength attribute defines the maximum number of characters (as UTF-16 code units) the user can enter into
     * an tag input.
     *
     * If no maxlength is specified, or an invalid value is specified, the tag input has no maximum length.
     *
     * @param int $value Positive integer.
     *
     * @return static
     *
     * @link https://www.w3.org/TR/2012/WD-html-markup-20120329/input.url.html#input.url.attrs.maxlength
     */
    public function maxlength(int $value): self
    {
        $new = clone $this;
        $new->attributes['maxlength'] = $value;
        return $new;
    }

    /**
     * The minimum number of characters (as UTF-16 code units) the user can enter into the text input.
     *
     * This must be an non-negative integer value smaller than or equal to the value specified by maxlength.
     * If no minlength is specified, or an invalid value is specified, the text input has no minimum length.
     *
     * @param int $value
     *
     * @return static
     *
     * @link https://html.spec.whatwg.org/multipage/input.html#attr-input-minlength
     */
    public function minlength(int $value): self
    {
        $new = clone $this;
        $new->attributes['minlength'] = $value;
        return $new;
    }

    /**
     * The pattern attribute, when specified, is a regular expression that the input's value must match in order for
     * the value to pass constraint validation. It must be a valid JavaScript regular expression, as used by the
     * RegExp type.
     *
     * @param string $value
     *
     * @return static
     *
     * @link https://www.w3.org/TR/2012/WD-html-markup-20120329/input.url.html#input.url.attrs.pattern
     */
    public function pattern(string $value): self
    {
        $new = clone $this;
        $new->attributes['pattern'] = $value;
        return $new;
    }

    /**
     * It allows defining placeholder.
     *
     * @param string $value
     *
     * @return static
     *
     * @link https://www.w3.org/TR/2012/WD-html-markup-20120329/input.url.html#input.url.attrs.placeholder
     */
    public function placeholder(string $value): self
    {
        $new = clone $this;
        $new->attributes['placeholder'] = $value;
        return $new;
    }

    /**
     * The height of the <select> with multiple is true.
     *
     * Default value is 4.
     *
     * @param int $value
     *
     * @return static
     *
     * @link https://www.w3.org/TR/2012/WD-html-markup-20120329/input.url.html#input.url.attrs.size
     */
    public function size(int $value = 4): self
    {
        $new = clone $this;
        $new->attributes['size'] = $value;
        return $new;
    }

    /**
     * @return string the generated input tag.
     */
    protected function run(): string
    {
        $new = clone $this;

        /**
         * @link https://www.w3.org/TR/2012/WD-html-markup-20120329/input.url.html#input.url.attrs.value
         */
        $value = HtmlForm::getAttributeValue($new->getFormModel(), $new->attribute);

        if (!is_string($value)) {
            throw new InvalidArgumentException('Url widget must be a string.');
        }

        return Input::tag()
            ->type('url')
            ->attributes($new->attributes)
            ->id($new->getId())
            ->name(HtmlForm::getInputName($new->getFormModel(), $new->attribute))
            ->value($value)
            ->render();
    }
}
