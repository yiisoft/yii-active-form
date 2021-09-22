<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\Widget\Attribute;

use PHPUnit\Framework\TestCase;
use Yiisoft\Form\Tests\TestSupport\TestTrait;
use Yiisoft\Form\Tests\TestSupport\Widget\WithoutModelAttributeWidget;
use Yiisoft\Html\Html;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Widget\WidgetFactory;

final class WithoutModelAttributeTest extends TestCase
{
    use TestTrait;

    public function testAutoIdPrefix(): void
    {
        $this->assertSame(
            '<test id="t-1" name="t-1" value>',
            WithoutModelAttributeWidget::widget()->autoIdPrefix('t-')->render(),
        );
    }

    public function testAttributes(): void
    {
        $this->assertSame(
            '<test id="1" name="1" value disabled>',
            WithoutModelAttributeWidget::widget()->attributes(['disabled' => true])->render(),
        );
    }

    public function testId(): void
    {
        $this->assertSame(
            '<test id="test-id" name="test-id" value>',
            WithoutModelAttributeWidget::widget()->id('test-id')->render(),
        );
    }

    public function testValue(): void
    {
        $this->assertSame(
            '<test id="1" name="1" value="test-value">',
            WithoutModelAttributeWidget::widget()->value('test-value')->render(),
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        WidgetFactory::initialize(new SimpleContainer(), []);
        $this->setInaccessibleProperty(new Html(), 'generateIdCounter', []);
    }
}
