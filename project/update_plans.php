<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$horizonPlans = [
    [
        'title' => 'Nora Brave',
        'price' => 0,
        'days' => 14,
        'allowed_products' => 10,
        'details' => "<ul>
            <li><i class='fas fa-check'></i> 10 Product Uploads</li>
            <li><i class='fas fa-check'></i> Basic Design Tools</li>
            <li><i class='fas fa-check'></i> Standard Support</li>
            <li><i class='fas fa-check'></i> 14 Days Trial</li>
        </ul>"
    ],
    [
        'title' => 'Carja Merchant',
        'price' => 29,
        'days' => 30,
        'allowed_products' => 50,
        'details' => "<ul>
            <li><i class='fas fa-check'></i> 50 Product Uploads</li>
            <li><i class='fas fa-check'></i> Advanced Editor Access</li>
            <li><i class='fas fa-check'></i> Meridian Market Analytics</li>
            <li><i class='fas fa-check'></i> Priority Support</li>
        </ul>"
    ],
    [
        'title' => 'Tenakth Conqueror',
        'price' => 79,
        'days' => 30,
        'allowed_products' => 200,
        'details' => "<ul>
            <li><i class='fas fa-check'></i> 200 Product Uploads</li>
            <li><i class='fas fa-check'></i> Auto-Sync to All Platforms</li>
            <li><i class='fas fa-check'></i> Clan Promotions</li>
            <li><i class='fas fa-check'></i> Zero-Commission Sales</li>
        </ul>"
    ],
    [
        'title' => 'Zenith Ascendant',
        'price' => 299,
        'days' => 365,
        'allowed_products' => 0,
        'details' => "<ul>
            <li><i class='fas fa-check'></i> Unlimited Uploads</li>
            <li><i class='fas fa-check'></i> Orbital Fabrication Access</li>
            <li><i class='fas fa-check'></i> Dedicated Focus Manager</li>
            <li><i class='fas fa-check'></i> AI-Powered Trends</li>
        </ul>"
    ]
];

$subs = App\Models\Subscription::all();

if($subs->count() < count($horizonPlans)) {
    // Create missing plans
    $needed = count($horizonPlans) - $subs->count();
    for($i=0; $i<$needed; $i++) {
        App\Models\Subscription::create([
            'title' => 'Temp',
            'price' => 0,
            'days' => 0,
            'allowed_products' => 0,
            'details' => '',
            'currency' => '$',
            'currency_code' => 'USD'
        ]);
    }
    $subs = App\Models\Subscription::all();
}

$i = 0;
foreach($subs as $sub) {
    if(isset($horizonPlans[$i])) {
        $sub->update($horizonPlans[$i]);
        echo "Updated Plan: " . $horizonPlans[$i]['title'] . "\n";
    }
    $i++;
}
