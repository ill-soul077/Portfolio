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
        'achievements' => [],
        'academic_highlights' => [],
        'interests' => []
    ];
    
    // Get basic information (updated for new structure)
    $stmt = $conn->prepare("SELECT * FROM portfolio_basics WHERE is_active = 1 LIMIT 1");
    $stmt->execute();
    $basics = $stmt->fetch();
    
    if ($basics) {
        // Get social profiles
        $stmt = $conn->prepare("SELECT * FROM social_profiles WHERE is_active = 1 ORDER BY display_order");
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
            'image' => $basics['image'], // Keep as is, path will be handled in frontend
            'summary' => $basics['summary'],
            'summarytr' => $basics['summary_tr'] ?? '',
            'email' => $basics['email'],
            'website' => $basics['website'],
            'location' => [
                'city' => $basics['city'],
                'countryCode' => $basics['country_code']
            ],
            'profiles' => $profilesData
        ];
    }
    
    // Get skills (updated for new structure)
    $stmt = $conn->prepare("SELECT * FROM skills WHERE is_active = 1 ORDER BY display_order, situation");
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
            'situation' => $skill['situation'], // Keep as 'situation' to match frontend
            'keywords' => $keywords,
            'level' => $skill['level'],
            'proficiency_percentage' => $skill['proficiency_percentage'] ?? 0,
            'years_experience' => $skill['years_experience'] ?? 0
        ];
    }
    
    // Get repositories/projects (updated for new structure)
    $stmt = $conn->prepare("SELECT * FROM repositories ORDER BY display_order, date DESC");
    $stmt->execute();
    $repositories = $stmt->fetchAll();
    
    foreach ($repositories as $repo) {
        $tags = [];
        if (!empty($repo['tags'])) {
            $decoded = json_decode($repo['tags'], true);
            if (is_array($decoded)) {
                $tags = $decoded;
            }
        }
        
        $response['repository'][] = [
            'name' => $repo['name'],
            'explanation' => $repo['explanation'],
            'tag' => $tags,
            'bestLang' => $repo['best_lang'],
            'date' => $repo['date'],
            'link' => $repo['link'],
            'viewLink' => $repo['view_link'] ?? $repo['demo_url'] ?? '',
            'github_url' => $repo['github_url'] ?? $repo['link']
        ];
    }
    
    // Get work experience (updated for new structure)
    $stmt = $conn->prepare("SELECT * FROM work_experience ORDER BY display_order, start_date DESC");
    $stmt->execute();
    $workExperiences = $stmt->fetchAll();
    
    foreach ($workExperiences as $work) {
        $technologies = [];
        if (!empty($work['technologies'])) {
            $decoded = json_decode($work['technologies'], true);
            if (is_array($decoded)) {
                $technologies = $decoded;
            }
        }
        
        $response['work'][] = [
            'position' => $work['position'],
            'name' => $work['company_name'],
            'startDate' => $work['start_date'],
            'endDate' => $work['end_date'],
            'location' => $work['location'],
            'summary' => $work['summary'],
            'technologies' => $technologies,
            'is_current' => $work['is_current'] ?? false
        ];
    }
    
    // Get education (updated for new structure)
    $stmt = $conn->prepare("SELECT * FROM education ORDER BY display_order, start_date DESC");
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
            'courses' => $courses,
            'location' => $edu['location'] ?? '',
            'is_current' => $edu['is_current'] ?? false
        ];
    }
    
    // Get achievements
    $stmt = $conn->prepare("SELECT * FROM achievements WHERE is_active = 1 ORDER BY display_order, year DESC");
    $stmt->execute();
    $achievements = $stmt->fetchAll();
    
    foreach ($achievements as $achievement) {
        $response['achievements'][] = [
            'title' => $achievement['title'],
            'year' => $achievement['year'],
            'description' => $achievement['description'],
            'category' => $achievement['category'],
            'icon' => $achievement['icon'],
            'color' => $achievement['color']
        ];
    }
    
    // Get academic highlights (updated for new structure)
    $stmt = $conn->prepare("SELECT * FROM academic_highlights ORDER BY display_order, created_at");
    $stmt->execute();
    $highlights = $stmt->fetchAll();
    
    foreach ($highlights as $highlight) {
        $response['academic_highlights'][] = $highlight['title'];
    }
    
    // Get interests (updated for new structure)
    $stmt = $conn->prepare("SELECT * FROM interests ORDER BY display_order, interest");
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
