<?php

declare(strict_types=1);

namespace Yiisoft\Form\Widget;

use Yiisoft\Form\Widget\Attribute\CommonAttribute;
use Yiisoft\Html\Tag\Input;

/**
 * The input element with a type attribute whose value is "password" represents a one-line plain-text edit control for
 * entering a password.
 *
 * @link https://www.w3.org/TR/2012/WD-html-markup-20120329/input.password.html#input.password
 */
final class Password extends Widget
{
    use CommonAttribute;

    /**
     * Specifies the form element the tag input element belongs to. The value of this attribute must be the id
     * attribute of a {@see Form} element in the same document.
     *
     * @param string $value
     *
     * @return static
     */
    public function form(string $value): self
    {
        $new = clone $this;
        $new->attributes['form'] = $value;
        return $new;
    }

    /**
     * The maxlength attribute defines the maximum number of characters (as UTF-16 code units) the user can enter into
     * an tag input.
     *
     * If no maxlength is specified, or an invalid value is specified, the tag input has no maximum length.
     *
     * @param int $value
     *
     * @return static
     *
     * @link https://www.w3.org/TR/2012/WD-html-markup-20120329/input.password.html#input.password.attrs.maxlength
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
     * @link https://www.w3.org/TR/2012/WD-html-markup-20120329/input.password.html#input.password.attrs.pattern
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
     * @link https://www.w3.org/TR/2012/WD-html-markup-20120329/input.password.html#input.password.attrs.placeholder
     */
    public function placeholder(string $value): self
    {
        $new = clone $this;
        $new->attributes['placeholder'] = $value;
        return $new;
    }

    /**
     * A Boolean attribute which, if present, means this field cannot be edited by the user.
     * Its value can, however, still be changed by JavaScript code directly setting the HTMLInputElement.value
     * property.
     *
     * @param bool $value
     *
     * @return static
     *
     * @link https://www.w3.org/TR/2012/WD-html-markup-20120329/input.password.html#input.password.attrs.readonly
     */
    public function readOnly(bool $value = true): self
    {
        $new = clone $this;
        $new->attributes['readonly'] = $value;
        return $new;
    }

    /**
     * Generates a password input tag for the given form attribute.
     *
     * @return string the generated input tag,
     */
    protected function run(): string
    {
        $new = clone $this;

        $value = $new->getValue();

        /** @link https://www.w3.org/TR/2012/WD-html-markup-20120329/input.password.html#input.password.attrs.value */
        $value = $new->getValue();

        if (!is_string($value)) {
            throw new InvalidArgumentException('Password widget requires a string value.');
        }

        return Input::password($new->getId(), $new->getValue())->attributes($new->attributes)->render();
    }
}