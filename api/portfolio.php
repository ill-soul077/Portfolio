<?php
// Set headers first
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Disable error display to prevent HTML output
ini_set('display_errors', 0);
error_reporting(E_ALL);

try {
    // Try different paths for database config
    $configPaths = [
        '../config/database.php',
        __DIR__ . '/../config/database.php',
        dirname(__FILE__) . '/../config/database.php'
    ];
    
    $configLoaded = false;
    foreach ($configPaths as $path) {
        if (file_exists($path)) {
            require_once $path;
            $configLoaded = true;
            break;
        }
    }
    
    if (!$configLoaded) {
        throw new Exception('Database configuration file not found');
    }

    $db = new Database();
    $conn = $db->getConnection();
    
    // Initialize response array
    $response = [
        'meta' => ['theme' => 'actual'],
        'basics' => [],
        'skills' => [],
        'repository' => [],
        'work' => [],
        'education' => [],
        'academic_highlights' => [],
        'interests' => []
    ];
    
    // Get basic information
    $stmt = $conn->prepare("SELECT * FROM portfolio_basics LIMIT 1");
    $stmt->execute();
    $basics = $stmt->fetch();
    
    if ($basics) {
        // Get social profiles
        $stmt = $conn->prepare("SELECT * FROM social_profiles");
        $stmt->execute();
        $profiles = $stmt->fetchAll();
        
        $profilesData = [];
        foreach ($profiles as $profile) {
            $profilesData[$profile['platform']] = [
                'username' => $profile['username'],
                'url' => $profile['url']
            ];
        }
        
        $response['basics'] = [
            'name' => $basics['name'],
            'label' => $basics['label'],
            'image' => 'assets/images/' . $basics['image'],
            'summary' => $basics['summary'],
            'email' => $basics['email'],
            'website' => $basics['website'],
            'location' => [
                'city' => $basics['city'],
                'countryCode' => $basics['country_code']
            ],
            'profiles' => $profilesData
        ];
    }
    
    // Get skills
    $stmt = $conn->prepare("SELECT * FROM skills ORDER BY created_at");
    $stmt->execute();
    $skills = $stmt->fetchAll();
    
    foreach ($skills as $skill) {
        $keywords = [];
        if (!empty($skill['keywords'])) {
            $decoded = json_decode($skill['keywords'], true);
            if (is_array($decoded)) {
                $keywords = $decoded;
            } else {
                // If not valid JSON, try to split by comma
                $keywords = array_map('trim', explode(',', $skill['keywords']));
            }
        }
        
        $response['skills'][] = [
            'name' => $skill['situation'], // Changed to 'name' for consistency
            'keywords' => $keywords,
            'level' => $skill['level']
        ];
    }
    
    // Get repositories/projects
    $stmt = $conn->prepare("SELECT * FROM repositories ORDER BY date DESC");
    $stmt->execute();
    $repositories = $stmt->fetchAll();
    
    foreach ($repositories as $repo) {
        $response['repository'][] = [
            'name' => $repo['name'],
            'explanation' => $repo['explanation'],
            'tag' => json_decode($repo['tags'], true) ?: [],
            'bestLang' => $repo['best_lang'],
            'date' => $repo['date'],
            'link' => $repo['link'],
            'viewLink' => $repo['view_link']
        ];
    }
    
    // Get work experience
    $stmt = $conn->prepare("SELECT * FROM work_experience ORDER BY start_date DESC");
    $stmt->execute();
    $workExperiences = $stmt->fetchAll();
    
    foreach ($workExperiences as $work) {
        $response['work'][] = [
            'position' => $work['position'],
            'name' => $work['company_name'],
            'startDate' => $work['start_date'],
            'endDate' => $work['end_date'],
            'location' => $work['location'],
            'summary' => $work['summary']
        ];
    }
    
    // Get education
    $stmt = $conn->prepare("SELECT * FROM education ORDER BY start_date DESC");
    $stmt->execute();
    $educations = $stmt->fetchAll();
    
    foreach ($educations as $edu) {
        $courses = [];
        if (!empty($edu['achievement'])) {
            $courses[] = $edu['achievement'];
        }
        
        $response['education'][] = [
            'institution' => $edu['institution'],
            'area' => $edu['area'],
            'studyType' => $edu['study_type'],
            'startDate' => $edu['start_date'],
            'endDate' => $edu['end_date'],
            'gpa' => $edu['gpa'],
            'courses' => $courses
        ];
    }
    
    // Get academic highlights
    $stmt = $conn->prepare("SELECT * FROM academic_highlights ORDER BY created_at");
    $stmt->execute();
    $highlights = $stmt->fetchAll();
    
    foreach ($highlights as $highlight) {
        $response['academic_highlights'][] = $highlight['title'];
    }
    
    // Get interests
    $stmt = $conn->prepare("SELECT * FROM interests ORDER BY created_at");
    $stmt->execute();
    $interests = $stmt->fetchAll();
    
    foreach ($interests as $interest) {
        $response['interests'][] = $interest['interest'];
    }
    
    // Return JSON response
    echo json_encode($response, JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch portfolio data: ' . $e->getMessage()]);
}
?>
