<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.xbwreckers
 * @author Roger Creagh-Osborne (C) 2025 based on Cassopedia template by Joomla
 * @version 0.0.1.1 21st May 2025
 * @copyright   (C) 2025 Roger C-O <https:crosborne.uk> and (C) 2017 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

/** @var Joomla\CMS\Document\HtmlDocument $this */

$app   = Factory::getApplication();
$input = $app->getInput();
$wa    = $this->getWebAssetManager();

// Browsers support SVG favicons
// $this->addHeadLink(HTMLHelper::_('image', 'favicons/favicon-96x96.png', '', [], true, 1), 'icon', 'rel', ['type' => 'image/png']);
// $this->addHeadLink(HTMLHelper::_('image', 'favicons/favicon.svg', '', [], true, 1), 'icon', 'rel', ['type' => 'image/svg+xml']);
// $this->addHeadLink(HTMLHelper::_('image', 'favicons/favicon.ico', '', [], true, 1), 'shortcut icon', 'rel', ['type' => 'image/png']);
// $this->addHeadLink(HTMLHelper::_('image', 'favicons/apple-touch-icon.png', '', ['sizes="180x180"'], true, 1), 'apple-touch-icon', 'rel', ['type' => 'image/png']);
// $this->addHeadLink(HTMLHelper::_('meta', 'favicons/apple-touch-icon.png', '', ['sizes="180x180"'], true, 1), 'apple-touch-icon', 'rel', ['type' => 'image/png']);
// $this->addHeadLink(HTMLHelper::_('image', 'favicons/apple-touch-icon.png', '', ['sizes="180x180"'], true, 1), 'apple-touch-icon', 'rel', ['type' => 'image/png']);
//$this->addHeadLink(HTMLHelper::_('image', 'favicon.ico', '', [], true, 1), 'alternate icon', 'rel', ['type' => 'image/vnd.microsoft.icon']);
//$this->addHeadLink(HTMLHelper::_('image', 'joomla-favicon-pinned.svg', '', [], true, 1), 'mask-icon', 'rel', ['color' => '#000']);

// Detecting Active Variables
$option   = $input->getCmd('option', '');
$view     = $input->getCmd('view', '');
$layout   = $input->getCmd('layout', '');
$task     = $input->getCmd('task', '');
$itemid   = $input->getCmd('Itemid', '');
$sitename = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');
$menu     = $app->getMenu()->getActive();
$pageclass = $menu !== null ? $menu->getParams()->get('pageclass_sfx', '') : '';

// Color Theme
$paramsColorName = $this->params->get('colorName', 'colors_standard');
$assetColorName  = 'theme.' . $paramsColorName;

// // Use a font scheme if set in the template style options
// $paramsFontScheme = $this->params->get('useFontScheme', false);
// $fontStyles       = '';

// if ($paramsFontScheme) {
//     if (stripos($paramsFontScheme, 'https://') === 0) {
//         $this->getPreloadManager()->preconnect('https://fonts.googleapis.com/', ['crossorigin' => 'anonymous']);
//         $this->getPreloadManager()->preconnect('https://fonts.gstatic.com/', ['crossorigin' => 'anonymous']);
//         $this->getPreloadManager()->preload($paramsFontScheme, ['as' => 'style', 'crossorigin' => 'anonymous']);
//         $wa->registerAndUseStyle('fontscheme.current', $paramsFontScheme, [], ['rel' => 'lazy-stylesheet', 'crossorigin' => 'anonymous']);

//         if (preg_match_all('/family=([^?:]*):/i', $paramsFontScheme, $matches) > 0) {
//             $fontStyles = '--xbwreckers-font-family-body: "' . str_replace('+', ' ', $matches[1][0]) . '", sans-serif;
// 			--xbwreckers-font-family-headings: "' . str_replace('+', ' ', $matches[1][1] ?? $matches[1][0]) . '", sans-serif;
// 			--xbwreckers-font-weight-normal: 400;
// 			--xbwreckers-font-weight-headings: 700;';
//         }
//     } elseif ($paramsFontScheme === 'system') {
//         $fontStylesBody    = $this->params->get('systemFontBody', '');
//         $fontStylesHeading = $this->params->get('systemFontHeading', '');

//         if ($fontStylesBody) {
//             $fontStyles = '--xbwreckers-font-family-body: ' . $fontStylesBody . ';
//             --xbwreckers-font-weight-normal: 400;';
//         }
//         if ($fontStylesHeading) {
//             $fontStyles .= '--xbwreckers-font-family-headings: ' . $fontStylesHeading . ';
//     		--xbwreckers-font-weight-headings: 700;';
//         }
//     } else {
//         $wa->registerAndUseStyle('fontscheme.current', $paramsFontScheme, ['version' => 'auto'], ['rel' => 'lazy-stylesheet']);
//         $this->getPreloadManager()->preload($wa->getAsset('style', 'fontscheme.current')->getUri() . '?' . $this->getMediaVersion(), ['as' => 'style']);
//     }
// }

// Enable assets
$wa->usePreset('template.xbwreckers.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr'))
    ->useStyle('template.active.language')
    ->registerAndUseStyle($assetColorName, 'global/' . $paramsColorName . '.css')
    ->useStyle('template.user')
    ->useScript('template.user')
    ->addInlineStyle(":root {
		--hue: 214;
		--template-bg-light: #f0f4fb;
		--template-text-dark: #495057;
		--template-text-light: #ffffff;
		--template-link-color: var(--link-color);
		--template-special-color: #001B4C;
	}");

// Override 'template.active' asset to set correct ltr/rtl dependency
$wa->registerStyle('template.active', '', [], [], ['template.xbwreckers.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr')]);

// Logo file or site title param
if ($this->params->get('logoFileLeft')) {
    $logocircle = HTMLHelper::_('image', Uri::root(false) . htmlspecialchars($this->params->get('logoFileLeft'), ENT_QUOTES), $sitename, ['loading' => 'eager', 'decoding' => 'async'], false, 0);
} else {
    $logocircle = HTMLHelper::_('image', Uri::root(false) . '/media/templates/site/xbwreckers/images/wr-circle-logo-250.png', $sitename, ['loading' => 'eager', 'decoding' => 'async'], false, 0);   
}

if ($this->params->get('logoFileCentre')) {
    $logotext = HTMLHelper::_('image', Uri::root(false) . htmlspecialchars($this->params->get('logoFileCentre'), ENT_QUOTES), $sitename, ['loading' => 'eager', 'decoding' => 'async'], false, 0);
} else {
    $logotext = HTMLHelper::_('image', Uri::root(false) . '/media/templates/site/xbwreckers/images/wr-text-logo-350.png', $sitename, ['loading' => 'eager', 'decoding' => 'async'], false, 0);
}

$hasClass = '';

if ($this->countModules('sidebar-left', true)) {
    $hasClass .= ' has-sidebar-left';
}

if ($this->countModules('sidebar-right', true)) {
    $hasClass .= ' has-sidebar-right';
}

// Container
$wrapper = $this->params->get('fluidContainer') ? 'wrapper-fluid' : 'wrapper-static';

$this->setMetaData('viewport', 'width=device-width, initial-scale=1');

$stickyHeader = $this->params->get('stickyHeader') ? 'position-sticky sticky-top' : '';

// Defer fontawesome for increased performance. Once the page is loaded javascript changes it to a stylesheet.
$wa->getAsset('style', 'fontawesome')->setAttribute('rel', 'lazy-stylesheet');
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">

<head>
    <link rel="icon" type="image/png" href="<?php echo Uri::base(true); ?>/media/templates/site/xbwreckers/images/favicons/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/media/templates/site/xbwreckers/images/favicons/favicon.svg" />
    <link rel="shortcut icon" href="/media/templates/site/xbwreckers/images/favicons/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/media/templates/site/xbwreckers/images/favicons/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="Wreckers Radio" />
    <link rel="manifest" href="/media/templates/site/xbwreckers/images/favicons/site.webmanifest" />
    <jdoc:include type="metas" />
    <jdoc:include type="styles" />
    <jdoc:include type="scripts" />
</head>

<body class="site <?php echo $option
    . ' ' . $wrapper
    . ' view-' . $view
    . ($layout ? ' layout-' . $layout : ' no-layout')
    . ($task ? ' task-' . $task : ' no-task')
    . ($itemid ? ' itemid-' . $itemid : '')
    . ($pageclass ? ' ' . $pageclass : '')
    . $hasClass
    . ($this->direction == 'rtl' ? ' rtl' : '');
?>">
	<header class="header container-header full-width<?php echo $stickyHeader ? ' ' . $stickyHeader : ''; ?>">
<div>
    	<div class="wrheader wrheadergrid">
        	<div class="wrheadergrid-item">
                <div class="xbstreamer" >
    				<jdoc:include type="modules" name="streamer" style="wrheader-stream" />
                </div>
        	</div>
        	<div class="wrheadergrid-item">
                <div class="wrsite-textlogo" >
                 	<?php echo $logotext; ?>                
                </div>
                <div class="wrsite-slug">
                	<?php echo htmlspecialchars($this->params->get('siteSlug')); ?>
                </div>
                <div class="wrsite-desc">
                	<p><?php if ($this->params->get('siteDescription') == '') : ?>
                	Specialising in supporting local musicians and venues with live music<br /> from, or about, or played "West-the-Exe". <br />Any genre so long as local folk are involved.
                	<?php else : echo htmlspecialchars($this->params->get('siteDescription')); endif; ?>
                	</p>
                </div>

            </div>
        	<div class="wrheadergrid-item">
        		<div class="wrsite-circlelogo">
            		<a href="<?php echo $this->baseurl; ?>/" title="<?php echo $sitename.' - home';?>">
                    	<?php echo $logocircle; ?>
                    </a>    	
        		</div>
         	</div>
        </div>
</div>
        <?php if ($this->countModules('menu', true) || $this->countModules('search', true)) : ?>
            <div class="grid-child container-nav">
                <?php if ($this->countModules('menu', true)) : ?>
                    <jdoc:include type="modules" name="menu" style="none" />
                <?php endif; ?>
                <?php if ($this->countModules('search', true)) : ?>
                    <div class="container-search">
                        <jdoc:include type="modules" name="search" style="none" />
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </header>

    <div class="site-grid">
        <?php if ($this->countModules('banner', true)) : ?>
            <div class="container-banner full-width">
                <jdoc:include type="modules" name="banner" style="none" />
            </div>
        <?php endif; ?>

        <?php if ($this->countModules('top-a', true)) : ?>
            <div class="grid-child container-top-a">
                <jdoc:include type="modules" name="top-a" style="card" />
            </div>
        <?php endif; ?>

        <?php if ($this->countModules('top-b', true)) : ?>
            <div class="grid-child container-top-b">
                <jdoc:include type="modules" name="top-b" style="card" />
            </div>
        <?php endif; ?>

        <?php if ($this->countModules('sidebar-left', true)) : ?>
            <div class="grid-child container-sidebar-left">
                <jdoc:include type="modules" name="sidebar-left" style="card" />
            </div>
        <?php endif; ?>

        <div class="grid-child container-component">
            <jdoc:include type="modules" name="breadcrumbs" style="none" />
            <jdoc:include type="modules" name="main-top" style="card" />
            <jdoc:include type="message" />
            <main>
                <jdoc:include type="component" />
            </main>
            <jdoc:include type="modules" name="main-bottom" style="card" />
        </div>

        <?php if ($this->countModules('sidebar-right', true)) : ?>
            <div class="grid-child container-sidebar-right">
                <jdoc:include type="modules" name="sidebar-right" style="card" />
            </div>
        <?php endif; ?>

        <?php if ($this->countModules('bottom-a', true)) : ?>
            <div class="grid-child container-bottom-a">
                <jdoc:include type="modules" name="bottom-a" style="card" />
            </div>
        <?php endif; ?>

        <?php if ($this->countModules('bottom-b', true)) : ?>
            <div class="grid-child container-bottom-b">
                <jdoc:include type="modules" name="bottom-b" style="card" />
            </div>
        <?php endif; ?>
    </div>

    <?php if ($this->countModules('footer', true)) : ?>
        <footer class="container-footer footer full-width">
            <div class="grid-child">
                <jdoc:include type="modules" name="footer" style="none" />
            </div>
        </footer>
    <?php endif; ?>

    <?php if ($this->params->get('backTop') == 1) : ?>
        <a href="#top" id="back-top" class="back-to-top-link" aria-label="<?php echo Text::_('TPL_CASSIOPEIA_BACKTOTOP'); ?>">
            <span class="icon-arrow-up icon-fw" aria-hidden="true"></span>
        </a>
    <?php endif; ?>

    <jdoc:include type="modules" name="debug" style="none" />
</body>

</html>
