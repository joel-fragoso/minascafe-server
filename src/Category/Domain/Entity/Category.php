<?php

declare(strict_types=1);

namespace Minascafe\Category\Domain\Entity;

use Minascafe\Category\Domain\ValueObject\CategoryId;
use Minascafe\Category\Domain\ValueObject\CategoryName;

final class Category
{
    private function __construct(private CategoryId $id, private CategoryName $name)
    {
    }

    public static function create(CategoryId $id, CategoryName $name): self
    {
        return new self($id, $name);
    }

    public function id(): CategoryId
    {
        return $this->id;
    }

    public function name(): CategoryName
    {
        return $this->name;
    }

    /**
     * @param array<string, string> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            new CategoryId($data['id']),
            new CategoryName($data['name'])
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
        ];
    }
}
