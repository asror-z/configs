<?php


use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\VarExporter\VarExporter;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseArrayHelper;
use zetsoft\system\Az;
use zetsoft\system\helpers\ZArrayHelper;
use zetsoft\system\helpers\ZFileHelper;


class Boot
{

    public const ipLocal = '127.0.0.1';
    /**
     *
     * Developer Checker
     */

    public $folderCaching;
    public $folderLoggers;
    public $folderRuntime;

    /**
     * @var null
     *
     *
     */
    public $ipType = null;
    // public $ipType = self::type['prod'];

    /**
     *
     * User Vars
     */
    public $allwaysDebug = false;
    public $ipUser;
    public $ipPC;
    public $ipHost;
    public $ipALL;
    public $ipRangeBegin;
    public $ipRangeEnd;
    public $nameUser;
    public $namePC;

    public $identityClass;

    public array $timer = [];
    public bool $returnTimer = false;

    public const linkType = [
        'dir' => 'dir',
        'junk' => 'junk',
        'file' => 'file',
    ];

    public const core = [
        'PAMI\Message\Event\Factory\Impl\EventFactoryImpl' => Root . '/system/consts/PAMI/EventFactoryImpl.php',
        'Carbon\Traits\Localization' => Root . '/system/consts/carbon/Localization.php',
        'yii\debug\DbAsset' => Root . '/system/consts/debug/DbAsset.php',
        'yii\debug\DebugAsset' => Root . '/system/consts/debug/DebugAsset.php',
        'yii\debug\TimelineAsset' => Root . '/system/consts/debug/TimelineAsset.php',
        'yii\debug\UserswitchAsset' => Root . '/system/consts/debug/UserswitchAsset.php',
    ];

    #region IP Init

    public function apps()
    {
        /**
         *
         * Core Paths
         */

        $this->folderLoggers = Root . '/storing/loggers/' . Mode . '/' . App;
        $this->folderRuntime = Root . '/storing/runtime/' . Mode . '/' . App;
        $this->folderCaching = Root . '/storing/caching/' . Mode . '/' . App;
   
    }

    public function ipALL()
    {


        if (defined('App')) {
            $app = App;
            $allApp = " | App = {$app}";
        } else
            $allApp = '';

        $this->ipALL = "Host: $this->ipHost | PC: $this->ipPC | User: $this->ipUser  $allApp";


        return $this->ipALL;
    }

    public function init()
    {
        /**
         *
         * User Config
         */

        $this->namePC = $this->env('COMPUTERNAME');
        $this->nameUser = $this->env('USERNAME');


        /**
         *
         * User IP
         */


        if (!$this->isCLI()) {
            $this->ipUser = $_SERVER['REMOTE_ADDR'];
        } else {
            $this->ipUser = '127.0.0.1';
        }

        //vdd($this->ipUser);

        $this->ipHost = getHostByName(getHostName());

        if (array_key_exists('SERVER_ADDR', $_SERVER))
            $this->ipPC = $_SERVER['SERVER_ADDR'];

        $this->ipPC = $this->ipPC ?? $this->ipHost;

        $this->ipALL();

    }

    #endregion


    #region Utility


    public function makelink($target, $link, $type = self::linkType['dir'])
    {
        switch ($type) {
            case self::linkType['dir']:
                $pswitch = '/d';
                break;
            case self::linkType['junk']:
                $pswitch = '/j';
                break;
            case self::linkType['file']:
                $pswitch = '';
                break;
        }

        $replace = [
            '/' => '\\'
        ];
        $target = strtr($target, $replace);
        $link = strtr($link, $replace);

        return exec('mklink ' . $pswitch . ' "' . $link . '" "' . $target . '"');
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function env($key, $default = null)
    {
        $value = getenv($key) ?? $_ENV[$key] ?? $_SERVER[$key];

        if ($value === false)
            $return = $default;
        else
            switch (strtolower($value)) {
                case 'true':
                case '(true)':
                    $return = true;
                    break;

                case 'false':
                case '(false)':
                    $return = false;
                    break;

                default:

                    $pos = strpos($value, '|');

                    if ($pos === false)
                        $return = $value;
                    else
                        $return = explode('|', $value);
                    break;
            }


        return $return;

    }

    public function isCLI(): bool
    {
        if (PHP_SAPI === 'cli')
            return true;

        return false;
    }

    #endregion


    #region VarDump


    public function logs($data, $export = false)
    {
        if ($export) {
            $dump = \yii\helpers\VarDumper::dumpAsString($data);
            $file = $this->file() . EOL;
            return $dump . EOL . EOL . $file;
        }

        VarDumper::dump($data);
        echo $this->file() . EOL;

    }

    public function key(array $debug)
    {
        foreach ($debug as $key => $item) {
            if (in_array($item['function'], [
                'vf',
                'vd',
                'vfd',
                'vfd',
                'vdd',
                'vdda',
            ]))
                return $key;
        }
        return null;
    }

    public function file(): ?string
    {
        $debug = debug_backtrace();

        $key = $this->key($debug);

        if (!array_key_exists($key, $debug))
            return null;

        $b1 = !array_key_exists('file', $debug[$key]);
        $b2 = !array_key_exists('line', $debug[$key]);

        if ($b1 || $b2)
            return null;

        $file = $debug[$key]['file'];
        $line = $debug[$key]['line'];

        return "$file:$line";
    }


    final public function fileAll()
    {
        $file = $this->file();
        $file = strtr($file, [
            ':' => '-',
            '?' => '_',
            '\\' => '_',
            '/' => '_',
            Root => '',
        ]);

        $file = trim($file, '_');
        $file .= '_' . date('d-m-Y_H-i-s');

        return $file;
    }

    final public function trace($backTrace = false)
    {
        if ($backTrace)
            return debug_backtrace();

        $e = new Exception();
        $trace = explode("\n", $e->getTraceAsString());
        // reverse array to make steps line up chronologically
        $trace = array_reverse($trace);
        array_shift($trace); // remove {main}
        array_pop($trace); // remove call to this method
        $result = array();

        foreach ($trace as $i => $iValue) {
            $result[] = ($i + 1) . ')' . substr($iValue, strpos($iValue, ' ')); // replace '#someNum' with '$i)', set the right ordering
        }

        return "\t" . implode("\n\t", $result);
    }


    #endregion

    #region Check

    public function isWindows(): bool
    {
        return stripos(PHP_OS, 'WIN') === 0;
    }


    public function isConnected(): bool
    {
        $sCheckHost = 'api.telegram.org';
        return (bool)@fsockopen($sCheckHost, 443, $iErrno, $sErrStr, 5);
    }

    #endregion


    #region Utils

    public function gone()
    {
        $check = extension_loaded('SourceGuardian');

        if (!$check)
            require Root . '/binary/native/index.php';
    }

    public function map()
    {
        return BaseArrayHelper::merge(ZALL::map(), self::core);
    }

    #endregion

    #region User

    public function enableDebug()
    {

        if ($this->allwaysDebug)
            return true;

        if ($this->userDev() && $this->env('moduleDebug'))
            return true;

        return false;
    }

    public function userDev(): bool
    {

     //   return false;

        if ($this->userLocal())
            return true;

        if ($this->userMain()) {
            return true;
        }

        return false;
    }

    public function userLocal()
    {
        $return = $this->ipUser === self::ipLocal;
        //   vdd($this->ipUser);
        return $return;
    }

    public function userMain()
    {

        $b1 = $this->ipUser === $this->env('mainIP');
        $b2 = $this->ipUser === $this->env('homeIP');

        if ($b1 || $b2)
            return true;

        if (ArrayHelper::getValue($_COOKIE, 'userMain', '0') === '1224')
            return true;

        return false;
    }



    #endregion

    #region IP

    public function ipCheck(string $sIP)
    {
        $bBegin = ip2long($sIP) >= ip2long($this->ipRangeBegin);
        $bEnd = ip2long($sIP) <= ip2long($this->ipRangeEnd);

        return ($bBegin && $bEnd);
    }


    public function userLAN(): bool
    {
        if ($this->ipLAN($this->ipUser))
            return true;

        return false;

    }

    public const type = [
        'home' => 'home',
        'main' => 'main',
        'prod' => 'prod',
        'dock' => 'dock',
        'local' => 'local',
    ];

    public function ipType()
    {


        return self::type['local'];

        switch (true) {
            case $this->ipPC === $this->env('prodIP'):
            case $this->namePC === 'PRODPC':
                return self::type['prod'];
                break;

            case $this->ipPC === $this->env('homeIP'):
            case $this->namePC === 'HOMEPC':
                return self::type['home'];
                break;

            case $this->ipPC === $this->env('mainIP'):
            case $this->namePC === 'WORKPC':
                return self::type['main'];
                break;

            default:
                return self::type['dock'];
                break;
        }

    }


    public function ipDb()
    {

        switch ($this->ipType()) {
            case self::type['prod']:
                $data = $this->env('prodDbIP');
                break;

            case self::type['home']:
                $data = $this->env('homeDbIP');
                break;

            case self::type['main']:
                $data = $this->env('mainDbIP');
                break;

            default:
                $data = $this->env('dockDbIP');
                break;
        }

        return $data;

    }


    public function ipRedis()
    {
    
        switch ($this->ipType()) {
            case self::type['prod']:
                $data = $this->env('prodRedisIP');
                break;

            case self::type['home']:
                $data = $this->env('homeRedisIP');
                break;

            case self::type['main']:
                $data = $this->env('mainRedisIP');
                break;

            case self::type['local']:
                $data = $this->env('mainRedisIP');
                break;

            default:
                $data = $this->env('dockRedisIP');
                break;
        }
       
        return $data;

    }

    public function ipSphinx()
    {

        switch ($this->ipType()) {
            case self::type['prod']:
                return $this->env('prodSphinxIP');
                break;

            case self::type['home']:
                return $this->env('homeSphinxIP');
                break;

            case self::type['main']:
                return $this->env('mainSphinxIP');
                break;

            default:
                return $this->env('localSphinxIP');
                break;

        }

    }


    public function ipLAN(string $sIP)
    {
        $this->ipRange('10.0.0.0', '10.255.255.255');
        $b10 = $this->ipCheck($sIP);

        $this->ipRange('172.16.0.0', '172.31.255.255');
        $b172 = $this->ipCheck($sIP);

        $this->ipRange('192.168.0.0', '192.168.255.255');
        $b192 = $this->ipCheck($sIP);

        if ($b10 || $b172 || $b192)
            return true;

        return false;
    }

    public function ipRange(string $sIP_Begin, string $sIP_End)
    {
        $this->ipRangeBegin = $sIP_Begin;
        $this->ipRangeEnd = $sIP_End;
    }

    #endregion


    #region IO

    /**
     *
     * Function  mkdir
     * @param $path
     * @param bool $remove
     * @return string
     */
    public function mkdir($path, bool $remove = false): string
    {

        if (is_dir($path)) {
            if ($remove) {
                $this->rmdir($path);
            } else
                return $this->echo($path, 'Directory is Exists!');
        } else
            $this->rmfile($path);

        if (!mkdir($path, 0777, true) && !is_dir($path))
            return $this->echo($path, 'Cannot create path', false);

        return $this->echo($path, 'Created', true);
    }

    public function rmfile($path)
    {
        if (!file_exists($path))
            return $this->echo($path, 'Cannot Remove! File not exists: ', false);

        if (!unlink($path))
            return $this->echo($path, 'Cannot unlink path', false);
        else
            return $this->echo($path, 'File is deleted: ', true);

    }

    public function remove($link)
    {

        if (file_exists($link)) {

            switch (true) {

                case is_link($link) && is_dir($link):

                    $this->echo($link, 'Symlink will be removed', true);
                    $this->rmdir($link);
                    break;

                case is_link($link):

                    $this->echo($link, 'Symlink will be removed', true);
                    $this->rmfile($link);
                    break;

                case is_file($link):

                    $this->echo($link, 'File will be removed', true);
                    $this->rmfile($link);
                    break;

                case is_dir($link):

                    $this->echo($link, 'Dir will be removed', true);
                    $this->rmdir($link);
                    break;

            }
        }
    }

    public function rmdir(string $path)
    {
        ZFileHelper::removeDirectory($path);

        if (!is_dir($path))
            return $this->echo($path, 'Directory Removed');
        else
            return $this->echo($path, 'Cannot Remove Path');
    }

    public function echo($data, $title = null, $return = true)
    {
        $data = var_export($data, true);

        if ($title)
            $echo = "{$title} | {$data}";
        else
            $echo = $data;

        $echo .= EOL;
        echo $echo;

        return $return;

    }

    #endregion

    #region Service

    public function mklink($target, $link, bool $remove = true)
    {

        if (!file_exists($target))
            $this->echo($target, 'Target is Not exists', false);

        if ($remove)
            $this->remove($link);

        if (file_exists($link))
            return $this->echo($link, 'Path is already exists', true);


        if ($this->env('mklink')) {
            if (!$this->makelink($target, $link))
                return $this->echo("Cannot Create Symlink from: $target to: $link", '', false);
        } else {
            if (!symlink($target, $link))
                return $this->echo("Cannot Create Symlink from: $target to: $link", '', false);
        }

        if (file_exists($link))
            return $this->echo($target, 'Symlink Created', true);
        else
            return $this->echo($target, 'Symlink Not Created', true);

    }

    public function eol(int $count = 1)
    {
        $return = '';
        for ($iI = 1; $iI <= $count; $iI++) {
            $return .= EOL;
        }

        echo $return;
    }

    #endregion


    #region Timer

    /**
     * Начало выполнения
     * @param string $key
     */
    public function start($key = 'main')
    {
        $this->timer[$key] = microtime(true);
    }

    /**
     * Разница между текущей меткой времени и меткой self::$start
     * @param string $key
     * @return float
     */
    public function finish($key = 'main')
    {

        if (!$this->env('timerEnable'))
            return null;

        if (!BaseArrayHelper::keyExists($key, $this->timer))
            return null;

        $time = microtime(true) - $this->timer[$key];

        if ($this->returnTimer) {
            return $key . ": $time" . ' sec.';
        } else
            echo($key . ": $time" . ' sec.');
    }

    public static function zettime($message)
    {
        global $timeStart;

        $timeEnd = microtime(true);
        if ($timeStart === 0)
            return 'Timer Not started';

        $time = number_format($timeEnd - $timeStart, 3);

        return "$message: $time";

    }

    #endregion

}

