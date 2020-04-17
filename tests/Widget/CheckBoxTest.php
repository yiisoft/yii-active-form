<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\Widget;

use Yiisoft\Form\Tests\TestCase;
use Yiisoft\Form\Tests\Stub\StubForm;
use Yiisoft\Form\Widget\CheckBox;

final class CheckBoxTest extends TestCase
{
    /**
     * Data provider for {@see Checkbox()}.
     *
     * @return array test data.
     */
    public function dataProviderCheckbox(): array
    {
        return [
            [
                false,
                false,
                false,
                [],
                '<input type="checkbox" id="stubform-fieldcheck" name="StubForm[fieldCheck]" value="1">',
            ],
            [
                true,
                false,
                false,
                [],
                '<input type="checkbox" id="stubform-fieldcheck" name="StubForm[fieldCheck]" value="1" checked>',
            ],
            [
                false,
                true,
                false,
                [],
                '<label><input type="checkbox" id="stubform-fieldcheck" name="StubForm[fieldCheck]" value="1"> Field Check</label>',
            ],
            [
                true,
                true,
                false,
                [],
                '<label><input type="checkbox" id="stubform-fieldcheck" name="StubForm[fieldCheck]" value="1" checked> Field Check</label>',
            ],
            [
                false,
                false,
                true,
                [],
                '<input type="hidden" name="StubForm[fieldCheck]" value="1"><input type="checkbox" id="stubform-fieldcheck" name="StubForm[fieldCheck]" value="1">',
            ],
        ];
    }

    /**
     * @dataProvider dataProviderCheckbox
     *
     * @param bool $value
     * @param bool $label
     * @param bool $uncheck
     * @param array $options
     * @param string $expected
     */
    public function testCheckbox(bool $value, bool $label, bool $uncheck, array $options, string $expected): void
    {
        $form = new StubForm();
        $form->fieldCheck($value);

        $created = (new CheckBox())
            ->form($form)
            ->attribute('fieldCheck')
            ->label($label)
            ->uncheck($uncheck)
            ->options($options)
            ->run();
        $this->assertEquals($expected, $created);
    }
}
