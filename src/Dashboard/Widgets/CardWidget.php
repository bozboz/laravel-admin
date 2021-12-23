<?php

namespace Bozboz\Admin\Dashboard\Widgets;

class CardWidget implements Widget
{
    protected $title;
    protected $heading;
    protected $body;
    protected $footer;
    protected $width = 'col-sm-6 col-md-4 col-lg-3';
    protected $flex = '';
    protected $panelClass = 'panel-default';
    protected $panelHeadingClass = 'panel-heading';
    protected $panelBodyClass = 'panel-body';
    protected $panelFooterClass = 'panel-footer';
    protected $maxWidth = '100%';

    /**
     * @param string $heading
     */
    public function setHeading(string $heading)
    {
        $this->heading = $heading;
        return $this;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param string $width
     */
    public function setWidth(string $width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @param string $maxWidth
     */
    public function setMaxWidth(string $maxWidth)
    {
        $this->maxWidth = $maxWidth;
        return $this;
    }

    /**
     * @param string $flex
     */
    public function setFlex(string $flex)
    {
        $this->flex = $flex;
        return $this;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @param mixed $footer
     */
    public function setFooter($footer)
    {
        $this->footer = $footer;
        return $this;
    }

    /**
     * @param string $panelClass
     */
    public function setPanelClass(string $panelClass)
    {
        $this->panelClass = $panelClass;
        return $this;
    }

    /**
     * @param string $panelHeadingClass
     */
    public function setPanelHeadingClass(string $panelHeadingClass)
    {
        $this->panelHeadingClass = $panelHeadingClass;
        return $this;
    }

    /**
     * @param string $panelBodyClass
     */
    public function setPanelBodyClass(string $panelBodyClass)
    {
        $this->panelBodyClass = $panelBodyClass;
        return $this;
    }

    /**
     * @param string $panelFooterClass
     */
    public function setPanelFooterClass(string $panelFooterClass)
    {
        $this->panelFooterClass = $panelFooterClass;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getHeading()
    {
        return $this->heading;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return mixed
     */
    public function getFooter()
    {
        return $this->footer;
    }

    /**
     * @return mixed
     */
    public function getHeadingElement()
    {
        if (!$this->getHeading() && !$this->getTitle()) {
            return '';
        }
        return <<<HTML
<div class="{$this->panelHeadingClass}">
    {$this->getTitleElement()}{$this->getHeading()}
</div>
HTML;
    }

    /**
     * @return mixed
     */
    public function getTitleElement()
    {
        if (!$this->getTitle()) {
            return '';
        }
        return <<<HTML
<div class="panel-title">{$this->getTitle()}</div>
HTML;
    }

    /**
     * @return mixed
     */
    public function getBodyElement()
    {
        if (!$this->getBody()) {
            return '';
        }
        return <<<HTML
<div class="{$this->panelBodyClass}">
    {$this->getBody()}
</div>
HTML;
    }

    /**
     * @return mixed
     */
    public function getFooterElement()
    {
        if (!$this->getFooter()) {
            return '';
        }
        return <<<HTML
<div class="{$this->panelFooterClass}">
    {$this->getFooter()}
</div>
HTML;
    }

    public function render()
    {
        return <<<HTML
<div class="{$this->width} {$this->flex}">
    <div class="panel {$this->panelClass}">
        {$this->getHeadingElement()}{$this->getBodyElement()}{$this->getFooterElement()}
    </div>
</div>
HTML;

    }
}
