<?php
// Include the class (using require_once just for good measure)
require_once('xml.php');

// This is just dummy data to show what the class does
$exampleData = array('root'=> array('foobar'=> 'foovalue',
				    'flump'=> array('doh'),
                                    'hedk option="something"'=> array('gnu'=> 'gnu is not unix',
                                                                      'linux' => 'linux is gnu'),),);

// Initiate the class
$xml = new xml();

// Set the array so the class knows what to create the XML from
$xml->setArray($exampleData);

// Print the XML to screen
$xml->outputXML('echo');

?>