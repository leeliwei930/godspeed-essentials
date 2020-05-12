<?php


return [
    'essentials' => [
        'label' => 'GodSpeed Essential',
        'url' => Backend::url('godspeed/essentials/producers'),
        'iconSvg' => "plugins/godspeed/essentials/assets/5x/godspeed@5x.png",
        'order' => 500,
        "sideMenu" => [
            "producers" => [
                "label" => "Producers",
                "icon" => 'icon-address-book',
                "url" => Backend::url("godspeed/essentials/producers"),
                'permissions' => [
                    'godspeed.essentials.browse_producers'
                ]
            ],
            "products" => [
                "label" => "Products",
                "icon" => 'icon-shopping-bag',
                "url" => Backend::url("godspeed/essentials/products"),
                'permissions' => [
                    'godspeed.essentials.browse_products'
                ]
            ],
            "referrals" => [
                'label' => "Referrals",
                'icon' => 'icon-ticket',
                'url' => Backend::url('godspeed/essentials/referrals'),
                'permissions' => [
                    'godspeed.essentials.browse_referrals'
                ]

            ],
            "events" => [
                'label' => "Events",
                'icon' => 'icon-calendar',
                'url' => Backend::url('godspeed/essentials/events'),
                'permissions' => [
                    'godspeed.essentials.browse_events'
                ]
            ],
            'qrcodes' => [
                'label' => "QR Codes",
                'icon' => 'icon-qrcode',
                'url' => Backend::url('godspeed/essentials/qrcodes'),
                'permissions' => [
                    'godspeed.essentials.browse_qrcodes'
                ]
            ],
            "trainings" => [
                'label' => "Trainings",
                'icon' => 'icon-rocket',
                'url' => Backend::url('godspeed/essentials/trainings'),
                'permissions' => [
                    'godspeed.essentials.browse_trainings'
                ]
            ],
            "videos" => [
                'label' => "Videos",
                'icon' => 'icon-film',
                'url' => Backend::url('godspeed/essentials/videos'),
                'permissions' => [
                    'godspeed.essentials.browse_videos'
                ]
            ],
            'faqs' => [
                "label" => "Faqs",
                "icon" => "icon-question-circle",
                "url" => Backend::url("godspeed/essentials/faqs"),
                'permissions' => [
                    'godspeed.essentials.browse_faqs'
                ]
            ],
            "imagesliders" => [
                "label" => "Carousel/Image Slider",
                "icon" => 'icon-picture-o',
                "url" => Backend::url("godspeed/essentials/imagesliders"),
                'permissions' => [
                    'godspeed.essentials.browse_image_sliders'
                ]
            ],

        ]
    ],
];
