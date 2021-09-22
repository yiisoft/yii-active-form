<?php

declare(strict_types=1);

namespace Yiisoft\Form\Widget\Attribute;

trait DateAttributes
{
    /**
     * The earliest acceptable date.
     *
     * @param string $value
     *
     * @return static
     *
     * @link https://html.spec.whatwg.org/multipage/input.html#attr-input-min
     */
    public function min(?string $value): self
    {
        $new = clone $this;
        $new->attributes['min'] = $value;
        return $new;
    }

    /**
     * The latest acceptable date.
     *
     * @param string $value
     *
     * @return static
     *
     * @link https://html.spec.whatwg.org/multipage/input.html#attr-input-max
     */
    public function max(?string $value): self
    {
        $new = clone $this;
        $new->attributes['max'] = $value;
        return $new;
    }
}
