<?php

namespace Psi\Component\Description\Enhancer\Doctrine;

use Doctrine\ODM\PHPCR\Mapping\ClassMetadataFactory;
use Doctrine\Common\Util\ClassUtils;

use Psi\Component\Description\EnhancerInterface;
use Psi\Component\Description\DescriptionInterface;
use Psi\Component\Description\Subject;
use Psi\Component\Description\Descriptor\BooleanDescriptor;
use Psi\Component\Description\Descriptor\ArrayDescriptor;
use Psi\Component\Description\Descriptor\ClassDescriptor;

class PhpcrOdmEnhancer implements EnhancerInterface
{
    private $metadataFactory;

    public function __construct(ClassMetadataFactory $metadataFactory)
    {
        $this->metadataFactory = $metadataFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function enhanceFromClass(DescriptionInterface $description, \ReflectionClass $class)
    {
        $metadata = $this->metadataFactory->getMetadataFor(ClassUtils::getRealClass($class->getName()));
        $childClasses = $metadata->getChildClasses();
        $childTypes = [];

        // explode the allowed types into concrete classes
        foreach ($this->metadataFactory->getAllMetadata() as $childMetadata) {
            foreach ($childClasses as $childClass) {
                $childRefl = $childMetadata->getReflectionClass();
                if ($childClass == $childRefl->getName() || $childRefl->isSubclassOf($childClass)) {
                    $childTypes[] = $childRefl->getName();
                }
            }
        }

        $description->set(new BooleanDescriptor('hierarchy.allow_children', !$metadata->isLeaf()));
        $description->set(new ArrayDescriptor('hierarchy.children_types', $childTypes));
        $description->set(new ClassDescriptor('std.class', new \ReflectionClass(ClassUtils::getRealClass($class->getName()))));
    }

    /**
     * {@inheritdoc}
     */
    public function enhanceFromObject(DescriptionInterface $description, Subject $subject)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Subject $subject)
    {
        return $this->metadataFactory->hasMetadataFor(ClassUtils::getRealClass($subject->getClass()->getName()));
    }
}
