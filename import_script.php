<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\ClassAttribute;

// Extract unique values from existing classes
$types = [
    'category'  => DB::table('martial_arts_classes')->whereNotNull('category')->distinct()->pluck('category')->filter()->values(),
    'type'      => DB::table('martial_arts_classes')->whereNotNull('type')->distinct()->pluck('type')->filter()->values(),
    'age_group' => DB::table('martial_arts_classes')->whereNotNull('age_group')->distinct()->pluck('age_group')->filter()->values(),
    'format'    => DB::table('martial_arts_classes')->whereNotNull('format')->distinct()->pluck('format')->filter()->values(),
    'room'      => DB::table('martial_arts_classes')->whereNotNull('room')->where('room', '!=', '')->distinct()->pluck('room')->filter()->values(),
];

$total = 0;
foreach ($types as $type => $values) {
    foreach ($values as $value) {
        $value = trim($value);
        if (empty($value)) continue;

        ClassAttribute::firstOrCreate(
            ['type' => $type, 'name' => $value],
            ['status' => 'active']
        );
        $total++;
    }
}

echo "Done! Inserted $total class attributes.\n";

// Show counts per type
foreach ($types as $type => $values) {
    $count = ClassAttribute::where('type', $type)->count();
    echo "  $type: $count\n";
}
