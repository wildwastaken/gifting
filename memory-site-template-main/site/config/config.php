<?php

return [
    'debug'  => true,
    'panel' => [
        'css' => 'assets/css/panel.css'
    ],
    'd4l' => [
        'static_site_generator' => [
            'endpoint' => 'generate-static-site',
            'output_folder' => __DIR__ . '/../../static',
            'preserve' => ['media']
        ]
    ],
    'ssh-deploy' => [
        'enabled' => true,
        'host' => '167.99.158.175',
        'user' => 'elliott',
        'path' => '/var/www/elliott.computer/memory/',
        'source' => 'static/',
        'exclude' => [
            '.DS_Store',
            '.vscode',
            '.nova',
            'deploy.sh',
            '_archive',
            '_drafts',
            '.gitignore',
            '.git',
            '**/.git',
            '.kirbystatic'
        ],
        'chmod' => 'Du=rwx,Dgo=rx,Fu=rw,Fog=r',
        'delete' => true
    ],
    'markdown' => [
        'extra' => true
    ],
];





