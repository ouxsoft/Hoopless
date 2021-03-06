<?php
/**
 * This file is part of the Hoopless package.
 *
 * (c) Ouxsoft <contact@ouxsoft.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LHTML\Element\Custom\Example;

use LivingMarkup\Element\AbstractElement;

/**
 * Class UserProfile
 *
 * @package LivingMarkup\Modules\Widgets
 */
class UserProfile extends AbstractElement
{
    public $username = 'jane_doe';
    public $first_name = 'Jane';
    public $last_name = 'Doe';

    /**
     * Prints Hello, World
     *
     * @return mixed|string
     */
    public function onRender()
    {
        return '<div class="user_profile">' . $this->xml . '</div>';
    }
}
