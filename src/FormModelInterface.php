<?php

declare(strict_types=1);

namespace Yiisoft\Form;

use Yiisoft\Validator\DataSetInterface;

/**
 * FormModelInterface model represents an HTML form: its data, validation and presentation.
 */
interface FormModelInterface extends DataSetInterface, FormMetadataInterface
{
    /**
     * Add error for the specified attribute.
     *
     * @param string $attribute attribute name.
     * @param string $error attribute error message.
     */
    public function addError(string $attribute, string $error): void;

    /**
     * Returns the errors for single attribute.
     *
     * @param string $attribute attribute name. Use null to retrieve errors for all attributes.
     *
     * @return array
     */
    public function getError(string $attribute): array;

    /**
     * Returns the value for the specified attribute.
     *
     * @param string $attribute
     *
     * @return iterable|object|scalar|Stringable|null
     */
    public function getAttributeValue(string $attribute);

    /**
     * Returns the errors for all attributes.
     *
     * @return array errors for all attributes or the specified attribute. null is returned if no error.
     *
     * Note that when returning errors for all attributes, the result is a two-dimensional array, like the following:
     *
     * ```php
     * [
     *     'username' => [
     *         'Username is required.',
     *         'Username must contain only word characters.',
     *     ],
     *     'email' => [
     *         'Email address is invalid.',
     *     ]
     * ]
     * ```
     *
     * {@see getFirstErrors()}
     * {@see getFirstError()}
     */
    public function getErrors(): array;

    /**
     * Returns the errors for all attributes as a one-dimensional array.
     *
     * @param bool $showAllErrors boolean, if set to true every error message for each attribute will be shown otherwise
     * only the first error message for each attribute will be shown.
     *
     * @return array errors for all attributes as a one-dimensional array. Empty array is returned if no error.
     *
     * {@see getErrors()}
     * {@see getFirstErrors(){}
     */
    public function getErrorSummary(bool $showAllErrors): array;

    /**
     * Returns the first error of the specified attribute.
     *
     * @param string $attribute attribute name.
     *
     * @return string the error message. Empty string is returned if there is no error.
     *
     * {@see getErrors()}
     * {@see getFirstErrors()}
     */
    public function getFirstError(string $attribute): string;

    /**
     * Returns the first error of every attribute in the model.
     *
     * @return array the first errors. The array keys are the attribute names, and the array values are the
     * corresponding error messages. An empty array will be returned if there is no error.
     *
     * {@see getErrors()}
     * {@see getFirstError()}
     */
    public function getFirstErrors(): array;

    /**
     * Returns the form name that this model class should use.
     *
     * The form name is mainly used by {@see \Yiisoft\Form\Helper\HtmlForm} to determine how to name the input
     * fields for the attributes in a model.
     * If the form name is "A" and an attribute name is "b", then the corresponding input name would be "A[b]".
     * If the form name is an empty string, then the input name would be "b".
     *
     * The purpose of the above naming schema is that for forms which contain multiple different models, the attributes
     * of each model are grouped in sub-arrays of the POST-data and it is easier to differentiate between them.
     *
     * By default, this method returns the model class name (without the namespace part) as the form name. You may
     * override it when the model is used in different forms.
     *
     * @return string the form name of this model class.
     *
     * {@see load()}
     */
    public function getFormName(): string;

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by {@see \Yiisoft\Validator\Validator} to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     *
     * Each rule is an array with the following structure:
     *
     * ```php
     * public function rules(): array
     * {
     *     return [
     *         'login' => $this->loginRules()
     *     ];
     * }
     *
     * private function loginRules(): array
     * {
     *   return [
     *       new \Yiisoft\Validator\Rule\Required(),
     *       (new \Yiisoft\Validator\Rule\HasLength())
     *       ->min(4)
     *       ->max(40)
     *       ->tooShortMessage('Is too short.')
     *       ->tooLongMessage('Is too long.'),
     *       new \Yiisoft\Validator\Rule\Email()
     *   ];
     * }
     * ```
     *
     * @return array Validation rules.
     */
    public function getRules(): array;

    /**
     * Returns a value indicating whether there is any validation error.
     *
     * @param string|null $attribute attribute name. Use null to check all attributes.
     *
     * @return bool whether there is any error.
     */
    public function hasErrors(?string $attribute = null): bool;

    /**
     * This method allows to know if the validation was executed or not in the model.
     *
     * @return bool If the model was validated.
     */
    public function isValidated(): bool;

    /**
     * Populates the model with input data.
     *
     * which, with `load()` can be written as:
     *
     * ```php
     * $body = $request->getParsedBody();
     * $method = $request->getMethod();
     *
     * if ($method === Method::POST && $loginForm->load($body)) {
     *     // handle success
     * }
     * ```
     *
     * `load()` gets the `'FormName'` from the {@see getFormName()} method (which you may override), unless the
     * `$formName` parameter is given. If the form name is empty string, `load()` populates the model with the whole of
     * `$data` instead of `$data['FormName']`.
     *
     * @param array $data the data array to load, typically server request attributes.
     * @param string|null $formName scope from which to get data
     *
     * @return bool whether `load()` found the expected form in `$data`.
     */
    public function load(array $data, ?string $formName = null): bool;

    /**
     * Set specified attribute
     *
     * @param string $name of the attribute to set
     * @param iterable|object|scalar|Stringable|null $value
     */
    public function setAttribute(string $name, $value): void;
}
