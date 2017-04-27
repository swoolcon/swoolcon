<?php
/**
 * Phanbook : Delightfully simple forum software
 *
 * Licensed under The GNU License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @link    http://phanbook.com Phanbook Project
 * @since   1.0.0
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
 */

use Phalcon\Mvc\Router\Group as RouterGroup;

$error = new RouterGroup([
    'module'    => 'Error',
    'namespace' => Swoolcon\Modules\Error\Controllers::class,
]);

$error->addGet('/bad-request', 'Index::show400')
      ->setName('bad-request');

$error->addGet('/unauthorized', 'Index::show401')
      ->setName('unauthorized');

$error->addGet('/forbidden', 'Index::show403')
      ->setName('forbidden');

$error->addGet('/page-not-found', 'Index::show404')
    ->setName('page-not-found');

$error->addGet('/internal-error', 'Index::show500')
    ->setName('internal-error');

$error->addGet('/maintenance', 'Index::show503')
    ->setName('maintenance');
return $error;
