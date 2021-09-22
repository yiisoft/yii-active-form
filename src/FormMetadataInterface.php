<?php

declare(strict_types=1);

namespace Yiisoft\Form;

/**
 * FormModelInterface model represents an HTML form: its data, validation and presentation.
 */
interface FormMetadataInterface
{
    /**
     * Returns the text label for the specified attribute.
     *
     * @param string $attribute the attribute name.
     *
     * @return string the attribute label.
     */
    public function getAttributeLabel(string $attribute): string;

    /**
     * Returns the attribute labels.
     *
     * Attribute labels are mainly used for display purpose. For example, given an attribute `firstName`, we can
     * declare a label `First Name` which is more user-friendly and can be displayed to end users.
     *
     * By default an attribute label is generated automatically. This method allows you to
     * explicitly specify attribute labels.
     *
     * Note, in order to inherit labels defined in the parent class, a child class needs to merge the parent labels
     * with child labels using functions such as `array_merge()`.
     *
     * @return array attribute labels (name => label)
     *
     * {@see \Yiisoft\Form\FormModel::getAttributeLabel()}
     */
    public function getAttributeLabels(): array;

    /**
     * Returns the text hint for the specified attribute.
     *
     * @param string $attribute the attribute name.
     *
     * @return string the attribute hint.
     */
    public function getAttributeHint(string $attribute): string;

    /**
     * Returns the attribute hints.
     *
     * Attribute hints are mainly used for display purpose. For example, given an attribute `isPublic`, we can declare
     * a hint `Whether the post should be visible for not logged in users`, which provides user-friendly description of
     * the attribute meaning and can be displayed to end users.
     *
     * Unlike label hint will not be generated, if its explicit declaration is omitted.
     *
     * Note, in order to inherit hints defined in the parent class, a child class needs to merge the parent hints with
     * child hints using functions such as `array_merge()`.
     *
     * @return array attribute hints (name => hint)
     */
    public function getAttributeHints(): array;

    /**
     * Returns the text placeholder for the specified attribute.
     *
     * @param string $attribute the attribute name.
     *
     * @return string the attribute placeholder.
     */
    public function getAttributePlaceholder(string $attribute): string;

    /**
     * Returns the attribute placeholders.
     *
     * @return array attribute placeholder (name => placeholder)
     */
    public function getAttributePlaceholders(): array;
}
