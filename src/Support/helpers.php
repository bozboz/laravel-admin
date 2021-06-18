<?php

if ( ! function_exists('asset_version')) {

    /**
     * Append file last modified time to asset filenames for busting that cache
     * @param string $assetPath
     * @return string
     */
    function asset_version($assetPath, $realPath = null)
    {
        try {
            clearstatcache();
            $filemtime = filemtime($realPath ?: public_path($assetPath));
            return asset(preg_replace('/\.([^\.]+)$/', ".$1?$filemtime", $assetPath));
        } catch (Exception $e) {
            return $assetPath;
        }
    }

}

if (!function_exists('file_upload_max_size')) {
    function file_upload_max_size() {
        static $max_size = -1;

        if ($max_size < 0) {
            // Start with post_max_size.
            $post_max_size = parse_size(ini_get('post_max_size'));
            if ($post_max_size > 0) {
                $max_size = $post_max_size;
            }

            // If upload_max_size is less, then reduce. Except if upload_max_size is
            // zero, which indicates no limit.
            $upload_max = parse_size(ini_get('upload_max_filesize'));
            if ($upload_max > 0 && $upload_max < $max_size) {
                $max_size = $upload_max;
            }
        }
        return $max_size;
    }
}

if (!function_exists('parse_size')) {
    function parse_size($size)
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
        $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
        if ($unit) {
            // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        } else {
            return round($size);
        }
    }
}
