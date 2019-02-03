<?php

namespace Finko\QueueBundle;

use Finko\QueueBundle\DependencyInjection\Compiler\FinkoQueueBundlePass;
use Finko\QueueBundle\DependencyInjection\FinkoQueueExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class FinkoQueueBundle
 *
 * @package Finko\QueueBundle
 */
class FinkoQueueBundle extends Bundle
{
    /**
     * @inheritdoc
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new FinkoQueueBundlePass);
    }

    /**
     * @inheritdoc
     */
    protected function createContainerExtension()
    {
        return new FinkoQueueExtension;
    }

    /**
     * @inheritdoc
     */
    protected function getContainerExtensionClass()
    {
        return FinkoQueueExtension::class;
    }

    /**
     * @inheritdoc
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {

            $extension = $this->createContainerExtension();
            $this->extension = $extension;
        }

        return $this->extension;
    }
}
