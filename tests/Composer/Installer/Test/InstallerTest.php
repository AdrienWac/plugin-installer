<?php

namespace Composer\Installers\Test;

use Composer\Composer;
use Composer\Config;
use Composer\Installer\Installer;
use Composer\Package\Package;
use Composer\Package\RootPackage;
use Composer\Util\Filesystem;
use PHPUnit\Framework\TestCase;

class InstallerTest extends TestCase
{

    private $composer;
    private $config;
    private $vendorDir;
    private $binDir;
    private $dm;
    private $repository;
    private $io;
    private $fs;

    public function setUp(): void
    {

        $this->fs = new Filesystem;

        $this->composer = new Composer();
        $this->config = new Config();
        $this->composer->setConfig($this->config);

        $this->vendorDir = realpath(sys_get_temp_dir()) . DIRECTORY_SEPARATOR . 'baton-test-vendor';
        $this->ensureDirectoryExistsAndClear($this->vendorDir);

        $this->binDir = realpath(sys_get_temp_dir()) . DIRECTORY_SEPARATOR . 'baton-test-bin';
        $this->ensureDirectoryExistsAndClear($this->binDir);

        $this->config->merge(array(
            'config' => array(
                'vendor-dir' => $this->vendorDir,
                'bin-dir' => $this->binDir,
            ),
        ));

        $this->dm = $this->getMockBuilder('Composer\Downloader\DownloadManager')
            ->disableOriginalConstructor()
            ->getMock();
        $this->composer->setDownloadManager($this->dm);

        $this->repository = $this->getMockBuilder('Composer\Repository\InstalledRepositoryInterface')->getMock();
        $this->io = $this->getMockBuilder('Composer\IO\IOInterface')->getMock();

        $consumerPackage = new RootPackage('foo/bar', '1.0.0', '1.0.0');
        $this->composer->setPackage($consumerPackage);

    }


    /**
     * tearDown
     *
     * @return void
     */
    public function tearDown(): void
    {
        $this->fs->removeDirectory($this->vendorDir);
        $this->fs->removeDirectory($this->binDir);
    }

    protected function ensureDirectoryExistsAndClear($directory)
    {
        $fs = new Filesystem();
        if (is_dir($directory)) {
            $fs->removeDirectory($directory);
        }
        mkdir($directory, 0777, true);
    }

    public function testExample()
    {
        $var1 = true;

        $this->assertTrue($var1);
    }

    /**
     * testInstallPath
     *
     * @dataProvider dataForTestInstallPath
     */
    public function testInstallPath($type, $path, $name, $version = '1.0.0')
    {
        $installer = new Installer($this->io, $this->composer);
        $package = new Package($name, $version, $version);

        $package->setType($type);
        $result = $installer->getInstallPath($package);
        var_dump($result);die;
        $this->assertEquals($path, $result);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function dataForTestInstallPath()
    {
        return array(
            // array('cakephp-plugin', 'Plugin/Ftp/', 'shama/ftp'),
            array('cakephp-plugin', 'Plugin/Ftp/', 'webandcow/cakephp-sanitize')
        );
    }

}
