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

final class FieldRadioTest extends TestCase
{
    use TestTrait;

    private TypeForm $formModel;

    public function testAnyLabel(): void
    {
        $expected = <<<'HTML'
        <div>
        <input type="radio" id="typeform-bool" name="TypeForm[bool]" value="0">
        </div>
        HTML;
        $html = Field::widget()
            ->config($this->formModel, 'bool')
            ->radio([], false)
            ->label(['label' => false])
            ->render();
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testEnclosedByLabel(): void
    {
        // Enclosed by label `false`
        $expected = <<<'HTML'
        <div>
        <label for="typeform-bool">Bool</label>
        <input type="radio" id="typeform-bool" name="TypeForm[bool]" value="0">
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'bool')->radio([], false)->render(),
        );

        // Enclosed by label `true`
        $expected = <<<'HTML'
        <div>
        <label><input type="radio" id="typeform-bool" name="TypeForm[bool]" value="0"> Bool</label>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'bool')->radio([], true)->render(),
        );
    }

    public function testEnclosedByLabelWithLabelAttributes(): void
    {
        // Enclosed by label `false` with label attributes
        $expected = <<<'HTML'
        <div>
        <label class="test-class" for="typeform-bool">Bool</label>
        <input type="radio" id="typeform-bool" name="TypeForm[bool]" value="0">
        </div>
        HTML;
        $html = Field::widget()
            ->config($this->formModel, 'bool')
            ->radio([], false)
            ->label(['class' => 'test-class'])
            ->render();
        $this->assertEqualsWithoutLE($expected, $html);

        // Enclosed by label `true` with label attributes
        $expected = <<<'HTML'
        <div>
        <label class="test-class"><input type="radio" id="typeform-bool" name="TypeForm[bool]" value="0"> Bool</label>
        </div>
        HTML;
        $html = Field::widget()
            ->config($this->formModel, 'bool')
            ->radio(['labelAttributes' => ['class' => 'test-class']])
            ->render();
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testEnclosedByLabelCustomText(): void
    {
        // Enclosed by label `false` with custom text
        $expected = <<<'HTML'
        <div>
        <label for="typeform-bool">test-text-label</label>
        <input type="radio" id="typeform-bool" name="TypeForm[bool]" value="0">
        </div>
        HTML;
        $html = Field::widget()
            ->config($this->formModel, 'bool')
            ->radio([], false)
            ->label(['label' => 'test-text-label'])
            ->render();
        $this->assertEqualsWithoutLE($expected, $html);

        // Enclosed by label `true` with custom text
        $expected = <<<'HTML'
        <div>
        <label><input type="radio" id="typeform-bool" name="TypeForm[bool]" value="0"> test-text-label</label>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'bool')->radio(['label' => 'test-text-label'])->render(),
        );
    }

    public function testForceUncheckedValue(): void
    {
        $expected = <<<'HTML'
        <div>
        <input type="hidden" name="TypeForm[bool]" value="0"><label><input type="radio" id="typeform-bool" name="TypeForm[bool]" value="0"> Bool</label>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'bool')->radio(['forceUncheckedValue' => '0'])->render(),
        );
    }

    public function testForm(): void
    {
        $expected = <<<'HTML'
        <div>
        <label><input type="radio" id="typeform-bool" name="TypeForm[bool]" value="0" form="form-id"> Bool</label>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'bool')->radio(['form' => 'form-id'])->render(),
        );
    }

    public function testRender(): void
    {
        $expected = <<<'HTML'
        <div>
        <label><input type="radio" id="typeform-int" name="TypeForm[int]" value="0"> Int</label>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'int')->radio()->render()
        );
    }

    public function testValues(): void
    {
        // value bool false
        $this->formModel->setAttribute('bool', false);
        $expected = <<<'HTML'
        <div>
        <label><input type="radio" id="typeform-bool" name="TypeForm[bool]" value="0"> Bool</label>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'bool')->radio()->render(),
        );

        // value bool true
        $this->formModel->setAttribute('bool', true);
        $expected = <<<'HTML'
        <div>
        <label><input type="radio" id="typeform-bool" name="TypeForm[bool]" value="1" checked> Bool</label>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'bool')->radio()->render(),
        );

        // value int 0
        $this->formModel->setAttribute('int', 0);
        $expected = <<<'HTML'
        <div>
        <label><input type="radio" id="typeform-int" name="TypeForm[int]" value="0"> Int</label>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'int')->radio()->render(),
        );

        // value int 1
        $this->formModel->setAttribute('int', 1);
        $expected = <<<'HTML'
        <div>
        <label><input type="radio" id="typeform-int" name="TypeForm[int]" value="1" checked> Int</label>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'int')->radio()->render(),
        );

        // value string '0'
        $this->formModel->setAttribute('string', '0');
        $expected = <<<'HTML'
        <div>
        <label><input type="radio" id="typeform-string" name="TypeForm[string]" value="0"> String</label>
        <div>Write your text string.</div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'string')->radio()->render(),
        );

        // value string '1'
        $this->formModel->setAttribute('string', '1');
        $expected = <<<'HTML'
        <div>
        <label><input type="radio" id="typeform-string" name="TypeForm[string]" value="1" checked> String</label>
        <div>Write your text string.</div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'string')->radio()->render(),
        );

        // value null
        $this->formModel->setAttribute('toNull', null);
        $expected = <<<'HTML'
        <div>
        <label><input type="radio" id="typeform-tonull" name="TypeForm[toNull]" value="0"> To Null</label>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'toNull')->radio()->render(),
        );
    }

    public function testValueException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Radio widget requires a bool|float|int|string|null value.');
        Field::widget()->config($this->formModel, 'array')->radio()->render();
    }

    protected function setUp(): void
    {
        parent::setUp();
        WidgetFactory::initialize(new SimpleContainer(), []);
        $this->formModel = new TypeForm();
    }
}
