<?php

declare(strict_types=1);

namespace Yiisoft\Form\Widget;

use Yiisoft\Arrays\ArrayHelper;
use Yiisoft\Form\Helper\HtmlForm;
use Yiisoft\Form\Widget\Attribute\ModelAttributes;
use Yiisoft\Html\Tag\CustomTag;
use Yiisoft\Widget\Widget;

/**
 * The Error widget displays an error message.
 */
final class Error extends Widget
{
    use ModelAttributes;

    private string $message = '';

    /**
     * Error message to display.
     *
     * @return static
     */
    public function message(string $value): self
    {
        $new = clone $this;
        $new->message = $value;
        return $new;
    }

    /**
     * Callback that will be called to obtain an error message.
     *
     * The signature of the callback must be:
     *
     * ```php
     * [$FormModel, function()]
     * ```
     *
     * @param array $value
     *
     * @return static
     */
    public function messageCallback(array $value = []): self
    {
        $new = clone $this;
        $new->attributes['messageCallback'] = $value;
        return $new;
    }

    /**
     * The tag name of the container element.
     *
     * Empty to render error messages without container {@see Html::tag()}.
     *
     * @param string $value
     *
     * @return static
     */
    public function tag(string $value = ''): self
    {
        $new = clone $this;
        $new->attributes['tag'] = $value;
        return $new;
    }

    /**
     * Generates a tag that contains the first validation error of the specified form attribute.
     *
     * @return string the generated label tag
     */
    protected function run(): string
    {
        $new = clone $this;

        /** @var bool */
        $encode = $new->attributes['encode'] ?? true;

        $error = $new->message !== '' ? $new->message : HtmlForm::getFirstError($new->getFormModel(), $new->attribute);

        /** @var string */
        $tag = ArrayHelper::remove($new->attributes, 'tag', 'div');

        /** @var array|null */
        $messageCallback = $new->attributes['messageCallback'] ?? null;

        if ($messageCallback !== null) {
            /** @var string */
            $error = $messageCallback($new->getFormModel(), $new->attribute);
        }

        unset($new->attributes['messageCallback']);

        $html = $tag !== ''
            ? CustomTag::name($tag)->attributes($new->attributes)->content($error)->encode($encode)->render()
            : $error;

        return $error !== '' ? $html : '';
    }
}
