Testcases created using Phpunit for https://autoblog.truecar.com 

To execute the script, download phpunit from https://phpunit.de/ and you should have selenium server running in your local (Host is set to local and browser: firefox)

To execute the whole file, run --> phpunit TrueCarTest.php. 


This is a simple Automation test suite designed to navigate through the pages and to check the trim value in the dealer page. 

This testsuite right now is designed with the DOM elements and data defined as mutlidimesion array in the same suite. But this can be improved by creating UImap for each page and righting some classes/functions to call them in the test suite like 
$this->click('button', 'Next') will click the button the Next in the specified form.

 We can create a framework with steps for each page and we can read  element by element in a step and based on the type of element (like button, text etc), the form is filled and also we can name a element called "ViewNext". When "ViewNext" element is read, the framework will automatically move to the next page. So the same framework will be reused across all the pages and can handle any element.
 
 Also general improvements on the framework to run by a particular group, including bootstrap, config files etc can be done.
 






