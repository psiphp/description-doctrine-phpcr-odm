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
use Doctrine\ODM\PHPCR\DocumentManagerInterface;

class PhpcrOdmEnhancer implements EnhancerInterface
{
    private $documentManager;

    public function __construct(DocumentManagerInterface $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    /**
     * {@inheritdoc}
     */
    public function enhanceFromClass(DescriptionInterface $description, \ReflectionClass $class)
    {
        $metadataFactory = $this->documentManager->getMetadataFactory();
        $metadata = $metadataFactory->getMetadataFor(ClassUtils::getRealClass($class->getName()));
        $childClasses = $metadata->getChildClasses();
        $childTypes = [];

        // explode the allowed types into concrete classes
        foreach ($metadataFactory->getAllMetadata() as $childMetadata) {
            foreach ($childClasses as $childClass) {
                $childRefl = $childMetadata->getReflectionClass();
                if ($childClass == $childRefl->getName() || $childRefl->isSubclassOf($childClass)) {
                    $childTypes[] = $childRefl->getName();
                }
            }
        }

        $description->set('hierarchy.allow_children', new BooleanDescriptor(!$metadata->isLeaf()));
        $description->set('hierarchy.children_types', new ArrayDescriptor($childTypes));
        $description->set('std.class', new ClassDescriptor(new \ReflectionClass(ClassUtils::getRealClass($class->getName()))));
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
        return $this->documentManager->getMetadataFactory()->hasMetadataFor(ClassUtils::getRealClass($subject->getClass()->getName()));
    }
}
