<?php
namespace Omeka\View\Helper;

use Zend\Form\Element\Select;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;

class DataType extends AbstractHelper
{
    protected $manager;

    protected $dataTypes;

    protected $valueOptions = [];

    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->manager = $serviceLocator->get('Omeka\DataTypeManager');
        $this->dataTypes = $this->manager->getRegisteredNames();
        foreach ($this->dataTypes as $dataType) {
            $this->valueOptions[$dataType] = $this->manager->get($dataType)->getLabel();
        }
    }

    /**
     * Get the data type select markup.
     *
     * @param string $name
     * @param string $value
     */
    public function getSelect($name, $value)
    {
        $element = new Select($name);
        $element->setValueOptions($this->valueOptions);
        if (!array_key_exists($value, $this->valueOptions)) {
            $value = 'normal';
        }
        $element->setValue($value);
        return $this->getView()->formSelect($element);
    }

    public function getTemplates()
    {
        $templates = '';
        foreach ($this->dataTypes as $dataType) {
            $templates .= $this->getView()->partial('common/data-type-wrapper', [
                'dataType' => $dataType,
            ]);
        }
        return $templates;
    }

    public function getTemplate($dataType)
    {
        return $this->manager->get($dataType)->getTemplate($this->getView());
    }
}
