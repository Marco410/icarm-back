<?php

if (!function_exists('dbg')) {

    function dbg($data, $die = false)
    {
        $backtace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $backtace = reset($backtace);
        echo '<pre style="background-color: #18171B; color: #FF8400; padding: 10px">';
        if (!empty($backtace)) {
            echo '<div>File ' . $backtace['file'] . '<br>Line ' . $backtace['line'] . '</div>';
            echo '<hr>';
        } else {
            echo 'NO LINE: NO FILE <br>';
        }
        if ($data != null) {
            print_r($data);
        }
        echo '</pre>';

        if ($die) {
            die();
        }
    }
}

if (!function_exists('printMemoryUsage')) {

    function printMemoryUsage($die = false)
    {
        $backtace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $backtace = reset($backtace);
        echo '<pre style="background-color: #18171B; color: #FF8400; padding: 10px">';
        if (!empty($backtace)) {
            echo '<div>File ' . $backtace['file'] . '<br>Line ' . $backtace['line'] . '</div>';
            echo '<hr>';
        } else {
            echo 'NO LINE: NO FILE <br>';
        }
        echo date('Y-m-d H:i:s') . " Peak memory usage: " . (memory_get_peak_usage(true) / 1024 / 1024) . " MB";
        echo '</pre>';

        if ($die) {
            die();
        }
    }
}

if (!function_exists('renameFileOrDirectory')) {
    function renameFileOrDirectory($src, $dst, $mkdirMode = 0777)
    {
        if (!rename($src, $dst)) {
            if (copyAllFiles($src, $dst, $mkdirMode)) {
                deleteAllFiles($src);
                if (is_dir($src)) {
                    rmdir($src);
                }
                return true;
            }
            return false;
        }
        return true;
    }
}

if (!function_exists('copyAllFiles')) {
    function copyAllFiles($src, $dst, $mkdirMode = 0777)
    {
        $dir = opendir($src);
        @mkdir($dst, $mkdirMode, true);
        while ($file = readdir($dir)) {

            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    copyAllFiles($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
        return true;
    }
}
if (!function_exists('deleteAllFiles')) {
    function deleteAllFiles($src)
    {
        $dir = opendir($src);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                $full = $src . '/' . $file;
                if (is_dir($full)) {
                    deleteAllFiles($full);
                } else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }
}
