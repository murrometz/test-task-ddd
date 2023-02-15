<?php

namespace App\Product\Domain\FileConverter\Dto;

use App\Product\Domain\FileConverter\Dto\Collection\RowCollection;

class Row
{
    private string $itemName;
    private string $type;
    private string $parent;
    private string $relation;
    private RowCollection $children;

    public function __construct()
    {
        $this->children = new RowCollection();
    }

    public function getItemName(): string
    {
        return $this->itemName;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getParent(): string
    {
        return $this->parent;
    }

    public function getRelation(): string
    {
        return $this->relation;
    }

    public function getChildren(): RowCollection
    {
        return $this->children;
    }

    public function setItemName(string $itemName): Row
    {
        $this->itemName = $itemName;
        return $this;
    }

    public function setType(string $type): Row
    {
        $this->type = $type;
        return $this;
    }

    public function setParent(string $parent): Row
    {
        $this->parent = $parent;
        return $this;
    }

    public function setRelation(string $relation): Row
    {
        $this->relation = $relation;
        return $this;
    }

    public function setChildren(RowCollection $children): Row
    {
        $this->children = $children;
        return $this;
    }

    public function addChild(Row $child): Row
    {
        $this->children[] = $child;
        return $this;
    }

}