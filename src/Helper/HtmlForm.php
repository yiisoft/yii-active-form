<?php

declare(strict_types=1);

namespace Yiisoft\Form\Helper;

use InvalidArgumentException;
use UnexpectedValueException;
use Yiisoft\Form\FormModelInterface;

/**
 * Form-related HTML tag generation
 */
final class HtmlForm
{
    /**
     * Return the attribute hint for the model.
     *
     * @param FormModelInterface $form the form object.
     * @param string $attribute the attribute name or expression.
     *
     * @return string
     */
    public static function getAttributeHint(FormModelInterface $formModel, string $attribute): string
    {
        return $formModel->getAttributeHint(self::getAttributeName($formModel, $attribute));
    }

    /**
     * Returns the label of the specified attribute name.
     *
     * @param FormModelInterface $form the form object.
     * @param string $attribute the attribute name or expression.
     *
     * @throws InvalidArgumentException if the attribute name contains non-word characters.
     *
     * @return string
     */
    public static function getAttributeLabel(FormModelInterface $formModel, string $attribute): string
    {
        return $formModel->getAttributeLabel(self::getAttributeName($formModel, $attribute));
    }

    /**
     * Returns the real attribute name from the given attribute expression.
     * If `$attribute` has neither prefix nor suffix, it will be returned back without change.
     *
     * @param FormModelInterface $form the form object.
     * @param string $attribute the attribute name or expression
     *
     * @throws InvalidArgumentException if the attribute name contains non-word characters.
     *
     * @return string the attribute name without prefix and suffix.
     *
     * @see static::parseAttribute()
     */
    public static function getAttributeName(FormModelInterface $formModel, string $attribute): string
    {
        $attribute = self::parseAttribute($attribute)['name'];

        if (!$formModel->hasAttribute($attribute)) {
            throw new invalidArgumentException("Attribute '$attribute' does not exist.");
        }

        return $attribute;
    }

    /**
     * Returns the value of the specified attribute name or expression.
     *
     * For an attribute expression like `[0]dates[0]`, this method will return the value of `$form->dates[0]`.
     * See {@see getAttributeName()} for more details about attribute expression.
     *
     * If an attribute value an array of such instances, the primary value(s) of the AR instance(s) will be returned
     * instead.
     *
     * @param FormModelInterface $form the form object.
     * @param string $attribute the attribute name or expression.
     *
     * @throws InvalidArgumentException if the attribute name contains non-word characters.
     *
     * @return iterable<mixed, mixed>|object|scalar|null the corresponding attribute value.
     */
    public static function getAttributeValue(FormModelInterface $formModel, string $attribute)
    {
        return $formModel->getAttributeValue(self::getAttributeName($formModel, $attribute));
    }

    /**
     * Return the attribute first error message.
     *
     * @param FormModelInterface $form the form object.
     * @param string $attribute the attribute name or expression.
     *
     * @return string
     */
    public static function getFirstError(FormModelInterface $formModel, string $attribute): string
    {
        return $formModel->getFirstError(self::getAttributeName($formModel, $attribute));
    }

    /**
     * Generates an appropriate input ID for the specified attribute name or expression.
     *
     * This method converts the result {@see getInputName()} into a valid input ID.
     *
     * For example, if {@see getInputName()} returns `Post[content]`, this method will return `post-content`.
     *
     * @param FormModelInterface $formModel the form object
     * @param string $attribute the attribute name or expression. See {@see getAttributeName()} for explanation of
     * attribute expression.
     * @param string $charset default `UTF-8`.
     *
     * @throws InvalidArgumentException if the attribute name contains non-word characters.
     * @throws UnexpectedValueException if charset is unknown
     *
     * @return string the generated input ID.
     */
    public static function getInputId(
        FormModelInterface $formModel,
        string $attribute,
        string $charset = 'UTF-8'
    ): string {
        $name = mb_strtolower(self::getInputName($formModel, $attribute), $charset);
        return str_replace(['[]', '][', '[', ']', ' ', '.'], ['', '-', '-', '', '-', '-'], $name);
    }

    /**
     * Generates an appropriate input name for the specified attribute name or expression.
     *
     * This method generates a name that can be used as the input name to collect user input for the specified
     * attribute. The name is generated according to the of the form and the given attribute name. For example, if the
     * form name of the `Post` form is `Post`, then the input name generated for the `content` attribute would be
     * `Post[content]`.
     *
     * See {@see getAttributeName()} for explanation of attribute expression.
     *
     * @param FormModelInterface $form the form object.
     * @param string $attribute the attribute name or expression.
     *
     * @throws InvalidArgumentException if the attribute name contains non-word characters
     * or empty form name for tabular inputs
     *
     * @return string the generated input name.
     */
    public static function getInputName(FormModelInterface $formModel, string $attribute): string
    {
        $data = self::parseAttribute($attribute);
        $formName = $formModel->getFormName();

        if ($formName === '' && $data['prefix'] === '') {
            return $attribute;
        }

        if ($formName !== '') {
            return "{$formName}{$data['prefix']}[{$data['name']}]{$data['suffix']}";
        }

        throw new InvalidArgumentException('formName() cannot be empty for tabular inputs.');
    }

    /**
     * This method parses an attribute expression and returns an associative array containing
     * real attribute name, prefix and suffix.
     * For example: `['name' => 'content', 'prefix' => '', 'suffix' => '[0]']`
     *
     * An attribute expression is an attribute name prefixed and/or suffixed with array indexes. It is mainly used in
     * tabular data input and/or input of array type. Below are some examples:
     *
     * - `[0]content` is used in tabular data input to represent the "content" attribute for the first model in tabular
     *    input;
     * - `dates[0]` represents the first array element of the "dates" attribute;
     * - `[0]dates[0]` represents the first array element of the "dates" attribute for the first model in tabular
     *    input.
     *
     * @param string $attribute the attribute name or expression
     *
     * @throws InvalidArgumentException if the attribute name contains non-word characters.
     *
     * @return string[] the attribute name, prefix and suffix.
     */
    private static function parseAttribute(string $attribute): array
    {
        if (!preg_match('/(^|.*\])([\w\.\+]+)(\[.*|$)/u', $attribute, $matches)) {
            throw new InvalidArgumentException('Attribute name must contain word characters only.');
        }
        return [
            'name' => $matches[2],
            'prefix' => $matches[1],
            'suffix' => $matches[3],
        ];
    }
}
