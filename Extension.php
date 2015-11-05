<?php

namespace Bolt\Extension\Leskis\CookieConsent;

use Bolt\Application;
use Bolt\BaseExtension;

class Extension extends BaseExtension
{

    public function getName()
    {
        return "cookie-consent";
    }

    public function initialize()
    {
        $this->addSnippet('endofhead', 'snippetConfCookieConsent');

        $this->addJavascript(
            "assets/cookieconsent2/build/cookieconsent.min.js",
            array('late' => true, 'priority' => 1000)
        );
    }

    protected function snippetConfCookieConsent()
    {
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
<script src="cookieconsent.min.js"></script>
EOM;

        $html = str_replace("%message%", htmlspecialchars($this->config['message'], ENT_QUOTES, "UTF-8"), $html);
        $html = str_replace("%dismiss%", htmlspecialchars($this->config['dismiss'], ENT_QUOTES, "UTF-8"), $html);
        $html = str_replace("%learnMore%", htmlspecialchars($this->config['learnMore'], ENT_QUOTES, "UTF-8"), $html);

        if ($this->config['link'] != "") {
            $html = str_replace("%link%", "'" . htmlspecialchars($this->config['link'], ENT_QUOTES, "UTF-8") . "'", $html);
        } else {
            $html = str_replace("%link%", null, $html);
        }

        if ($this->config['container'] != "") {
            $html = str_replace("%container%", "'" . htmlspecialchars($this->config['container'], ENT_QUOTES, "UTF-8") . "'", $html);
        } else {
            $html = str_replace("%container%", null, $html);
        }

        $html = str_replace("%theme%", htmlspecialchars($this->config['theme'], ENT_QUOTES, "UTF-8"), $html);
        $html = str_replace("%path%", htmlspecialchars($this->config['path'], ENT_QUOTES, "UTF-8"), $html);
        $html = str_replace("%expiryDays%", $this->config['expiryDays'], $html);

        return $html;
    }

}
