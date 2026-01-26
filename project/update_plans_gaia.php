<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$gaiaPlans = [
    [
        'title' => 'ELEUTHIA',
        'price' => 0,
        'days' => 14,
        'allowed_products' => 10,
        'details' => "<ul>
            <li><i class='fas fa-check'></i> <strong>Cradle of Creativity</strong></li>
            <li><i class='fas fa-check'></i> 10 Product Limit</li>
            <li><i class='fas fa-check'></i> Standard Design Tools</li>
            <li><i class='fas fa-check'></i> Basic Analytics</li>
        </ul>"
    ],
    [
        'title' => 'DEMETER',
        'price' => 29,
        'days' => 30,
        'allowed_products' => 50,
        'details' => "<ul>
            <li><i class='fas fa-check'></i> <strong>Floral Growth</strong></li>
            <li><i class='fas fa-check'></i> 50 Product Limit</li>
            <li><i class='fas fa-check'></i> Advanced Templates</li>
            <li><i class='fas fa-check'></i> Priority Support</li>
        </ul>"
    ],
    [
        'title' => 'HEPHAESTUS',
        'price' => 79,
        'days' => 30,
        'allowed_products' => 200,
        'details' => "<ul>
            <li><i class='fas fa-check'></i> <strong>The Forge</strong></li>
            <li><i class='fas fa-check'></i> 200 Product Limit</li>
            <li><i class='fas fa-check'></i> Auto-Manufacturing Access</li>
            <li><i class='fas fa-check'></i> Zero Commission</li>
        </ul>"
    ],
    [
        'title' => 'GAIA PRIME',
        'price' => 299,
        'days' => 365,
        'allowed_products' => 0,
        'details' => "<ul>
            <li><i class='fas fa-check'></i> <strong>Governing Intelligence</strong></li>
            <li><i class='fas fa-check'></i> Unlimited Products</li>
            <li><i class='fas fa-check'></i> Global Ecosystem Access</li>
            <li><i class='fas fa-check'></i> Dedicated Focus Manager</li>
        </ul>"
    ]
];

$subs = App\Models\Subscription::all();

$i = 0;
foreach($subs as $sub) {
    if(isset($gaiaPlans[$i])) {
        $sub->update($gaiaPlans[$i]);
        echo "Updated Plan: " . $gaiaPlans[$i]['title'] . "\n";
    }
    $i++;
}
