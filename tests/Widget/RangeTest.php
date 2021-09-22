<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\Widget;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Yiisoft\Form\Tests\TestSupport\Form\TypeForm;
use Yiisoft\Form\Widget\Range;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Widget\WidgetFactory;

final class RangeTest extends TestCase
{
    private TypeForm $formModel;

    public function testImmutability(): void
    {
        $range = Range::widget();
        $this->assertNotSame($range, $range->max(0));
        $this->assertNotSame($range, $range->min(0));
    }

    public function testMax(): void
    {
        $this->assertSame(
            '<input type="range" id="typeform-int" name="TypeForm[int]" value="0" max="8">',
            Range::widget()->config($this->formModel, 'int')->max(8)->render(),
        );
    }

    public function testMin(): void
    {
        $this->assertSame(
            '<input type="range" id="typeform-int" name="TypeForm[int]" value="0" min="4">',
            Range::widget()->config($this->formModel, 'int')->min(4)->render(),
        );
    }

    public function testRender(): void
    {
        $this->assertSame(
            '<input type="range" id="typeform-int" name="TypeForm[int]" value="0">',
            Range::widget()->config($this->formModel, 'int')->render(),
        );
    }

    public function testValue(): void
    {
        // string value numeric `1`.
        $this->formModel->setAttribute('string', '1');
        $this->assertSame(
            '<input type="range" id="typeform-string" name="TypeForm[string]" value="1">',
            Range::widget()->config($this->formModel, 'string')->render(),
        );
    }

    public function testValueException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Range widget must be a numeric value.');
        Range::widget()->config($this->formModel, 'array')->render();
    }

    protected function setUp(): void
    {
        parent::setUp();
        WidgetFactory::initialize(new SimpleContainer(), []);
        $this->formModel = new TypeForm();
    }
}
