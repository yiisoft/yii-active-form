<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\Widget;

use PHPUnit\Framework\TestCase;
use Yiisoft\Form\Tests\TestSupport\Form\TypeForm;
use Yiisoft\Form\Tests\TestSupport\TestTrait;
use Yiisoft\Form\Widget\Field;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Widget\WidgetFactory;

final class FieldFileTest extends TestCase
{
    use TestTrait;

    private TypeForm $formModel;

    public function testAccept(): void
    {
        $expected = <<<HTML
        <div>
        <label for="typeform-tonull">To Null</label>
        <input type="file" id="typeform-tonull" name="TypeForm[toNull]" accept="image/*">
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'toNull')->file(['accept' => 'image/*'])->render(),
        );
    }

    public function testForceUncheckedValue(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="typeform-tonull">To Null</label>
        <input type="hidden" name="TypeForm[toNull]" value><input type="file" id="typeform-tonull" name="TypeForm[toNull]">
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'toNull')->file(['forceUncheckedValue' => ''])->render(),
        );
    }

    public function testHiddenAttributes(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="typeform-tonull">To Null</label>
        <input type="hidden" id="test-id" name="TypeForm[toNull]" value><input type="file" id="typeform-tonull" name="TypeForm[toNull]">
        </div>
        HTML;
        $html = Field::widget()
            ->config($this->formModel, 'toNull')
            ->file(['forceUncheckedValue' => '', 'hiddenAttributes' => ['id' => 'test-id']])
            ->render();
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testMultiple(): void
    {
        $expected = <<<HTML
        <div>
        <label for="typeform-tonull">To Null</label>
        <input type="file" id="typeform-tonull" name="TypeForm[toNull]" multiple>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'toNull')->file(['multiple' => true])->render(),
        );
    }

    public function testRender(): void
    {
        $expected = <<<HTML
        <div>
        <label for="typeform-tonull">To Null</label>
        <input type="file" id="typeform-tonull" name="TypeForm[toNull]">
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'toNull')->file()->render(),
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        WidgetFactory::initialize(new SimpleContainer(), []);
        $this->formModel = new TypeForm();
    }
}
