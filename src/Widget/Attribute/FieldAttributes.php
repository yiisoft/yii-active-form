<?php

declare(strict_types=1);

namespace Yiisoft\Form\Widget\Attribute;

use InvalidArgumentException;
use Yiisoft\Form\FormModelInterface;
use Yiisoft\Form\Helper\HtmlForm;
use Yiisoft\Html\Html;
use Yiisoft\Validator\Rule\HasLength;
use Yiisoft\Validator\Rule\MatchRegularExpression;
use Yiisoft\Validator\Rule\Number;
use Yiisoft\Validator\Rule\Required;
use Yiisoft\Validator\Rule\Url;

trait FieldAttributes
{
    private bool $ariaDescribedBy = false;
    private string $attribute = '';
    private array $attributes = [];
    private string $containerClass = '';
    private string $errorClass = '';
    private string $errorMessage = '';
    private string $hintClass = '';
    private string $id = '';
    private string $inputClass = '';
    private string $labelClass = '';
    private string $invalidClass = '';
    private string $validClass = '';
    private array $parts = [];
    private string $template = "{label}\n{input}\n{hint}\n{error}";
    private string $validationStateOn = 'input';
    private ?FormModelInterface $formModel = null;

    /**
     * Set aria-describedby attribute.
     *
     * @return static
     *
     * @link https://www.w3.org/TR/WCAG20-TECHS/ARIA1.html
     */
    public function ariaDescribedBy(): self
    {
        $new = clone $this;
        $new->ariaDescribedBy = true;
        return $new;
    }

    /**
     * Set form interface, attribute name and attributes, and attributes for the widget.
     *
     * @param FormModelInterface $formModel Form.
     * @param string $attribute Form model property this widget is rendered for.
     * @param array $attributes The HTML attributes for the widget container tag.
     *
     * @return static
     *
     * {@see \Yiisoft\Html\Html::renderTagAttributes()} for details on how attributes are being rendered.
     */
    public function config(FormModelInterface $formModel, string $attribute, array $attributes = []): self
    {
        $new = clone $this;
        $new->formModel = $formModel;
        $new->attribute = $attribute;
        $new->attributes = $attributes;
        return $new;
    }

    /**
     * Set container css class.
     *
     * @return static
     */
    public function containerClass(string $value): self
    {
        $new = clone $this;
        $new->containerClass = $value;
        return $new;
    }

    /**
     * Set error css class.
     *
     * @return static
     */
    public function errorClass(string $value): self
    {
        $new = clone $this;
        $new->errorClass = $value;
        return $new;
    }

    /**
     * Set hint css class.
     *
     * @return static
     */
    public function hintClass(string $value): self
    {
        $new = clone $this;
        $new->hintClass = $value;
        return $new;
    }

    /**
     * Set input css class.
     *
     * @return static
     */
    public function inputClass(string $value): self
    {
        $new = clone $this;
        $new->inputClass = $value;
        return $new;
    }

    /**
     * Set invalid css class.
     *
     * @return static
     */
    public function invalidClass(string $value): self
    {
        $new = clone $this;
        $new->invalidClass = $value;
        return $new;
    }

    /**
     * Set the label css class.
     *
     * @return static
     */
    public function labelClass(string $value): self
    {
        $new = clone $this;
        $new->labelClass = $value;
        return $new;
    }

    /**
     * Set layout template for render a field.
     *
     * @param string $template
     *
     * @return static
     */
    public function template(string $value): self
    {
        $new = clone $this;
        $new->template = $value;
        return $new;
    }

    /**
     * Set the value valid css class.
     *
     * @param string $value is the valid css class.
     *
     * @return static
     */
    public function validClass(string $value): self
    {
        $new = clone $this;
        $new->validClass = $value;
        return $new;
    }

    protected function getFormModel(): FormModelInterface
    {
        if ($this->formModel === null) {
            throw new InvalidArgumentException('Form model is not set.');
        }

        return $this->formModel;
    }

    private function addValidatorAttributeHtml(
        FormModelInterface $formModel,
        string $attribute,
        array $attributes,
        string $type
    ): array {
        /** @var array */
        $rules = $formModel->getRules()[$attribute] ?? [];

        /** @var object $rule */
        foreach ($rules as $rule) {
            if ($rule instanceof Required) {
                $attributes['required'] = true;
            }
            if ($rule instanceof HasLength && in_array($type, self::HAS_LENGTH_TYPES, true)) {
                /** @var string */
                $attributes['maxlength'] = $rule->getOptions()['max'];
                /** @var string */
                $attributes['minlength'] = $rule->getOptions()['min'];
            }
            if ($rule instanceof MatchRegularExpression && in_array($type, self::MATCH_REGULAR_EXPRESSION_TYPES, true)) {
                /** @var string */
                $pattern = $rule->getOptions()['pattern'];
                $attributes['pattern'] = Html::normalizeRegexpPattern($pattern);
            }
            if ($rule instanceof Number && $type === self::TYPE_NUMBER) {
                /** @var string */
                $attributes['max'] = $rule->getOptions()['max'];
                /** @var string */
                $attributes['min'] = $rule->getOptions()['min'];
            }
            if ($rule instanceof Url && $type === self::TYPE_URL) {
                /** @var array<array-key, string> */
                $schemes = $rule->getOptions()['validSchemes'];
                /** @var array<array-key, float|int|string>|string */
                $pattern = $rule->getOptions()['pattern'];
                $normalizePattern = str_replace('{schemes}', '(' . implode('|', $schemes) . ')', $pattern);
                $attributes['pattern'] = Html::normalizeRegexpPattern($normalizePattern);
            }
        }

        return $attributes;
    }

    /**
     * Return the imput id.
     *
     * @return string
     */
    private function getId(): string
    {
        $new = clone $this;

        /** @var string */
        $id = $new->attributes['id'] ?? $new->id;

        return $id === '' ? HtmlForm::getInputId($new->getFormModel(), $new->attribute) : $id;
    }

    private function setInputAttributes(array $attributes): array
    {
        $new = clone $this;
        $placeHolder = '';
        /** @var string */
        $type = $attributes['type'] ?? '';
        unset($attributes['type']);
        $attributes = $new->addValidatorAttributeHtml($new->getFormModel(), $new->attribute, $attributes, $type);
        $attributeName = HtmlForm::getAttributeName($new->getFormModel(), $new->attribute);

        if ($new->ariaDescribedBy === true) {
            $attributes['aria-describedby'] = $new->getId();
        }

        if ($new->inputClass !== '') {
            Html::addCssClass($attributes, $new->inputClass);
        }

        if ($new->getFormModel()->hasErrors($attributeName) && $new->errorClass !== '') {
            Html::addCssClass($attributes, $new->invalidClass);
        } elseif ($new->getFormModel()->isValidated() && $new->validClass !== '') {
            Html::addCssClass($attributes, $new->validClass);
        }

        if (!in_array($type, self::NO_PLACEHOLDER_TYPES, true)) {
            $placeHolder = $new->getFormModel()->getAttributePlaceholder($new->attribute);
        }

        if (!isset($attributes['placeholder']) && $placeHolder !== '') {
            $attributes['placeholder'] = $placeHolder;
        }

        return $attributes;
    }
}
