<?php
namespace EXAM0098\Lib;

/**
 * FC2 ブログ RDF 構文解析クラス。
 *
 * @package EXAM0098\Lib
 */
class Fc2BlogRdfParser
{

    /**
     * FC2BLOG の新着情報 RSS から取得した RDF データを解析して必要なデータを配列として返す。
     * <item.+rdf:about.+>...</item> の ... のタグのデータを連想配列に変換したオブジェクトを複数保持した配列を返す。
     *
     * @param string $url FC2 ブログエントリー URL
     * @return array データ
     * @throws ApplicationException RuntimeException 例外が発生した場合
     */
    public function parseFromUrl($url = 'http://blog.fc2.com/newentry.rdf')
    {
        $file = @fopen($url, 'r');

        $entries = array();
        try {
            if ($file) {
                $entry = null;
                while ($line = fgets($file)) {
                    if (preg_match("#^<item.+rdf:about.+>$#", $line, $matches)) {
                        $entry = array();
                    } else if (preg_match("#^</item>$#", $line, $matches) && !empty($entry)) {
                        $entries[] = $entry;
                        $entry = null;
                    } else if (!is_null($entry)) {
                        if (preg_match("#^<([\\w:]+)>(.+)</[\\w:]+>$#", $line, $matches)) {
                            $entry[$matches[1]] = $matches[2];
                        }
                    }
                }
            }
        } catch (\RuntimeException $e) {
            throw new ApplicationException('Failed to fetch data from fc2', $previous = $e);
        } finally {
            if ($file) {
                fclose($file);
            }
        }

        return $entries;
    }

}


