<?php

/*
|-------------------------------------------------------------------------------------------------------
| Hier werden die Routen beschrieben die auf den angelegten
| Seiten aus der Datenbank beruhen. 
|-------------------------------------------------------------------------------------------------------
*/


$route['(:any)/(:any)/aktuelles/news'] = "pagebuilder/index/news";
$route['(:any)/(:any)/aktuelles/news/(:any)/(:any)'] = "pagebuilder/index/news/$3/$4";
$route['(:any)/(:any)/aktuelles/einsaetze'] = "pagebuilder/index/Unsere_Einsaetze";
$route['(:any)/(:any)/aktuelles/einsaetze/(:any)'] = "pagebuilder/index/Unsere_Einsaetze/$3";
$route['(:any)/(:any)/aktuelles/termine'] = "pagebuilder/index/Termine";

$route['(:any)/(:any)/aktuelles/berichte/Unwetterbericht_Mai_2016'] = "pagebuilder/index/Unwetterbericht_Mai_2016";

$route['(:any)/(:any)/technik/fahrzeuge'] = "pagebuilder/index/fahrzeuge";
$route['(:any)/(:any)/technik/fahrzeuge/(:any)/(:any)'] = "pagebuilder/index/fahrzeuge/$3/$4";

$route['(:any)/(:any)/mannschaft/einsatzabteilung'] = "pagebuilder/index/einsatzabteilung";

$route['(:any)/(:any)/vereine'] = "pagebuilder/index/Vereine";
$route['(:any)/(:any)/vereine/Freiwillige_Feuerwehr_Langenseifen_eV'] = "pagebuilder/index/Freiwillige_Feuerwehr_Langenseifen_eV";
$route['(:any)/(:any)/vereine/Freiwillige_Feuerwehr_Bad_Schwalbach_eV'] = "pagebuilder/index/Freiwillige_Feuerwehr_Bad_Schwalbach_eV";
$route['(:any)/(:any)/vereine/Freiwillige_Feuerwehr_Hettenhain_eV'] = "pagebuilder/index/Freiwillige_Feuerwehr_Hettenhain_eV";
$route['(:any)/(:any)/vereine/Freiwillige_Feuerwehr_Lindschied_eV'] = "pagebuilder/index/Freiwillige_Feuerwehr_Lindschied_eV";
$route['(:any)/(:any)/vereine/Freiwillige_Feuerwehr_Adolfseck_eV'] = "pagebuilder/index/Freiwillige_Feuerwehr_Adolfseck_eV";
$route['(:any)/(:any)/vereine/Freiwillige_Feuerwehr_Heimbach_eV'] = "pagebuilder/index/Freiwillige_Feuerwehr_Heimbach_eV";
$route['(:any)/(:any)/vereine/Freiwillige_Feuerwehr_Ramschied_eV'] = "pagebuilder/index/Freiwillige_Feuerwehr_Ramschied_eV";
$route['(:any)/(:any)/vereine/Freiwillige_Feuerwehr_Fischbach_eV'] = "pagebuilder/index/Freiwillige_Feuerwehr_Fischbach_eV";

$route['(:any)/(:any)/kontakt'] = "pagebuilder/index/kontakt";
$route['(:any)/(:any)/impressum'] = "pagebuilder/index/impressum";





