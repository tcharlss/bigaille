<?php
/**
 * Fichier gérant l'installation et désinstallation du plugin Bigaille
 *
 * @plugin     Bigaille
 * @copyright  2015
 * @author     C.R
 * @licence    GNU/GPL
 * @package    SPIP\Bigaille\Installation
 */

if (!defined('_ECRIRE_INC_VERSION')) return;


/**
 * Fonction d'installation et de mise à jour du plugin Bigaille.
 *
 * @param string $nom_meta_base_version
 *     Nom de la meta informant de la version du schéma de données du plugin installé dans SPIP
 * @param string $version_cible
 *     Version du schéma de données dans ce plugin (déclaré dans paquet.xml)
 * @return void
**/
function bigaille_upgrade($nom_meta_base_version, $version_cible) {
	$maj = array();

	$maj['create'] = array(
		array('maj_tables', array('spip_taxes', 'spip_prix_objets', 'spip_devises')),
		array('peupler_base_devises')
	);

	// ajout de la colonne 'exclure_pays' pour les modes de livraison
	$maj['1.0.1'] = array(
		array('maj_tables', array('spip_livraisonmodes')),
	);

	// ajout de la colonne 'identifiant' pour les modes de livraison
	$maj['1.0.2'] = array(
		array('maj_tables', array('spip_livraisonmodes')),
	);

	// augmenter les décimales pour les prix
	$maj['1.0.3'] = array(
		array('sql_alter', 'TABLE spip_prix_objets CHANGE prix_ht prix_ht decimal(10,4) NOT NULL default 0'),
		array('sql_alter', 'TABLE spip_prix_objets CHANGE prix prix decimal(10,4) NOT NULL default 0'),
	);

	include_spip('base/upgrade');
	include_spip('base/peupler_base_devises');
	maj_plugin($nom_meta_base_version, $version_cible, $maj);
}


/**
 * Fonction de désinstallation du plugin Bigaille.
 *
 * @param string $nom_meta_base_version
 *     Nom de la meta informant de la version du schéma de données du plugin installé dans SPIP
 * @return void
**/
function bigaille_vider_tables($nom_meta_base_version) {

	sql_drop_table("spip_taxes");
	sql_drop_table("spip_prix_objets");
	sql_drop_table("spip_devises");

	# Nettoyer les versionnages et forums
	sql_delete("spip_versions",              sql_in("objet", array('taxe', 'devise')));
	sql_delete("spip_versions_fragments",    sql_in("objet", array('taxe', 'devise')));
	sql_delete("spip_forum",                 sql_in("objet", array('taxe', 'devise')));

	effacer_meta($nom_meta_base_version);
}

?>
