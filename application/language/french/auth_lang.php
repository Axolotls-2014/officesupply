<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Auth Lang - English
*
* Author: Ben Edmunds
* 		  ben.edmunds@gmail.com
*         @benedmunds
*
* Author: Daniel Davis
*         @ourmaninjapan
*
* Location: https://github.com/benedmunds/CodeIgniter-Ion-Auth
*
* Created:  03.09.2013
*
* Description:  English language file for Ion Auth example views
*
*/

// Errors
$lang['error_csrf'] 		   = "Ce formulaire n'a pas réussi nos contrôles de sécurité.";

// Login
$lang['login_heading']         = "Login";
$lang['login_subheading']      = "Veuillez vous connecter avec votre e-mail / nom d'utilisateur et mot de passe ci-dessous.";
$lang['login_identity_label']  = "Email/Username:";
$lang['login_password_label']  = 'Mot de passe:';
$lang['login_remember_label']  = 'Souviens-toi de moi:';
$lang['login_submit_btn']      = "S'identifier";
$lang['login_forgot_password'] = 'Mot de passe oublié?';

// Index
$lang['index_heading']           = 'Utilisatrices';
$lang['index_subheading']        = 'Voici une liste des utilisateurs.';
$lang['index_fname_th']          = 'Prénom';
$lang['index_lname_th']          = 'Nom de famille';
$lang['index_email_th']          = 'Email';
$lang['index_groups_th']         = 'Groupes';
$lang['index_status_th']         = 'Statut';
$lang['index_action_th']         = 'action';
$lang['index_active_link']       = 'Active';
$lang['index_inactive_link']     = 'Inactive';
$lang['index_create_user_link']  = 'Créer un nouvel utilisateur';
$lang['index_create_group_link'] = 'Créer un nouveau groupe';

// Deactivate User
$lang['deactivate_heading']                  = "Désactiver l'utilisateur";
$lang['deactivate_subheading']               = "Voulez-vous vraiment désactiver l'utilisateur \ '% s \'";
$lang['deactivate_confirm_y_label']          = 'Oui:';
$lang['deactivate_confirm_n_label']          = 'Non:';
$lang['deactivate_submit_btn']               = 'Soumettre';
$lang['deactivate_validation_confirm_label'] = 'confirmation';
$lang['deactivate_validation_user_id_label'] = 'user ID';

// Create User
$lang['create_user_heading']                           = 'Créer un utilisateur';
$lang['create_user_subheading']                        = "Veuillez saisir les informations de l'utilisateur ci-dessous.";
$lang['create_user_fname_label']                       = 'Prénom:';
$lang['create_user_lname_label']                       = 'Nom de famille:';
$lang['create_user_company_label']                     = 'Nom de la compagnie:';
$lang['create_user_identity_label']                    = 'Identité:';
$lang['create_user_email_label']                       = 'Email:';
$lang['create_user_phone_label']                       = 'Téléphone:';
$lang['create_user_password_label']                    = 'Mot de passe:';
$lang['create_user_password_confirm_label']            = 'Confirmez le mot de passe:';
$lang['create_user_submit_btn']                        = 'Créer un utilisateur';
$lang['create_user_validation_fname_label']            = 'Prénom';
$lang['create_user_validation_lname_label']            = 'Nom de famille';
$lang['create_user_validation_identity_label']         = 'Identité';
$lang['create_user_validation_email_label']            = 'Adresse électronique';
$lang['create_user_validation_phone_label']            = 'Téléphone';
$lang['create_user_validation_company_label']          = 'Nom de la compagnie';
$lang['create_user_validation_password_label']         = 'Mot de passe';
$lang['create_user_validation_password_confirm_label'] = 'Confirmation mot de passe';

// Edit User
$lang['edit_user_heading']                           = "Modifier l'utilisateur";
$lang['edit_user_subheading']                        = "Veuillez saisir les informations de l'utilisateur ci-dessous.";
$lang['edit_user_fname_label']                       = 'Prénom:';
$lang['edit_user_lname_label']                       = 'Nom de famille:';
$lang['edit_user_company_label']                     = 'Nom de la compagnie:';
$lang['edit_user_email_label']                       = 'Email:';
$lang['edit_user_phone_label']                       = 'Téléphone:';
$lang['edit_user_password_label']                    = 'Mot de passe: (en cas de changement de mot de passe)';
$lang['edit_user_password_confirm_label']            = 'Confirmer le mot de passe: (en cas de changement de mot de passe)';
$lang['edit_user_groups_heading']                    = 'Membre de groupes';
$lang['edit_user_submit_btn']                        = "Enregistrer l'utilisateur";
$lang['edit_user_validation_fname_label']            = 'Prénom:';
$lang['edit_user_validation_lname_label']            = 'Nom de famille';
$lang['edit_user_validation_email_label']            = 'Adresse électronique';
$lang['edit_user_validation_phone_label']            = 'Téléphone';
$lang['edit_user_validation_company_label']          = 'Nom de la compagnie';
$lang['edit_user_validation_groups_label']           = 'Groupes';
$lang['edit_user_validation_password_label']         = 'Mot de passe';
$lang['edit_user_validation_password_confirm_label'] = 'Confirmation mot de passe';

// Create Group
$lang['create_group_title']                  = 'Créer un groupe';
$lang['create_group_heading']                = 'Créer un groupe';
$lang['create_group_subheading']             = 'Veuillez saisir les informations du groupe ci-dessous.';
$lang['create_group_name_label']             = 'Nom de groupe:';
$lang['create_group_desc_label']             = 'La description:';
$lang['create_group_submit_btn']             = 'Créer un groupe';
$lang['create_group_validation_name_label']  = 'Nom de groupe';
$lang['create_group_validation_desc_label']  = 'La description';

// Edit Group
$lang['edit_group_title']                  = 'Modifier le groupe';
$lang['edit_group_saved']                  = 'Groupe enregistré';
$lang['edit_group_heading']                = 'Modifier le groupe';
$lang['edit_group_subheading']             = 'Veuillez saisir les informations du groupe ci-dessous.';
$lang['edit_group_name_label']             = 'Nom de groupe:';
$lang['edit_group_desc_label']             = 'La description:';
$lang['edit_group_submit_btn']             = 'Enregistrer le groupe';
$lang['edit_group_validation_name_label']  = 'Nom de groupe';
$lang['edit_group_validation_desc_label']  = 'La description';

// Change Password
$lang['change_password_heading']                               = 'Changer le mot de passe';
$lang['change_password_old_password_label']                    = 'Ancien mot de passe:';
$lang['change_password_new_password_label']                    = 'Nouveau mot de passe (au moins% s caractères):';
$lang['change_password_new_password_confirm_label']            = 'Confirmer le nouveau mot de passe:';
$lang['change_password_submit_btn']                            = 'Changement';
$lang['change_password_validation_old_password_label']         = 'Ancien mot de passe';
$lang['change_password_validation_new_password_label']         = 'nouveau mot de passe';
$lang['change_password_validation_new_password_confirm_label'] = 'Confirmer le nouveau mot de passe';

// Forgot Password
$lang['forgot_password_heading']                 = 'Mot de passe oublié';
$lang['forgot_password_subheading']              = 'Veuillez saisir votre% s afin que nous puissions vous envoyer un e-mail pour réinitialiser votre mot de passe.';
$lang['forgot_password_email_label']             = '% s:';
$lang['forgot_password_submit_btn']              = 'Soumettre';
$lang['forgot_password_validation_email_label']  = 'Adresse électronique';
$lang['forgot_password_identity_label'] = 'Identité';
$lang['forgot_password_email_identity_label']    = 'Email';
$lang['forgot_password_email_not_found']         = 'Aucun enregistrement de cette adresse e-mail.';
$lang['forgot_password_identity_not_found']         = "Aucun enregistrement de ce nom d'utilisateur.";

// Reset Password
$lang['reset_password_heading']                               = 'Changer le mot de passe';
$lang['reset_password_new_password_label']                    = 'Nouveau mot de passe (au moins% s caractères):';
$lang['reset_password_new_password_confirm_label']            = 'Confirmer le nouveau mot de passe:';
$lang['reset_password_submit_btn']                            = 'Changement';
$lang['reset_password_validation_new_password_label']         = 'nouveau mot de passe';
$lang['reset_password_validation_new_password_confirm_label'] = 'Confirmer le nouveau mot de passe';
