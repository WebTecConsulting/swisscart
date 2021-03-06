<?php
/*
  $Id: login.php,v 1.14 2003/06/09 22:46:46 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Customized by swisscart�, Swiss Webshop Solutions
  http://www.swisscart.com

  Copyright (c) 2003-2007 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Identification');
define('HEADING_TITLE', 'Bienvenue, veuillez vous identifier');

define('HEADING_NEW_CUSTOMER', 'Nouveau client');
define('TEXT_NEW_CUSTOMER', 'Je suis un nouveau client.');
define('TEXT_NEW_CUSTOMER_INTRODUCTION', 'En cr&eacute;ant un compte aupr&egrave;s de ' . STORE_NAME . ' vous serez &agrave; m&ecirc;me d\'acheter en ligne plus vite, d\'&ecirc;tre &agrave; jour dans vos commandes et de garder trace de vos pr&eacute;c&eacute;dents achats.');

define('HEADING_RETURNING_CUSTOMER', 'Client membre');
define('TEXT_RETURNING_CUSTOMER', 'Je suis un client membre.');

define('TEXT_PASSWORD_FORGOTTEN', 'Mot de passe oubli&eacute;&nbsp;? Cliquez ici.');

define('TEXT_LOGIN_ERROR', 'Erreur&nbsp;: Aucune correspondance pour l\'adresse email et/ou le mot de passe.');
define('TEXT_VISITORS_CART', '<font color="#ff0000"><b>Note&nbsp;:</b></font> Le contenu de votre &quot;Panier visiteur&quot; sera ajout&eacute; au contenu de votre &quot;Panier membre&quot; une fois que vous vous serez identifi&eacute;. <a href="javascript:session_win();">[Plus d\'information]</a>');

define('HEADING_GUEST_CUSTOMER', 'Je ne veux pas cr�er un compte.');
define('TEXT_GUEST_INTRODUCTION', 'Vos cordonn� sont seulement enregistr�es pour cette commande. Vous ne pouvez pas verifier l\'�tat de votre commande online, mais vous en serez inform� par e-mail.');
?>
