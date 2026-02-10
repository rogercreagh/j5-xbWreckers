<?php 
/*******
 * @package xbWreckers
 * @filesource install.xbwreckers.php
 * @version 1.0.4.1 10th February 2026
 * @author Roger C-O
 * @copyright Copyright (c) Roger Creagh-Osborne, 2026
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 ******/


use Joomla\CMS\Factory;
use Joomla\CMS\Installer\Installer;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Installer\InstallerScriptInterface;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Version;
use Joomla\Filesystem\File;
use Joomla\Filesystem\Folder;
use Joomla\Filesystem\Exception\FilesystemException;

return new class () implements InstallerScriptInterface {

    private string $minphp = '8.2';
    private string $jminver = '5.0';
    private string $jmaxver = '6.0';
    private string $extension = 'tpl_xbwreckers';
    private string $extname = 'xbWreckers';
    private string $extslug = 'xbwreckers';
    private string $ver = 'v1.2.3.4';
    private string $date = '32nd January 2026';
    private string $oldver = 'v1.2.3.4';
    private string $olddate = '32nd January 2026';
    
    public function install(InstallerAdapter $adapter): bool
    {
        return true;
    }

    public function update(InstallerAdapter $adapter): bool
    {
        return true;
    }

    public function uninstall(InstallerAdapter $adapter): bool
    {
        // we could unistall streamer here - it will be deleted if reinstall is run but we might want it left
        return true;
    }

    public function preflight(string $type, InstallerAdapter $adapter): bool
    {        
        if (version_compare(PHP_VERSION, $this->minphp, '<')) {
            Factory::getApplication()->enqueueMessage(sprintf(Text::_('JLIB_INSTALLER_MINIMUM_PHP'), $this->minimumPhp), 'error');
            return false;
        }

        if (version_compare(JVERSION, $this->jminver, '<')) {
            Factory::getApplication()->enqueueMessage(sprintf(Text::_('JLIB_INSTALLER_MINIMUM_JOOMLA'), $this->jminver), 'error');
            return false;
        }
        if (version_compare(JVERSION, $this->jmaxver, '>')) {
            Factory::getApplication()->enqueueMessage(sprintf(Text::_('JLIB_INSTALLER_MAXIMUM_JOOMLA'), $this->jmaxver), 'error');
            return false;
        }
        
        if ($type=='update') {
            $prevManifestXML = Installer::parseXMLInstallFile(JPATH_ROOT . '/templates/'.$this->extslug.'/templateDetails.xml');
//            Factory::getApplication()->enqueueMessage('xml=<pre>'.print_r($prevManifestXML,true).'</pre>');
            if ($prevManifestXML) {
                 $this->oldver = $prevManifestXML['version'];
                 $this->olddate = $prevManifestXML['creationDate'];
            } else {
                Factory::getApplication()->enqueueMessage('problem reading '.JPATH_ROOT . '/templates/'.$this->extslug.'/templateDetails.xml');
            }
        }
        return true;
    }

    public function postflight(string $type, InstallerAdapter $adapter): bool
    {
//        echo "tpl_xbwreckers postflight<br>";
        $app = Factory::getApplication();
        if (($type == 'install') || ($type=='update') || ($type=='discover_install')) {
            $manifest = $adapter->manifest;
            $msg = '';
            if ($type == 'update') {
                $msg .= '<p><b>'.$this->extname.'</b> Template has been updated from '.$this->oldver.' of '.$this->olddate;
                $msg .= ' to v<b>'.$manifest->version.'</b> dated '.$manifest->creationDate.'</p>';
            } else {
                $msg .= '<p><b>'.$this->extname.'</b> Template v<b>'.$manifest->version.'</b> dated '.$manifest->creationDate.' installed ok.</p>';
            }
//            if (Folder::exists(JPATH_ROOT.'/streamer')) {
//                $res = Folder::delete(JPATH_ROOT.'/streamer/');
//            }
//            $res = $this->deleteDirectorySPL(JPATH_ROOT.'/streamer/');
//            if ($res == false) {
//                $app->enqueueMessage('old streamer not found <code>'.JPATH_ROOT.'/streamer/</code>');                   
//            } else {
//                $app->enqueueMessage('removed old streamer <code>'.JPATH_ROOT.'/streamer/</code>');                   
 //           }
//            $app->enqueueMessage('moving <code>'.JPATH_ROOT.'/templates/'.$this->extslug.'/streamer/</code>');
//            $res = Folder::move(JPATH_ROOT . '/templates/'.$this->extslug.'/streamer/',JPATH_ROOT.'/');
 //           $res = $this->rmove(JPATH_ROOT . '/templates/'.$this->extslug.'/streamer/',JPATH_ROOT.'/');
//            if ($res !== true) {
//                $app->enqueueMessage($res.'<br />Error moving <code>'.JPATH_ROOT . '/templates/'.$this->extslug.'/streamer/</code> to site root. Extract folder from zip file and upload to site root manually (eg using SFTP)', 'error');
    //            return false;
//            } else {
//                $msg .= ' '.'Streamer tool moved to site root ok.</p>';
//            }
            $msg .= 'Now set template parameters in <code>Extensions | Templates | Styles and Templates</code>';
            $app->enqueueMessage($msg);
        } elseif($type=='uninstall') {
            $app->enqueueMessage('Uninstalled '.$this->extname.' v'.$this->oldver.' of '.$this->olddate);
        }
        return true;
    }
    
};