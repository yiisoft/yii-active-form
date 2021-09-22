<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\Widget;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use StdClass;
use Yiisoft\Form\Tests\TestSupport\Form\TypeForm;
use Yiisoft\Form\Tests\TestSupport\TestTrait;
use Yiisoft\Form\Widget\Field;
use Yiisoft\Html\Widget\CheckboxList\CheckboxItem;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Widget\WidgetFactory;

final class FieldCheckBoxListTest extends TestCase
{
    use TestTrait;

    private array $sex = [1 => 'Female', 2 => 'Male'];
    private TypeForm $formModel;

    public function testContainerAttributes(): void
    {
        $this->formModel->setAttribute('int', 2);
        $expected = <<<'HTML'
        <div>
        <label for="typeform-int">Int</label>
        <div id="typeform-int" class="test-class">
        <label><input type="checkbox" name="TypeForm[int][]" value="1"> Female</label>
        <label><input type="checkbox" name="TypeForm[int][]" value="2" checked> Male</label>
        </div>
        </div>
        HTML;
        $html = Field::widget()
            ->config($this->formModel, 'int')
            ->checkboxList(['containerAttributes' => ['class' => 'test-class']], $this->sex)
            ->render();
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testContainerTag(): void
    {
        $this->formModel->setAttribute('int', 1);
        $expected = <<<'HTML'
        <div>
        <label for="typeform-int">Int</label>
        <span id="typeform-int">
        <label><input type="checkbox" name="TypeForm[int][]" value="1" checked> Female</label>
        <label><input type="checkbox" name="TypeForm[int][]" value="2"> Male</label>
        </span>
        </div>
        HTML;
        $html = Field::widget()
            ->config($this->formModel, 'int')
            ->checkboxList(['containerTag' => 'span'], $this->sex)
            ->render();
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testContainerTagWithFalse(): void
    {
        $this->formModel->setAttribute('int', 1);
        $expected = <<<'HTML'
        <div>
        <label for="typeform-int">Int</label>
        <label><input type="checkbox" name="TypeForm[int][]" value="1" checked> Female</label>
        <label><input type="checkbox" name="TypeForm[int][]" value="2"> Male</label>
        </div>
        HTML;
        $html = Field::widget()
            ->config($this->formModel, 'int')
            ->checkboxList(['containerTag' => false], $this->sex)
            ->render();
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testDisabled(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="typeform-int">Int</label>
        <div id="typeform-int">
        <label><input type="checkbox" name="TypeForm[int][]" value="1" disabled> Female</label>
        <label><input type="checkbox" name="TypeForm[int][]" value="2" disabled> Male</label>
        </div>
        </div>
        HTML;
        $html = Field::widget()
            ->config($this->formModel, 'int')
            ->checkboxList(['disabled' => true], $this->sex)
            ->render();
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testForceUncheckedValue(): void
    {
        $expected = <<<'HTML'
        <div>
        <label for="typeform-int">Int</label>
        <input type="hidden" name="TypeForm[int]" value="0">
        <div id="typeform-int">
        <label><input type="checkbox" name="TypeForm[int][]" value="1"> Female</label>
        <label><input type="checkbox" name="TypeForm[int][]" value="2"> Male</label>
        </div>
        </div>
        HTML;
        $html = Field::widget()
            ->config($this->formModel, 'int')
            ->checkboxList(['forceUncheckedValue' => '0'], $this->sex)
            ->render();
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testIndividualItemsAttributes(): void
    {
        $this->formModel->setAttribute('int', 2);
        $expected = <<<'HTML'
        <div>
        <label for="typeform-int">Int</label>
        <div id="typeform-int">
        <label><input type="checkbox" name="TypeForm[int][]" value="1" disabled> Female</label>
        <label><input type="checkbox" class="test-class" name="TypeForm[int][]" value="2" checked> Male</label>
        </div>
        </div>
        HTML;
        $html = Field::widget()
            ->config($this->formModel, 'int')
            ->checkboxList(
                [
                    'individualItemsAttributes' => [1 => ['disabled' => true], 2 => ['class' => 'test-class']],
                ],
                $this->sex,
            )
            ->render();
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testItemsAttributes(): void
    {
        $this->formModel->setAttribute('int', 2);
        $expected = <<<'HTML'
        <div>
        <label for="typeform-int">Int</label>
        <div id="typeform-int">
        <label><input type="checkbox" class="test-class" name="TypeForm[int][]" value="1"> Female</label>
        <label><input type="checkbox" class="test-class" name="TypeForm[int][]" value="2" checked> Male</label>
        </div>
        </div>
        HTML;
        $html = Field::widget()
            ->config($this->formModel, 'int')
            ->checkboxList(['itemsAttributes' => ['class' => 'test-class']], $this->sex)
            ->render();
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testItemFormater(): void
    {
        $this->formModel->setAttribute('int', 2);
        $expected = <<<'HTML'
        <div>
        <label for="typeform-int">Int</label>
        <div id="typeform-int">
        <label><input type='checkbox' name='TypeForm[int][]' value='1'> Female</label>
        <label><input type='checkbox' name='TypeForm[int][]' value='2' checked> Male</label>
        </div>
        </div>
        HTML;
        $html = Field::widget()
            ->config($this->formModel, 'int')
            ->checkboxList(
                [
                    'itemsFormatter' => static function (CheckboxItem $item) {
                        return $item->checked
                            ? "<label><input type='checkbox' name='{$item->name}' value='{$item->value}' checked> {$item->label}</label>"
                            : "<label><input type='checkbox' name='{$item->name}' value='{$item->value}'> {$item->label}</label>";
                    },
                ],
                $this->sex
            )
            ->render();
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testReadOnly(): void
    {
        $this->formModel->setAttribute('int', 1);
        $expected = <<<'HTML'
        <div>
        <label for="typeform-int">Int</label>
        <div id="typeform-int">
        <label><input type="checkbox" name="TypeForm[int][]" value="1" checked readonly> Female</label>
        <label><input type="checkbox" name="TypeForm[int][]" value="2" readonly> Male</label>
        </div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'int')->checkboxList(['readonly' => true], $this->sex)->render(),
        );
    }

    public function testRender(): void
    {
        $this->formModel->setAttribute('int', 2);
        $expected = <<<'HTML'
        <div>
        <label for="typeform-int">Int</label>
        <div id="typeform-int">
        <label><input type="checkbox" name="TypeForm[int][]" value="1"> Female</label>
        <label><input type="checkbox" name="TypeForm[int][]" value="2" checked> Male</label>
        </div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'int')->checkboxList([], $this->sex)->render(),
        );
    }

    public function testSeparator(): void
    {
        $this->formModel->setAttribute('int', 2);
        $expected = <<<'HTML'
        <div>
        <label for="typeform-int">Int</label>
        <div id="typeform-int">
        <label><input type="checkbox" name="TypeForm[int][]" value="1"> Female</label>&#9866;<label><input type="checkbox" name="TypeForm[int][]" value="2" checked> Male</label>
        </div>
        </div>
        HTML;
        $html = Field::widget()
            ->config($this->formModel, 'int')
            ->checkboxList(['separator' => '&#9866;'], $this->sex)
            ->render();
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testValue(): void
    {
        // value int 0
        $this->formModel->setAttribute('int', 1);
        $expected = <<<'HTML'
        <div>
        <label for="typeform-int">Int</label>
        <div id="typeform-int">
        <label><input type="checkbox" name="TypeForm[int][]" value="1" checked> Female</label>
        <label><input type="checkbox" name="TypeForm[int][]" value="2"> Male</label>
        </div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'int')->checkboxList([], $this->sex)->render(),
        );

        // value int 1
        $this->formModel->setAttribute('int', 2);
        $expected = <<<'HTML'
        <div>
        <label for="typeform-int">Int</label>
        <div id="typeform-int">
        <label><input type="checkbox" name="TypeForm[int][]" value="1"> Female</label>
        <label><input type="checkbox" name="TypeForm[int][]" value="2" checked> Male</label>
        </div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'int')->checkboxList([], $this->sex)->render(),
        );

        // value iterable
        $this->formModel->setAttribute('array', [1, 2]);
        $expected = <<<'HTML'
        <div>
        <label for="typeform-array">Array</label>
        <div id="typeform-array">
        <label><input type="checkbox" name="TypeForm[array][]" value="1" checked> Female</label>
        <label><input type="checkbox" name="TypeForm[array][]" value="2" checked> Male</label>
        </div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'array')->checkboxList([], $this->sex)->render(),
        );

        // value string '0'
        $this->formModel->setAttribute('string', '1');
        $expected = <<<'HTML'
        <div>
        <label for="typeform-string">String</label>
        <div id="typeform-string">
        <label><input type="checkbox" name="TypeForm[string][]" value="1" checked> Female</label>
        <label><input type="checkbox" name="TypeForm[string][]" value="2"> Male</label>
        </div>
        <div>Write your text string.</div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'string')->checkboxList([], $this->sex)->render(),
        );

        // value string '1'
        $this->formModel->setAttribute('string', '2');
        $expected = <<<'HTML'
        <div>
        <label for="typeform-string">String</label>
        <div id="typeform-string">
        <label><input type="checkbox" name="TypeForm[string][]" value="1"> Female</label>
        <label><input type="checkbox" name="TypeForm[string][]" value="2" checked> Male</label>
        </div>
        <div>Write your text string.</div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'string')->checkboxList([], $this->sex)->render(),
        );

        // value null
        $this->formModel->setAttribute('toNull', null);
        $expected = <<<'HTML'
        <div>
        <label for="typeform-tonull">To Null</label>
        <div id="typeform-tonull">
        <label><input type="checkbox" name="TypeForm[toNull][]" value="1"> Female</label>
        <label><input type="checkbox" name="TypeForm[toNull][]" value="2"> Male</label>
        </div>
        </div>
        HTML;
        $this->assertEqualsWithoutLE(
            $expected,
            Field::widget()->config($this->formModel, 'toNull')->checkboxList([], $this->sex)->render(),
        );
    }

    public function testValueException(): void
    {
        $this->formModel->setAttribute('object', new StdClass());
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('CheckboxList widget requires a int|string|iterable|null value.');
        $html = Field::widget()->config($this->formModel, 'object')->checkboxList()->render();
    }

    protected function setUp(): void
    {
        parent::setUp();
        WidgetFactory::initialize(new SimpleContainer(), []);
        $this->formModel = new TypeForm();
    }
}
