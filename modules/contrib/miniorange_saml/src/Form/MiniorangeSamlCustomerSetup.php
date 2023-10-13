<?php

/**
 * @file
 * Contains \Drupal\miniorange_saml\Form\MiniorangeSamlCustomerSetup.
 */

namespace Drupal\miniorange_saml\Form;

use Drupal\Core\Url;
use Drupal\Core\Form\FormBase;
use Drupal\miniorange_saml\Utilities;
use Drupal\Core\Form\FormStateInterface;

class MiniorangeSamlCustomerSetup extends FormBase {

    public function getFormId() {
        return 'miniorange_saml_customer_setup';
    }

    public function buildForm(array $form, FormStateInterface $form_state) {

        global $base_url;

        \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_last_tab_visited', 'REGISTER/LOGIN')->save();

        $current_status = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_status');
        $form['miniorange_saml_markup_library'] = array(
          '#attached' => array(
            'library' => array(
              'miniorange_saml/miniorange_saml.admin',
              'core/drupal.dialog.ajax',
            )
          ),
        );
        if ($current_status == 'VALIDATE_OTP') {

            /**
             * Create container to hold @moVlidateOTPTab form elements.
             */
            $form['mo_saml_validate_OTP_tab'] = array(
                '#type' => 'fieldset',
                '#title' => t('Validate OTP'),
                '#attributes' => array( 'style' => 'padding:2% 2% 40%; margin-bottom:2%' ),
                '#prefix' => '<div class="mo_saml_sp_table_layout_1"><div class="mo_saml_table_layout mo_saml_sp_container">',
            );

            $form['mo_saml_validate_OTP_tab']['miniorange_saml_customer_otp_token'] = array(
                '#type' => 'textfield',
                '#title' => t('Enter OTP <span style="color: #FF0000">*</span>'),
                '#attributes' => array('style' => 'width:80%'),
                '#description' => t('<strong>Note:</strong> We have sent an OTP. Please enter the OTP to verify your email.'),
                '#prefix' => '<br><hr><br>',
            );

            $form['mo_saml_validate_OTP_tab']['miniorange_saml_customer_validate_otp_button'] = array(
                '#type' => 'submit',
                '#button_type' => 'primary',
                '#value' => t('Validate OTP'),
                '#submit' => array('::miniorange_saml_validate_otp_submit'),
            );

            $form['mo_saml_validate_OTP_tab']['miniorange_saml_customer_setup_resendotp'] = array(
                '#type' => 'submit',
                '#value' => t('Resend OTP'),
                '#submit' => array('::miniorange_saml_resend_otp'),
            );

            $form['mo_saml_validate_OTP_tab']['miniorange_saml_customer_setup_back'] = array(
                '#type' => 'submit',
                '#button_type' => 'danger',
                '#value' => t('Back'),
                '#submit' => array('::miniorange_saml_back'),
                '#suffix' => '<br></div>'
            );
            Utilities::advertiseNetworkSecurity($form, $form_state);

            return $form;
        }
        elseif ($current_status == 'PLUGIN_CONFIGURATION') {

            $form['markup_top'] = array(
                '#markup' => t('<div class="mo_saml_sp_table_layout_1"><div class="mo_saml_table_layout mo_saml_sp_container">
                                  <div class="mo_saml_welcome_message">Thank you for registering with miniOrange</div>')
            );

            /**
             * Create container to hold @moProfileDetails form elements.
             */
            $form['mo_saml_profile_details_tab'] = array(
                '#type' => 'fieldset',
                '#title' => t('Profile Details:'),
                '#attributes' => array( 'style' => 'padding:2% 2% 6%; margin-bottom:2%' ),
            );

          $module_info = \Drupal::service('extension.list.module')->getExtensionInfo('miniorange_saml');

          $header = array( t('Attribute'),  t('Value'),);
            $options = array(
                array( 'Customer Email', \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_customer_admin_email')),
                array( 'Customer ID', \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_customer_id')),
                array( 'Module Version', $module_info['version']),
                array( 'Drupal Version', Utilities::mo_get_drupal_core_version()),
                array( 'PHP Version', phpversion()),
            );

            $form['mo_saml_profile_details_tab']['fieldset']['customerinfo'] = array(
                '#theme' => 'table',
                '#header' => $header,
                '#rows' => $options,
                '#prefix' => '<br>',
                '#attributes' => array( 'style' => 'margin:1% 0% 7%;' ),
            );

            $form['mo_saml_profile_details_tab']['miniorange_saml_customer_Remove_Account_info'] = array(
                '#markup' => t('<br/><h4>Remove Account:</h4><p>This section will help you to remove your current
                        logged in account without losing your current configurations.</p>')
            );

            $form['mo_saml_profile_details_tab']['miniorange_saml_customer_Remove_Account'] = array(
                '#type' => 'link',
                '#title' => $this->t('Remove Account'),
                '#url' => Url::fromRoute('miniorange_saml.modal_form'),
                '#attributes' => [
                    'class' => [
                        'use-ajax',
                        'button',
                    ],
                ],
                '#suffix' => '</div>'
            );

            $form['#attached']['library'][] = 'core/drupal.dialog.ajax';

            Utilities::advertiseNetworkSecurity($form, $form_state);

            return $form;
        }

        $url = $base_url . '/admin/config/people/miniorange_saml/customer_setup';

        $tab = isset($_GET['tab']) && $_GET['tab'] == 'login' ? $_GET['tab'] : 'register';

        $form['div_for_start'] = array(
            '#markup' => t('<div class="mo_saml_sp_table_layout_1"><div class="mo_saml_table_layout mo_saml_sp_container"'),
        );

        if ($tab == 'register'){

            $form['markup_14'] = array(
                '#markup' => t('<div><div class="mo_saml_font_for_heading">Register with mini<span class="mo_orange"><b>O</b></span>range</div><p style="clear: both"></p><hr>'),
            );

            /**
             * Create container to hold @moSAMLRegistrationTab form elements.
             */
            $form['mo_saml_registration_tab'] = array(
                '#type' => 'fieldset',
                '#title' => t('Why should I register?'),
                '#attributes' => array( 'style' => 'padding:2% 2% 5%; margin-bottom:2%' ),
            );

            $form['mo_saml_registration_tab']['markup_msg_1'] = array(
                '#markup' => t('<div class="mo_saml_highlight_background_note_1">Sign up to receive assistance from us when needed. Additionally, a miniOrange account is required to upgrade to the premium module version. Rest assured, we only store your provided email for registration purposes.</div><br>')
            );
            $form['mo_saml_registration_tab']['mo_register'] = array(
                    '#markup' => t('<div class="mo_saml_highlight_background_note_1" style="width: auto">If you face any issues during registration then you can <b><a href="https://www.miniorange.com/businessfreetrial" target="_blank">click here</a></b> to register and use the same credentials below to login into the module.</div><div id="Register_Section">'),
            );


            $form['mo_saml_registration_tab']['miniorange_saml_customer_register_username'] = array(
                '#type' => 'email',
                '#title' => t('Email<span style="color: #FF0000">*</span>'),
                '#attributes' => array(
                    'style' => 'width:50%'
                ),
            );

            $form['mo_saml_registration_tab']['miniorange_saml_customer_register_password'] = array(
                '#type' => 'password_confirm',
                '#required' => TRUE,
            );

            $form['mo_saml_registration_tab']['miniorange_saml_customer_register_button'] = array(
                '#type' => 'submit',
                '#value' => t('Register'),
                '#button_type' => 'primary',
                '#prefix' => '<div class="ns_row"><div class="ns_name">',
                '#suffix' => '</div>'
            );

            $form['mo_saml_registration_tab']['already_account_link'] = array(
                '#type' => 'link',
                '#title' => $this->t('Already have an account?'),
                '#url' => Url::fromUserInput('/admin/config/people/miniorange_saml/customer_setup/?tab=login'),
                '#attributes' => array('class' => 'button'),
                '#prefix' => '<div class="ns_value">',
                '#suffix' => '</div></div></div></div><br>'
            );
        }
        else{

            $form['markup_15'] = array(
                '#markup' => t('<div>'),
            );
            /**
             * Create container to hold @moSAMLloginTab form elements.
             */
          $form['markup_14'] = array(
            '#markup' => t('<div class="mo_saml_font_for_heading">Login with mini<span class="mo_orange"><b>O</b></span>range</div><p style="clear: both"></p><hr>'),
          );

            $form['mo_saml_login_tab'] = array(
                '#type' => 'fieldset',
                '#title' => t('Please login with your miniorange account.'),
                '#attributes' => array( 'style' => 'padding:2% 2% 5%; margin-bottom:2%' ),
            );

            $form['mo_saml_login_tab']['miniorange_saml_customer_login_username'] = array(
                '#type' => 'email',
                '#title' => t('Email <span style="color: red">*</span>'),
                '#attributes' => array('style' => 'width:50%'),
            );

            $form['mo_saml_login_tab']['miniorange_saml_customer_login_password'] = array(
                '#type' => 'password',
                '#title' => t('Password <span style="color: red">*</span>'),
                '#attributes' => array('style' => 'width:50%'),
            );

            $form['mo_saml_login_tab']['miniorange_saml_customer_login_button'] = array(
                '#type' => 'submit',
                '#value' => t('Login'),
                '#button_type' => 'primary',
                '#prefix' => '<div class="ns_row"><div class="ns_name">',
                '#suffix' => '</div>'
            );

            $form['mo_saml_login_tab']['register_link'] = array(
                '#type' => 'link',
                '#title' => $this->t('Create an account?'),
                '#url' => Url::fromUserInput('/admin/config/people/miniorange_saml/customer_setup'),
                '#attributes' => array('class' => 'button'),
                '#prefix' => '<div class="ns_value">',
                '#suffix' => '</div></div><br></div>'
            );
        }

        Utilities::advertiseNetworkSecurity($form, $form_state);

        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        $form_values = $form_state->getValues();
        $tab = isset($_GET['tab']) ? $_GET['tab'] : 'register';
        $phone = '';

        if ( $tab == 'register' ) {
            $username = trim($form_values['miniorange_saml_customer_register_username']);
            $phone    = trim($form_values['miniorange_saml_customer_register_phone']);
            $password = trim($form_values['miniorange_saml_customer_register_password']);
        }else{
            $username = trim($form_values['miniorange_saml_customer_login_username']);
            $password = trim($form_values['miniorange_saml_customer_login_password']);
        }

        if ( empty( $username ) || empty( $password ) ) {
            \Drupal::messenger()->addMessage(t('The <b><u>Email Address</u></b> and <b><u>Password</u></b> fields are mandatory.'), 'error');
            return;
        }

        if ( $tab == 'register' )
            Utilities::customer_setup_submit($username, $phone, $password);
        else
            Utilities::customer_setup_submit($username, $phone, $password, true);
    }


    /**
     * Handle back button submit for customer setup.
     */
    function miniorange_saml_back(&$form, $form_state) {
         Utilities::saml_back();
    }

    /**
     * Resend OTP.
     */
    public function miniorange_saml_resend_otp(&$form, $form_state) {

        Utilities::saml_resend_otp();
    }

    /**
     * Validate OTP.
     */
    public function miniorange_saml_validate_otp_submit(&$form, FormStateInterface $form_state) {
         $form_values = $form_state->getValues();
         $otp_token = trim($form_values['miniorange_saml_customer_otp_token']);
         if (empty($otp_token)) {
            \Drupal::messenger()->addMessage(t('OTP field is required.'), 'error');
            return;
         }
         Utilities::validate_otp_submit($otp_token);
    }
}
