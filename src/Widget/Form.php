<?php

declare(strict_types=1);

namespace Yiisoft\Form\Widget;

use Yiisoft\Html\Html;
use Yiisoft\Http\Method;
use Yiisoft\Widget\Widget;

use function explode;
use function implode;
use function strpos;
use function substr;
use function urldecode;

/**
 *  Generates a form start tag.
 *
 *  @link https://www.w3.org/TR/html52/sec-forms.html
 */
final class Form extends Widget
{
    private string $action = '';
    private array $attributes = [];
    private string $id = '';
    private string $method = Method::POST;

    /**
     * @return string the generated form start tag.
     *
     * {@see end())}
     */
    public function begin(): string
    {
        parent::begin();

        $new = clone $this;

        $hiddenInputs = [];

        /** @var string */
        $new->attributes['id'] = $new->attributes['id'] ?? $new->id;

        if ($new->attributes['id'] === '') {
            unset($new->attributes['id']);
        }

        /** @var string */
        $csrfToken = $new->attributes['_csrf'] ?? '';

        if ($csrfToken === '') {
            unset($new->attributes['_csrf']);
        }

        if ($csrfToken !== '' && $new->method === Method::POST) {
            $hiddenInputs[] = Html::hiddenInput('_csrf', $csrfToken);
        }

        if ($new->method === Method::GET && ($pos = strpos($new->action, '?')) !== false) {
            /**
             * Query parameters in the action are ignored for GET method we use hidden fields to add them back.
             */
            foreach (explode('&', substr($new->action, $pos + 1)) as $pair) {
                if (($pos1 = strpos($pair, '=')) !== false) {
                    $hiddenInputs[] = Html::hiddenInput(
                        urldecode(substr($pair, 0, $pos1)),
                        urldecode(substr($pair, $pos1 + 1))
                    );
                } else {
                    $hiddenInputs[] = Html::hiddenInput(urldecode($pair), '');
                }
            }

            $new->action = substr($new->action, 0, $pos);
        }

        if ($new->action !== '') {
            $new->attributes['action'] = $new->action;
        }

        $new->attributes['method'] = $new->method;

        $form = Html::openTag('form', $new->attributes);

        if (!empty($hiddenInputs)) {
            $form .= PHP_EOL . implode(PHP_EOL, $hiddenInputs);
        }

        return $form;
    }

    /**
     * The accept-charset content attribute gives the character encodings that are to be used for the submission.
     * If specified, the value must be an ordered set of unique space-separated tokens that are ASCII case-insensitive,
     * and each token must be an ASCII case-insensitive match for one of the labels of an ASCII-compatible encoding.
     *
     * @param string $value the accept-charset attribute value.
     *
     * @return static
     *
     * @link https://www.w3.org/TR/html52/sec-forms.html#element-attrdef-form-accept-charset
     */
    public function acceptCharset(string $value): self
    {
        $new = clone $this;
        $new->attributes['accept-charset'] = $value;
        return $new;
    }

    /**
     * The action and formaction content attributes, if specified, must have a value that is a valid non-empty URL
     * potentially surrounded by spaces.
     *
     * @param string $action the action attribute value.
     *
     * @return static
     *
     * @link https://www.w3.org/TR/html52/sec-forms.html#element-attrdef-form-action
     */
    public function action(string $value): self
    {
        $new = clone $this;
        $new->action = $value;
        return $new;
    }

    /**
     * The HTML attributes for the form. The following special options are recognized.
     *
     * @param array $value the HTML attributes for the form.
     *
     * @return static
     */
    public function attributes(array $value): self
    {
        $new = clone $this;
        $new->attributes = $value;
        return $new;
    }

    /**
     * Specifies whether the element represents an input control for which a UA is meant to store the value entered by
     * the user (so that the UA can prefill the form later).
     *
     * @param bool $value
     *
     * @return static
     *
     * @link https://www.w3.org/TR/html52/sec-forms.html#element-attrdef-autocompleteelements-autocomplete
     */
    public function autocomplete(bool $value = true): self
    {
        $new = clone $this;
        $new->attributes['autocomplete'] = $value ? 'on' : 'off';
        return $new;
    }

    /**
     * The csrf-token content attribute is a space-separated list of tokens that are known to be safe to use for.
     *
     * @param string $value the csrf-token attribute value.
     *
     * @return static
     */
    public function csrf(string $value): self
    {
        $new = clone $this;
        $new->attributes['_csrf'] = $value;
        return $new;
    }

    /**
     * The formenctype content attribute specifies the content type of the form submission.
     *
     * @param string $value the formenctype attribute value.
     *
     * @return static
     *
     * @link https://www.w3.org/TR/html52/sec-forms.html#element-attrdef-form-enctype
     */
    public function enctype(string $value): self
    {
        $new = clone $this;
        $new->id = $value;
        return $new;
    }

    /**
     * The id content attribute is a unique identifier for the element.
     *
     * @param string $value the id attribute value.
     *
     * @return static
     */
    public function id(string $value): self
    {
        $new = clone $this;
        $new->id = $value;
        return $new;
    }

    /**
     * The method content attribute specifies how the form-data should be submitted.
     *
     * @param string $value the method attribute value.
     *
     * @return static
     *
     * @link https://www.w3.org/TR/html52/sec-forms.html#element-attrdef-form-method
     */
    public function method(string $value): self
    {
        $new = clone $this;
        $new->method = strtoupper($value);
        return $new;
    }

    /**
     * The novalidate and formnovalidate content attributes are boolean attributes. If present, they indicate that the
     * form is not to be validated during submission.
     *
     * @return static
     *
     * @link https://www.w3.org/TR/html52/sec-forms.html#element-attrdef-form-novalidate
     */
    public function noHtmlValidatation(): self
    {
        $new = clone $this;
        $new->attributes['novalidate'] = true;
        return $new;
    }

    /**
     * The target and formtarget content attributes, if specified, must have values that are valid browsing context
     * names or keywords.
     *
     * @param string $value the target attribute value, for default its `_blank`.
     *
     * @return static
     *
     * @link https://www.w3.org/TR/html52/sec-forms.html#element-attrdef-form-target
     */
    public function target(string $value): self
    {
        $new = clone $this;
        $new->attributes['target'] = $value;
        return $new;
    }

    /**
     * Generates a form end tag.
     *
     * @return string the generated tag.
     *
     * {@see beginForm()}
     */
    protected function run(): string
    {
        return Html::closeTag('form');
    }
}
