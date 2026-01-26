<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$relicPlans = [
    [
        'title' => 'Initiate Signal',
        'price' => 0,
        'days' => 14,
        'allowed_products' => 10,
        'currency' => 'DT',
        'currency_code' => 'TND',
        'details' => "<ul>
            <li><i class='fas fa-check'></i> <strong>Theme: First Connection</strong></li>
            <li><i class='fas fa-check'></i> 10 Product Limit</li>
            <li><i class='fas fa-check'></i> Basic Design Tools</li>
            <li><i class='fas fa-check'></i> Standard Support</li>
        </ul>"
    ],
    [
        'title' => 'Merchant Relay',
        'price' => 29,
        'days' => 30,
        'allowed_products' => 50,
        'currency' => 'DT',
        'currency_code' => 'TND',
        'details' => "<ul>
            <li><i class='fas fa-check'></i> <strong>Theme: Trade Network</strong></li>
            <li><i class='fas fa-check'></i> 50 Product Limit</li>
            <li><i class='fas fa-check'></i> Relay Analytics (Views/Clicks)</li>
            <li><i class='fas fa-check'></i> Advanced Editor Access</li>
            <li><i class='fas fa-check'></i> Priority Support</li>
        </ul>"
    ],
    [
        'title' => 'Vanguard Sync',
        'price' => 79,
        'days' => 30,
        'allowed_products' => 200,
        'currency' => 'DT',
        'currency_code' => 'TND',
        'details' => "<ul>
            <li><i class='fas fa-check'></i> <strong>Theme: Full Synchronization</strong></li>
            <li><i class='fas fa-check'></i> 200 Product Limit</li>
            <li><i class='fas fa-check'></i> Auto-Sync Channels</li>
            <li><i class='fas fa-check'></i> Vanguard Boosts</li>
            <li><i class='fas fa-check'></i> <strong>0% Marketplace Commission</strong></li>
        </ul>"
    ],
    [
        'title' => 'Vanguard Sync — Weekly Pass',
        'price' => 25,
        'days' => 7,
        'allowed_products' => 60,
        'currency' => 'DT',
        'currency_code' => 'TND',
        'details' => "<ul>
            <li><i class='fas fa-check'></i> <strong>Theme: Weekly Burst</strong></li>
            <li><i class='fas fa-check'></i> 7 Day Duration</li>
            <li><i class='fas fa-check'></i> 60 Product Limit (Weekly Cap)</li>
            <li><i class='fas fa-check'></i> Full Pro Features</li>
            <li><i class='fas fa-check'></i> Upgrade Creditable to Monthly</li>
        </ul>"
    ],
    [
        'title' => 'Ascendant Core',
        'price' => 599,
        'days' => 365,
        'allowed_products' => 0, // Unlimited
        'currency' => 'DT',
        'currency_code' => 'TND',
        'details' => "<ul>
            <li><i class='fas fa-check'></i> <strong>Theme: Core Access</strong></li>
            <li><i class='fas fa-check'></i> Unlimited Products</li>
            <li><i class='fas fa-check'></i> Priority Manufacturing Queue</li>
            <li><i class='fas fa-check'></i> Dedicated Account Manager</li>
            <li><i class='fas fa-check'></i> AI-Powered Trend Insights</li>
        </ul>"
    ]
];

// Verify we have enough rows
$subs = App\Models\Subscription::all();
$needed = count($relicPlans) - $subs->count();
if($needed > 0) {
    echo "Creating $needed new subscription rows...\n";
    for($i=0; $i<$needed; $i++) {
        App\Models\Subscription::create([
            'title' => 'Temp Plan',
            'price' => 0,
            'days' => 1,
            'allowed_products' => 0,
            'details' => '',
            'currency' => 'DT',
            'currency_code' => 'TND'
        ]);
    }
    // Refresh collection
    $subs = App\Models\Subscription::all();
}

$i = 0;
foreach($subs as $sub) {
    if(isset($relicPlans[$i])) {
        // We only update if we have a plan definition for this row
        $sub->update($relicPlans[$i]);
        echo "Updated Plan: " . $relicPlans[$i]['title'] . " (" . $relicPlans[$i]['price'] . " " . $relicPlans[$i]['currency'] . ")\n";
    } elseif ($i >= count($relicPlans)) {
        // Optional: Delete extra plans if any? 
        // For now, let's just mark them as inactive or rename them to avoid confusion
        // $sub->update(['title' => 'Legacy/Unused', 'price' => 9999]);
    }
    $i++;
}
