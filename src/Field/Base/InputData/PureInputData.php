<?php

declare(strict_types=1);

namespace Yiisoft\Form\Field\Base\InputData;

final class PureInputData implements InputDataInterface
{
    /**
     * @param string[] $validationErrors
     *
     * @psalm-param list<string> $validationErrors
     */
    public function __construct(
        private ?string $name = null,
        private mixed $value = null,
        private ?string $label = null,
        private ?string $hint = null,
        private ?string $placeholder = null,
        private ?string $id = null,
        private ?array $validationErrors = null,
    ) {
    }

    public function getValidationRules(): mixed
    {
        return null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function getHint(): ?string
    {
        return $this->hint;
    }

    public function getPlaceholder(): ?string
    {
        return $this->placeholder;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function isValidated(): bool
    {
        return $this->validationErrors !== null;
    }

    public function getValidationErrors(): array
    {
        return $this->validationErrors ?? [];
    }
}
