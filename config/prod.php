<?php

use Silex\Provider\DoctrineServiceProvider;
use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Symfony\Component\Security\Core\Encoder\PlaintextPasswordEncoder;


// configure your app for the production environment

$app['twig.path'] = array(__DIR__ . '/../templates');
$app['twig.options'] = array('cache' => __DIR__ . '/../var/cache/twig');

include __DIR__.'/config.php'; // $dbOption
$app->register(new DoctrineServiceProvider(), $dbOption);

$app->register(new DoctrineOrmServiceProvider(),
    [
        'orm.proxies_dir' => sys_get_temp_dir(),
        'orm.em.options' => [
            'mappings' => [
                [
                    'type' => 'annotation',
                    'namespace' => 'Model',
                    'path' => __DIR__ . '/../src/Model',
                ]
            ]
        ]
    ]
);

$app->register(
    new SecurityServiceProvider(),
    [
        'security.firewalls' => array(
            'login_path' => array(
                'pattern' => '^/signin$',
                'anonymous' => true
            ),
            'default' => array(
                'pattern' => '^/.*$',
                'anonymous' => true,
                'form' => array(
                    'login_path' => '/signin',
                    'check_path' => '/user/signin_check',
                ),
                'logout' => array(
                    'logout_path' => '/logout',
                    'invalidate_session' => true,
                    'target_url' => '/'
                ),
                'users' => function() use ($app){
                    $repository = $app['orm.em']->getRepository(Model\User::class);
                    return new \Provider\DBUserProvider($repository);
                },
            )
        ),
        'security.access_rules' => array(
            array('^/signin', 'IS_AUTHENTICATED_ANONYMOUSLY'),
            array('^/signup', 'IS_AUTHENTICATED_ANONYMOUSLY'),
            array('^/about', 'IS_AUTHENTICATED_ANONYMOUSLY'),
            array('^/support', 'IS_AUTHENTICATED_ANONYMOUSLY'),
            array('^/forgot_password', 'IS_AUTHENTICATED_ANONYMOUSLY'),
            array('^/reset_password', 'IS_AUTHENTICATED_ANONYMOUSLY'),
            array('^/.+$', 'ROLE_USER')
        ),
        'security.role_hierarchy' => [                  // Role hierarchy definition
            'ROLE_ADMIN' => ['ROLE_USER']               // Role admin is upper than role user
        ]
    ]
);

$app->register(new \Silex\Provider\SessionServiceProvider());

$app->register(new \Silex\Provider\ValidatorServiceProvider());

$app->register(new \Silex\Provider\FormServiceProvider());

$app->register(new \Silex\Provider\CsrfServiceProvider());

$app['locale'] = 'en_en';

$app->register(new \Silex\Provider\TranslationServiceProvider(),
    [
        'translator.domains ' => [],
    ]
);

$app->register(new Silex\Provider\SwiftmailerServiceProvider(), $swiftMaillerConfig);