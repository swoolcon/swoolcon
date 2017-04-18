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

use Swoolcon\ServiceProvider;

return [
    // Application Service Providers
    ServiceProvider\ConfigServiceProvider::class,
    ServiceProvider\UrlResolverServiceProvider::class,
    ServiceProvider\CollectionManagerServiceProvider::class,
    ServiceProvider\ModelsManagerServiceProvider::class,
    ServiceProvider\DataCacheServiceProvider::class,
    ServiceProvider\ViewCacheServiceProvider::class,
    ServiceProvider\VoltTemplateEngineServiceProvider::class,
    ServiceProvider\ViewServiceProvider::class,
    ServiceProvider\PhpTemplateEngineServiceProvider::class,
    ServiceProvider\FlashSessionServiceProvider::class,
    ServiceProvider\CryptServiceProvider::class,
    ServiceProvider\TagServiceProvider::class,
    ServiceProvider\FilterServiceProvider::class,
    ServiceProvider\SecurityServiceProvider::class,
    ServiceProvider\ModelsCacheServiceProvider::class,
    ServiceProvider\ModelsMetadataServiceProvider::class,
    ServiceProvider\LoggerServiceProvider::class,
    ServiceProvider\EscaperServiceProvider::class,
    ServiceProvider\RandomServiceProvider::class,
    ServiceProvider\RedisServiceProvider::class,



    // Third Party Providers
    // ...
];
