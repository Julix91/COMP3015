<?php

// import the required classes
require("Employee.php");
require("Custodian.php");//extends Employee

$tedd = new Custodian('Tedd','Longman',123,'Minimal');
$tedd->setCertificationLevel('Very high'); ?>
<p>Info on Employee #<?=$tedd->getEmployeeNumber()?>: <?=$tedd->getFirstName()?> <?=$tedd->getLastName()?> has <?=$tedd->getCertificationLevel()?> certification level(s).</p>
<?php
$frank = new Custodian();
$frank->setFirstName('Frank')->setLastName('Gallager')->setEmployeeNumber(124)->setCertificationLevel('Medium'); ?>
<p>Meanwhile, <?=$frank->getFirstName()?> <?=$frank->getLastName()?> (Employee #<?=$frank->getEmployeeNumber()?>) has <?=$frank->getCertificationLevel()?> level(s) of certification.</p>
