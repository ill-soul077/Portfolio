<?php
require_once '../config/database.php';

$db = new Database();
$conn = $db->getConnection();

echo "<h2>Skills Database Test</h2>";

// Get all skills
$stmt = $conn->prepare("SELECT id, situation, keywords, level, created_at FROM skills ORDER BY created_at DESC");
$stmt->execute();
$skills = $stmt->fetchAll();

echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>ID</th><th>Category</th><th>Keywords (Raw)</th><th>Keywords (Parsed)</th><th>Level</th><th>Created</th></tr>";

foreach ($skills as $skill) {
    echo "<tr>";
    echo "<td>" . $skill['id'] . "</td>";
    echo "<td>" . htmlspecialchars($skill['situation']) . "</td>";
    echo "<td>" . htmlspecialchars($skill['keywords']) . "</td>";
    
    $keywords = json_decode($skill['keywords'], true);
    echo "<td>";
    if (is_array($keywords)) {
        echo implode(', ', $keywords) . " (" . count($keywords) . " items)";
    } else {
        echo "Invalid JSON";
    }
    echo "</td>";
    
    echo "<td>" . htmlspecialchars($skill['level']) . "</td>";
    echo "<td>" . $skill['created_at'] . "</td>";
    echo "</tr>";
}

echo "</table>";

// Test API output
echo "<h2>API Output Test</h2>";
echo "<h3>Skills from API:</h3>";

$stmt = $conn->prepare("SELECT * FROM skills ORDER BY created_at");
$stmt->execute();
$skills = $stmt->fetchAll();

$apiSkills = [];
foreach ($skills as $skill) {
    $keywords = [];
    if (!empty($skill['keywords'])) {
        $decoded = json_decode($skill['keywords'], true);
        if (is_array($decoded)) {
            $keywords = $decoded;
        } else {
            $keywords = array_map('trim', explode(',', $skill['keywords']));
        }
    }
    
    $apiSkills[] = [
        'name' => $skill['situation'],
        'keywords' => $keywords,
        'level' => $skill['level']
    ];
}

echo "<pre>" . json_encode($apiSkills, JSON_PRETTY_PRINT) . "</pre>";
?>
