<?php

declare(strict_types=1);

namespace Yiisoft\Form\Widget;

use JsonException;
use Yiisoft\Form\FormModelInterface;
use Yiisoft\Html\Html;
use Yiisoft\Html\Tag\CustomTag;
use Yiisoft\Widget\Widget;

use function array_merge;
use function array_unique;
use function array_values;

/**
 * The error summary widget displays a summary of the errors in a form.
 *
 * @psalm-suppress MissingConstructor
 */
final class ErrorSummary extends Widget
{
    private array $attributes = [];
    private FormModelInterface $formModel;

    /**
     * The HTML attributes. The following special options are recognized.
     *
     * @param array $value
     *
     * @return static
     *
     * See {@see Html::renderTagAttributes()} for details on how attributes are being rendered.
     */
    public function attributes(array $value): self
    {
        $new = clone $this;
        $new->attributes = $value;
        return $new;
    }

    /**
     * Set model class name.
     *
     * @param string $class name of model.
     *
     * @return static
     */
    public function model(FormModelInterface $formModel): self
    {
        $new = clone $this;
        $new->formModel = $formModel;
        return $new;
    }

    /**
     * Return array of the validation errors.
     *
     * @param bool $encode , if set to false then the error messages won't be encoded.
     * @param bool $showAllErrors , if set to true every error message for each attribute will be shown otherwise only
     * the first error message for each attribute will be shown.
     *
     * @return array of the validation errors.
     */
    private function collectErrors(bool $encode, bool $showAllErrors): array
    {
        $new = clone $this;

        $lines = [];

        foreach ([$new->formModel] as $form) {
            $lines = array_unique(array_merge($lines, $form->getErrorSummary($showAllErrors)));
        }

        /**
         * If there are the same error messages for different attributes, array_unique will leave gaps between
         * sequential keys. Applying array_values to reorder array keys.
         */
        $lines = array_values($lines);

        if ($encode) {
            /** @var string $line */
            foreach ($lines as &$line) {
                $line = Html::encode($line);
            }
        }

        return $lines;
    }

    /**
     * Generates a summary of the validation errors.
     *
     * @throws JsonException
     *
     * @return string the generated error summary
     */
    protected function run(): string
    {
        $new = clone $this;

        /** @var bool */
        $encode = $new->attributes['encode'] ?? true;

        /** @var string */
        $footer = $new->attributes['footer'] ?? '';

        /** @var string */
        $header = $new->attributes['header'] ?? '<p>' . 'Please fix the following errors:' . '</p>';

        /** @var bool */
        $showAllErrors = $new->attributes['showAllErrors'] ?? false;

        /** @psalm-var non-empty-string */
        $tag = $new->attributes['tag'] ?? 'div';

        unset(
            $new->attributes['encode'],
            $new->attributes['footer'],
            $new->attributes['header'],
            $new->attributes['showAllErrors'],
            $new->attributes['tag'],
        );

        /** @var array<string, string> */
        $lines = $new->collectErrors($encode, $showAllErrors);

        if (empty($lines)) {
            /** still render the placeholder for client-side validation use */
            $content = '<ul></ul>';
            $new->attributes['style'] = isset($new->attributes['style'])
                ? rtrim((string)$new->attributes['style'], ';') . '; display:none' : 'display:none';
        } else {
            $content = '<ul><li>' . implode("</li>\n<li>", $lines) . '</li></ul>';
        }

        return CustomTag::name($tag)
            ->attributes($new->attributes)
            ->encode(false)
            ->content($header . $content . $footer)
            ->render();
    }
}
