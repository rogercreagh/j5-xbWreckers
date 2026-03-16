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
//                 $url = $manifest->changelogurl;
//                 $ext_mess = '<div style="position: relative; margin: 15px 15px 15px -15px; padding: 1rem; border:solid 1px #444; border-radius: 6px;">';
//                 $ext_mess .= $this->showChanglog($manifest->version, $url);
//                 $ext_mess .= '</div>';
//                 echo $ext_mess;
                
                $msg .= '<p><b>'.$this->extname.'</b> Template has been updated from '.$this->oldver.' of '.$this->olddate;
                $msg .= ' to v<b>'.$manifest->version.'</b> dated '.$manifest->creationDate.'</p>';
            } else {
                $msg .= '<p><b>'.$this->extname.'</b> Template v<b>'.$manifest->version.'</b> dated '.$manifest->creationDate.' installed ok.</p>';
            }
            $msg .= 'Now set template parameters in <code>Extensions | Templates | Styles and Templates</code>';
            $app->enqueueMessage($msg);
        } elseif($type=='uninstall') {
            $app->enqueueMessage('Uninstalled '.$this->extname.' v'.$this->oldver.' of '.$this->olddate);
        }
        return true;
    }

    function showChanglog($ver, $url) {
        $output = '<div style="max-width: 750px; background-color: white; border: 1px solid black; padding:15px 25px; margin:10px auto; font-size:0.9rem;">';
        if (!$this->remoteFileExists($url)) {
            $output .= '<p style="color:red;">Could not find changelog file <code>'.$url.'</code></p></div>';
            return $output;
        }
        $xml = simplexml_load_file($url, null , LIBXML_NOCDATA);
        if ($xml===false) {
            $output .= '<p style="color:red;">Could not parse changelog file <code>'.$url.'</code></p></div>';
            return $output;
        } else {
            $json = json_encode($xml);
            $changelog = json_decode($json,true);
            $log = 0;
            if (array_key_exists('element',$changelog)) {
                //only 1 changelog in file
                $log = $changelog;
                $newver = $log['version'];
                if (version_compare($newver, $ver) !== 0) {
                    $output.= '<p style="color:red;">Changelog for v'.$ver.' not found. v'.$newver.' is only one available.</p>';
                }
            } else {
                $changelog = $changelog['changelog'];
                //look for current version
                for ($i = 0; $i < count($changelog); $i++) {
                    if (version_compare($changelog[$i]['version'], $ver) === 0) $log = $changelog[$i];
                }
                if ($log === 0 ) {
                    $log = $changelog[0];
                    $output.= '<p style="color:red;">Changelog for v'.$ver.' not found; displaying most recent</p>';
                }
            }
            $output .= '<h4>Changelog for ';
            
            if (key_exists('title',$log)) {
                $output .= $log['title'];
                //               unset($log['title']);
            } else {
                $output .= $log['element'];
            }
            $output .= ' v'.$log['version'].' ';
            if (key_exists('date',$log)) {
                $output .= $log['date'];
            }
            $output .= '</h4><hr />';
            
            $colours = array('security'=>'bg-danger', 'addition'=>'bg-success', 'fix'=>'bg-dark','language'=>'bg-primary',
                'change'=>'bg-warning text-dark','remove'=>'bg-secondary','note'=>'bg-info'
            );
            $output .= '<table style="margin-left:20px; width:90%;">';
            foreach ($colours as $colkey=>$col) {
                if ((isset($log[$colkey])) && isset($log[$colkey]['item'])) {
                    $output .= '<tr style="border-bottom:1px solid #888;"><td style="background-color:#ddd; vertical-align: top; padding: 5px 10px;">';
                    $output .=  '<span class="badge '.$col.'" style="font-size: 0.8rem;padding: 0.3rem 0.5rem;">'.$colkey.'</span>';
                    $output .= '</td><td style="vertical-align: top; padding: 5px 10px;"><ul>';
                    if (is_array($log[$colkey]['item'])) {
                        foreach ($log[$colkey]['item'] as $item) {
                            $output .= '<li>'.$item.'</li>';
                        }
                    } else {
                        $output .= '<li>'.$log[$colkey]['item'].'</li>';
                    }
                    $output .= '</ul></td></tr>';
                }
            }
            $output .= '</table>';
        }
        $output .= '</div>';
        return $output;
    }
    
    function remoteFileExists($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);      // Don't fetch the body
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);       // Set timeout
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return $httpCode === 200;
    }
    
};