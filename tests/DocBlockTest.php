<?php


namespace Deployee\Components\DocBlock;


use Deployee\Components\DocBlock\Test\TestClass;
use Deployee\Components\DocBlock\Test\TestClassWithouDocBlock;
use PHPUnit\Framework\TestCase;

class DocBlockTest extends TestCase
{
    public function testDocBlock()
    {
        $docBlock = new DocBlock();
        $allTags = $docBlock->getTags(TestClass::class);
        $barTags = $docBlock->getTagsByName(TestClass::class, 'bar');
        $hasAwesomeTag = $docBlock->hasTag(TestClass::class, 'awesome');

        $this->assertSame([
            'foo' => [],
            'bar' => ['one', 'two', 'three'],
            'awesome' => ['yes']
        ], $allTags);

        $this->assertSame(['one', 'two', 'three'], $barTags);
        $this->assertTrue($hasAwesomeTag);
        $this->assertFalse($docBlock->hasTag(TestClass::class, 'doesNotExist'));
    }

    public function testDocBlockFailOnNonExistentClass()
    {
        $docBlock = new DocBlock();
        $this->expectException(\ReflectionException::class);
        $docBlock->getTags('SomeClassThatDoesNotExist');
    }

    public function testDocBlockOnClassWithoutDocBlock()
    {
        $docBlock = new DocBlock();
        $this->assertSame([], $docBlock->getTagsByName(TestClassWithouDocBlock::class, 'bar'));
        $this->assertSame([], $docBlock->getTags(TestClassWithouDocBlock::class));
        $this->assertFalse($docBlock->hasTag(TestClassWithouDocBlock::class, 'awesome'));
    }
}