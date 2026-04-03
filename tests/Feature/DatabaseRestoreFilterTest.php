<?php

use App\Http\Controllers\Admin\DatabaseManagementController;
use App\Services\DatabaseBackupService;

function invokePrivateRestoreMethod(DatabaseManagementController $controller, string $method, array $args = [])
{
    $reflection = new ReflectionMethod($controller, $method);
    $reflection->setAccessible(true);

    return $reflection->invokeArgs($controller, $args);
}

test('it detects IF EXISTS and IF NOT EXISTS table references', function () {
    $controller = new DatabaseManagementController(new DatabaseBackupService());

    expect(invokePrivateRestoreMethod(
        $controller,
        'statementReferencesTable',
        ['DROP TABLE IF EXISTS `sessions`;', 'sessions']
    ))->toBeTrue();

    expect(invokePrivateRestoreMethod(
        $controller,
        'statementReferencesTable',
        ['CREATE TABLE IF NOT EXISTS `cache` (`key` varchar(255));', 'cache']
    ))->toBeTrue();

    expect(invokePrivateRestoreMethod(
        $controller,
        'statementReferencesTable',
        ['INSERT INTO `sessions` (`id`) VALUES ("abc");', 'sessions']
    ))->toBeTrue();
});

test('it filters volatile runtime table statements', function () {
    $controller = new DatabaseManagementController(new DatabaseBackupService());

    $statements = [
        'DROP TABLE IF EXISTS `sessions`;',
        'CREATE TABLE IF NOT EXISTS `cache` (`key` varchar(255));',
        'INSERT INTO `sessions` (`id`) VALUES ("abc");',
        'DROP TABLE IF EXISTS `cache`;',
        'INSERT INTO `users` (`id`, `name`) VALUES (1, "Admin");',
        'CREATE TABLE `users` (`id` bigint unsigned not null);',
    ];

    $filtered = invokePrivateRestoreMethod($controller, 'filterRestorableStatements', [$statements]);

    expect($filtered)->toHaveCount(2);
    expect($filtered[0])->toContain('INSERT INTO `users`');
    expect($filtered[1])->toContain('CREATE TABLE `users`');
});
