<?php

namespace Finko\QueueBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FinkoQueueBundlePass implements CompilerPassInterface
{
    /**
     * This method processes the container definition. In this case we are processing just the finko_queue.connector
     * definition.
     *
     * @param ContainerBuilder $container The container builder.
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('finko_queue')) {

            return;
        }

        $serviceDefinition = $container->getDefinition('finko_queue');
        $tagged = $container->findTaggedServiceIds('finko_queue.connector');

        foreach ($tagged as $id => $attr) {

            $serviceDefinition->addMethodCall('addConnector', [new Reference($id)]);
        }
    }
}
