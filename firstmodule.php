<?php 

if (!defined('_PS_VERSION_')) {
    exit;
}

class FirstModule extends Module
{
    public function __construct() 
    {
        $this->name = "firstmodule";
        $this->tab = "front_office";
        $this->version = "1.0";
        $this->author = "Krystian Kempa";
        $this->need_instance = 0;
        $this->ps_version_compliancy = [
            "min" => "1.6",
            "max" => _PS_VERSION_
        ];
        $this ->bootstrap = true;

        parent::__construct();

        $this->displayName = "FirstModule";
        $this->description = "Moduł PrestaShop 1.7, którego zadaniem jest wyświetlanie bloku HTML na głównej stronie sklepu internetowego";
        $this->confirmUnistall = "Are you sure you want to unistall this module?";
    }

    public function install() 
    {
        return parent::install()
        && Configuration::updateValue('FIRST_MODULE', 'Moduł PrestaShop 1.7, którego zadaniem jest wyświetlanie bloku HTML na głównej stronie sklepu internetowego')
        && $this->registerHook('displayHome');
    }

    public function uninstall() 
    {
        return parent::uninstall()
        && Configuration::deleteByName('FIRST_MODULE');
    }

    public function hookdisplayHome($params) 
    {
        $this->context->controller->addCSS($this->getPathUri() . 'views/css/firstmodule.css');
        $this->context->controller->addJS($this->_path . 'front.js');
        Media::addJsDef([
            'firstmodule_ajax_url' => $this->context->link->getModuleLink('firstmodule', 'ajax')
        ]);
        return $this->display(__FILE__, 'views\templates\hook\firstmodule.tpl');
    }
    
    public function displayForm()
    {
        $form = [
            'form' => [
                'legend' => [
                    'title' => $this->l('Ustawienia modułu'),
                ],
                'input' => [
                    [
                        'type' => 'text',
                        'label' => $this->l('Wpisz wartość: '),
                        'name' => 'FIRST_MODULE',
                        'size' => 20,
                        'required' => true,
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Zapisz.'),
                    'class' => 'btn btn-default pull-right',
                ],
            ],
        ];

        $helper = new HelperForm();

        $helper->table = $this->table;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&' . http_build_query(['configure' => $this->name]);
        $helper->submit_action = 'submit' . $this->name;

        $helper->default_form_language = (int) Configuration::get('PS_LANG_DEFAULT');

        $helper->fields_value['FIRST_MODULE'] = Tools::getValue('FIRST_MODULE', Configuration::get('FIRST_MODULE'));

        return $helper->generateForm([$form]);
    }    

    public function getContent()
    { 
        $output = '';

        if (Tools::isSubmit('submit' . $this->name)) {
            $configValue = (string) Tools::getValue('FIRST_MODULE');
    
            if (empty($configValue) || !Validate::isGenericName($configValue)) {
                $output = $this->displayError($this->l('Nie wprowadziłeś danych!'));
            } else {
                Configuration::updateValue('FIRST_MODULE', $configValue);
                $output = $this->displayConfirmation($this->l('Dane zmienione pomyślnie!'));
            }
        }

        return $output . $this->displayForm();
    }
}