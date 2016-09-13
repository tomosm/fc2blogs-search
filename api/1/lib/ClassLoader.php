<?php
namespace EXAM0098\Lib;

require_once __DIR__ . '/StringUtils.php';

/**
 * クラスローダークラス。
 *
 * @package EXAM0098\Lib
 */
class ClassLoader
{
    private $dirs = null;

    /**
     * クラスオートロード処理追加。
     */
    public function register()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }

    /**
     * ロード時に読み込むディレクトリを登録する。
     *
     * @param string $dir ロードディレクトリ
     */
    public function registerDir($dir)
    {
        $this->dirs[] = $dir;
    }

    /**
     * クラスを読み込む。
     *
     * @param $class
     */
    private function loadClass($class)
    {
        if (count($this->dirs) === 0) return;

        foreach ($this->dirs as $dir) {
            $file = $dir . '/' . $this->getLoadClassPath($class) . '.php';
            if (is_readable($file)) {
                require_once $file;
                return;
            }
        }
        // throw new \RuntimeException("Not Found $class.");
    }

    private function getLoadClassPath($loadClass)
    {
        $packageNameFragments = explode('/', str_replace('\\', '/', $loadClass));
        // トップパッケージはベンター名のため削除
        array_shift($packageNameFragments);
        // クラス名以外はスネークケース
        for ($i = 0, $l = count($packageNameFragments) - 1; $i < $l; $i++) {
            $packageNameFragments[$i] = StringUtils::snakecase($packageNameFragments[$i]);
        }
        return implode('/', $packageNameFragments);
    }
}
