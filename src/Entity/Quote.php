<?php

class Quote extends AbstractEntity
{
    public $id;
    public $siteId;
    public $destinationId;
    public $dateQuoted;

    protected static $_tokenList = [
        'destination_link',
        'summary_html',
        'summary',
        'destination_name',
        'destination_link'
    ];

    public function __construct($id, $siteId, $destinationId, $dateQuoted)
    {
        $this->id = $id;
        $this->siteId = $siteId;
        $this->destinationId = $destinationId;
        $this->dateQuoted = $dateQuoted;
    }

    public function renderDestinationLink()
    {
        $destinationItem = DestinationRepository::getInstance()->getById($this->destinationId);
        if (empty($destinationItem)) {
            // Log: Destination: $this->destinationId is missing from repository
            return null;
        }
        $siteItem = SiteRepository::getInstance()->getById($this->siteId);
        if (empty($siteItem)) {
            // Log: Site: $this->siteId is missing from repository
            return null;
        }
        return $siteItem->url . '/' . $destinationItem->countryName . '/quote/' . $this->id;
    }

    public function renderSummaryHtml()
    {
        return '<p>' . $this->id . '</p>';
    }

    public function renderSummary()
    {
        return (string) $this->id;
    }

    public function renderDestinationName()
    {
        $destinationItem = DestinationRepository::getInstance()->getById($this->destinationId);
        return $destinationItem->countryName;
    }
}