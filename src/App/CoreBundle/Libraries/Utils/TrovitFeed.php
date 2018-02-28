<?php

namespace App\CoreBundle\Libraries\Utils;

class TrovitFeed {

    private $dom;
    private $trovit;

    /**
     * TrovitFeed constructor.
     */
    public function __construct() {
        $this->dom = new DOMDocument('1.0', 'UTF-8');
        $this->trovit = $this->dom->createElement('trovit');
        $this->dom->appendChild($this->trovit);
    }

    /**
     * @param $ad
     * @param $id
     * @param $content
     */
    private function appendElement($ad, $id, $content) {
        if (empty($content)) {
            return;
        }

        $element = $this->dom->createElement($id);
        $cdata = $this->dom->createCDATASection($content);
        $element->appendChild($cdata);
        $ad->appendChild($element);
    }

    /**
     * @param Bike $bike
     * @param $url
     * @param $imageUrls
     */
    public function addBikeAd(Bike $bike, $url, $imageUrls) {
        $ad = $this->dom->createElement('ad');
        $this->trovit->appendChild($ad);

        $this->appendElement($ad, 'id', $bike->getId());

        $url .= '?utm_source=trovit&utm_medium=ad&utm_campaign=feed&utm_content=' . $bike->getSlug();
        $this->appendElement($ad, 'url', $url);

        $this->appendElement($ad, 'title', $bike->getNicename());

        $description = implode(', ', array(
            $bike->getDescription(),
            $bike->getNiceName(),
            $bike->getFrameSizeInch(),
            $bike->getFrameMaterialName(),
            $bike->bikeTypeList(),
            'bicycle for sale on bikesoup, the UK\'s Premier New &amp; Used Bicycle Marketplace'
        ));

        $this->appendElement($ad, 'content', $description);
        $this->appendElement($ad, 'category', 'Bicycles');

        $this->appendElement($ad, 'make', $bike->getBrandname());
        $this->appendElement($ad, 'model', $bike->getModel());
        $this->appendElement($ad, 'price', $bike->getPrice());

        $this->appendElement($ad, 'date', strftime('%d/%m/%Y', strtotime($bike->getCreatedAt()))
        );
        $this->appendElement($ad, 'time', strftime('%H:%M', strtotime($bike->getCreatedAt()))
        );

        if (!empty($imageUrls)) {
            $pictures = $this->dom->createElement('pictures');
            $ad->appendChild($pictures);

            $i = 1;
            foreach ($imageUrls as $imageUrl) {
                $picture = $this->dom->createElement('picture');
                $pictures->appendChild($picture);
                $this->appendElement($picture, 'picture_title', 'image ' . ($i++));
                $this->appendElement($picture, 'picture_url', $imageUrl);
            }
        }

        $profile = $bike->getSeller()->getProfile();
        $this->appendElement($ad, 'city', $profile->getCity());
        $this->appendElement($ad, 'region', $profile->getCounty());
    }

    public function getXml() {
        return $this->dom->saveXml();
    }

}
