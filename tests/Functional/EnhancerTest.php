<?php

namespace Psi\Component\Description\Doctrine\Tests\Functional;

use Doctrine\ODM\PHPCR\Mapping\ClassMetadataFactory;
use Psi\Component\Description\Schema\Schema;
use Psi\Component\Description\Schema\Extension\StandardExtension;
use Psi\Component\Description\Schema\Extension\HierarchyExtension;
use Psi\Component\Description\DescriptionFactory;
use Psi\Component\Description\Enhancer\Doctrine\PhpcrOdmEnhancer;
use Psi\Component\Description\Subject;
use Doctrine\ODM\PHPCR\Mapping\ClassMetadata;
use Doctrine\ODM\PHPCR\DocumentManagerInterface;

class EnhancerTest extends \PHPUnit_Framework_TestCase
{
    private $metadataFactory;
    private $factory;

    public function setUp()
    {
        $this->metadataFactory = $this->prophesize(ClassMetadataFactory::class);
        $documentManager = $this->prophesize(DocumentManagerInterface::class);
        $documentManager->getMetadataFactory()->willReturn($this->metadataFactory->reveal());
        $enhancer = new PhpcrOdmEnhancer($documentManager->reveal());

        $extensions = [
            new StandardExtension(),
            new HierarchyExtension(),
        ];
        $schema = new Schema($extensions);
        $this->factory = new DescriptionFactory([$enhancer], $schema);
        $this->parentMetadata = $this->prophesize(ClassMetadata::class);
        $this->childOneMetadata = $this->prophesize(ClassMetadata::class);
        $this->subChildOneMetadata = $this->prophesize(ClassMetadata::class);
        $this->childTwoMetadata = $this->prophesize(ClassMetadata::class);
        $this->outsideMetadata = $this->prophesize(ClassMetadata::class);

        $this->parentMetadata->getReflectionClass()->willReturn(new \ReflectionClass(ParentClass::class));
        $this->childOneMetadata->getReflectionClass()->willReturn(new \ReflectionClass(ChildClassOne::class));
        $this->subChildOneMetadata->getReflectionClass()->willReturn(new \ReflectionClass(SubChildOne::class));
        $this->childTwoMetadata->getReflectionClass()->willReturn(new \ReflectionClass(ChildClassTwo::class));
        $this->outsideMetadata->getReflectionClass()->willReturn(new \ReflectionClass(OutsideClass::class));
    }

    public function testEnhancer()
    {
        $this->metadataFactory->getMetadataFor(ParentClass::class)->willReturn($this->parentMetadata->reveal());
        $this->metadataFactory->hasMetadataFor(ParentClass::class)->willReturn(true);

        $this->metadataFactory->getAllMetadata()->willReturn([
            $this->parentMetadata->reveal(),
            $this->childOneMetadata->reveal(),
            $this->childTwoMetadata->reveal(),
            $this->subChildOneMetadata->reveal(),
            $this->outsideMetadata->reveal(),
        ]);

        $this->parentMetadata->getChildClasses()->willReturn([
            ChildClassOne::class,
            ChildClassTwo::class,
        ]);

        $this->parentMetadata->isLeaf()->willReturn(false);

        $description = $this->factory->describe(Subject::createFromObject(ParentClass::class));

        $this->assertTrue($description->get('hierarchy.allow_children')->getValue());
        $this->assertCount(3, $description->get('hierarchy.children_types')->getValues());
        $this->assertEquals(ChildClassOne::class, $description->get('hierarchy.children_types')->getValues()[0]);
        $this->assertEquals(ChildClassTwo::class, $description->get('hierarchy.children_types')->getValues()[1]);
        $this->assertEquals(SubChildOne::class, $description->get('hierarchy.children_types')->getValues()[2]);
        $this->assertEquals(ParentClass::class, $description->get('std.class')->getClass()->getName());
    }
}

class ParentClass
{
}

class ChildClassOne
{
}

class ChildClassTwo
{
}

class SubChildOne extends ChildClassOne
{
}

class OutsideClass
{
}
