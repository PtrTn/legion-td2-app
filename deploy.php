<?php
namespace Deployer;

require 'recipe/symfony.php';

// Config
set('repository', 'git@github.com:cbpeter/legion-td2-app.git');

add('shared_files', ['.env.local']);
add('shared_dirs', ['data']);
add('writable_dirs', ['data']);

// Hosts
host('31.222.229.195')
    ->set('remote_user', 'deployer')
    ->set('deploy_path', '~/legion-td2-app');

// Tasks
task('deploy:build_assets', function () {
    cd('{{release_path}}');
    run('bin/console asset-map:compile');
});
task('deploy:import_data', function () {
    cd('{{release_path}}');
    run('bin/console app:download');
});

// Hooks
after('deploy:symlink', 'deploy:build_assets');
before('deploy:success', 'deploy:import_data');
after('deploy:failed', 'deploy:unlock');
