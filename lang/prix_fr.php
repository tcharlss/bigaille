<?php
// This is a SPIP language file  --  Ceci est un fichier langue de SPIP

if (!defined('_ECRIRE_INC_VERSION')) return;


$GLOBALS[$GLOBALS['idx_lang']] = array(

	// A
	'ajouter_lien_prix' => 'Ajouter ce prix',

	// C
	'champ_devise_label' => 'Devise',
	'champ_devise_aucune_explication' => 'Aucune devise : veillez à <a href="?exec=configurer_bigaille#devises">en configurer au moins une</a>.',
	'champ_prix_explication' => 'Chiffre sans unité',
	'champ_prix_ht_label' => 'Prix HT',
	'champ_prix_ttc_label' => 'Prix  TTC',
	'champ_prix_ttc_erreur' => 'Le prix TTC ne correspond pas au couple prix HT + taxe. Il devrait être d\'un montant de @prix@',
	'champ_taxe_label' => 'Règle de taxe',
	'champ_taxe_aucune' => 'Aucune taxe',
	'champ_taxe_aucune_explication' => 'Aucune règle de taxe : veillez à <a href="?exec=taxe_edit&new=oui">en créer au moins une</a>.',
	'champ_taxe_erreur_intervale' => 'La taxe doit être un nombre décimal compris entre 0 et 1',
	'champ_explication' => 'Saisissez soit le prix HT, soit le prix TTC, le champ complémentaire sera automatiquement mis à jour en fonction de la règle de taxe choisie.',
	'champ_pays_label' => 'Pays',
	'champ_pays_explication' => 'Vous pouvez associer le prix à un ou plusieurs pays',
	'champ_fieldset_options' => 'Options',
	'champ_fieldset_options_explication' => 'Vous pouvez ajouter des spécificités au prix : pays, clients...',
	'champ_auteurs_label' => 'Auteurs',

	// I
	'icone_creer_prix' => 'Créer un prix',
	'icone_modifier_prix' => 'Modifier ce prix',
	'info_1_prix' => 'Un prix',
	'info_aucun_prix' => 'Aucun prix',
	'info_nb_prix' => '@nb@ prix',
	'info_prix_auteur' => 'Les prix de cet auteur',

	// R
	'retirer_lien_prix' => 'Retirer ce prix',
	'retirer_tous_liens_prix' => 'Retirer tous les prix',

	// T
	'texte_ajouter_prix' => 'Ajouter un prix',
	'texte_changer_statut_prix' => 'Ce prix est :',
	'texte_creer_associer_prix' => 'Créer et associer un prix',
	'texte_definir_comme_traduction_prix' => 'Ce prix est une traduction du prix numéro :',
	'titre_langue_prix' => 'Langue de ce prix',
	'titre_logo_prix' => 'Logo de ce prix',
	'titre_prix' => 'Prix',
	'titre_prix_rubrique' => 'Prix de la rubrique',
);

?>
