<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== PRINTER & MANUFACTURING VIEWS ANALYSIS ===\n\n";

$viewsDir = __DIR__ . '/resources/views/admin/printer';
$files = scandir($viewsDir);

$viewAnalysis = [];

foreach ($files as $file) {
    if ($file == '.' || $file == '..') continue;
    
    $path = $viewsDir . '/' . $file;
    if (!is_file($path)) continue;
    
    $content = file_get_contents($path);
    $lines = substr_count($content, "\n") + 1;
    
    // Check for permission checks in the view
    $hasSectionCheck = strpos($content, 'sectionCheck') !== false;
    $hasIsSuper = strpos($content, 'IsSuper') !== false;
    
    // Check for forms/actions
    $hasForm = strpos($content, '<form') !== false;
    $hasButtons = substr_count($content, '<button');
    $hasLinks = substr_count($content, 'href=');
    
    $viewAnalysis[$file] = [
        'lines' => $lines,
        'has_section_check' => $hasSectionCheck,
        'has_is_super' => $hasIsSuper,
        'has_form' => $hasForm,
        'button_count' => $hasButtons,
        'link_count' => $hasLinks,
        'size' => filesize($path)
    ];
}

// Display analysis
echo "FILE BREAKDOWN:\n";
echo str_repeat("=", 80) . "\n";

foreach ($viewAnalysis as $file => $data) {
    echo "\n📄 {$file}\n";
    echo "   Lines: {$data['lines']}\n";
    echo "   Size: " . number_format($data['size']) . " bytes\n";
    echo "   Permission Checks: " . ($data['has_section_check'] ? '✅ Yes' : '❌ No') . "\n";
    echo "   Super Admin Check: " . ($data['has_is_super'] ? '✅ Yes' : '❌ No') . "\n";
    echo "   Has Forms: " . ($data['has_form'] ? '✅ Yes' : '❌ No') . "\n";
    echo "   Action Buttons: {$data['button_count']}\n";
    echo "   Links: {$data['link_count']}\n";
}

// Analyze what each role can see
echo "\n\n" . str_repeat("=", 80) . "\n";
echo "ROLE-BASED VIEW ACCESS:\n";
echo str_repeat("=", 80) . "\n\n";

$roles = [
    'printer' => ['print_production'],
    'printer all in one' => ['print_production', 'manufacturing'],
    'Manifacturing' => ['manufacturing']
];

foreach ($roles as $roleName => $sections) {
    echo "🔐 ROLE: {$roleName}\n";
    echo "   Sections: " . implode(', ', $sections) . "\n";
    
    // Check if can access printer routes
    $canAccessPrinter = in_array('print_production', $sections);
    echo "   Can Access Printer Views: " . ($canAccessPrinter ? '✅ YES' : '❌ NO') . "\n";
    
    if ($canAccessPrinter) {
        echo "   Available Views:\n";
        foreach ($viewAnalysis as $file => $data) {
            echo "      • {$file}\n";
        }
    }
    echo "\n";
}

file_put_contents(__DIR__ . '/VIEWS_ANALYSIS.txt', ob_get_contents());
