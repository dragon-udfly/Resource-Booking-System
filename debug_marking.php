<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\MarkingScheme;
use App\Models\QuarterApplication;
use Carbon\Carbon;

echo "--- Debugging Marking Scheme ---\n";
$schemes = MarkingScheme::all();
foreach ($schemes as $s) {
    echo "{$s->marking_option}: {$s->defined_mark}\n";
}

echo "\n--- Debugging Date Calc ---\n";
$created = Carbon::now()->subYears(2);
$now = Carbon::now();
$diff = $created->diffInYears($now);
echo "Created: {$created}\n";
echo "Now: {$now}\n";
echo "Diff in Years: {$diff}\n";
