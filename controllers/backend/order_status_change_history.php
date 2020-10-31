<?php
/***************************************************************************
 *                                                                          *
 *   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
 *                                                                          *
 * This  is  commercial  software,  only  users  who have purchased a valid *
 * license  and  accept  to the terms of the  License Agreement can install *
 * and use this program.                                                    *
 *                                                                          *
 ****************************************************************************
 * PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
 * "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
 ****************************************************************************/

use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($mode == 'manage') {
    $params = array_merge(['q_action'=>'status'], $_REQUEST);

    list($history, $search) = fn_get_logs($params, Registry::get('settings.Appearance.admin_elements_per_page'));

    foreach ($history as &$item) {
        $old_status = null;
        $new_status = null;
        $order_id = null;
        foreach ($item['content'] as $key => $value) {
            if ($key == 'status') {
                $old_status = explode(' -> ', $value)[0];
                $new_status = explode(' -> ', $value)[1];
            }
            if ($key == 'id') {
                $order_id = $value;
            }
        }
        $item['old_status'] = $old_status;
        $item['new_status'] = $new_status;
        $item['order_id'] = $order_id;
    }
    Tygh::$app['view']
        ->assign('history', $history)
        ->assign('search', $search);
}