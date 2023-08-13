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
task('deploy:optimize', function () {
    cd('{{release_path}}');
    run('composer dump-env prod');
});
task('deploy:remove_files', function () {
    cd('{{release_path}}');
    run('rm -rf .github');
    run('rm .gitignore');
    run('rm importmap.php');
    run('rm symfony.lock');
    run('rm composer.lock');
    run('rm composer.json');
});

// Hooks
after('deploy:symlink', 'deploy:build_assets');
after('deploy:symlink', 'deploy:optimize');
before('deploy:success', 'deploy:import_data');
before('deploy:success', 'deploy:remove_files');
after('deploy:failed', 'deploy:unlock');
