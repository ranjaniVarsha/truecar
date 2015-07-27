<?php

/**
 * @author: ranjani 
 * @category    tests
 * @package     selenium
 * @subpackage  tests
 * 
 * Test case to test the trim value in TrueCar site: https://autoblog.truecar.com 
 */

class TrueCarTest extends PHPUnit_Extensions_Selenium2TestCase {
    protected $siteURL = "https://autoblog.truecar.com";
    
    //array of all DOM elements
    protected $uiMapElements= array (
        "HomePage" => 
        array(
            "GetStarted" => 
            array(
                "links" =>
                array(
                    "selectCarMakeDropDownLink" => "//div[@id='s2id_home_select_make']/a",
                    "modelInMakeDropDownLink" => "//div[@id='s2id_home_select_model']/a"
                ),    
                "dropdowns" =>
                array(
                    "carNameinMakeDropDown" => "//ul[@class='select2-results']",
                    "makeInModelDropdown" => "//ul[@class='select2-result-sub']"
                ),
                "buttons" =>
                array(
                     "go" => "//span[@id='home_cta']"
                ),    
            ),    
        ),
        "Configurator" =>
        array(
            "Preferences" =>
            array(
                "links" =>
                array(
                    "selectPreferenceDropDownLink" =>"//*[@id='s2id_select_style']//a", 
                ),
                "dropdowns" =>
                array(
                    "style" => "//div[@id='select2-drop']/ul",
                ),
                "buttons" =>
                array(
                    "editOptions" => "//li[contains(., 'Select Options')]/ul[@class='select-details clearfix']//button",
                    "editIncentives" => "//li[contains(., 'Choose Incentives')]/ul[@class='select-details clearfix']//button",
                    "saveAndUpdate" => "//span[contains(. , 'Save & Update')]",
                    "viewDealerPricing" => "//div[@id='price-summary']/div[2]/a"
                ),
                "checkboxes" =>
                array(
                    "optionsPreferencesCheckBox" => "//table[@id='group_primary_paint']//tr[contains(@id, 'row_')]//td[1]/span",
                    "incentivesPreferencesOptions" => "//span[contains(@id, 'incentive_')]"
                )
            )   
        ),
        "Registration" =>
        array(
            "Information" =>
            array(
                "fields" =>
                array(
                    "firstName" =>"//input[@id='given_name']",
                    "lastName" => "//input[@id='family_name']",
                    "address" => "//input[@id='street_address']",
                    "city" => "//input[@id= 'city']",
                    "state" => "//input[@id='state']",
                    "zip" => "//input[@id='postal_code']",
                    "phone" => "//input[@id='phone_number']",
                    "email" => "//input[@id='email_address']"
                ),
                "buttons" =>
                array(
                    "viewDealerPricing" => "//*[contains(@class, 'dealer-pricing')]"
                )    
            )   
        ),
        "Dealer-Selection" =>
        array(
            "GetDealerSelection"=>
            array(
                "buttons" =>
                array(
                    "next" => "//button[contains(@class, 'get-cert')]"
                )
            )
        ),
        "Certification" =>
        array(
            "certificationDetails" =>
            array(
                "pageelements" =>
                 array(
                    "trimValue" => "//div[@id='cert_controls']//h1" , 
                    "dealerName" => "html/body/div[1]/ol/li/div[1]/h2"
                 )
                
            )
        )
    );
    
    //data array
    protected $dataForUIMap = array(
        "HomePage" => 
        array(
            "GetStarted" => 
            array(        
                "carNameInMakeDropDown" => "Lincoln",
                "yearInModelDropDown"  => 2015,
                "makeInModelDropdown"  => 'MKS'
            )
        ),    
        "Configurator" =>
        array(
            "Preferences" =>
            array(
                "expectedUrlConfigValue" => 271887,
                "style" => "3.5L EcoBoost AWD"
            )            
        ),
        "Registration" =>
        array(
            "Information" =>
            array(
                "firstName" =>"testFirst",
                "lastName" => "testLast",
                "address" => "testAddress",
                "city" => "testCity",
                "state" => "CA",
                "zip" => "90405",
                "phone" => "9194567890",
                "email" => "test@test.com"
            )
        )
    );
    
    public static $browsers = array(
        array(
            "name" => "Firefox",
            "browserName" => "firefox",
        ),
    );
 
    /**
     * setup will be run for all our tests
     */
    protected function setUp()  {
        $this->setBrowserUrl($this->siteURL);
        $this->setHost('127.0.0.1');
        $session = $this->prepareSession();
        $session->currentWindow()->maximize();
        
    } // setUp()
 
    /** 
     * <p> Function to test if the Home Page "Get Started" section works right </p>
     * <p> Steps </p>
     * <ol>
     * <li> Navigate to Home Page </li>
     * <li> Complete the 'Get started section </li>
     * <li> Verify if Configurator page with correct url is opened </li>
     * </ol>
     * 
     *@test
     *@group 
     */
    public function test_VerifyTrueCarHomePageGetStarted()  {
 
        //open url
        $this->url($this->siteURL);
        
        //select car make and model
        $configuratorPageID = $this-> makeHomePageSelection();
        
        $url = parse_url($this->url());
      
        preg_match("/\d{6}/", $url['path'], $matches );
        $this->assertEquals($this->dataForUIMap['Configurator']['Preferences']['expectedUrlConfigValue'], $matches[0], "Url Opened after Home Page is wrong"); 
        $this->assertRegExp("/configurator/", $url['path'], "Configurator Page not opened after the Home Page");  
    }    
    /** 
     * <p> Function to test if the Configurator Page "Style selection" section works right </p>
     * <p> Steps </p>
     * <ol>
     * <li> Navigate to Configurator Page</li>
     * <li> Complete the 'Style" section </li>
     * <li> Verify if Registration page with correct url is opened </li>
     * </ol>
     * 
     *@test
     *@group 
     */
    public function test_VerifyTrueCarConfigurator()  {   
        
        $this->url($this->siteURL . "/nc/configurator/" . $this->dataForUIMap['Configurator']['Preferences']['expectedUrlConfigValue']);
        
        sleep(2);
        //select style selections
        $url = $this-> makeConfiguratorPageSelection();
        $url = parse_url($url);
        $this->assertRegExp("/registration/", $url['path'], "Registration Page not opened after the Configurator Page");  
        
    } 
    /** 
     * <p> Function to test if the Cerfication Page  works right </p>
     * <p> Steps </p>
     * <ol>
     * <li> Navigate to Registration Page</li>
     * <li> Complete the Registration Form </li>
     * <li> Verify if Dealer section page with correct url is opened </li>
     * <li> Open Certification page from the Dealer selection page </li>
     * <li> Check if atleast one dealer is present </li>
     * <li> Check if the trim value is displayed correct </li>
     * </ol>
     * 
     *@test
     *@group 
     */
    public function test_VerifyTrueCarRegistration()  {   
        
        $this->url($this->siteURL . "/nc/registration/" . $this->dataForUIMap['Configurator']['Preferences']['expectedUrlConfigValue']); 
        sleep(2);
        $url = $this-> makeRegistrationPageSelection();
        $dealerURL = $url;
        $url = parse_url($url);
        $this->assertRegExp("/dealer-selection/", $url['path'], "Dealer-selection Page not opened after the Registration Page");  
           

        $this->url($dealerURL); 
        sleep(2);
        $url = $this-> dealerSelectionPage();
        $certificateUrl = $url;
        $url = parse_url($url);
        $this->assertRegExp("/certificate/", $url['path'], "Dealer Certificate Page not opened after the Registration Page");  
         
        
        $this->url($certificateUrl);
        
        // to test the Trim text
        $trimText = $this->byXPath($this->uiMapElements['Certification']['certificationDetails']['pageelements']['trimValue']) -> text();
        $this->assertRegExp("/" . $this->dataForUIMap['HomePage']['GetStarted']['carNameInMakeDropDown']. "/", $trimText,
                $this->dataForUIMap['HomePage']['GetStarted']['carNameInMakeDropDown'] . " not seen in Trim selection");
        
        $this->assertRegExp("/" . $this->dataForUIMap['HomePage']['GetStarted']['yearInModelDropDown']. "/", $trimText,
                $this->dataForUIMap['HomePage']['GetStarted']['yearInModelDropDown'] . " not seen in Trim selection");
        
        $this->assertRegExp("/" . $this->dataForUIMap['HomePage']['GetStarted']['makeInModelDropdown']. "/", $trimText,
                $this->dataForUIMap['HomePage']['GetStarted']['makeInModelDropdown'] . " not seen in Trim selection");
        
        //To check if dealer exists for the selected trim
        $iframe = $this->getIframe();

        if ($iframe) {
            $this->frame($iframe);
        } else {
            $this->fail('No dealers found for the selection');
            return false;
        }
       
       
    }
    /**
     * Helper Methods
     */
    
    /**
     * Function to make Home Page Selection - Get started section - select Car make and model
     * @return type
     */
    private function makeHomePageSelection() {
        $this-> selectCarMakeInHomePage();
        $this -> selectCarModelInHomePage();
        
        $this-> clickElement($this->uiMapElements['HomePage']['GetStarted'] ['buttons']['go']);
        sleep(2);
        return $this->url();
        
        
    }
    /**
     * Function to make Configurator Page Selection - select style, options and incentives
     * @return type
     */
    
    private function makeConfiguratorPageSelection() {
        $this -> selectStyleInConfiguratorPage();
        $this -> selectOptionsInConfiguratorPage();
        $this -> selectIncentivesInConfiguratorPage();
        sleep(2); 
        $this->clickElement($this->uiMapElements['Configurator']['Preferences']['buttons']['viewDealerPricing']) ;
        return $this->url();
    }
    
    /**
     * Function to fill up Registration page
     * @return type
     */
    private function makeRegistrationPageSelection(){
        $this -> fillFieldsInRegistrationPage();
        $this-> clickElement($this->uiMapElements['Registration']['Information']['buttons']['viewDealerPricing']);
        sleep(2);
        return $this->url();
    }
    
    /**
     * Function to fill up Dealer selection 
     * @return type
     */
    private function  dealerSelectionPage(){
        
        $this-> clickElement($this->uiMapElements['Dealer-Selection']['GetDealerSelection']['buttons']['next']);
        sleep(2);
        return $this->url();
    }
    /**
     * Function to select Car Make in Home Page 
     * 
     */
    private function selectCarMakeInHomePage(){
         //select Car Make in Home Page
        $this-> clickElement($this->uiMapElements['HomePage']['GetStarted']['links']['selectCarMakeDropDownLink']);
        $carNameXPath = $this -> selectData('li',$this->dataForUIMap['HomePage']['GetStarted']['carNameInMakeDropDown']);
        $this-> clickElement($this->uiMapElements['HomePage']['GetStarted']['dropdowns']['carNameinMakeDropDown'] . $carNameXPath );
    }
    /**
     * Function to select Car Model in Home Page 
     */
    private function selectCarModelInHomePage(){
        // select Model Model in Home Page
        $this-> clickElement($this->uiMapElements['HomePage']['GetStarted']['links']['modelInMakeDropDownLink']);
        $yearModelXPath = $this->selectData('div', $this->dataForUIMap['HomePage']['GetStarted']['yearInModelDropDown']);
        $modelMakeXPath = $this->uiMapElements['HomePage']['GetStarted']['dropdowns']['makeInModelDropdown'] . $this->selectData ('li',$this->dataForUIMap['HomePage']['GetStarted']['makeInModelDropdown']);
        $this->clickElement($this->uiMapElements['HomePage']['GetStarted']['dropdowns']['carNameinMakeDropDown']. $yearModelXPath . "/.." . $modelMakeXPath);
         
    }
    /**
     * Function to select Style in Configurator Page 
     */
    private function selectStyleInConfiguratorPage() {
        $this-> clickElement($this->uiMapElements['Configurator']['Preferences'] ['links']['selectPreferenceDropDownLink']);
        $styleXPath = $this->selectData('li', $this->dataForUIMap['Configurator']['Preferences']['style']);
        $this-> clickElement($this->uiMapElements['Configurator']['Preferences']['dropdowns']['style'] .$styleXPath );
    }
    /**
     * Function to select Options in Configurator Page 
     */
    private function selectOptionsInConfiguratorPage(){
        $this-> clickElement($this->uiMapElements['Configurator']['Preferences']['buttons']['editOptions']);
        $this-> clickElement($this->uiMapElements['Configurator']['Preferences']['checkboxes']['optionsPreferencesCheckBox']);
        $this-> clickElement($this->uiMapElements['Configurator']['Preferences']['buttons']['saveAndUpdate']);
    }
    /**
     * Function to select Incentives in Configurator Page 
     */
    private function selectIncentivesInConfiguratorPage(){
        $this-> clickElement($this->uiMapElements['Configurator']['Preferences']['buttons']['editIncentives']);
        $this-> clickElement($this->uiMapElements['Configurator']['Preferences']['checkboxes']['incentivesPreferencesOptions']);
        $this-> clickElement($this->uiMapElements['Configurator']['Preferences']['buttons']['saveAndUpdate']);
    }
    
    /**
     * Function to fill fields in Registration page
     */
    private function fillFieldsInRegistrationPage(){
        
        foreach($this->uiMapElements['Registration']['Information']['fields'] as $key => $value){
            $elem = $this->byXPath($value);
            $elem ->click();
            $elem->clear();
            $this ->keys($this->dataForUIMap['Registration']['Information'][$key]);
        }
        
    }
    /**
     * Function to click on an element
     */
    private function clickElement($xpath){
        $this->byXPath($xpath)->click();
    }
    /**
     * Function to select Data
     */
    private function selectData($tag, $data){
        $xPath = "//". $tag . "[contains(., '" . $data . "')]";
        return $xPath;
    }
    /**
     * Function to get iframe
     */
    private function getIframe(){
        return $this->element($this->using('tag name')->value('iframe'));
    }
    
}  

    
    