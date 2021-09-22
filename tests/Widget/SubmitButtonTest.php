<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\Widget;

use PHPUnit\Framework\TestCase;
use Yiisoft\Form\Tests\TestSupport\TestTrait;
use Yiisoft\Form\Widget\SubmitButton;
use Yiisoft\Html\Html;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Widget\WidgetFactory;

final class SubmitButtonTest extends TestCase
{
    use TestTrait;

    public function testRender(): void
    {
        $this->assertSame(
            '<input type="submit" id="submit-1" name="submit-1">',
            SubmitButton::widget()->render(),
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        WidgetFactory::initialize(new SimpleContainer(), []);
        $this->setInaccessibleProperty(new Html(), 'generateIdCounter', []);
    }
}
