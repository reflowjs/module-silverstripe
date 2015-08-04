<?php

/**
 * @mixin Slide
 */
class Slide_Controller extends ContentController
{
    /**
     * @var array
     */
    private static $allowed_actions = array(
        "Markup",
        "Styles",
        "Scripts",
        "Capture",
    );

    /**
     * @return string
     */
    public function Markup()
    {
        return $this->Markup;
    }

    /**
     * @return string
     */
    public function Styles()
    {
        $this->response->addHeader("Content-Type", "text/css");

        return $this->Styles;
    }

    /**
     * @return string
     */
    public function Scripts()
    {
        $this->response->addHeader("Content-Type", "text/javascript");

        return $this->Scripts;
    }

    /**
     * @return string
     */
    public function Capture()
    {
        $this->response->addHeader("Content-Type", "text/javascript");

        $parent = $this->Parent();
        $segment = $this->URLSegment;

        $url = rtrim($parent->AbsoluteLink(), "/") . "#" . $segment;

        return "
            var page = require('webpage').create();
            var fs = require('fs');

            page.open('{$url}', function() {
                page.render('{$segment}.png');

                fs.write('/dev/stdout', page.renderBase64('PNG'), 'w');

                phantom.exit();
            });
        ";
    }
}
