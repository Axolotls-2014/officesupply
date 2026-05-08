<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Ion Auth Lang - English
*
* Author: Ben Edmunds
*         ben.edmunds@gmail.com
*         @benedmunds
*
* Location: https://github.com/benedmunds/CodeIgniter-Ion-Auth
*
* Created:  03.14.2010
*
* Description:  English language file for Ion Auth messages and errors
*
*/

// Account Creation
$lang['account_creation_successful']            = 'Compte créé avec succès';
$lang['account_creation_unsuccessful']          = 'Impossible de créer un compte';
$lang['account_creation_duplicate_email']       = 'Courriel déjà utilisé ou non valide';
$lang['account_creation_duplicate_identity']    = 'Identité déjà utilisée ou non valide';
$lang['account_creation_missing_default_group'] = "Le groupe par défaut n'est pas défini";
$lang['account_creation_invalid_default_group'] = 'Jeu de noms de groupe par défaut non valide';


// Password
$lang['password_change_successful']          = 'Mot de passe changé avec succès';
$lang['password_change_unsuccessful']        = 'Impossible de changer le mot de passe';
$lang['forgot_password_successful']          = 'Email de réinitialisation du mot de passe envoyé';
$lang['forgot_password_unsuccessful']        = "Impossible d'envoyer par e-mail le lien Réinitialiser le mot de passe";

// Activation
$lang['activate_successful']                 = 'Compte activé';
$lang['activate_unsuccessful']               = "Unable to Activate Account";
$lang['deactivate_successful']               = 'Compte désactivé';
$lang['deactivate_unsuccessful']             = 'Impossible de désactiver le compte';
$lang['activation_email_successful']         = "E-mail d'activation envoyé. Veuillez vérifier votre boîte de réception ou spam";
$lang['activation_email_unsuccessful']       = "Impossible d'envoyer un e-mail d'activation";
$lang['deactivate_current_user_unsuccessful']= 'Vous ne pouvez pas désactiver votre auto.';

// Login / Logout
$lang['login_successful']                    = 'Connecté avec succès';
$lang['login_unsuccessful']                  = 'Login incorrect';
$lang['login_unsuccessful_not_active']       = 'Le compte est inactif';
$lang['login_timeout']                       = 'Verrouillé temporairement. Réessayez plus tard.';
$lang['logout_successful']                   = 'Déconnecté avec succès';

// Account Changes
$lang['update_successful']                   = 'Informations sur le compte mises à jour avec succès';
$lang['update_unsuccessful']                 = 'Impossible de mettre à jour les informations de compte';
$lang['delete_successful']                   = 'Utilisateur supprimé';
$lang['delete_unsuccessful']                 = "Impossible de supprimer l'utilisateur";

// Groups
$lang['group_creation_successful']           = 'Groupe créé avec succès';
$lang['group_already_exists']                = 'Nom de groupe déjà pris';
$lang['group_update_successful']             = 'Détails du groupe mis à jour';
$lang['group_delete_successful']             = 'Groupe supprimé';
$lang['group_delete_unsuccessful']           = 'Impossible de supprimer le groupe';
$lang['group_delete_notallowed']             = "Impossible de supprimer le groupe d'administrateurs \ '";
$lang['group_name_required']                 = 'Le nom du groupe est un champ obligatoire';
$lang['group_name_admin_not_alter']          = "Le nom du groupe d'admin ne peut pas être changé";

// Activation Email
$lang['email_activation_subject']            = 'Activation de compte';
$lang['email_activate_heading']              = 'Activer le compte pour% s';
$lang['email_activate_subheading']           = 'Veuillez cliquer sur ce lien vers% s.';
$lang['email_activate_link']                 = 'Activez votre compte';

// Forgot Password Email
$lang['email_forgotten_password_subject']    = 'Vérification du mot de passe oublié';
$lang['email_forgot_password_heading']       = 'Réinitialiser le mot de passe pour% s';
$lang['email_forgot_password_subheading']    = 'Veuillez cliquer sur ce lien vers% s.';
$lang['email_forgot_password_link']          = 'Réinitialisez votre mot de passe';

// Delete Modal
$lang['btn_modal_close']					 = 'Fermer';
$lang['btn_modal_delete']					 = 'Supprimer';

