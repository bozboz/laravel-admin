<?php

if ( ! function_exists('asset_version')) {

    /**
     * Append file last modified time to asset filenames for busting that cache
     * @param string $assetPath
     * @return string
     */
    function asset_version($assetPath)
    {
        clearstatcache();
        $filemtime = filemtime(public_path($assetPath));
        return asset(preg_replace('/\.([^\.]+)$/', "--$filemtime.$1", $assetPath));
    }

}
