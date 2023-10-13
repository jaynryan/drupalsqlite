<?php
/**
 * @file
 * Contains Licensing information for miniOrange SAML Login Module.
 */

/**
 * Showing Licensing form info.
 */
namespace Drupal\miniorange_saml\Form;

use Drupal\Core\Form\FormBase;
use Drupal\miniorange_saml\Utilities;
use Drupal\Core\Form\FormStateInterface;
use Drupal\miniorange_saml\MiniorangeSAMLConstants;
use Drupal\Core\Render\Markup;


class MiniorangeLicensing extends FormBase {

    public function getFormId() {
        return 'miniorange_saml_licensing';
    }

    public function buildForm(array $form, FormStateInterface $form_state, $options = NULL) {
      global  $base_url;

      \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_last_tab_visited', 'UPGRADE PLANS')->save();

         $form['miniorange_saml_licensing_tab'] = array(
            '#attached' => array(
                'library' => array(
                  'miniorange_saml/miniorange_saml.admin',
                  'core/drupal.dialog.ajax',
                )
            ),
        );
      $form['markup_1'] = array(
        '#markup' =>t('<div class="mo_saml_sp_table_layout_1"><div class="mo_saml_table_layout">'),
      );

      $admin_email        = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_customer_admin_email');
      $admin_email        = (isset($admin_email) && !empty($admin_email)) ? $admin_email : '';
      $URL_Redirect_std   = 'https://login.xecurify.com/moas/login?username='.$admin_email.'&redirectUrl=https://login.xecurify.com/moas/initializepayment&requestOrigin=drupal8_miniorange_saml_standard_plan';
      $URL_Redirect_prem  = 'https://login.xecurify.com/moas/login?username='.$admin_email.'&redirectUrl=https://login.xecurify.com/moas/initializepayment&requestOrigin=drupal8_miniorange_saml_premium_plan';
      $URL_Redirect_enter = 'https://login.xecurify.com/moas/login?username='.$admin_email.'&redirectUrl=https://login.xecurify.com/moas/initializepayment&requestOrigin=drupal8_miniorange_saml_enterprise_plan';

      $features = [
        [ Markup::create(t('<h3>FEATURES</h3>')), Markup::create(t('<br><h2>FREE</h2> <p class="mo_pricing-rate"><sup>$</sup> 0</p>')), Markup::create(t('<br><h2>STANDARD</h2><p class="mo_pricing-rate"><sup>$</sup> 249 <sup>*</sup></p>')), Markup::create(t('<br><h2>PREMIUM</h2><p class="mo_pricing-rate"><sup>$</sup> 399 <sup>*</sup></p>')), Markup::create(t('<br><h2>ENTERPRISE</h2><p class="mo_pricing-rate"><sup>$</sup> 449 <sup>*</sup></p>')),],
        [ '', Markup::create(t('<span class="button button--small">Current Plan</span>')), Markup::create(t('<a class="button button--primary button--small" target="_blank" href="'.$base_url.'/payment_page_visited/standard">Upgrade Now</a>')), Markup::create(t('<a class="button button--primary button--small" target="_blank" href="'.$base_url.'/payment_page_visited/premium">Upgrade Now</a>')), Markup::create(t('<a class="button button--primary button--small" target="_blank" href="'.$base_url.'/payment_page_visited/enterprise">Upgrade Now</a>'))],

        [ Markup::create(t('Unlimited Authentication via IdP')),              Markup::create(t('&#x2714;')), Markup::create(t('&#x2714;')), Markup::create(t('&#x2714;')), Markup::create(t('&#x2714;')), ],
        [ Markup::create(t('Configure SP Using Metadata XML File')),          Markup::create(t('&#x2714;')), Markup::create(t('&#x2714;')), Markup::create(t('&#x2714;')), Markup::create(t('&#x2714;')), ],
        [ Markup::create(t('Configure SP Using Metadata URL')),               Markup::create(t('&#x2714;')), Markup::create(t('&#x2714;')), Markup::create(t('&#x2714;')), Markup::create(t('&#x2714;')), ],
        [ Markup::create(t('Basic Attribute Mapping')),                       Markup::create(t('&#x2714;')), Markup::create(t('&#x2714;')), Markup::create(t('&#x2714;')), Markup::create(t('&#x2714;')), ],
        [ Markup::create(t('Basic Role Mapping')),                            Markup::create(t('&#x2714;')), Markup::create(t('&#x2714;')), Markup::create(t('&#x2714;')), Markup::create(t('&#x2714;')), ],
        [ Markup::create(t('Export Configuration')),                          Markup::create(t('&#x2714;')), Markup::create(t('&#x2714;')), Markup::create(t('&#x2714;')), Markup::create(t('&#x2714;')), ],
        [ Markup::create(t('Options to select SAML Request Binding Type')),   Markup::create(t('')),         Markup::create(t('&#x2714;')), Markup::create(t('&#x2714;')), Markup::create(t('&#x2714;')), ],
        [ Markup::create(t('Sign SAML Request')),                             Markup::create(t('')),         Markup::create(t('&#x2714;')), Markup::create(t('&#x2714;')), Markup::create(t('&#x2714;')), ],
        [ Markup::create(t('Import Configuration')),                          Markup::create(t('')),         Markup::create(t('&#x2714;')), Markup::create(t('&#x2714;')), Markup::create(t('&#x2714;')), ],
        [ Markup::create(t('Protect your whole site')),                       Markup::create(t('')),         Markup::create(t('&#x2714;')), Markup::create(t('&#x2714;')), Markup::create(t('&#x2714;')), ],
        [ Markup::create(t('Force authentication on each login attempt')),    Markup::create(t('')),         Markup::create(t('&#x2714;')), Markup::create(t('&#x2714;')), Markup::create(t('&#x2714;')), ],
        [ Markup::create(t('Default Redirect Url after Login')),              Markup::create(t('')),         Markup::create(t('&#x2714;')), Markup::create(t('&#x2714;')), Markup::create(t('&#x2714;')), ],
        [ Markup::create(t('Integrated Windows Authentication (With ADFS)')), Markup::create(t('')),         Markup::create(t('&#x2714;')), Markup::create(t('&#x2714;')), Markup::create(t('&#x2714;')), ],
        [ Markup::create(t('SAML Single Logout')),                            Markup::create(t('')),         Markup::create(t('')),         Markup::create(t('&#x2714;')), Markup::create(t('&#x2714;')), ],
        [ Markup::create(t('Custom Attribute Mapping')),                      Markup::create(t('')),         Markup::create(t('')),         Markup::create(t('&#x2714;')), Markup::create(t('&#x2714;')), ],
        [ Markup::create(t('Custom Role Mapping')),                           Markup::create(t('')),         Markup::create(t('')),         Markup::create(t('&#x2714;')), Markup::create(t('&#x2714;')), ],
        [ Markup::create(t('Auto-sync IdP Configuration from metadata')),     Markup::create(t('')),         Markup::create(t('')),         Markup::create(t('')),         Markup::create(t('&#x2714;')), ],
        [ Markup::create(t('Support for Profile module')),                    Markup::create(t('')),         Markup::create(t('')),         Markup::create(t('')),         Markup::create(t('&#x2714;')), ],
        [ Markup::create(t('Generate Custom SP Certificate')),                Markup::create(t('')),         Markup::create(t('')),         Markup::create(t('')),         Markup::create(t('&#x2714;')), ],
        [ Markup::create(t('Signed requests using different algorithm	')),    Markup::create(t('')),         Markup::create(t('')),         Markup::create(t('')),         Markup::create(t('&#x2714;')), ],
        [ Markup::create(t('SLO through iframe')),                            Markup::create(t('')),         Markup::create(t('')),         Markup::create(t('')),         Markup::create(t('&#x2714;')), ],
        [ Markup::create(t('Support multiple certificates of IDP')),          Markup::create(t('')),         Markup::create(t('')),         Markup::create(t('')),         Markup::create(t('&#x2714;')), ],
        [ Markup::create(t('Multiple IdP Support**')),                        Markup::create(t('')),         Markup::create(t('')),         Markup::create(t('')),         Markup::create(t('&#x2714;')), ],
      ];

      $form['miniorange_saml_feature_list'] = array(
        '#type' => 'table',
        '#responsive' => FALSE,
        '#rows' => $features,
        '#size' => 5,
        '#attributes' => ['class' => 'mo_upgrade_plans_features'],
      );

      $rows = [
        [ Markup::create(t('<b>1.</b> Click on Upgrade Now button for required licensed plan and you will be redirected to miniOrange login console.</li>')), Markup::create(t('<b>5.</b> Uninstall and then delete the free version of the module from your Drupal site.')) ],
        [ Markup::create(t('<b>2.</b> Enter your username and password with which you have created an account with us. After that you will be redirected to payment page.')), Markup::create(t('<b>6.</b> Now install the downloaded licensed version of the module.')) ],
        [ Markup::create(t('<b>3.</b> Enter your card details and proceed for payment. On successful payment completion, the Licensed version module(s) will be available to download.')), Markup::create(t('<b>7.</b> Clear Drupal Cache from <a href="'.$base_url.'/admin/config/development/performance" >here</a>.')) ],
        [ Markup::create(t('<b>4.</b> Download the licensed module(s) from Module Releases and Downloads section.')), Markup::create(t('<b>8.</b> After enabling the licensed version of the module, login using the account you have registered with us.')) ],
      ];

      $form['markup_how to upgrade'] = array(
        '#markup' => '<h3 class="mo_saml_text_center">How to Upgrade to Licensed Version Module</h3>'
      );

      $form['miniorange_how_to_upgrade_table'] = array(
        '#type' => 'table',
        '#responsive' => TRUE,
        '#rows' => $rows,
        '#attributes' => ['style' => 'border:groove', 'class' => ['mo_how_to_upgrade']],
      );

      $form['markup_faq'] = array(
        '#markup' => '<h3 class="mo_saml_text_center"><br>Frequently Asked Questions</h3>'
      );

      $form['miniorange_FAQ_table'] = array(
        '#type' => 'table',
        '#responsive' => FALSE,
        '#attributes' => ['class' => ['mo_faq_table']],
      );

      $form['miniorange_FAQ_table'][0][0] = array(
        '#type' => 'details',
        '#title' => $this->t('Are the Licenses Perpetual?' ),
        '#open' => false,
        '#attributes' => array( 'style' => ' width: 400px;' )
      );
      $form['miniorange_FAQ_table'][0][0]['detail1'] = array(
        '#markup' => $this->t('<span style="text-align: justify">The modules licenses are perpetual and includes 12 months of free maintenance (version updates).You can renew maintenance after 12 months at 50% of the current license cost.'),
      );
      $form['miniorange_FAQ_table'][0][1] = array(
        '#type' => 'details',
        '#title' => t('Does miniOrange Offer Technical Support?' ),
        '#open' => false,
        '#attributes' => array( 'style' => ' width: 400px;' )
      );

      $form['miniorange_FAQ_table'][0][1]['detail2'] = array(
        '#markup' => $this->t('Yes, we provide 24*7 support for all and any issues you might face while using the module, which includes technical support from our developers. You can get prioritized support based on the Support Plan you have opted.'),
      );

      $form['miniorange_FAQ_table'][1][0] = array(
        '#type' => 'details',
        '#title' => t('What is the Refund Policy?' ),
        '#open' => false,
        '#attributes' => array( 'style' => ' width: 400px;' )
      );

      $form['miniorange_FAQ_table'][1][0]['detail3'] = array(
        '#markup' => $this->t('At miniOrange, we want to ensure you are 100% happy with your purchase. If the module that you purchased is not working as advertised and you\'ve attempted to resolve any issues with our support team, which couldn\'t get resolved, we will refund the whole amount given that you raised a refund request within the first 10 days of the purchase. Please email us at <a href="mailto:drupalsupport@xecurify.com">drupalsupport@xecurify.com</a> for any queries regarding the return policy or contact us <a href="https://www.miniorange.com/contact" target="_blank">here</a>.'),
      );

      $form['miniorange_FAQ_table'][1][1] = array(
        '#type' => 'details',
        '#title' => t('Does miniOrange store any User data ?' ),
        '#open' => false,
        '#attributes' => array( 'style' => ' width: 400px;' )
      );

      $form['miniorange_FAQ_table'][1][1]['detail3'] = array(
        '#markup' => $this->t('MiniOrange does not store or transfer any data which is coming from the OAuth / OIDC provider to the Drupal. All the data remains within your premises / server.'),
      );

      $form['miniorange_saml_instance_based'] = array(
        '#markup' => t('<br><div><b>*</b> This module follows an <b>Instance Based</b> licensing structure. The listed prices are for purchase of a single instance. If you are planning to use the module on multiple instances, you can check out the bulk purchase discount on our <a href="https://plugins.miniorange.com/drupal-saml-single-sign-on-sso#pricing" target="_blank">website</a>.</div><br>
                        <div> <b>**</b> If you have multiple IdPs, there is additional cost for every supplementary IdP.</div><br>
                        <div class="mo_saml_highlight_background_note_1"><b><u>What is an Instance:</u></b> A Drupal instance refers to a single installation of a Drupal site. It refers to each individual website where the module is active. In the case of multisite/subsite Drupal setup, each site with a separate database will be counted as a single instance. For e.g. If you have the dev-staging-prod type of environment then you will require 3 licenses of the module (with additional discounts applicable on pre-production environments).</div><br>
                        '),
      );

        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
    }
}
