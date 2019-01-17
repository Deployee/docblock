<?php


namespace Deployee\Components\DocBlock;


use phpDocumentor\Reflection\DocBlock\Tags\Generic;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\DocBlockFactoryInterface;

class DocBlock implements DocBlockInterface
{
    /**
     * @var DocBlockFactoryInterface
     */
    private $factory;

    /**
     * @var
     */
    private static $blocks = [];

    public function __construct()
    {
        $this->factory = DocBlockFactory::createInstance();
    }

    /**
     * @param string $class
     * @return array
     * @throws \ReflectionException
     */
    public function getTags(string $class): array
    {
        $return = [];
        try {
            $tags = $this->createDocBlock($class)->getTags();
        }
        catch(\InvalidArgumentException $e){
            return [];
        }

        /* @var Generic $tag */
        foreach ($tags as $tag) {
            if (!isset($return[$tag->getName()])) {
                $return[$tag->getName()] = [];
            }


            if ($tag->getDescription()) {
                $return[$tag->getName()][] = $tag->getDescription()->render();
            }
        }

        return $return;
    }

    /**
     * @param string $class
     * @param string $tagName
     * @return array
     * @throws \ReflectionException
     */
    public function getTagsByName(string $class, string $tagName): array
    {
        $return = [];

        try {
            $tags = $this->createDocBlock($class)->getTagsByName($tagName);
        }
        catch(\InvalidArgumentException $e){
            return [];
        }

        /* @var Generic $tag */
        foreach ($tags as $tag) {
            if($tag->getDescription()) {
                $return[] = $tag->getDescription()->render();
            }
        }

        return $return;
    }

    /**
     * @param string $class
     * @param string $tagName
     * @return bool
     * @throws \ReflectionException
     */
    public function hasTag(string $class, string $tagName): bool
    {
        try {
            return $this->createDocBlock($class)->hasTag($tagName);
        }
        catch(\InvalidArgumentException $e){
            return false;
        }
    }

    /**
     * @param string $class
     * @return \phpDocumentor\Reflection\DocBlock
     * @throws \ReflectionException
     */
    private function createDocBlock(string $class): \phpDocumentor\Reflection\DocBlock
    {
        $hash = sha1($class);
        if(!isset($this->blocks[$hash])){
            self::$blocks[$hash] = $this->factory->create(new \ReflectionClass($class));
        }

        return self::$blocks[$hash];
    }
}