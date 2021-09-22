<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\Widget\Attribute;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Yiisoft\Form\Tests\TestSupport\Form\TypeForm;
use Yiisoft\Form\Tests\TestSupport\TestTrait;
use Yiisoft\Form\Tests\TestSupport\Widget\ModelAttributesWidget;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Widget\WidgetFactory;

final class ModelAttributesTest extends TestCase
{
    use TestTrait;

    private TypeForm $formModel;

    public function testGetFormModelException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Form model is not set.');
        $this->invokeMethod(ModelAttributesWidget::widget(), 'getFormModel');
    }

    public function testImmutability(): void
    {
        $modelAttributes = ModelAttributesWidget::widget();
        $this->assertNotSame($modelAttributes, $modelAttributes->charset(''));
        $this->assertNotSame($modelAttributes, $modelAttributes->config($this->formModel, 'string', []));
        $this->assertNotSame($modelAttributes, $modelAttributes->id(''));
    }

    protected function setUp(): void
    {
        parent::setUp();
        WidgetFactory::initialize(new SimpleContainer(), []);
        $this->formModel = new TypeForm();
    }
}
