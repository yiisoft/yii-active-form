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

final class FieldDateTimeTest extends TestCase
{
    use TestTrait;

    private TypeForm $formModel;

    public function testRender(): void
    {
        $expected = <<<HTML
        <div>
        <label for="typeform-todate">To Date</label>
        <input type="datetime" id="typeform-todate" name="TypeForm[toDate]" value>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'toDate')->datetime()->render(),
        );
    }

    public function testValue(): void
    {
        // string '2021-09-18'
        $this->formModel->setAttribute('string', '2021-09-18T23:59:00');
        $expected = <<<HTML
        <div>
        <label for="typeform-todate">To Date</label>
        <input type="datetime" id="typeform-todate" name="TypeForm[toDate]" value>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'toDate')->datetime()->render(),
        );
    }

    public function testValueException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('DateTime widget requires a string value.');
        $html = Field::widget()->config($this->formModel, 'array')->datetime()->render();
    }

    protected function setUp(): void
    {
        parent::setUp();
        WidgetFactory::initialize(new SimpleContainer(), []);
        $this->formModel = new TypeForm();
    }
}
