<?php

namespace App\FilesBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface {

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder() {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('app_files');

        $rootNode
                ->children()
                    ->scalarNode('driver')->defaultValue('gd')
                        ->validate()
                            ->ifTrue(function($v) { return !in_array($v, array('gd', 'imagick', 'gmagick')); })
                            ->thenInvalid('Invalid imagine driver specified: %s')
                        ->end()
                    ->end()
                    ->scalarNode('filePath')->defaultValue('uploads/')->end()
                    ->scalarNode('waterMarkName')->defaultValue('water.png')->end()
                    ->scalarNode('ImageDir')->defaultValue('Images')->end()
                    ->scalarNode('FileDir')->defaultValue('Files')->end()                         
                ->end();
                            
        $this->addContextsSection($rootNode);
        
        return $treeBuilder;
    }
    
    /**
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     */
    private function addContextsSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('contexts')
                    ->useAttributeAsKey('id')
                    ->prototype('array')
                        ->children()            
                           ->scalarNode('watermark')->defaultValue(false)->end()
                            ->arrayNode('formats')
                                ->isRequired()
                                ->useAttributeAsKey('id')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('width')->defaultValue(0)->end()
                                        ->scalarNode('height')->defaultValue(0)->end()
                                        ->scalarNode('quality')->defaultValue(80)->end()
                                        ->scalarNode('rotate')->defaultValue(0)->end()
                                        ->scalarNode('type')->defaultValue('simple')->end()
                                        ->scalarNode('format')->defaultValue('jpg')->end()
                                        ->scalarNode('constraint')->defaultValue(true)->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

}