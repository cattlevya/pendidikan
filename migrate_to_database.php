<?php
require_once 'database.php';

echo "Starting migration from JSON files to MySQL database...\n";

try {
    $db = getDB();
    
    // Migrate photos data
    if (file_exists('photos_data.json')) {
        echo "Migrating photos data...\n";
        $photos = json_decode(file_get_contents('photos_data.json'), true) ?? [];
        
        foreach ($photos as $photo) {
            $db->addPhoto(
                $photo['filename'],
                $photo['caption'] ?? '',
                null // uploaded_by will be null for existing photos
            );
        }
        
        echo "Migrated " . count($photos) . " photos\n";
        
        // Backup the original JSON file
        rename('photos_data.json', 'photos_data.json.backup');
        echo "Backed up photos_data.json to photos_data.json.backup\n";
    }
    
    // Migrate confession response data
    if (file_exists('confession_response.json')) {
        echo "Migrating confession response data...\n";
        $response = json_decode(file_get_contents('confession_response.json'), true);
        
        if ($response) {
            $db->saveConfessionResponse(
                $response['response'],
                $response['username'] ?? 'unknown'
            );
        }
        
        echo "Migrated confession response\n";
        
        // Backup the original JSON file
        rename('confession_response.json', 'confession_response.json.backup');
        echo "Backed up confession_response.json to confession_response.json.backup\n";
    }
    
    echo "Migration completed successfully!\n";
    
} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
?>