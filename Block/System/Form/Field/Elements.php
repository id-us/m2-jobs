<?php
namespace Idus\Jobs\Block\System\Form\Field;

class Elements extends \Idus\Core\Block\System\Form\Field\Multi {

    protected function _construct() {

        $this->addColumn('element', ['label' => __('Element'), 'type' => 'select', 'options' => $this->getElements(), 'class' => 'required-entry layouts_element_select']);
        $this->addColumn('structure', ['label' => __('Structure'), 'type' => 'textarea']);

        $this->addColumn('variable', ['label' => __('Variable'), 'type' => 'widget', 'input' => 'text', 'default_value' => '','class' => 'variable']);

        $this->_addAfter = true;
        $this->_addButtonLabel = __('Add');
        parent::_construct();
    }

    protected function getElements() {

        $dir = realpath(dirname(__FILE__).'/../../../../').'/view/frontend/templates/elements';
        $element = array();

        foreach (scandir($dir) as $file){
            if(is_file($dir.'/'.$file) && strstr($file, '.phtml')){
                $element['Idus_Jobs::elements/'.$file] = str_replace('.phtml', '', $file);
            }
        }

        $this->setRenderElements($element);
        $this->_eventManager->dispatch( 'idus_jobs_list_render_elements', ['instance' => $this] );
        return $this->getRenderElements();
    }
}