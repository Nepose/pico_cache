<?php
/**
 * Pico Cache plugin
 * Name "PicoZCache" is to be loaded as last.
 *
 * @author Maximilian Beck before 2.0, Nepose since 2.0
 * @link https://github.com/Nepose/PicoCache
 * @license http://opensource.org/licenses/MIT
 * @version 2.0
 */
class PicoZCache extends AbstractPicoPlugin
{

    const API_VERSION=2;
    protected $dependsOn = array();

    private $cacheDir = 'content/cache/';
    private $cacheTime = 604800; // 60*60*24*7, seven days
    private $doCache = true;
    private $cacheXHTML = false;
    private $cacheFileName;

	public function onConfigLoaded(array &$config)
	{
		$this->doCache = $this->getPluginConfig('enabled', false);
		$this->cacheTime = $this->getPluginConfig('time', 604800);
		$this->cacheXHTML = $this->getPluginConfig('xhtml_output', false);
		$this->cacheDir = trim($this->getPluginConfig('dir', 'content/cache/'),'/').'/';
	}

    public function onRequestUrl(&$url)
    {
        $query = (!empty($_GET)) ? '__'.md5(serialize($_GET)) : null;
        //replace any character except numbers and digits with a '-' to form valid file names
        $this->cacheFileName = $this->cacheDir . preg_replace('/[^A-Za-z0-9_\-]/', '_', $url.$query) . '.html';

        //if a cached file exists and the cacheTime is not expired, load the file and exit
        if ($this->doCache && file_exists($this->cacheFileName) && (time() - filemtime($this->cacheFileName)) < $this->cacheTime) {
            header("Expires: " . gmdate("D, d M Y H:i:s", $this->cacheTime + filemtime($this->cacheFileName)) . " GMT");
            ($this->cacheXHTML) ? header('Content-Type: application/xhtml+xml') : header('Content-Type: text/html');
            die(readfile($this->cacheFileName));
        }
    }

    public function on404ContentLoaded(&$rawContent)
    {
        //don't cache error pages. This prevents filling up the cache with non existent pages
        $this->doCache = false;
    }

    public function onPageRendered(&$output)
    {
        if ($this->doCache) {
            if (!is_dir($this->cacheDir)) {
                mkdir($this->cacheDir, 0755, true);
            }
            file_put_contents($this->cacheFileName, $output);
        }
    }

}
