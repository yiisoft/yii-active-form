<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\Widget;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Yiisoft\Form\Tests\TestSupport\Form\TypeForm;
use Yiisoft\Form\Widget\DateTimeLocal;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Widget\WidgetFactory;

final class DateTimeLocalTest extends TestCase
{
    private TypeForm $formModel;

    public function testRender(): void
    {
        $this->assertSame(
            '<input type="datetime-local" id="typeform-string" name="TypeForm[string]" value>',
            DateTimeLocal::widget()->config($this->formModel, 'string')->render(),
        );
    }

    public function testValue(): void
    {
        // string '2021-09-18'
        $this->formModel->setAttribute('string', '2021-09-18T23:59:00');
        $this->assertSame(
            '<input type="datetime-local" id="typeform-string" name="TypeForm[string]" value="2021-09-18T23:59:00">',
            DateTimeLocal::widget()->config($this->formModel, 'string')->render(),
        );
    }

    public function testValueException(): void
    {
        $this->formModel->setAttribute('array', []);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('DateTimeLocal widget requires a string value.');
        $html = DateTimeLocal::widget()->config($this->formModel, 'array')->render();
    }

    protected function setUp(): void
    {
        parent::setUp();
        WidgetFactory::initialize(new SimpleContainer(), []);
        $this->formModel = new TypeForm();
    }
}
