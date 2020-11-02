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

use Tygh\Navigation\LastView;

if (!defined('BOOTSTRAP')) {
    die('Access denied');
}

function fn_order_status_change_history_change_order_status($status_to, $status_from, $order_info, $force_notification, $order_statuses, $place_order)
{
    if (!empty(Tygh::$app['session']['auth']['user_id'])) {
        $user_id = Tygh::$app['session']['auth']['user_id'];
    } else {
        $user_id = 0;
    }

    $row = [
        'order_id' => $order_info['order_id'],
        'user_id' => $user_id,
        'timestamp' => TIME,
        'old_status' => $order_statuses[$status_from]['description'],
        'new_status' => $order_statuses[$status_to]['description']
    ];

    db_query("INSERT INTO ?:order_status_change_history ?e", $row);
}

function fn_get_order_status_change_history($params, $items_per_page = 0) {
    $params = LastView::instance()->update('order_status_change_history', $params);

    $default_params = [
        'page'           => 1,
        'items_per_page' => $items_per_page,
        'limit'          => 0
    ];

    $params = array_merge($default_params, $params);

    $sortings = [
        'timestamp' => ['?:order_status_change_history.timestamp', '?:order_status_change_history.log_id'],
        'user'      => ['?:users.lastname', '?:users.firstname'],
    ];

    $fields = [
        '?:order_status_change_history.*',
        '?:users.firstname',
        '?:users.lastname'
    ];

    $condition = '';

    $sorting = db_sort($params, $sortings, 'timestamp', 'desc');

    $join = "LEFT JOIN ?:users USING(user_id)";

    $limit = '';

    if (!empty($params['limit'])) {
        $limit = db_quote('LIMIT 0, ?i', $params['limit']);
    } elseif (!empty($params['items_per_page'])) {
        $params['total_items'] = db_get_field("SELECT COUNT(DISTINCT(?:order_status_change_history.log_id)) FROM ?:order_status_change_history ?p WHERE 1 ?p", $join, $condition);
        $limit = db_paginate($params['page'], $params['items_per_page'], $params['total_items']);
    }

    $data = db_get_array("SELECT " . join(', ', $fields) . " FROM ?:order_status_change_history ?p WHERE 1 ?p $sorting $limit", $join, $condition);



    return [$data, $params];

}
