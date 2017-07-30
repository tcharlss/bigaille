<?php
/**
 * Déclarations relatives à la base de données
 *
 * @plugin     Bigaille
 * @copyright  2015
 * @author     C.R
 * @licence    GNU/GPL
 * @package    SPIP\Bigaille\Pipelines
 */

if (!defined('_ECRIRE_INC_VERSION')) return;


/**
 * Déclaration des alias de tables et filtres automatiques de champs
 *
 * @pipeline declarer_tables_interfaces
 * @param array $interfaces
 *     Déclarations d'interface pour le compilateur
 * @return array
 *     Déclarations d'interface pour le compilateur
 */
function bigaille_declarer_tables_interfaces($interfaces) {

	$interfaces['table_des_tables']['taxes'] = 'taxes';
	$interfaces['table_des_tables']['devises'] = 'devises';
	$interfaces['table_des_tables']['prix_objets'] = 'prix_objets';

	$interfaces['table_des_traitements']['COMPLEMENT'][] = 'typo(extraire_multi(%s))';
	$interfaces['table_des_traitements']['PRIX']['prix_objets'] = '';
	$interfaces['table_des_traitements']['PRIX_HT']['prix_objets'] = '';

	return $interfaces;
}


/**
 * Déclaration des objets éditoriaux
 *
 * @pipeline declarer_tables_objets_sql
 * @param array $tables
 *     Description des tables
 * @return array
 *     Description complétée des tables
 */
function bigaille_declarer_tables_objets_sql($tables) {

	// TAXES
	$tables['spip_taxes'] = array(
		'type' => 'taxe',
		'principale' => "oui",
		'field'=> array(
			"id_taxe"            => "bigint(21) NOT NULL",
			"titre"              => "text NOT NULL DEFAULT ''",  // TVA standard 20%
			"descriptif"         => "text NOT NULL DEFAULT ''",  // bla bla...
			"taxe"               => "decimal(4,3) DEFAULT NULL", // 0.2
			"maj"                => "TIMESTAMP"
		),
		'key' => array(
			"PRIMARY KEY"        => "id_taxe",
		),
		'titre' => "titre AS titre, '' AS lang",
		 #'date' => "",
		'champs_editables'  => array('titre', 'descriptif', 'taxe'),
		'champs_versionnes' => array('titre', 'descriptif', 'taxe'),
		'rechercher_champs' => array(),
		'tables_jointures'  => array(),
	);

	// DEVISES
	$tables['spip_devises'] = array(
		'type' => 'devise',
		'principale' => "oui",
		'field'=> array(
			"id_devise"          => "bigint(21) NOT NULL",
			"devise"             => "varchar(3) NOT NULL default ''",    // EUR
			"nom"                => "text NOT NULL DEFAULT ''",          // Euro
			"complement"         => "varchar(255) NOT NULL DEFAULT ''",  // Ajusté, ancien, nouveau etc.
			"symbole"            => "varchar(6) NOT NULL default ''",    // &euro;
			"statut"             => "varchar(20)  DEFAULT '0' NOT NULL", // actuelle | obsolete
			"maj"                => "TIMESTAMP"
		),
		'key' => array(
			"PRIMARY KEY"        => "id_devise",
			"UNIQUE KEY"         => "devise",
			"KEY statut"         => "statut",
		),
		'titre' => "nom AS titre, '' AS lang",
		 #'date' => "",
		'champs_editables'  => array('devise', 'nom', 'complement', 'symbole'),
		'champs_versionnes' => array('devise', 'nom', 'complement', 'symbole'),
		'rechercher_champs' => array(),
		'tables_jointures'  => array(),
		'join'  => array(
			"devise" => "devise"
		),
		'statut_textes_instituer' => array(
			'actuelle'  => 'devise:texte_statut_actuelle',
			'obsolete' => 'devise:texte_statut_obsolete',
		),
		'statut_images' => array(
			'actuelle'       => 'puce-publier-8.png',
			'obsolete'       => 'puce-refuser-8.png',
		),
		'statut'=> array(
			array(
				'champ'     => 'statut',
				'publie'    => 'actuelle',
				'previsu'   => 'actuelle,obsolete',
				'exception' => array('statut','tout')
			)
		),
		'texte_changer_statut' => 'devise:texte_changer_statut_devise',
	);

	// MODES DE LIVRAISONS : compléments
	// champ 'exclure_pays' : booléen pour indiquer qu'on veut exclure les pays sélectionnés
	$tables['spip_livraisonmodes']['field']['exclure_pays'] = 'smallint(1) NOT NULL DEFAULT 0';
	$tables['spip_livraisonmodes']['champs_editables'][] = 'exclure_pays';
	// champ 'identifiant'
	$tables['spip_livraisonmodes']['field']['identifiant'] = 'text NOT NULL DEFAULT ""';
	$tables['spip_livraisonmodes']['champs_editables'][] = 'identifiant';

	return $tables;
}


/**
 * Déclaration des tables secondaires (liaisons)
 *
 * @pipeline declarer_tables_auxiliaires
 * @param array $tables
 *     Description des tables
 * @return array
 *     Description complétée des tables
 */
function bigaille_declarer_tables_auxiliaires($tables) {

	// PRIX
	$tables['spip_prix_objets'] = array(
		'field'=> array(
			"id_prix_objet" => "bigint(21) NOT NULL AUTO_INCREMENT",
			"objet"         => "VARCHAR (25) DEFAULT '' NOT NULL",
			"id_objet"      => "bigint(21) DEFAULT '0' NOT NULL",
			"prix_ht"       => "decimal(10,4) NOT NULL default 0", // 10
			"prix"          => "decimal(10,4) NOT NULL default 0", // 12
			"taxe"          => "decimal(4,3) DEFAULT NULL",       // 0.2 = #TAXE   dans spip_taxes
			"devise"        => "varchar(3) NOT NULL default ''",  // EUR = #DEVISE dans spip_devises
			"maj"           => "TIMESTAMP"
		),
		'key' => array(
			"PRIMARY KEY"       => "id_prix_objet",
			"KEY id_prix_objet" => "id_prix_objet",
			"KEY objet"         => "objet",
			"KEY id_objet"      => "id_objet",
		)
	);

	return $tables;
}