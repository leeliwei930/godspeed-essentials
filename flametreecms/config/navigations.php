<?php


return [
    'flametreecms' => [
        'label' => 'GodSpeed CMS',
        'url' => Backend::url('godspeed/flametreecms/producers'),
        'icon' => 'icon-window-restore',
        'order' => 500,
        "sideMenu" => [
            "producers" => [
                "label" => "Producers",
                "icon" => 'icon-address-book',
                "url" => Backend::url("godspeed/flametreecms/producers"),
                'permissions' => [
                    'godspeed.flametreecms.browse_producers'
                ]
            ],
            "products" => [
                "label" => "Products",
                "icon" => 'icon-shopping-bag',
                "url" => Backend::url("godspeed/flametreecms/products"),
                'permissions' => [
                    'godspeed.flametreecms.browse_products'
                ]
            ],
            "specialorders" => [
                'label' => 'Special Orders',
                'icon' => 'icon-inbox',
                'url' => Backend::url('godspeed/flametreecms/specialorders'),
                'permissions' => [
                    'godspeed.flametreecms.browse_special_orders'
                ]
            ],
            "referrals" => [
                'label' => "Referrals",
                'icon' => 'icon-ticket',
                'url' => Backend::url('godspeed/flametreecms/referrals'),
                'permissions' => [
                    'godspeed.flametreecms.browse_referrals'
                ]

            ],
            "events" => [
                'label' => "Events",
                'icon' => 'icon-calendar',
                'url' => Backend::url('godspeed/flametreecms/events'),
                'permissions' => [
                    'godspeed.flametreecms.browse_events'
                ]
            ],
            'qrcodes' => [
                'label' => "QR Codes",
                'icon' => 'icon-qrcode',
                'url' => Backend::url('godspeed/flametreecms/qrcodes'),
                'permissions' => [
                    'godspeed.flametreecms.browse_qrcodes'
                ]
            ],
            "trainings" => [
                'label' => "Trainings",
                'icon' => 'icon-rocket',
                'url' => Backend::url('godspeed/flametreecms/trainings'),
                'permissions' => [
                    'godspeed.flametreecms.browse_trainings'
                ]
            ],
            "videos" => [
                'label' => "Videos",
                'icon' => 'icon-film',
                'url' => Backend::url('godspeed/flametreecms/videos'),
                'permissions' => [
                    'godspeed.flametreecms.browse_videos'
                ]
            ],
            'faqs' => [
                "label" => "Faqs",
                "icon" => "icon-question-circle",
                "url" => Backend::url("godspeed/flametreecms/faqs"),
                'permissions' => [
                    'godspeed.flametreecms.browse_faqs'
                ]
            ],
            "imagesliders" => [
                "label" => "Carousel/Image Slider",
                "icon" => 'icon-picture-o',
                "url" => Backend::url("godspeed/flametreecms/imagesliders"),
                'permissions' => [
                    'godspeed.flametreecms.browse_image_sliders'
                ]
            ],

        ]
    ],
];
