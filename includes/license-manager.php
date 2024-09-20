<?php
// License generation
function myplugin_generate_license($user_id) {
    // Generate a 20-character, uppercase license key
    $license_key = strtoupper(wp_generate_password(20, false));
    
    // Store license key in user meta with validation to prevent overwrite
    if (!get_user_meta($user_id, '_user_license_key', true)) {
        update_user_meta($user_id, '_user_license_key', $license_key);
    }
    
    return $license_key;
}

// Embed license into downloadable file
function myplugin_embed_license_in_file($file_url, $license_key) {
    // Placeholder logic for embedding the license into the file
    // Optionally, modify metadata or ZIP the file with the license key
    return $file_url;  // Return the file URL after modification, if applicable
}
