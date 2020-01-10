# GyverLamp-PHP-API
GyverLamp PHP Controller. Can be used in fully remote lamp control from Internet, or in other 

## Documentation
All documentation written in PHPDoc in each class file.


## Example

```php
<?php

	include 'GyverLamp.class.php';
	include 'LampModes.class.php';
	
	// Connectiong to GyverLamp on 192.168.1.125:8888
	$lamp = new GyverLamp('192.168.1.125', 8888);
	
	// Power off lamp
	$lamp->setPower(false);
	
	// Select Fire mode
	$lamp->setMode(LampModes::byName('Fire'));
	// Set full brightness
	$lamp->setBrightness(255);
	// Set 25/255 effect speed
	$lamp->setSpeed(25);
	// Set 0 effect scale
	$lamp->setScale(0);
	
	// Toggle power, enabling lamp
	$lamp->togglePower();
	
	// Print current lamp info
	print_r($lamp->getInfo());
```
