<?php
// Cloud Storage Handler untuk Vercel
// Support: AWS S3, Cloudinary, Supabase Storage

class CloudStorage {
    private $storage_type;
    private $config;
    
    public function __construct($type = 'cloudinary') {
        $this->storage_type = $type;
        $this->config = $this->getConfig();
    }
    
    private function getConfig() {
        switch ($this->storage_type) {
            case 'cloudinary':
                return [
                    'cloud_name' => $_ENV['CLOUDINARY_CLOUD_NAME'] ?? '',
                    'api_key' => $_ENV['CLOUDINARY_API_KEY'] ?? '',
                    'api_secret' => $_ENV['CLOUDINARY_API_SECRET'] ?? '',
                    'upload_preset' => $_ENV['CLOUDINARY_UPLOAD_PRESET'] ?? 'romantic_web'
                ];
            case 'supabase':
                return [
                    'url' => $_ENV['SUPABASE_URL'] ?? '',
                    'key' => $_ENV['SUPABASE_ANON_KEY'] ?? '',
                    'bucket' => $_ENV['SUPABASE_BUCKET'] ?? 'photos'
                ];
            case 's3':
                return [
                    'bucket' => $_ENV['AWS_S3_BUCKET'] ?? '',
                    'region' => $_ENV['AWS_REGION'] ?? 'us-east-1',
                    'access_key' => $_ENV['AWS_ACCESS_KEY_ID'] ?? '',
                    'secret_key' => $_ENV['AWS_SECRET_ACCESS_KEY'] ?? ''
                ];
            default:
                return [];
        }
    }
    
    public function uploadFile($file, $folder = 'photos') {
        if (!$this->isConfigValid()) {
            return $this->fallbackToLocal($file);
        }
        
        switch ($this->storage_type) {
            case 'cloudinary':
                return $this->uploadToCloudinary($file, $folder);
            case 'supabase':
                return $this->uploadToSupabase($file, $folder);
            case 's3':
                return $this->uploadToS3($file, $folder);
            default:
                return $this->fallbackToLocal($file);
        }
    }
    
    private function isConfigValid() {
        switch ($this->storage_type) {
            case 'cloudinary':
                return !empty($this->config['cloud_name']) && 
                       !empty($this->config['api_key']) && 
                       !empty($this->config['api_secret']);
            case 'supabase':
                return !empty($this->config['url']) && 
                       !empty($this->config['key']);
            case 's3':
                return !empty($this->config['bucket']) && 
                       !empty($this->config['access_key']) && 
                       !empty($this->config['secret_key']);
            default:
                return false;
        }
    }
    
    private function uploadToCloudinary($file, $folder) {
        $cloud_name = $this->config['cloud_name'];
        $api_key = $this->config['api_key'];
        $api_secret = $this->config['api_secret'];
        $upload_preset = $this->config['upload_preset'];
        
        // Cloudinary upload URL
        $upload_url = "https://api.cloudinary.com/v1_1/{$cloud_name}/image/upload";
        
        // Prepare file data
        $file_data = file_get_contents($file['tmp_name']);
        $base64 = base64_encode($file_data);
        
        // Prepare form data
        $data = [
            'file' => 'data:' . $file['type'] . ';base64,' . $base64,
            'upload_preset' => $upload_preset,
            'folder' => $folder,
            'public_id' => time() . '_' . pathinfo($file['name'], PATHINFO_FILENAME)
        ];
        
        // Upload to Cloudinary
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $upload_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        $result = json_decode($response, true);
        
        if (isset($result['secure_url'])) {
            return [
                'success' => true,
                'url' => $result['secure_url'],
                'public_id' => $result['public_id'],
                'filename' => basename($result['secure_url'])
            ];
        }
        
        return ['success' => false, 'error' => 'Upload failed'];
    }
    
    private function uploadToSupabase($file, $folder) {
        $url = $this->config['url'];
        $key = $this->config['key'];
        $bucket = $this->config['bucket'];
        
        $filename = time() . '_' . basename($file['name']);
        $file_path = $folder . '/' . $filename;
        
        // Upload to Supabase Storage
        $upload_url = "{$url}/storage/v1/object/{$bucket}/{$file_path}";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $upload_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents($file['tmp_name']));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $key,
            'Content-Type: ' . $file['type']
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        $result = json_decode($response, true);
        
        if (isset($result['Key'])) {
            return [
                'success' => true,
                'url' => "{$url}/storage/v1/object/public/{$bucket}/{$file_path}",
                'filename' => $filename
            ];
        }
        
        return ['success' => false, 'error' => 'Upload failed'];
    }
    
    private function uploadToS3($file, $folder) {
        // AWS S3 upload implementation
        // Requires AWS SDK for PHP
        return ['success' => false, 'error' => 'S3 upload not implemented yet'];
    }
    
    private function fallbackToLocal($file) {
        // Fallback: save file info but not the actual file
        // This is just for development/testing
        $filename = time() . '_' . basename($file['name']);
        
        return [
            'success' => true,
            'url' => 'photos/' . $filename, // This won't work in production
            'filename' => $filename,
            'warning' => 'File not actually uploaded - Vercel limitation'
        ];
    }
    
    public function deleteFile($filename, $public_id = null) {
        switch ($this->storage_type) {
            case 'cloudinary':
                return $this->deleteFromCloudinary($public_id);
            case 'supabase':
                return $this->deleteFromSupabase($filename);
            default:
                return ['success' => true]; // Assume deleted
        }
    }
    
    private function deleteFromCloudinary($public_id) {
        if (!$public_id) return ['success' => false];
        
        $cloud_name = $this->config['cloud_name'];
        $api_key = $this->config['api_key'];
        $api_secret = $this->config['api_secret'];
        
        $signature = sha1("public_id={$public_id}&timestamp=" . time() . $api_secret);
        
        $url = "https://api.cloudinary.com/v1_1/{$cloud_name}/image/destroy";
        $data = [
            'public_id' => $public_id,
            'api_key' => $api_key,
            'timestamp' => time(),
            'signature' => $signature
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        $result = json_decode($response, true);
        
        return ['success' => isset($result['result']) && $result['result'] === 'ok'];
    }
    
    private function deleteFromSupabase($filename) {
        $url = $this->config['url'];
        $key = $this->config['key'];
        $bucket = $this->config['bucket'];
        
        $delete_url = "{$url}/storage/v1/object/{$bucket}/photos/{$filename}";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $delete_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $key
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        return ['success' => $response !== false];
    }
}

// Helper function
function getCloudStorage($type = 'cloudinary') {
    return new CloudStorage($type);
}
?>