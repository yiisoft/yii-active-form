<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\Widget\Attribute;

use PHPUnit\Framework\TestCase;
use Yiisoft\Form\Tests\TestSupport\Widget\DateAttributesWidget;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Widget\WidgetFactory;

final class DateAttributesTest extends TestCase
{
    public function testImmutability(): void
    {
        $dateAttributes = DateAttributesWidget::widget();
        $this->assertNotSame($dateAttributes, $dateAttributes->max(''));
        $this->assertNotSame($dateAttributes, $dateAttributes->min(''));
    }

    public function testMax(): void
    {
        $this->assertSame('<test max="10">', DateAttributesWidget::widget()->max('10')->render());
    }

    public function testMin(): void
    {
        $this->assertSame('<test min="1">', DateAttributesWidget::widget()->min('1')->render());
    }

    protected function setUp(): void
    {
        parent::setUp();
        WidgetFactory::initialize(new SimpleContainer(), []);
    }
}
