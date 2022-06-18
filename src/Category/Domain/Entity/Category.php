<?php

declare(strict_types=1);

namespace Minascafe\Category\Domain\Entity;

use Minascafe\Category\Domain\ValueObject\CategoryIcon;
use Minascafe\Category\Domain\ValueObject\CategoryId;
use Minascafe\Category\Domain\ValueObject\CategoryName;

final class Category
{
    private function __construct(private CategoryId $id, private CategoryName $name, private CategoryIcon $icon)
    {
    }

    public static function create(CategoryId $id, CategoryName $name, CategoryIcon $icon): self
    {
        return new self($id, $name, $icon);
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

    /**
     * @param array<string, string> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            new CategoryId($data['id']),
            new CategoryName($data['name']),
            new CategoryIcon($data['icon'])
        );
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id()->value(),
            'name' => $this->name()->value(),
            'icon' => $this->icon()->value(),
        ];
    }
}
