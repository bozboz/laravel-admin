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