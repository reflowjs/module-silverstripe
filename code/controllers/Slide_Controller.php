<?php

class Slide_Controller extends ContentController
{
    /**
     * @var array
     */
    private static $allowed_actions = array(
        "Markup",
        "Styles",
        "Scripts",
    );

    /**
     * @return string
     */
    public function Markup()
    {
        return $this->data()->Markup;
    }

    /**
     * @return string
     */
    public function Styles()
    {
        $this->response->addHeader("Content-Type", "text/css");

        return $this->data()->Styles;
    }

    /**
     * @return string
     */
    public function Scripts()
    {
        $this->response->addHeader("Content-Type", "text/javascript");

        return $this->data()->Scripts;
    }
}
