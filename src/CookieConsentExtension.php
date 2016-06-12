<?php

namespace Bolt\Extension\Leskis\CookieConsent;

use Bolt\Asset\Snippet\Snippet;
use Bolt\Asset\File\JavaScript;
use Bolt\Extension\SimpleExtension;

/**
 * CookieConsent extension class.
 */
class CookieConsentExtension extends SimpleExtension
{

    protected function getDefaultConfig()
    {
        return [
            'message'    => 'This website uses cookies to ensure you get the best experience on our website',
            'dismiss'    => 'Got it!',
            'learnMore'  => 'More info',
            'link'       => '',
            'container'  => '',
            'theme'      => 'light-floating',
            'path'       => '/',
            'domain'     => '',
            'expiryDays' => 365
        ];
    }

    protected function registerAssets()
    {
        return [
            (new JavaScript('cookieconsent.min.js'))->setZone(Zone::FRONTEND)->setLate(true)->setPriority(998),
            (new Snippet())->setCallback([$this, 'cookieConsentSnippet'])->setZone(Zone::FRONTEND)->setLate(true)->setPriority(997),
        ];
    }

    public function cookieConsentSnippet()
    {
        $config = $this->getConfig();

        $html = <<< EOM
<script>
window.cookieconsent_options = {
    message: '%message%',
    dismiss: '%dismiss%',
    learnMore: '%learnMore%',
    link: %link%,
    container: %container%,
    theme: '%theme%',
    path: '%path%',
    domain: '%domain%',
    expiryDays: %expiryDays%
};
</script>
EOM;
        $html = str_replace("%message%", htmlspecialchars($config['message'], ENT_QUOTES, "UTF-8"), $html);
        $html = str_replace("%dismiss%", htmlspecialchars($config['dismiss'], ENT_QUOTES, "UTF-8"), $html);
        $html = str_replace("%learnMore%", htmlspecialchars($config['learnMore'], ENT_QUOTES, "UTF-8"), $html);

        if ($config['link'] != "") {
            $html = str_replace("%link%", "'" . htmlspecialchars($config['link'], ENT_QUOTES, "UTF-8") . "'", $html);
        } else {
            $html = str_replace("%link%", 'null', $html);
        }

        if ($config['container'] != "") {
            $html = str_replace("%container%", "'" . htmlspecialchars($config['container'], ENT_QUOTES, "UTF-8") . "'", $html);
        } else {
            $html = str_replace("%container%", 'null', $html);
        }

        $html = str_replace("%theme%", htmlspecialchars($config['theme'], ENT_QUOTES, "UTF-8"), $html);
        $html = str_replace("%path%", htmlspecialchars($config['path'], ENT_QUOTES, "UTF-8"), $html);
        $html = str_replace("%domain%", htmlspecialchars($config['domain'], ENT_QUOTES, "UTF-8"), $html);
        $html = str_replace("%expiryDays%", $config['expiryDays'], $html);

        return new \Twig_Markup($html, 'UTF-8');
    }
}
