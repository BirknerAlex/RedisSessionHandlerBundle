<?php

namespace BirknerAlex\RedisSessionHandlerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class RedisSessionHandlerExtension extends Extension
{
    protected $container;

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $this->container = $container;

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->setDefaults($config);

        $redis = $container->register('redis', 'Redis')
            ->addMethodCall('connect', ['%redis_session_handler.host%', '%redis_session_handler.port%']);
        if (isset($config['password']) && !empty($config['password'])) {
            $redis->addMethodCall('auth', ['%redis_session_handler.password%']);
        }
        $redis->addMethodCall('select', ['%redis_session_handler.database%']);

        $container->register('redis.session.handler', 'BirknerAlex\RedisSessionHandlerBundle\Session\Storage\Handler\RedisSessionHandler')
            ->addArgument(new Reference('redis'))
            ->addArgument('%redis_session_handler.db_options%');
    }

    /**
     * Sets the Bundle default settings
     */
    protected function setDefaults($config) {
        $this->container->setParameter(
            'redis_session_handler.host',
            isset($config['host'])?$config['host']:'127.0.0.1'
        );
        $this->container->setParameter(
            'redis_session_handler.port',
            isset($config['port'])?$config['port']:6379
        );
        $this->container->setParameter(
            'redis_session_handler.database',
            isset($config['database'])?$config['database']:0
        );
        $this->container->setParameter(
            'redis_session_handler.password',
            isset($config['password'])?$config['password']:null
        );
        $this->container->setParameter(
            'redis_session_handler.db_options',
            isset($config['db_options'])?$config['db_options']:[]
        );
    }
}
