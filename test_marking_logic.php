<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\QuarterApplication;
use App\Models\FamilyQuarterApplication;
use App\Models\MarkingFamilyQuarter;
use App\Services\MarkingCalculatorService;
use Carbon\Carbon;
use Illuminate\Support\Str;

// Create dummy data for testing
try {
    echo "--- Starting Marking Calculation Test ---\n";


    // 1. Create a Quarter Application (2 years ago)
    $qAppId = 'TEST_QA_' . Str::random(5);
    $qApp = QuarterApplication::create([
        'application_id' => $qAppId,
        'quarter_type' => 'Family',
        'date_created' => Carbon::now()->subYears(2), // 2 Years waiting
        'officer_name' => 'Test Officer',
    ]);
    echo "Created Quarter Application: {$qAppId} (2 years old)\n";

    // 2. Create Family Quarter Application
    $fAppId = 'TEST_FQA_' . Str::random(5);
    $fApp = FamilyQuarterApplication::create([
        'f_application_id' => $fAppId,
        'application_id' => $qAppId,
    ]);

    // 3. Create Marking Record with specific criteria
    // Department: Ministry (20), Dependants: 3 (6), Distance: >100km (25)
    MarkingFamilyQuarter::create([
        'f_application_id' => $fAppId,
        'f_department' => 'Officers_attached_under_the_Ministry_of_Home_Affairs', // 20
        'f_number_of_dependant' => '03_person', // 9 (New Scheme)
        'f_distance_of_residency' => 'Out_District_above_100km', // 30 (New Scheme)
        'is_dependant_with_disability' => true, // 3 (New Scheme)
    ]);

    echo "Created Marking Record with attributes:\n";
    echo "- Department: Ministry (Expect 20)\n";
    echo "- Dependants: 3 (Expect 9) [Tier: 03 persons]\n";
    echo "- Distance: >100km (Expect 30) [Tier: >100km]\n";
    echo "- Disability: Yes (Expect 3) [Bonus]\n";
    echo "- Seniority: 2 Years (Expect 6) [Tier: 02 years]\n";
    echo "---------------------------------\n";
    echo "Total Expected Score: 68\n";

    // 4. Run Calculation
    $service = new MarkingCalculatorService();
    $score = $service->calculateScore($fApp);

    echo "Calculated Score: {$score}\n";

    if (abs($score - 68) < 0.001) {
        echo "✅ TEST PASSED: Score matches expected value.\n";
    } else {
        echo "❌ TEST FAILED: Score mismatch.\n";
    }

    // Cleanup
    $fApp->markingFamilyQuarter()->delete();
    $fApp->delete();
    $qApp->delete();

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
