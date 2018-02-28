<?php

namespace App\CoreBundle\Libraries\Utils;

class Xmlpipe2 {

    private $dom;
    private $docset;

    /**
     * @return array
     */
    public function getFields() {
        return array(
            'name',
            'model',
            'description',
            'brand_other',
            'frame_number',
            'location_postcode',
            'bike_type_list_name',
            'brand_name',
            'frame_size_name',
            'frame_material_name',
            'groupset_name',
            'brake_type_name',
            'wheel_size_name',
            'gender',
            'seller_type',
        );
    }

    /**
     * @return array
     */
    public function getAttributes() {
        return array(
            'new_bike' => 'bool',
            'bike_type_list' => 'multi',
            'brand_id' => 'int',
            'frame_size' => 'int',
            'frame_material_id' => 'int',
            'groupset_id' => 'int',
            'brake_type_id' => 'int',
            'wheel_size_id' => 'int',
            'gears_front' => 'int',
            'gears_rear' => 'int',
            'year' => 'int',
            'mileage' => 'int',
            'original_documentation' => 'bool',
            'price' => 'float',
            'location_lat' => 'deg',
            'location_lon' => 'deg',
            'created_at' => 'timestamp',
            'cycletowork' => 'bool',
            'registered' => 'bool',
        );
    }

    /**
     * Xmlpipe2 constructor.
     */
    public function __construct() {
        $this->dom = new \DOMDocument('1.0', 'UTF-8');
        $this->docset = $this->dom->createElement('sphinx:docset');
        $this->dom->appendChild($this->docset);

        $this->addSchema();
    }

    /**
     * @param $parent
     * @param $id
     * @param $content
     */
    protected function appendElement($parent, $id, $content) {
        if (empty($content)) {
            return;
        }

        $element = $this->dom->createElement($id);
        //$cdata = $this->dom->createCDATASection($content);
        $cdata = $this->dom->createTextNode($content);
        $element->appendChild($cdata);
        $parent->appendChild($element);
    }

    /**
     * @param $schema
     * @param $name
     */
    protected function addSchemaField($schema, $name) {
        $field = $this->dom->createElement('sphinx:field');
        $node = $schema->appendChild($field);
        $node->setAttribute('name', $name);
    }

    /**
     * @param $schema
     * @param $name
     * @param $type
     */
    protected function addSchemaAttr($schema, $name, $type) {
        $attr = $this->dom->createElement('sphinx:attr');
        $node = $schema->appendChild($attr);
        $node->setAttribute('name', $name);
        $node->setAttribute('type', $type);
    }

    protected function addSchema() {
        $schema = $this->dom->createElement('sphinx:schema');
        $this->docset->appendChild($schema);

        foreach ($this->getFields() as $name) {
            $this->addSchemaField($schema, $name);
        }

        foreach ($this->getAttributes() as $name => $type) {
            if ($type == 'deg') {
                $type = 'float';
            }
            $this->addSchemaAttr($schema, $name, $type);
        }
    }

    protected function appendMulti($document, $name, $values) {
        if (empty($values)) {
            return;
        }

        $this->appendElement($document, $name, join(',', $values));
    }

    public function addBike(Bike $bike) {
        $document = $this->dom->createElement('sphinx:document');
        $node = $this->docset->appendChild($document);
        $node->setAttribute('id', $bike->getId());

        foreach ($this->getFields() as $name) {
            $this->appendElement($document, $name, $bike->get($name));
        }
        foreach ($this->getAttributes() as $name => $type) {
            $value = $bike->get($name);
            switch ($type) {
                case 'deg':
                    $this->appendElement($document, $name, deg2rad($value));
                    break;
                case 'multi':
                    $this->appendMulti($document, $name, $value);
                    break;
                case 'timestamp':
                    $this->appendElement($document, $name, strtotime($value));
                    break;
                default:
                    $this->appendElement($document, $name, $value);
            }
        }
    }

    public function getXml() {
        return $this->dom->saveXml();
    }

}
