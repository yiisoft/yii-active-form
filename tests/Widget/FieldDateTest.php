<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\Widget;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Yiisoft\Form\Tests\TestSupport\Form\TypeForm;
use Yiisoft\Form\Tests\TestSupport\TestTrait;
use Yiisoft\Form\Widget\Field;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Widget\WidgetFactory;

final class FieldDateTest extends TestCase
{
    use TestTrait;

    private TypeForm $formModel;

    public function testRender(): void
    {
        $expected = <<<HTML
        <div>
        <label for="typeform-todate">To Date</label>
        <input type="date" id="typeform-todate" name="TypeForm[toDate]" value>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'toDate')->date()->render(),
        );
    }

    public function testValue(): void
    {
        // string '2021-09-18'
        $expected = <<<HTML
        <div>
        <label for="typeform-todate">To Date</label>
        <input type="date" id="typeform-todate" name="TypeForm[toDate]" value>
        </div>
        HTML;
        $this->formModel->setAttribute('string', '2021-09-18');
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'toDate')->date()->render(),
        );
    }

    public function testValueException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Date widget requires a string value.');
        $html = Field::widget()->config($this->formModel, 'array')->date()->render();
    }

    protected function setUp(): void
    {
        parent::setUp();
        WidgetFactory::initialize(new SimpleContainer(), []);
        $this->formModel = new TypeForm();
    }
}
