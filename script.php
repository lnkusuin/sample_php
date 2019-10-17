<?php

/**
 * 対象ディレクトリ配下のファイル情報(SplFileInfo)を取得する
 * @param string $base_dir 検索を行いたい対象ディレクトリ
 * @param bool $isDotFileIgnore 隠しファイルを除外するか
 * @return array[SplFileInfo]
 */
function getSplFileList($base_dir, $isDotFileIgnore = true){
    $iterator = new RecursiveDirectoryIterator($base_dir, FilesystemIterator::SKIP_DOTS);
    $iterator = new RecursiveIteratorIterator($iterator);

    $checkDotFile = function(SplFileInfo $item) use($isDotFileIgnore){
        if($isDotFileIgnore == false){
            return true;
        }

        return !(strpos($item->getFilename(), '.') === 0);
    };

    return array_filter(iterator_to_array($iterator), function(SplFileInfo $item) use($checkDotFile){
        return $item->isFile() && $checkDotFile($item);
    });
}


$expected1 = [
    BASE_DIR . "/169-200x300.jpg",
    BASE_DIR . "/subdir/169-200x300.jpg",
    BASE_DIR . "/subdir/554-200x300.jpg",
    BASE_DIR . "/subdir/536-200x300.jpg",
    BASE_DIR . "/subdir/683-200x300.jpg",
    BASE_DIR . "/554-200x300.jpg",
    BASE_DIR . "/536-200x300.jpg",
    BASE_DIR . "/683-200x300.jpg",
];

$expected2 = [
    BASE_DIR . "/.a",
    BASE_DIR . "/subdir/.txt",
];

const BASE_DIR = __DIR__ . '/images';

if (php_sapi_name() == 'cli') {
    echo('===処理開始===' . PHP_EOL);
    echo('隠しファイル除外されているかのテスト' . PHP_EOL);
    foreach (getSplFileList(BASE_DIR) as $key => $file_info){
        echo($key. PHP_EOL);
        assert(in_array($file_info->getPathname(), $expected1), );
        assert(!in_array($file_info->getPathname(), $expected2), );
    }

    echo(PHP_EOL . '隠しファイル含まれているかのテスト' . PHP_EOL);
    foreach (getSplFileList(BASE_DIR, $isDotFileIgnore=false) as $key => $file_info){
        echo($key. PHP_EOL);
        assert(in_array($file_info->getPathname(), array_merge($expected1, $expected2)), );
    }

    echo('===完了===' . PHP_EOL);
}