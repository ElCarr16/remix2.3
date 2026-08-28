<?php

return [
    // Parent menu: "Refund Requests" di bawah grup Sales
    [
        'key'   => 'sales.refund-requests',
        'name'  => 'Refund Requests',
        'route' => 'remix.admin.refund-requests.index',
        'sort'  => 7,
    ],
    [
        'key'   => 'sales.refund-requests.view',
        'name'  => 'View',
        'route' => 'remix.admin.refund-requests.index',
        'sort'  => 1,
    ],
    [
        'key'   => 'sales.refund-requests.approve',
        'name'  => 'Approve / Reject',
        'route' => 'remix.admin.refund-requests.approve',
        'sort'  => 2,
    ],

    // Menu terpisah di grup Settings: kelola daftar alasan refund
    [
        'key'   => 'settings.refund-reasons',
        'name'  => 'Refund Reasons',
        'route' => 'remix.admin.refund-reasons.index',
        'sort'  => 20,
    ],
];
