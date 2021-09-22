<?php

declare(strict_types=1);

namespace Yiisoft\Form\Widget\Attribute;

use Yiisoft\Html\Html;

trait WithoutModelAttribute
{
    private string $autoIdPrefix = '';
    private array $attributes = [];
    private string $id = '';
    private string $name = '';
    private string $value = '';

    /**
     * The prefix to the automatically generated widget IDs.
     *
     * @param string $value
     *
     * @return static
     */
    public function autoIdPrefix(string $value): self
    {
        $new = clone $this;
        $new->autoIdPrefix = $value;
        return $new;
    }

    /**
     * The HTML attributes. The following special options are recognized.
     *
     * @param array $value
     *
     * @return static
     *
     * See {@see \Yiisoft\Html\Html::renderTagAttributes()} for details on how attributes are being rendered.
     */
    public function attributes(array $value): self
    {
        $new = clone $this;
        $new->attributes = $value;
        return $new;
    }

    /**
     * Set the Id of the widget.
     *
     * @param string $value
     *
     * @return static
     *
     * @link https://html.spec.whatwg.org/multipage/dom.html#the-id-attribute
     */
    public function id(string $id): self
    {
        $new = clone $this;
        $new->id = $id;
        return $new;
    }

    /**
     * The name part of the name/value pair associated with this element for the purposes of form submission.
     *
     * @param string The name of the widget.
     *
     * @return static
     *
     * @link https://www.w3.org/TR/2012/WD-html-markup-20120329/input.reset.html#input.reset.attrs.name
     */
    public function name(string $value): self
    {
        $new = clone $this;
        $new->name = $value;
        return $new;
    }

    /**
     * Specifies a value for the input element.
     *
     * @param string $value
     *
     * @return static
     *
     * @link https://www.w3.org/TR/2012/WD-html-markup-20120329/input.reset.html#input.reset.attrs.value
     */
    public function value(string $value): self
    {
        $new = clone $this;
        $new->value = $value;
        return $new;
    }

    protected function getId(): string
    {
        return $this->id = $this->id !== '' ? $this->id : Html::generateId($this->autoIdPrefix);
    }

    protected function getName(): string
    {
        return $this->name = $this->name !== '' ? $this->name : $this->getId();
    }
}
