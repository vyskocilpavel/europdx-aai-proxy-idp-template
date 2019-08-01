<?php

use SimpleSAML\Module;
use SimpleSAML\Configuration;
use SimpleSAML\Logger;
use SimpleSAML\Error\Exception;
use SimpleSAML\Utils\HTTP;
use SimpleSAML\Module\perun\DiscoTemplate;

/**
 * This is simple example of template for perun Discovery service
 *
 * Allow type hinting in IDE
 * @var DiscoTemplate $this
 */

$this->data['jquery'] = array('core' => true, 'ui' => true, 'css' => true);

$this->data['head'] = '<link rel="stylesheet" media="screen" type="text/css" href="' .
                      Module::getModuleUrl('discopower/assets/css/disco.css') . '" />';

$this->data['head'] .= '<link rel="stylesheet" media="screen" type="text/css" href="' .
                       Module::getModuleUrl('perun/res/css/disco.css') . '" />';

$this->data['head'] .= '<script type="text/javascript" src="' .
                       Module::getModuleUrl('perun/res/js/jquery.livesearch.js') . '"></script>';

$this->data['head'] .= '<script type="text/javascript" src="' .
                       Module::getModuleUrl('discopower/assets/js/suggest.js') . '"></script>';

$this->data['head'] .= searchScript();


const PERUN_CONFIG_FILE_NAME = 'module_perun.php';


const URN_CESNET_PROXYIDP_IDPENTITYID = "urn:cesnet:proxyidp:idpentityid:";

$authContextClassRef = null;
$idpEntityId = null;


if (isset($this->data['AuthnContextClassRef'])) {
    $authContextClassRef = $this->data['AuthnContextClassRef'];
}

$this->includeAtTemplateBase('includes/header.php');

if ($authContextClassRef != null) {
    foreach ($authContextClassRef as $value) {
        if (substr($value, 0, strlen(URN_CESNET_PROXYIDP_IDPENTITYID))
            === URN_CESNET_PROXYIDP_IDPENTITYID) {
            $idpEntityId = substr($value, strlen(URN_CESNET_PROXYIDP_IDPENTITYID), strlen($value));
            Logger::info("Redirecting to " . $idpEntityId);
            $url = $this->getContinueUrl($idpEntityId);
            HTTP::redirectTrustedURL($url);
            exit;
        }
    }
}


if (!empty($this->getPreferredIdp())) {
    echo '<p class="descriptionp">' . $this->t('{perun:disco:previous_selection}') . '</p>';
    echo '<div class="metalist list-group">';
    echo showEntry($this, $this->getPreferredIdp(), true);
    echo '</div>';

}

echo '<div class="row">';
foreach ($this->getIdps('preferred') as $idpentry) {
    echo '<div class="col-md-4">';
    echo '<div class="metalist list-group">';
    echo showEntry($this, $idpentry, false);
    echo '</div>';
    echo '</div>';
}
echo '</div>';

echo '<div class="row">';
foreach ($this->getIdps('social') as $idpentry) {
    echo '<div class="col-md-4">';
    echo '<div class="metalist list-group">';
    echo showEntry($this, $idpentry, false);
    echo '</div>';
    echo '</div>';
}
echo '</div>';


$this->includeAtTemplateBase('includes/footer.php');

function searchScript()
{

    $script = '<script type="text/javascript">

	$(document).ready(function() { 
		$("#query").liveUpdate("#list");
	});
	
	</script>';

    return $script;
}

/**
 * @param DiscoTemplate $t
 * @param array $metadata
 * @param bool $favourite
 * @return string html
 */
function showEntry($t, $metadata, $favourite = false)
{

    if (isset($metadata['tags']) &&
        (in_array('social', $metadata['tags']) || in_array('preferred', $metadata['tags']))) {
        return showTaggedEntry($t, $metadata);
    }

    $extra = ($favourite ? ' favourite' : '');
    $html = '<a class="metaentry' . $extra . ' list-group-item" href="' .
            $t->getContinueUrl($metadata['entityid']) . '">';

    $html .= '<strong>' . $t->getTranslatedEntityName($metadata) . '</strong>';

    $html .= showIcon($metadata);

    $html .= '</a>';

    return $html;
}

/**
 * @param DiscoTemplate $t
 * @param array $metadata
 * @return string html
 */
function showTaggedEntry($t, $metadata)
{

    $bck = 'white';
    if (!empty($metadata['color'])) {
        $bck = $metadata['color'];
    }

    $html = '<a class="btn btn-block tagged" href="' . $t->getContinueUrl($metadata['entityid']) .
            '" style="background: ' . $bck . '">';

    $html .= '<img src="' . $metadata['icon'] . '">';

    $html .= '<strong>Sign in with ' . $t->getTranslatedEntityName($metadata) . '</strong>';

    $html .= '</a>';

    return $html;
}


function showIcon($metadata)
{
    $html = '';
    // Logos are turned off, because they are loaded via URL from IdP. Some IdPs have bad configuration,
    // so it breaks the WAYF.

    /*if (isset($metadata['UIInfo']['Logo'][0]['url'])) {
        $html .= '<img src="' .
                    htmlspecialchars(\SimpleSAML\Utils\HTTP::resolveURL($metadata['UIInfo']['Logo'][0]['url'])) .
                    '" class="idp-logo">';
    } else if (isset($metadata['icon'])) {
        $html .= '<img src="' . htmlspecialchars(\SimpleSAML\Utils\HTTP::resolveURL($metadata['icon'])) .
                    '" class="idp-logo">';
    }*/

    return $html;
}

