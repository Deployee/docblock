<?php

namespace Deployee\Components\DocBlock;

interface DocBlockInterface
{
    /**
     * @param string $class
     * @return array
     */
    public function getTags(string $class): array;

    /**
     * @param string $class
     * @param string $tagName
     * @return array
     */
    public function getTagsByName(string $class, string $tagName): array;

    /**
     * @param string $class
     * @param string $tagName
     * @return bool
     */
    public function hasTag(string $class, string $tagName): bool;
}