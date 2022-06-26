<?php

declare(strict_types=1);

namespace Minascafe\Category\Domain\Entity;

use Minascafe\Category\Domain\ValueObject\CategoryActive;
use Minascafe\Category\Domain\ValueObject\CategoryIcon;
use Minascafe\Category\Domain\ValueObject\CategoryId;
use Minascafe\Category\Domain\ValueObject\CategoryName;

final class Category
{
    private function __construct(
        private CategoryId $id,
        private CategoryName $name,
        private CategoryIcon $icon,
        private CategoryActive $active
    ) {
    }

    public static function create(CategoryId $id, CategoryName $name, CategoryIcon $icon, CategoryActive $active): self
    {
        return new self($id, $name, $icon, $active);
    }

    public function id(): CategoryId
    {
        return $this->id;
    }

    public function name(): CategoryName
    {
        return $this->name;
    }

    public function icon(): CategoryIcon
    {
        return $this->icon;
    }

    public function isActive(): CategoryActive
    {
        return $this->active;
    }

    /**
     * @param array<string, string|bool> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            new CategoryId($data['id']),
            new CategoryName($data['name']),
            new CategoryIcon($data['icon']),
            new CategoryActive($data['active'])
        );
    }

    /**
     * @return array<string, string|bool>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id()->value(),
            'name' => $this->name()->value(),
            'icon' => $this->icon()->value(),
            'active' => $this->isActive()->value(),
        ];
    }
}
