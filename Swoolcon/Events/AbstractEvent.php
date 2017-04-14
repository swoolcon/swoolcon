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
namespace Swoolcon\Events;

use Phalcon\DiInterface;
use Phalcon\Di\Injectable;


abstract class AbstractEvent extends Injectable
{

    /**
     * AbstractEvent constructor.
     *
     * @param DiInterface|null $di
     */
    public function __construct(DiInterface $di = null)
    {
        if ($di) {
            $this->setDI($di);
        }

    }
}
