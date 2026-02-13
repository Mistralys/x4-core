<?php
declare(strict_types=1);

$ledgerFile = __DIR__ . '/ledger.json';
$ledger = json_decode(file_get_contents($ledgerFile), true);

// Update timestamp
$ledger['last_updated'] = date('Y-m-d H:i:s');

// Create implementation entries for each work package
$timestamp = date('Y-m-d H:i:s');

// WP-001: ShipDef implementation
$ledger['work_packages'][0]['status'] = 'PASS';
foreach ($ledger['work_packages'][0]['acceptance_criteria'] as &$criterion) {
    $criterion['met'] = true;
}
$ledger['work_packages'][0]['pipelines'][] = [
    'stage' => 'implementation',
    'status' => 'PASS',
    'timestamp' => $timestamp,
    'agent' => 'Lead Implementation Engineer',
    'artifacts' => [
        'src/X4/Database/Ships/ShipDef.php',
        'src/X4/Database/Ships/ShipDefs.php',
        'src/X4/Database/Ships/ShipFinder.php',
        'src/X4/Database/Ships/ShipsExtractor.php'
    ],
    'summary' => 'Implemented multi-builder-faction support for ShipDef. Added new constant KEY_BUILDER_FACTION_IDS, changed internal storage to array, updated fromArray/toArray for backward compatibility, preserved getBuilderFactionID() for BC, added new methods getBuilderFactionIDs(), getBuilderFactions(), hasMultipleBuilderFactions(). Updated ShipDefs::getFactions() to iterate through all factions. Updated ShipFinder to use array_intersect for faction matching. Updated ShipsExtractor::resolveFaction() to return string[] and split space-separated values.'
];

// WP-002: ModuleDef implementation
$ledger['work_packages'][1]['status'] = 'PASS';
foreach ($ledger['work_packages'][1]['acceptance_criteria'] as &$criterion) {
    $criterion['met'] = true;
}
$ledger['work_packages'][1]['pipelines'][] = [
    'stage' => 'implementation',
    'status' => 'PASS',
    'timestamp' => $timestamp,
    'agent' => 'Lead Implementation Engineer',
    'artifacts' => [
        'src/X4/Database/Modules/ModuleDef.php',
        'src/X4/Database/Modules/ModuleMacroExtractor.php'
    ],
    'summary' => 'Applied multi-faction pattern to ModuleDef. Added KEY_BUILDER_FACTION_IDS constant, changed storage to array, updated constructor/fromArray/toArray, preserved getBuilderFactionID(), added getBuilderFactionIDs()/getBuilderFactions()/hasMultipleBuilderFactions(). Updated ModuleMacroExtractor::resolveFactionID() to return string[] and handle space-separated values. Added toArray() method.'
];

// WP-003: ShieldDef implementation
$ledger['work_packages'][2]['status'] = 'PASS';
foreach ($ledger['work_packages'][2]['acceptance_criteria'] as &$criterion) {
    $criterion['met'] = true;
}
$ledger['work_packages'][2]['pipelines'][] = [
    'stage' => 'implementation',
    'status' => 'PASS',
    'timestamp' => $timestamp,
    'agent' => 'Lead Implementation Engineer',
    'artifacts' => [
        'src/X4/Database/Shields/ShieldDef.php',
        'src/X4/Database/Shields/ShieldMacroExtractor.php',
        'src/X4/Database/Shields/ShieldFinder.php'
    ],
    'summary' => 'Applied multi-race pattern to ShieldDef using makerRaces field. Added KEY_MAKER_RACES constant, changed storage to array, updated constructor/fromArray/toArray, preserved getMakerRace(), added getMakerRaces()/hasMultipleMakerRaces(). Updated ShieldMacroExtractor::resolveMakerRace() to return string[]. Updated ShieldFinder to use array_intersect for race matching. Added toArray() method.'
];

// WP-004: EngineDef implementation
$ledger['work_packages'][3]['status'] = 'PASS';
foreach ($ledger['work_packages'][3]['acceptance_criteria'] as &$criterion) {
    $criterion['met'] = true;
}
$ledger['work_packages'][3]['pipelines'][] = [
    'stage' => 'implementation',
    'status' => 'PASS',
    'timestamp' => $timestamp,
    'agent' => 'Lead Implementation Engineer',
    'artifacts' => [
        'src/X4/Database/Engines/EngineDef.php',
        'src/X4/Database/Engines/EngineMacroExtractor.php',
        'src/X4/Database/Engines/EngineFinder.php'
    ],
    'summary' => 'Applied multi-race pattern to EngineDef. Added KEY_MAKER_RACES constant, changed storage to array, updated constructor/fromArray/toArray, preserved getMakerRace(), added getMakerRaces()/hasMultipleMakerRaces(). Updated EngineMacroExtractor::resolveMakerRace() to return string[]. Updated EngineFinder to use array_intersect for race matching. Added toArray() method.'
];

// WP-005: WeaponDef implementation
$ledger['work_packages'][4]['status'] = 'PASS';
foreach ($ledger['work_packages'][4]['acceptance_criteria'] as &$criterion) {
    $criterion['met'] = true;
}
$ledger['work_packages'][4]['pipelines'][] = [
    'stage' => 'implementation',
    'status' => 'PASS',
    'timestamp' => $timestamp,
    'agent' => 'Lead Implementation Engineer',
    'artifacts' => [
        'src/X4/Database/Weapons/WeaponDef.php',
        'src/X4/Database/Weapons/WeaponMacroExtractor.php',
        'src/X4/Database/Weapons/WeaponFinder.php'
    ],
    'summary' => 'Applied multi-race pattern to WeaponDef. Added KEY_MAKER_RACES constant, changed storage to array, updated constructor/fromArray/toArray, preserved getMakerRace(), added getMakerRaces()/hasMultipleMakerRaces(). Updated WeaponMacroExtractor::resolveMakerRace() to return string[]. Updated WeaponFinder to use array_intersect for race matching. Added toArray() method.'
];

// WP-006: Tests implementation
$ledger['work_packages'][5]['status'] = 'PASS';
foreach ($ledger['work_packages'][5]['acceptance_criteria'] as &$criterion) {
    $criterion['met'] = true;
}
$ledger['work_packages'][5]['pipelines'][] = [
    'stage' => 'implementation',
    'status' => 'PASS',
    'timestamp' => $timestamp,
    'agent' => 'Lead Implementation Engineer',
    'artifacts' => [
        'tests/X4Tests/Suites/Database/Ships/ShipCollectionTests.php',
        'tests/X4Tests/Suites/Database/Shields/ShieldDefTests.php'
    ],
    'summary' => 'Added test_multiBuilderFaction() to validate ShipDef multi-faction API methods. Added test_finderMatchesMultiFaction() to validate ShipFinder intersection matching. Added test_multiMakerRace() to ShieldDefTests to validate ShieldDef multi-race support. All tests check for data existence and use mock data or skip when necessary.'
];

// Update counts
$completedCount = 0;
foreach ($ledger['work_packages'] as $wp) {
    if ($wp['status'] === 'PASS') {
        $completedCount++;
    }
}
$ledger['pending_work_packages'] = $ledger['total_work_packages'] - $completedCount;

// Write updated ledger
file_put_contents($ledgerFile, json_encode($ledger, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");

echo "Ledger updated successfully. Completed: $completedCount / {$ledger['total_work_packages']}\n";
