<?php

declare(strict_types=1);

namespace Yiisoft\Form\Widget;

use Closure;
use ReflectionException;
use Yiisoft\Arrays\ArrayHelper;
use Yiisoft\Form\Widget\Attribute\FieldAttributes;
use Yiisoft\Html\Html;
use Yiisoft\Html\Tag\Div;
use Yiisoft\Html\Widget\CheckboxList\CheckboxItem;
use Yiisoft\Html\Widget\RadioList\RadioItem;
use Yiisoft\Widget\Widget;

use function strtr;

/**
 * Renders the field widget along with label and hint tag (if any) according to template.
 *
 * @psalm-suppress MissingConstructor
 */
final class Field extends Widget
{
    use FieldAttributes;

    public const TYPE_CHECKBOX = 'checkbox';
    public const TYPE_HIDDEN = 'hidden';
    public const TYPE_EMAIL = 'email';
    public const TYPE_NUMBER = 'number';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_RADIO = 'radio';
    public const TYPE_SELECT = 'select';
    public const TYPE_TEL = 'tel';
    public const TYPE_TEXT = 'text';
    public const TYPE_TEXTAREA = 'textarea';
    public const TYPE_URL = 'url';
    public const HAS_LENGTH_TYPES = [
        self::TYPE_EMAIL,
        self::TYPE_PASSWORD,
        self::TYPE_TEL,
        self::TYPE_TEXT,
        self::TYPE_TEXTAREA,
        self::TYPE_URL,
    ];
    public const MATCH_REGULAR_EXPRESSION_TYPES = [
        self::TYPE_EMAIL,
        self::TYPE_PASSWORD,
        self::TYPE_TEL,
        self::TYPE_TEXT,
        self::TYPE_URL,
    ];
    public const NO_PLACEHOLDER_TYPES = [
        self::TYPE_CHECKBOX,
        self::TYPE_HIDDEN,
        self::TYPE_RADIO,
        self::TYPE_SELECT,
    ];

    /**
     * Renders a checkbox.
     *
     * This method will generate the `checked` tag attribute according to the model attribute value.
     *
     * @param array $attributes the tag attributes in terms of name-value pairs. The following options are specially
     * handled:
     * - `forceUncheckedValue`: string, the value associated with the uncheck state of the {@see Checkbox}.
     * This attribute will render a hidden input so that if the {@see Checkbox} is not checked and is submitted,
     * the value of this attribute will still be submitted to the server via the hidden input. If you do not want any
     * hidden input, you should explicitly no set.
     * - `label`: string, a label displayed next to the checkbox. It will NOT be HTML-encoded. Therefore you can pass
     * in HTML code such as an image tag. If this is coming from end users, you should
     * {@see \Yiisoft\Html\Html::encode()|encode} it to prevent XSS attacks.
     * When this option is specified, the checkbox will be enclosed by a label tag.
     * - `labelAttributes`: array, the HTML attributes for the label tag. This is only used when the `label` attributes
     * is specified.
     *
     * The rest of the attribute will be rendered as the attributes of the resulting tag. The values will be
     * HTML-encoded using {@see \Yiisoft\Html\Html::encode()}. If you do not want any attribute no set.
     * @param bool $enclosedByLabel whether to enclose the checkbox with the label.
     *
     * @return static the field object itself.
     */
    public function checkbox(array $attributes = [], bool $enclosedByLabel = true): self
    {
        $new = clone $this;
        $checkbox = Checkbox::widget();
        $attributes['type'] = self::TYPE_CHECKBOX;
        $attributes = $new->setInputAttributes($attributes);

        if ($enclosedByLabel === true) {
            $new->parts['{label}'] = '';
        }

        if (isset($attributes['label']) && is_string($attributes['label'])) {
            $checkbox = $checkbox->label($attributes['label']);
        }

        if (isset($attributes['labelAttributes']) && is_array($attributes['labelAttributes'])) {
            $checkbox = $checkbox->labelAttributes($attributes['labelAttributes']);
        }

        unset($attributes['label'], $attributes['labelAttributes']);

        $new->parts['{input}'] = $checkbox
            ->config($new->getFormModel(), $new->attribute, $attributes)
            ->enclosedByLabel($enclosedByLabel)
            ->render();

        return $new;
    }

    /**
     * Renders a list of checkboxes.
     *
     * A checkbox list allows multiple selection, As a result, the corresponding submitted value is an array.
     * The selection of the checkbox list is taken from the value of the model attribute.
     *
     * @param array $attributes the tag attributes in terms of name-value pairs. The following options are specially
     * handled:
     * - `forceUncheckedValue`: string, the value associated with the uncheck state of the {@see CheckboxList}.
     * This attribute will render a hidden input so that if the {@see CheckboxList} is not checked and is submitted, the
     * value of this attribute will still be submitted to the server via the hidden input. If you do not want any hidden
     * input, you should explicitly no set.
     * - `itemsAttributes`: array, the HTML attributes for the items checkboxlist. This is only used when the `items`
     * attribute is specified.
     * - `separator`: string, the HTML code that separates items.
     *
     * The rest of the attribute will be rendered as the attributes of the resulting tag. The values will be
     * HTML-encoded using {@see \Yiisoft\Html\Html::encode()}. If you do not want any attribute no set.
     * @param array $items the data item used to generate the checkbox list. The array values are the labels,
     * while the array keys are the corresponding checkbox values.
     *
     * @return static the field object itself.
     *
     * @psalm-param array<array-key, string> $items
     */
    public function checkboxList(array $attributes = [], array $items = []): self
    {
        $new = clone $this;
        $checkboxList = CheckboxList::widget();
        $attributes = $new->setInputAttributes($attributes);
        /** @var bool|string|null */
        $containerTag = ArrayHelper::remove($attributes, 'containerTag', '');

        if (isset($attributes['containerAttributes']) && is_array($attributes['containerAttributes'])) {
            $checkboxList = $checkboxList->containerAttributes($attributes['containerAttributes']);
        }

        if ($containerTag === false) {
            $checkboxList = $checkboxList->containerTag();
        } elseif (is_string($containerTag) && $containerTag !== '') {
            $checkboxList = $checkboxList->containerTag($containerTag);
        }

        if (isset($attributes['disabled'])) {
            $checkboxList = $checkboxList->disabled();
        }

        if (isset($attributes['individualItemsAttributes']) && is_array($attributes['individualItemsAttributes'])) {
            /** @var array<array-key, array<array-key, mixed>> */
            $individualItemsAttributes = $attributes['individualItemsAttributes'];
            $checkboxList = $checkboxList->individualItemsAttributes($individualItemsAttributes);
        }

        if (isset($attributes['itemsAttributes']) && is_array($attributes['itemsAttributes'])) {
            $checkboxList = $checkboxList->itemsAttributes($attributes['itemsAttributes']);
        }

        if (isset($attributes['itemsFormatter']) && ($attributes['itemsFormatter'] instanceof Closure)) {
            /** @var Closure(CheckboxItem):string|null */
            $formatter = $attributes['itemsFormatter'];
            $checkboxList = $checkboxList->itemsFormatter($formatter);
        }

        if (isset($attributes['readonly'])) {
            $checkboxList = $checkboxList->readOnly();
        }

        if (isset($attributes['separator']) && is_string($attributes['separator'])) {
            $checkboxList = $checkboxList->separator($attributes['separator']);
        }

        unset(
            $attributes['disabled'],
            $attributes['individualItemsAttributes'],
            $attributes['itemsAttributes'],
            $attributes['itemsFormatter'],
            $attributes['readonly'],
            $attributes['separator'],
        );

        $new->parts['{input}'] = $checkboxList
            ->config($new->getFormModel(), $new->attribute, $attributes)
            ->items($items)
            ->render();

        return $new;
    }

    /**
     * Renders a date widget
     *
     * @param array $attributes the tag attributes in terms of name-value pairs.
     *
     * @return static the field object itself.
     */
    public function date(array $attributes = []): self
    {
        $new = clone $this;
        $attributes = $new->setInputAttributes($attributes);

        $new->parts['{input}'] = Date::widget()->config($new->getFormModel(), $new->attribute, $attributes)->render();

        return $new;
    }

    /**
     * Renders a datetime widget
     *
     * @param array $attributes the tag attributes in terms of name-value pairs.
     *
     * @return static the field object itself.
     */
    public function datetime(array $attributes = []): self
    {
        $new = clone $this;
        $attributes = $new->setInputAttributes($attributes);

        $new->parts['{input}'] = DateTime::widget()->config($new->getFormModel(), $new->attribute, $attributes)->render();

        return $new;
    }

    /**
     * Renders a datetimelocal widget
     *
     * @param array $attributes the tag attributes in terms of name-value pairs.
     *
     * @return static the field object itself.
     */
    public function datetimelocal(array $attributes = []): self
    {
        $new = clone $this;
        $attributes = $new->setInputAttributes($attributes);

        $new->parts['{input}'] = DateTimeLocal::widget()
            ->config($new->getFormModel(), $new->attribute, $attributes)
            ->render();

        return $new;
    }

    /**
     * Renders a email widget.
     *
     * This method will generate the `name` and `value` tag attributes automatically for the model attribute unless
     * they are explicitly specified in `$attributes`.
     *
     * @param array $attributes the tag attributes in terms of name-value pairs. These will be rendered as the
     * attributes of the resulting tag. The values will be HTML-encoded using {@see \Yiisoft\Html\Html::encode()}.
     *
     * @return static the field object itself.
     */
    public function email(array $attributes = []): self
    {
        $new = clone $this;
        $attributes['type'] = self::TYPE_EMAIL;
        $attributes = $new->setInputAttributes($attributes);

        $new->parts['{input}'] = Email::widget()->config($new->getFormModel(), $new->attribute, $attributes)->render();

        return $new;
    }

    /**
     * Generates a tag that contains the first validation error of {@see attribute}.
     *
     * Note that even if there is no validation error, this method will still return an empty error tag.
     *
     * @param array $attributes the tag attributes in terms of name-value pairs. These will be rendered as the
     * attributes of the hint tag. The values will be HTML-encoded using {@see Html::encode()}. The following options
     * are specially handled:
     * - `encode`: boolean, whether to encode the error. If `false`, the error will be left as is.
     * - `errorMessage`: string, the error message to be displayed. If this is not set, a default error message will be
     * displayed.
     * - `messageCallback`: callback, a PHP callback that returns the error message to be displayed.
     * - `tag`: string, the tag name of the container. if not set, `div` will be used. if `null`, no container tag will
     * be rendered.
     *
     * @return static the field object itself.
     */
    public function error(array $attributes = []): self
    {
        $new = clone $this;
        /** @var string */
        $errorMessage = $attributes['errorMessage'] ?? '';

        if ($new->errorClass !== '') {
            Html::addCssClass($attributes, $new->errorClass);
        }

        $new->parts['{error}'] = Error::widget()
            ->config($new->getFormModel(), $new->attribute, $attributes)
            ->message($errorMessage)
            ->render();

        return $new;
    }

    /**
     * Renders a file widget.
     *
     * This method will generate the `name` tag attribute automatically for the model attribute unless they are
     * explicitly specified in `$attributes`.
     *
     * @param array $attributes the tag options in terms of name-value pairs. These will be rendered as the attributes
     * of the resulting tag. The values will be HTML-encoded using {@see \Yiisoft\Html\Html::encode()}.
     *
     * @return static the field object itself.
     */
    public function file(array $attributes = []): self
    {
        $new = clone $this;
        $attributes = $new->setInputAttributes($attributes);

        $new->parts['{input}'] = File::widget()->config($new->getFormModel(), $new->attribute, $attributes)->render();

        return $new;
    }

    /**
     * Renders a hidden widget.
     *
     * Note that this method is provided for completeness. In most cases because you do not need to validate a hidden
     * widget, you should not need to use this method.
     *
     * This method will generate the `name` and `value` tag attributes automatically for the model attribute unless
     * they are explicitly specified in `$attributes`.
     *
     * @param array $attributes the tag options in terms of name-value pairs. These will be rendered as the attributes
     * of the resulting tag. The values will be HTML-encoded using {@see \Yiisoft\Html\Html::encode()}.
     *
     * @return static the field object itself.
     */
    public function hidden(array $attributes = []): self
    {
        $new = clone $this;
        $attributes['type'] = self::TYPE_HIDDEN;
        $attributes = $new->setInputAttributes($attributes);

        $new->parts['{label}'] = '';
        $new->parts['{hint}'] = '';
        $new->parts['{error}'] = '';
        $new->parts['{input}'] = Hidden::widget()->config($new->getFormModel(), $new->attribute, $attributes)->render();

        return $new;
    }

    /**
     * Renders the hint tag.
     *
     * @param array $attributes the tag attributes in terms of name-value pairs. These will be rendered as the
     * attributes of the hint tag. The values will be HTML-encoded using {@see Html::encode()}. The following options
     * are specially handled:
     * - `id`: string, the hint tag id. If not set, an id will be generated from the hint content.
     * - `hint`: string, the content of the hint tag. Note that it will NOT be HTML-encoded. If no set, the hint will be
     * generated via {@see \Yiisoft\Form\FormModel::getAttributeHint()}. if `null` it will not be rendered.
     * - `tag`: string, the tag name of the hint tag. if not set, `div` will be used. if `null` no tag will be used.
     *
     * @return static
     */
    public function hint(array $attributes = []): self
    {
        $new = clone $this;

        if ($new->ariaDescribedBy === true) {
            $attributes['id'] = $new->getId();
        }

        if ($new->hintClass !== '') {
            Html::addCssClass($attributes, $new->hintClass);
        }

        $new->parts['{hint}'] = Hint::widget()->config($new->getFormModel(), $new->attribute, $attributes)->render();

        return $new;
    }

    /**
     * Renders a image widget.
     *
     * This method will generate the `name` and `value` tag attributes automatically for the model attribute unless
     * they are explicitly specified in `$attributes`.
     *
     * @param array $attributes the tag attributes in terms of name-value pairs. These will be rendered as the
     * attributes of the resulting tag. The values will be HTML-encoded using {@see \Yiisoft\Html\Html::encode()}.
     *
     * @return static the field object itself.
     */
    public function image(array $attributes = []): self
    {
        $new = clone $this;
        $image = Image::widget();
        $new->parts['{error}'] = '';
        $new->parts['{hint}'] = '';
        $new->parts['{label}'] = '';

        $new->parts['{input}'] = $image->attributes($attributes)->render();

        return $new;
    }

    /**
     * Generates a label tag for {@see attribute}.
     *
     * @param array $attributes the tag attributes in terms of name-value pairs. These will be rendered as the
     * attributes of the label tag. The values will be HTML-encoded using {@see \Yiisoft\Html\Html::encode()}.
     * The following options are specially handled:
     * - `for`: string, the ID of the input element the label is associated with.
     * - `label`: string, the content of the label tag. Note that it will NOT be HTML-encoded. If no set, the hint will
     * be generated via {@see \Yiisoft\Form\FormModel::getAttributeLabel()}. if `null` it will not be rendered.
     * - `labelAttribute`: array, the HTML attributes for the label tag. This is only used when the `label` attribute is
     * specified.
     *
     * @return static the field object itself.
     */
    public function label(array $attributes = []): self
    {
        $new = clone $this;

        if ($new->labelClass !== '') {
            Html::addCssClass($attributes, $new->labelClass);
        }

        $new->parts['{label}'] = Label::widget()->config($new->getFormModel(), $new->attribute, $attributes)->render();

        return $new;
    }

    /**
     * Renders a number widget.
     *
     * This method will generate the `name` and `value` tag attributes automatically for the model attribute unless
     * they are explicitly specified in `$attributes`.
     *
     * @param array $attributes the tag attributes in terms of name-value pairs. These will be rendered as the
     * attributes of the resulting tag. The values will be HTML-encoded using {@see \Yiisoft\Html\Html::encode()}.
     *
     * @return static the field object itself.
     */
    public function number(array $attributes = []): self
    {
        $new = clone $this;
        $attributes['type'] = self::TYPE_NUMBER;
        $attributes = $new->setInputAttributes($attributes);

        $new->parts['{input}'] = Number::widget()->config($new->getFormModel(), $new->attribute, $attributes)->render();

        return $new;
    }

    /**
     * Renders a password widget.
     *
     * This method will generate the `name` and `value` tag attributes automatically for the model attribute unless
     * they are explicitly specified in `$attributes`.
     *
     * @param array $attributes the tag attributes in terms of name-value pairs. These will be rendered as the
     * attributes of the resulting tag. The values will be HTML-encoded using {@see \Yiisoft\Html\Html::encode()}.
     *
     * @return static the field object itself.
     */
    public function password(array $attributes = []): self
    {
        $new = clone $this;
        $attributes['type'] = self::TYPE_PASSWORD;
        $attributes = $new->setInputAttributes($attributes);

        $new->parts['{input}'] = Password::widget()->config($new->getFormModel(), $new->attribute, $attributes)->render();

        return $new;
    }

    /**
     * Renders a radio button widget.
     *
     * This method will generate the `checked` tag attribute according to the model attribute value.
     *
     * @param array $attributes the tag attributes in terms of name-value pairs. The following options are specially
     * handled:
     * - `forceUncheckedValue`: string, the value associated with the uncheck state of the {@see Radio}.
     * This attribute will render a hidden input so that if the {@see Radio} is not checked and is submitted,
     * the value of this attribute will still be submitted to the server via the hidden input. If you do not want any
     * hidden input, you should explicitly no set.
     * - `label`: string, a label displayed next to the checkbox. It will NOT be HTML-encoded. Therefore you can pass
     * in HTML code such as an image tag. If this is coming from end users, you should
     * {@see \Yiisoft\Html\Html::encode()|encode} it to prevent XSS attacks.
     * When this option is specified, the radio will be enclosed by a label tag.
     * - `labelAttributes`: array, the HTML attributes for the label tag. This is only used when the `label` attributes
     * is specified.
     *
     * The rest of the attribute will be rendered as the attributes of the resulting tag. The values will be
     * HTML-encoded using {@see \Yiisoft\Html\Html::encode()}. If you do not want any attribute no set.
     * @param bool $enclosedByLabel whether to enclose the checkbox with the label.
     *
     * @return self the field object itself.
     */
    public function radio(array $attributes = [], bool $enclosedByLabel = true): self
    {
        $new = clone $this;
        $radio = Radio::widget();
        $attributes['type'] = self::TYPE_RADIO;
        $attributes = $new->setInputAttributes($attributes);

        if ($enclosedByLabel === true) {
            $new->parts['{label}'] = '';
        }

        if (isset($attributes['label']) && is_string($attributes['label'])) {
            $radio = $radio->label($attributes['label']);
        }

        if (isset($attributes['labelAttributes']) && is_array($attributes['labelAttributes'])) {
            $radio = $radio->labelAttributes($attributes['labelAttributes']);
        }

        unset($attributes['label'], $attributes['labelAttributes']);

        $new->parts['{input}'] = $radio
            ->config($new->getFormModel(), $new->attribute, $attributes)
            ->enclosedByLabel($enclosedByLabel)
            ->render();

        return $new;
    }

    /**
     * Renders a list of radios.
     *
     * A radio list allows multiple selection, As a result, the corresponding submitted value is an array.
     * The selection of the radio list is taken from the value of the model attribute.
     *
     * @param array $attributes the tag attributes in terms of name-value pairs. The following options are specially
     * handled:
     * - `forceUncheckedValue`: string, the value associated with the uncheck state of the {@see RadioList}.
     * This attribute will render a hidden input so that if the {@see RadioList} is not checked and is submitted, the
     * value of this attribute will still be submitted to the server via the hidden input. If you do not want any hidden
     * input, you should explicitly no set.
     * - `itemsAttributes`: array, the HTML attributes for the items checkboxlist. This is only used when the `items`
     * attribute is specified.
     * - `separator`: string, the HTML code that separates items.
     *
     * The rest of the attribute will be rendered as the attributes of the resulting tag. The values will be
     * HTML-encoded using {@see \Yiisoft\Html\Html::encode()}. If you do not want any attribute no set.
     * @param array $items the data item used to generate the radio list. The array values are the labels,
     * while the array keys are the corresponding radio values.
     *
     * @return static the field object itself.
     *
     * @psalm-param array<array-key, string> $items
     */
    public function radioList(array $attributes = [], array $items = []): self
    {
        $new = clone $this;
        $radioList = RadioList::widget();
        $attributes = $new->setInputAttributes($attributes);
        /** @var bool|string|null */
        $containerTag = ArrayHelper::remove($attributes, 'containerTag', '');

        if (isset($attributes['containerAttributes']) && is_array($attributes['containerAttributes'])) {
            $radioList = $radioList->containerAttributes($attributes['containerAttributes']);
        }

        if ($containerTag === false) {
            $radioList = $radioList->containerTag();
        } elseif (is_string($containerTag) && $containerTag !== '') {
            $radioList = $radioList->containerTag($containerTag);
        }

        if (isset($attributes['disabled'])) {
            $radioList = $radioList->disabled();
        }

        if (isset($attributes['individualItemsAttributes']) && is_array($attributes['individualItemsAttributes'])) {
            /** @var array<array-key, array<array-key, mixed>> */
            $individualItemsAttributes = $attributes['individualItemsAttributes'];
            $radioList = $radioList->individualItemsAttributes($individualItemsAttributes);
        }

        if (isset($attributes['itemsAttributes']) && is_array($attributes['itemsAttributes'])) {
            $radioList = $radioList->itemsAttributes($attributes['itemsAttributes']);
        }

        if (isset($attributes['itemsFormatter']) && ($attributes['itemsFormatter'] instanceof Closure)) {
            /** @var Closure(RadioItem):string|null */
            $formatter = $attributes['itemsFormatter'];
            $radioList = $radioList->itemsFormatter($formatter);
        }

        if (isset($attributes['readonly'])) {
            $radioList = $radioList->readOnly();
        }

        if (isset($attributes['separator']) && is_string($attributes['separator'])) {
            $radioList = $radioList->separator($attributes['separator']);
        }

        unset(
            $attributes['disabled'],
            $attributes['individualItemsAttributes'],
            $attributes['itemsAttributes'],
            $attributes['itemsFormatter'],
            $attributes['readonly'],
            $attributes['separator'],
        );

        $new->parts['{input}'] = $radioList
            ->config($new->getFormModel(), $new->attribute, $attributes)
            ->items($items)
            ->render();

        return $new;
    }

    /**
     * Renders a number widget.
     *
     * This method will generate the `name` and `value` tag attributes automatically for the model attribute unless
     * they are explicitly specified in `$attributes`.
     *
     * @param array $attributes the tag attributes in terms of name-value pairs. These will be rendered as the
     * attributes of the resulting tag. The values will be HTML-encoded using {@see \Yiisoft\Html\Html::encode()}.
     *
     * @return static the field object itself.
     */
    public function range(array $attributes = []): self
    {
        $new = clone $this;
        $attributes['type'] = self::TYPE_NUMBER;
        $attributes = $new->setInputAttributes($attributes);

        $new->parts['{input}'] = Range::widget()->config($new->getFormModel(), $new->attribute, $attributes)->render();

        return $new;
    }

    /**
     * Renders a reset button widget.
     *
     * This method will generate the `name` and `value` tag attributes automatically for the model attribute unless
     * they are explicitly specified in `$attributes`.
     *
     * @param array $attributes the tag attributes in terms of name-value pairs. These will be rendered as the
     * attributes of the resulting tag. The values will be HTML-encoded using {@see \Yiisoft\Html\Html::encode()}.
     *
     * @return static the field object itself.
     */
    public function resetButton(array $attributes = []): self
    {
        $new = clone $this;
        $reset = ResetButton::widget();
        $new->parts['{error}'] = '';
        $new->parts['{hint}'] = '';
        $new->parts['{label}'] = '';

        if (isset($attributes['autoIdPrefix']) && is_string($attributes['autoIdPrefix'])) {
            $reset = $reset->autoIdPrefix($attributes['autoIdPrefix']);
        }

        if (isset($attributes['id']) && is_string($attributes['id'])) {
            $reset = $reset->id($attributes['id']);
        }

        if (isset($attributes['name']) && is_string($attributes['name'])) {
            $reset = $reset->name($attributes['name']);
        }

        if (isset($attributes['value']) && is_string($attributes['value'])) {
            $reset = $reset->value($attributes['value']);
        }

        unset($attributes['autoIdPrefix'], $attributes['id'], $attributes['name'], $attributes['value']);

        $new->parts['{input}'] = $reset->attributes($attributes)->render();

        return $new;
    }

    /**
     * Renders a select widget..
     *
     * The selection of the drop-down list is taken from the value of the model attribute.
     *
     * @param array $attributes the tag attributes in terms of name-value pairs. The following options are specially
     * handled:
     * - `forceUncheckedValue`: string, the value associated with the uncheck state of the {@see RadioList}.
     * This attribute will render a hidden input so that if the {@see RadioList} is not checked and is submitted, the
     * value of this attribute will still be submitted to the server via the hidden input. If you do not want any hidden
     * input, you should explicitly no set.
     * - `itemsAttributes`: array, the HTML attributes for the items checkboxlist. This is only used when the `items`
     * attribute is specified.
     * - `separator`: string, the HTML code that separates items.
     *
     * The rest of the attribute will be rendered as the attributes of the resulting tag. The values will be
     * HTML-encoded using {@see \Yiisoft\Html\Html::encode()}. If you do not want any attribute no set.
     * @param array $items the data item used to generate the radio list. The array values are the labels,
     * while the array keys are the corresponding radio values.
     * @param array $groups The attributes for the optgroup tags.
     *
     * The structure of this is similar to that of 'attributes', except that the array keys represent the optgroup
     * labels specified in $items.
     *
     * ```php
     * [
     *     'groups' => [
     *         '1' => ['label' => 'Chile'],
     *         '2' => ['label' => 'Russia']
     *     ],
     * ];
     * ```
     *
     * @return static the field object itself.
     */
    public function select(array $attributes = [], array $items = [], array $groups = []): self
    {
        $new = clone $this;
        $attributes['type'] = self::TYPE_SELECT;
        $attributes = $new->setInputAttributes($attributes);
        /** @var bool */
        $encode = $attributes['encode'] ?? false;
        /** @var array<array-key, string> */
        $itemsAttributes = $attributes['itemsAttributes'] ?? [];
        /** @var array<array-key, string> */
        $optionsData = $attributes['optionsData'] ?? [];
        /** @var array<array-key, mixed> */
        $prompt = $attributes['prompt'] ?? [];

        unset($attributes['encode'], $attributes['itemsAttributes'], $attributes['optionsData'], $attributes['prompt']);

        $new->parts['{input}'] = Select::widget()
            ->config($new->getFormModel(), $new->attribute, $attributes)
            ->groups($groups)
            ->items($items)
            ->itemsAttributes($itemsAttributes)
            ->optionsData($optionsData, $encode)
            ->prompt($prompt)
            ->render();

        return $new;
    }

    /**
     * Renders a submit button widget.
     *
     * This method will generate the `name` and `value` tag attributes automatically for the model attribute unless
     * they are explicitly specified in `$attributes`.
     *
     * @param array $attributes the tag attributes in terms of name-value pairs. These will be rendered as the
     * attributes of the resulting tag. The values will be HTML-encoded using {@see \Yiisoft\Html\Html::encode()}.
     *
     * @return static the field object itself.
     */
    public function submitButton(array $attributes = []): self
    {
        $new = clone $this;
        $submit = SubmitButton::widget();
        $new->parts['{error}'] = '';
        $new->parts['{hint}'] = '';
        $new->parts['{label}'] = '';

        if (isset($attributes['autoIdPrefix']) && is_string($attributes['autoIdPrefix'])) {
            $submit = $submit->autoIdPrefix($attributes['autoIdPrefix']);
        }

        if (isset($attributes['id']) && is_string($attributes['id'])) {
            $submit = $submit->id($attributes['id']);
        }

        if (isset($attributes['name']) && is_string($attributes['name'])) {
            $submit = $submit->name($attributes['name']);
        }

        if (isset($attributes['value']) && is_string($attributes['value'])) {
            $submit = $submit->value($attributes['value']);
        }

        unset($attributes['autoIdPrefix'], $attributes['id'], $attributes['name'], $attributes['value']);

        $new->parts['{input}'] = $submit->attributes($attributes)->render();

        return $new;
    }

    /**
     * Renders a telephone widget.
     *
     * This method will generate the `name` and `value` tag attributes automatically for the model attribute unless
     * they are explicitly specified in `$attributes`.
     *
     * @param array $attributes the tag attributes in terms of name-value pairs. These will be rendered as the
     * attributes of the resulting tag. The values will be HTML-encoded using {@see \Yiisoft\Html\Html::encode()}.
     *
     * @return static the field object itself.
     */
    public function telephone(array $attributes = []): self
    {
        $new = clone $this;
        $attributes['type'] = self::TYPE_TEL;
        $attributes = $new->setInputAttributes($attributes);

        $new->parts['{input}'] = Telephone::widget()->config($new->getFormModel(), $new->attribute, $attributes)->render();

        return $new;
    }

    /**
     * Renders a text widget.
     *
     * This method will generate the `name` and `value` tag attributes automatically for the model attribute unless
     * they are explicitly specified in `$attributes`.
     *
     * @param array $attributes the tag attributes in terms of name-value pairs. These will be rendered as the
     * attributes of the resulting tag. The values will be HTML-encoded using {@see \Yiisoft\Html\Html::encode()}.
     *
     * @return static the field object itself.
     */
    public function text(array $attributes = []): self
    {
        $new = clone $this;
        $attributes['type'] = self::TYPE_TEXT;
        $attributes = $new->setInputAttributes($attributes);
        $text = Text::widget();

        if (isset($attributes['dirname']) && is_string($attributes['dirname'])) {
            $text = $text->dirname($attributes['dirname']);
        }

        unset($attributes['dirname']);

        $new->parts['{input}'] = $text->config($new->getFormModel(), $new->attribute, $attributes)->render();

        return $new;
    }

    /**
     * Renders a text area.
     *
     * The model attribute value will be used as the content in the textarea.
     *
     * @param array $attributes the tag attributes in terms of name-value pairs. These will be rendered as the
     * attributes of the resulting tag. The values will be HTML-encoded using {@see \Yiisoft\Html\Html::encode()}.
     *
     * @return static the field object itself.
     */
    public function textArea(array $attributes = []): self
    {
        $new = clone $this;
        $textArea = TextArea::widget();
        $attributes = $new->setInputAttributes($attributes);

        if (isset($attributes['dirname']) && is_string($attributes['dirname'])) {
            $textArea = $textArea->dirname($attributes['dirname']);
        }

        if (isset($attributes['wrap']) && is_string($attributes['wrap'])) {
            $textArea = $textArea->wrap($attributes['wrap']);
        }

        unset($attributes['dirname'], $attributes['wrap']);

        $new->parts['{input}'] = $textArea->config($new->getFormModel(), $new->attribute, $attributes)->render();

        return $new;
    }

    /**
     * Renders a Url widget.
     *
     * This method will generate the `name` and `value` tag attributes automatically for the model attribute unless
     * they are explicitly specified in `$attributes`.
     *
     * @param array $attributes the tag attributes in terms of name-value pairs. These will be rendered as the
     * attributes of the resulting tag. The values will be HTML-encoded using {@see \Yiisoft\Html\Html::encode()}.
     *
     * @return static the field object itself.
     */
    public function url(array $attributes = []): self
    {
        $new = clone $this;
        $attributes['type'] = self::TYPE_URL;
        $attributes = $new->setInputAttributes($attributes);

        $new->parts['{input}'] = Url::widget()->config($new->getFormModel(), $new->attribute, $attributes)->render();

        return $new;
    }

    /**
     * Renders the whole field.
     *
     * This method will generate the label, input tag and hint tag (if any), and assemble them into HTML according to
     * {@see template}.
     *
     * If (not set), the default methods will be called to generate the label and input tag, and use them as the
     * content.
     *
     * @throws ReflectionException
     *
     * @return string the rendering result.
     */
    protected function run(): string
    {
        $new = clone $this;

        $div = Div::tag();

        if (!isset($new->parts['{label}'])) {
            $new = $new->label();
        }

        if (!isset($new->parts['{input}'])) {
            $new = $new->text();
        }

        if (!isset($new->parts['{hint}'])) {
            $new = $new->hint();
        }

        if (!isset($this->parts['{error}'])) {
            $new = $new->error();
        }

        if ($new->containerClass !== '') {
            $div = $div->class($new->containerClass);
        }

        $content = preg_replace('/^\h*\v+/m', '', trim(strtr($new->template, $new->parts)));

        return $div->content(PHP_EOL . $content . PHP_EOL)->encode(false)->render();
    }
}
