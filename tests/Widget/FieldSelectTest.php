<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\Widget;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use StdClass;
use Yiisoft\Form\Tests\TestSupport\Form\TypeForm;
use Yiisoft\Form\Tests\TestSupport\TestTrait;
use Yiisoft\Form\Widget\Field;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Widget\WidgetFactory;

final class FieldSelectTest extends TestCase
{
    use TestTrait;

    private TypeForm $formModel;
    private array $cities = [];

    public function testForceUnselectValueWithMultiple(): void
    {
        $this->formModel->setAttribute('array', [1, 3]);
        $expected = <<<'HTML'
        <div>
        <label for="typeform-array">Array</label>
        <select id="typeform-array" name="TypeForm[array]">
        <option value="1" selected>Moscu</option>
        <option value="2">San Petersburgo</option>
        <option value="3" selected>Novosibirsk</option>
        <option value="4">Ekaterinburgo</option>
        </select>
        </div>
        HTML;
        $html = Field::widget()
            ->config($this->formModel, 'array', ['unselectValue' => 0, 'multiple' => true])
            ->select([], $this->cities)
            ->render();
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testGroups(): void
    {
        $this->formModel->setAttribute('int', 8);
        $expected = <<<'HTML'
        <div>
        <label for="typeform-int">Int</label>
        <select id="typeform-int" name="TypeForm[int]">
        <optgroup label="Russia">
        <option value="2"> Moscu</option>
        <option value="3"> San Petersburgo</option>
        <option value="4"> Novosibirsk</option>
        <option value="5"> Ekaterinburgo</option>
        </optgroup>
        <optgroup label="Chile">
        <option value="6">Santiago</option>
        <option value="7">Concepcion</option>
        <option value="8" selected>Chillan</option>
        </optgroup>
        </select>
        </div>
        HTML;
        $html = Field::widget()
            ->config($this->formModel, 'int')
            ->select([], $this->citiesGroups, $this->groups)
            ->render();
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testGroupsItemsAttributes(): void
    {
        $this->formModel->setAttribute('int', 7);
        $expected = <<<'HTML'
        <div>
        <label for="typeform-int">Int</label>
        <select id="typeform-int" name="TypeForm[int]">
        <optgroup label="Russia">
        <option value="2" disabled> Moscu</option>
        <option value="3"> San Petersburgo</option>
        <option value="4"> Novosibirsk</option>
        <option value="5"> Ekaterinburgo</option>
        </optgroup>
        <optgroup label="Chile">
        <option value="6">Santiago</option>
        <option value="7" selected>Concepcion</option>
        <option value="8">Chillan</option>
        </optgroup>
        </select>
        </div>
        HTML;
        $html = Field::widget()
            ->config($this->formModel, 'int')
            ->select(['itemsAttributes' => ['2' => ['disabled' => true]]], $this->citiesGroups, $this->groups)
            ->render();
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testMultiple(): void
    {
        $this->formModel->setAttribute('array', [1, 4]);
        $expected = <<<'HTML'
        <div>
        <label for="typeform-array">Array</label>
        <input type="hidden" name="TypeForm[array]" value="0">
        <select id="typeform-array" name="TypeForm[array][]" multiple size="4">
        <option value="1" selected>Moscu</option>
        <option value="2">San Petersburgo</option>
        <option value="3">Novosibirsk</option>
        <option value="4" selected>Ekaterinburgo</option>
        </select>
        </div>
        HTML;
        $html = Field::widget()
            ->config($this->formModel, 'array')
            ->select(['multiple' => true, 'unselectValue' => 0], $this->cities)
            ->render();
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testOptionsDataEncode(): void
    {
        // encode false.
        $cities = [
            '1' => '<b>Moscu</b>',
            '2' => 'San Petersburgo',
            '3' => 'Novosibirsk',
            '4' => 'Ekaterinburgo',
        ];
        $this->formModel->setAttribute('cityBirth', 3);
        $expected = <<<'HTML'
        <div>
        <label for="typeform-int">Int</label>
        <select id="typeform-int" name="TypeForm[int]">
        <option value="1"><b>Moscu</b></option>
        <option value="2">San Petersburgo</option>
        <option value="3">Novosibirsk</option>
        <option value="4">Ekaterinburgo</option>
        </select>
        </div>
        HTML;
        $html = Field::widget()
            ->config($this->formModel, 'int')
            ->select(['optionsData' => $cities, 'encode' => false])
            ->render();
        $this->assertEqualsWithoutLE($expected, $html);

        // encode true.
        $cities = [
            '1' => '<b>Moscu</b>',
            '2' => 'San Petersburgo',
            '3' => 'Novosibirsk',
            '4' => 'Ekaterinburgo',
        ];
        $this->formModel->setAttribute('cityBirth', 3);
        $expected = <<<'HTML'
        <div>
        <label for="typeform-int">Int</label>
        <select id="typeform-int" name="TypeForm[int]">
        <option value="1">&lt;b&gt;Moscu&lt;/b&gt;</option>
        <option value="2">San Petersburgo</option>
        <option value="3">Novosibirsk</option>
        <option value="4">Ekaterinburgo</option>
        </select>
        </div>
        HTML;
        $html = Field::widget()
            ->config($this->formModel, 'int')
            ->select(['optionsData' => $cities, 'encode' => true])
            ->render();
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testPrompt(): void
    {
        $prompt = [
            'text' => 'Select City Birth',
            'attributes' => [
                'value' => '0',
                'selected' => 'selected',
            ],
        ];
        $expected = <<<'HTML'
        <div>
        <label for="typeform-int">Int</label>
        <select id="typeform-int" name="TypeForm[int]">
        <option value="0" selected>Select City Birth</option>
        <option value="1">Moscu</option>
        <option value="2">San Petersburgo</option>
        <option value="3">Novosibirsk</option>
        <option value="4">Ekaterinburgo</option>
        </select>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'int')->select(['prompt' => $prompt], $this->cities)->render(),
        );
    }

    public function testRender(): void
    {
        $this->formModel->setAttribute('int', 2);
        $expected = <<<'HTML'
        <div>
        <label for="typeform-int">Int</label>
        <select id="typeform-int" name="TypeForm[int]">
        <option value="1">Moscu</option>
        <option value="2" selected>San Petersburgo</option>
        <option value="3">Novosibirsk</option>
        <option value="4">Ekaterinburgo</option>
        </select>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'int')->select([], $this->cities)->render(),
        );
    }

    public function testSizeWithMultiple(): void
    {
        $this->formModel->setAttribute('int', 2);
        $expected = <<<'HTML'
        <div>
        <label for="typeform-int">Int</label>
        <input type="hidden" name="TypeForm[int]" value>
        <select id="typeform-int" name="TypeForm[int][]" multiple size="4">
        <option value="1">Moscu</option>
        <option value="2" selected>San Petersburgo</option>
        <option value="3">Novosibirsk</option>
        <option value="4">Ekaterinburgo</option>
        </select>
        </div>
        HTML;
        $html = Field::widget()
            ->config($this->formModel, 'int')
            ->select(['multiple' => true, 'size' => 4], $this->cities)
            ->render();
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testValues(): void
    {
        // value int 1.
        $this->formModel->setAttribute('int', 1);
        $expected = <<<'HTML'
        <div>
        <label for="typeform-int">Int</label>
        <select id="typeform-int" name="TypeForm[int]">
        <option value="1" selected>Moscu</option>
        <option value="2">San Petersburgo</option>
        <option value="3">Novosibirsk</option>
        <option value="4">Ekaterinburgo</option>
        </select>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'int')->select([], $this->cities)->render(),
        );

        // value int 2.
        $this->formModel->setAttribute('int', 2);
        $expected = <<<'HTML'
        <div>
        <label for="typeform-int">Int</label>
        <select id="typeform-int" name="TypeForm[int]">
        <option value="1">Moscu</option>
        <option value="2" selected>San Petersburgo</option>
        <option value="3">Novosibirsk</option>
        <option value="4">Ekaterinburgo</option>
        </select>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'int')->select([], $this->cities)->render(),
        );

        // value iterable [2, 3].
        $this->formModel->setAttribute('array', [2, 3]);
        $expected = <<<'HTML'
        <div>
        <label for="typeform-array">Array</label>
        <select id="typeform-array" name="TypeForm[array]">
        <option value="1">Moscu</option>
        <option value="2" selected>San Petersburgo</option>
        <option value="3" selected>Novosibirsk</option>
        <option value="4">Ekaterinburgo</option>
        </select>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'array')->select([], $this->cities)->render(),
        );

        // value string '1'
        $this->formModel->setAttribute('string', '1');
        $expected = <<<'HTML'
        <div>
        <label for="typeform-string">String</label>
        <select id="typeform-string" name="TypeForm[string]">
        <option value="1" selected>Moscu</option>
        <option value="2">San Petersburgo</option>
        <option value="3">Novosibirsk</option>
        <option value="4">Ekaterinburgo</option>
        </select>
        <div>Write your text string.</div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'string')->select([], $this->cities)->render(),
        );

        // value string '2'
        $this->formModel->setAttribute('string', '2');
        $expected = <<<'HTML'
        <div>
        <label for="typeform-string">String</label>
        <select id="typeform-string" name="TypeForm[string]">
        <option value="1">Moscu</option>
        <option value="2" selected>San Petersburgo</option>
        <option value="3">Novosibirsk</option>
        <option value="4">Ekaterinburgo</option>
        </select>
        <div>Write your text string.</div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'string')->select([], $this->cities)->render(),
        );

        // value null
        $this->formModel->setAttribute('toNull', null);
        $expected = <<<'HTML'
        <div>
        <label for="typeform-tonull">To Null</label>
        <select id="typeform-tonull" name="TypeForm[toNull]">
        <option value="1">Moscu</option>
        <option value="2">San Petersburgo</option>
        <option value="3">Novosibirsk</option>
        <option value="4">Ekaterinburgo</option>
        </select>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'toNull')->select([], $this->cities)->render(),
        );
    }

    public function testValueException(): void
    {
        $this->formModel->setAttribute('object', new StdClass());
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Select widget required bool|float|int|iterable|string|null.');
        Field::widget()->config($this->formModel, 'object')->select()->render();
    }

    protected function setUp(): void
    {
        parent::setUp();
        WidgetFactory::initialize(new SimpleContainer(), []);
        $this->formModel = new TypeForm();
        $this->cities = [
            '1' => 'Moscu',
            '2' => 'San Petersburgo',
            '3' => 'Novosibirsk',
            '4' => 'Ekaterinburgo',
        ];
        $this->citiesGroups = [
            '1' => [
                '2' => ' Moscu',
                '3' => ' San Petersburgo',
                '4' => ' Novosibirsk',
                '5' => ' Ekaterinburgo',
            ],
            '2' => [
                '6' => 'Santiago',
                '7' => 'Concepcion',
                '8' => 'Chillan',
            ],
        ];
        $this->groups = [
            '1' => ['label' => 'Russia'],
            '2' => ['label' => 'Chile'],
        ];
    }
}
