<?php
session_start();
require_once '../config/database.php';
require_once 'auth_check.php';

$db = new Database();
$conn = $db->getConnection();

try {
    // Get basic information
    $basicInfo = $conn->query("SELECT * FROM portfolio_basics LIMIT 1")->fetch();
    
    // Get social profiles
    $socialProfiles = $conn->query("SELECT * FROM social_profiles")->fetchAll();
    $profiles = [];
    foreach ($socialProfiles as $profile) {
        $profiles[$profile['platform']] = [
            'username' => $profile['username'],
            'url' => $profile['url']
        ];
    }
    
    // Get skills
    $skills = $conn->query("SELECT * FROM skills ORDER BY id")->fetchAll();
    $skillsArray = [];
    foreach ($skills as $skill) {
        $skillsArray[] = [
            'situation' => $skill['situation'],
            'keywords' => json_decode($skill['keywords'], true),
            'level' => $skill['level'] ?? ''
        ];
    }
    
    // Get repositories
    $repositories = $conn->query("SELECT * FROM repositories ORDER BY date DESC")->fetchAll();
    $repoArray = [];
    foreach ($repositories as $repo) {
        $repoArray[] = [
            'name' => $repo['name'],
            'explanation' => $repo['explanation'],
            'tag' => json_decode($repo['tags'], true),
            'bestLang' => $repo['best_lang'],
            'date' => $repo['date'],
            'link' => $repo['link'],
            'viewLink' => $repo['view_link']
        ];
    }
    
    // Get work experience
    $work = $conn->query("SELECT * FROM work_experience ORDER BY start_date DESC")->fetchAll();
    $workArray = [];
    foreach ($work as $w) {
        $workArray[] = [
            'position' => $w['position'],
            'name' => $w['company_name'],
            'startDate' => $w['start_date'],
            'endDate' => $w['end_date'],
            'location' => $w['location'],
            'summary' => $w['summary']
        ];
    }
    
    // Get education
    $education = $conn->query("SELECT institution FROM education ORDER BY end_date DESC")->fetchAll();
    $educationArray = array_column($education, 'institution');
    
    // Get academic highlights
    $highlights = $conn->query("SELECT highlight FROM academic_highlights ORDER BY id")->fetchAll();
    $highlightsArray = array_column($highlights, 'highlight');
    
    // Get interests
    $interests = $conn->query("SELECT interest FROM interests ORDER BY id")->fetchAll();
    $interestsArray = array_column($interests, 'interest');
    
    // Build the JSON structure
    $resumeData = [
        'meta' => [
            'theme' => 'actual'
        ],
        'basics' => [
            'name' => $basicInfo['name'] ?? '',
            'label' => $basicInfo['label'] ?? '',
            'image' => $basicInfo['image'] ?? '',
            'summary' => $basicInfo['summary'] ?? '',
            'website' => $basicInfo['website'] ?? '#',
            'email' => $basicInfo['email'] ?? '',
            'location' => [
                'city' => $basicInfo['city'] ?? '',
                'countryCode' => $basicInfo['country_code'] ?? ''
            ],
            'profiles' => $profiles
        ],
        'repository' => $repoArray,
        'education' => $educationArray,
        'academic_highlights' => $highlightsArray,
        'skills' => $skillsArray,
        'work' => $workArray,
        'interests' => $interestsArray,
        'references' => [
            ['reference' => '', 'name' => ''],
            ['reference' => '', 'name' => ''],
            ['reference' => '', 'name' => ''],
            ['reference' => '', 'name' => '']
        ]
    ];
    
    // Generate JSON
    $jsonData = json_encode($resumeData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
    // Save to file
    file_put_contents('../resume.json', $jsonData);
    
    $success = "Resume JSON generated successfully!";
    
} catch (Exception $e) {
    $error = "Error generating resume: " . $e->getMessage();
}

// Redirect back to dashboard with message
if (isset($success)) {
    header('Location: dashboard.php?success=' . urlencode($success));
} else {
    header('Location: dashboard.php?error=' . urlencode($error));
}
exit();
?>
