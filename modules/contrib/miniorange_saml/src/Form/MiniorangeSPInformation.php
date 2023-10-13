<?php

/**
 * @file
 * Contains \Drupal\miniorange_saml\Form\MiniorangeSPInformation.
 */

namespace Drupal\miniorange_saml\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Render\Markup;
use Drupal\Core\Url;
use Drupal\miniorange_saml\Utilities;
use Drupal\miniorange_saml\MetadataReader;
use Drupal\miniorange_saml\MiniorangeSAMLConstants;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MiniorangeSPInformation extends FormBase {
    /**
    * {@inheritdoc}
    */
    public function getFormId() {
        return 'miniorange_sp_setup';
    }

    public function buildForm(array $form, FormStateInterface $form_state) {
        global $base_url;

        \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_last_tab_visited', 'SERVICE PROVIDER SETUP')->save();

        $id_req = \Drupal::request()->query->all();
        $idp_issuer = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_idp_issuer');
        $idp_name = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_idp_name');

        if(isset($id_req['delete']) && $id_req['delete'] == 'true') {
          global $base_url;
          $editConfig = \Drupal::configFactory()->getEditable('miniorange_saml.settings');
          $editConfig
            ->clear('miniorange_saml_idp_name')
            ->clear('miniorange_saml_sp_issuer')
            ->clear('miniorange_saml_idp_issuer')
            ->clear('miniorange_saml_nameid_format')
            ->clear('miniorange_saml_idp_login_url')
            ->clear('miniorange_saml_idp_x509_certificate')
            ->clear('miniorange_saml_enable_login')
            ->clear('miniorange_saml_character_encoding')
            ->save();
          \Drupal::messenger()->addMessage($this->t('Identity Provider Configuration Successfully Deleted.'));
          $response = new RedirectResponse($base_url . '/admin/config/people/miniorange_saml/sp_setup/');
          $response->send();
        }

        if(!(empty($idp_issuer) || empty($idp_name)) && empty($id_req)) {
          $form['markup_library'] = array(
            '#attached' => array(
              'library' => array(
                "miniorange_saml/miniorange_saml.test",
                "miniorange_saml/miniorange_saml.admin",
                "miniorange_saml/miniorange_saml.license",
                "core/drupal.dialog.ajax"
              )
            )
          );
          $form['mo_saml_markup_sp_table'] = array(
            '#markup' => t('<div class="mo_saml_sp_table_layout_1"><div class="mo_saml_table_layout mo_saml_sp_container">&nbsp;&nbsp;&nbsp;
                                <div class="mo_saml_font_for_heading">' . $this->t('Service Provider Setup') . '</div><p style="clear: both"></p><hr>'),
            );

          $form['mo_saml_idp_list'] = array(
            '#type' => 'fieldset',
          );

          $form['mo_saml_idp_list']['mo_saml_add_idp'] = array(
            '#markup' => '<a data-dialog-type="modal"  href="add_new_idp" class="use-ajax button button--primary">+ Add New IDP</a>'
          );

          $header = array(
            'idp_name' => array(
              'data' => $this->t('IDP Name')
            ),
            'issuer' => array(
              'data' => $this->t('IDP Entity-ID/Issuer')
            ),
            'test' => array(
              'data' => $this->t('Test')
            ),
            'action' => array(
              'data' => $this->t('Action')
            ),
          );

          $idp_name =  \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_idp_name');
          $idp_issuer = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_idp_issuer');

          $form['mo_saml_idp_list']['mo_saml_idp_list_table'] = array(
            '#type' => 'table',
            '#header' => $header,
            '#suffix' => '</div>',
          );

          $form['mo_saml_idp_list']['mo_saml_idp_list_table'][0]['idp_name'] = [
            '#markup' => $idp_name,
          ];

          $form['mo_saml_idp_list']['mo_saml_idp_list_table'][0]['issuer_name'] = [
            '#markup' => $idp_issuer,
          ];

          $form['mo_saml_idp_list']['mo_saml_idp_list_table'][0]['test_configuration'] = [
            '#markup' => Markup::create('<a style="cursor: pointer;" id="testConfigButton">Test</a>'),
          ];


          $form['mo_saml_idp_list']['mo_saml_idp_list_table'][0]['dropbutton'] = array(
            '#type' => 'dropbutton',
            '#dropbutton_type' => 'small',
            '#links' => [
              'edit' => [
                'title' => $this->t('Edit'),
                'url' => Url::fromUri($base_url . '/admin/config/people/miniorange_saml/sp_setup/?edit=true'),
              ],
              'delete' => [
                'title' => $this->t('Delete'),
                'url' => Url::fromUri($base_url . '/admin/config/people/miniorange_saml/sp_setup/?delete=true'),
              ],
            ],
          );

          Utilities::spConfigGuide($form, $form_state);
          return $form;
      }

        $form['markup_library'] = array(
            '#attached' => array(
                'library' => array(
                    "miniorange_saml/miniorange_saml.test",
                    "miniorange_saml/miniorange_saml.admin",
                    "miniorange_saml/miniorange_saml.license",
                    "miniorange_saml/miniorange_saml.button",
                    "core/drupal.dialog.ajax"
                )
            )
        );

        $form['miniorange_saml_IDP_tab'] = array(
            '#markup' => t('<div class="mo_saml_sp_table_layout_1"><div class="mo_saml_table_layout mo_saml_sp_container">&nbsp;&nbsp;&nbsp;
                                <div class="mo_saml_font_for_heading">Service Provider Setup</div><p style="clear: both"></p><hr><br><div class="mo_saml_font_SP_setup_for_heading">Provide the Identity Provider metadata below.</div>'),
        );


        /**
         * Create container to hold @moSAMLIDPSetup form elements.
         */
        $form['mo_saml_IDP_setup'] = array(
            '#type' => 'details',
            '#title' => t('Upload IDP Metadata' ),
            '#id' => 'upload-metadata-details',
            //'#open' => TRUE,
            '#attributes' => array( 'style' => 'padding:0% 2%; margin-bottom:2%' )
        );

        $form['mo_saml_IDP_setup']['metadata_file'] = array(
            '#type' => 'file',
            '#title' => t('Upload Metadata File'),
            '#prefix' => '<hr><br><div class="container-inline">',

        );

        $form['mo_saml_IDP_setup']['metadata_upload'] = array(
            '#type' => 'submit',
            '#value' => t('Upload File'),
            '#button_type' => 'primary',
            '#submit' => array('::miniorange_saml_upload_file'),
            '#suffix'  => t('</div><br><h2>&emsp;&emsp;&emsp;OR</h2><br>'),
        );

        $form['mo_saml_IDP_setup']['metadata_URL'] = array(
            '#type' => 'textfield',
            '#title' => t('Upload Metadata URL'),
            '#attributes' => array('style' => 'width:65%','placeholder' => t('Enter metadata URL of your IdP.')),
            '#maxlength' => NULL,
            '#prefix' => '<div class="container-inline">',
        );

        $form['mo_saml_IDP_setup']['metadata_fetch'] = array(
            '#type' => 'submit',
            '#button_type' => 'primary',
            '#value' => t('Fetch Metadata'),
            '#submit' => array('::miniorange_saml_fetch_metadata'),
            '#suffix' => '</div><br>',
        );

        $form['mo_saml_IDP_setup']['miniorange_saml_fetch_metadata_1'] = array(
            '#type' => 'checkbox',
            '#title' => t('Allow auto-update IdP signature certificate. <a href="' . $base_url . MiniorangeSAMLConstants::LICENSING_TAB_URL .'">[Premium and Enterprise]</a>.'),
            '#disabled' => TRUE,
            '#description' => t('The metadata URL of IdP will be stored and pinged on regular intervals (<a target="_blank" href="' . $base_url . '/admin/config/system/cron ">cron run</a>) to fetch updated X.509 certs.'),
        );

        /**
         * Create container to hold @ServiceProviderSetup form elements.
         */
        $form['mo_saml_service_provider_metadata'] = array(
            '#type' => 'fieldset',
            //'#title' => t('Service Provider Metadata'),
            '#attributes' => array( 'style' => 'padding:2% 2% 5%; margin-bottom:2%' ),
        );

        $form['mo_saml_service_provider_metadata']['miniorange_saml_idp_name'] = array(
            '#type' => 'textfield',
            '#title' => t('Identity Provider Name<span style="color: #FF0000">*</span>'),
            '#default_value' => \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_idp_name'),
            '#attributes' => array('style' => 'width:90%;margin-bottom:1%;', 'placeholder' => t('Identity Provider Name')),
            '#prefix' =>'<div id = "miniorange_saml_idp_name_div">',
            '#suffix' =>'</div>',
        );

        $form['mo_saml_service_provider_metadata']['miniorange_saml_idp_issuer'] = array(
            '#type' => 'textfield',
            '#title' => t('IdP Entity ID or Issuer<span style="color: #FF0000">*</span>'),
            '#default_value' => \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_idp_issuer'),
            '#attributes' => array('style' => 'width:90%;','placeholder' => t('IdP Entity ID or Issuer')),
            '#prefix' =>'<div id = "miniorange_saml_idp_issuer_div">',
            '#suffix' =>'</div>',
        );

        $form['mo_saml_service_provider_metadata']['miniorange_saml_idp_binding'] = array(
            '#type' => 'radios',
            '#title' => t('HTTP Binding of SSO Request ') . '<a href="' . $base_url . MiniorangeSAMLConstants::LICENSING_TAB_URL .'">[Standard, Premium and Enterprise]</a></b>',
            '#options' => array('HTTP-Redirect'=>t('HTTP-Redirect'),'HTTP-POST'=>t('HTTP-POST')),
            '#disabled' => TRUE,
            '#prefix' => '<div class="container-inline">',
            '#suffix' => '</div>'
          );

        $form['mo_saml_service_provider_metadata']['miniorange_saml_idp_login_url'] = array(
            '#type' => 'url',
            '#title' => t('SAML Login URL<span style="color: #FF0000">*</span>'),
            '#default_value' => \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_idp_login_url'),
            '#attributes' => array('style' => 'width:90%;', 'placeholder' => t('SAML Login URL')),
            '#prefix' =>'<div id="miniorange_saml_idp_login_url_start">',
            '#suffix' =>'</div>',
        );

        $form['mo_saml_service_provider_metadata']['miniorange_saml_nameid_format'] = array(
            '#type' => 'select',
            '#title' => t('NameID Format <a href="' . $base_url . MiniorangeSAMLConstants::LICENSING_TAB_URL .'"> [Premium]</a>'),
            '#options' => array('nameid-format' => t('urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified'),),
            '#attributes' => array('style' => 'width:90%;'),
            '#disabled' => TRUE,
        );

        $form['mo_saml_service_provider_metadata']['miniorange_saml_slo_binding'] = array(
            '#type' => 'radios',
            '#title' => t('HTTP Binding of SLO Request ') . '<a href="' . $base_url . MiniorangeSAMLConstants::LICENSING_TAB_URL .'">[Premium]</a>',
            '#options' => array('HTTP-Redirect'=>t('HTTP-Redirect'),'HTTP-POST'=>t('HTTP-POST')),
            '#disabled' => TRUE,
            '#prefix' => '<div class="container-inline">',
            '#suffix' => '</div>'
        );

        $form['mo_saml_service_provider_metadata']['miniorange_saml_idp_logout_url'] = array(
            '#type' => 'textfield',
            '#title' => t('SAML Logout URL <a href="' . $base_url . MiniorangeSAMLConstants::LICENSING_TAB_URL .'">[Premium]</a>'),
            '#attributes' => array('style' => 'width:90%; background-color: hsla(0,0%,0%,0.08) !important;', 'placeholder' => t('SAML Logout URL')),
            '#disabled' => TRUE,
        );

        $form['mo_saml_service_provider_metadata']['myradios'] = array(
            '#title' => t('X.509 Certificate Value'),
            '#type' => 'radios',
            '#options' => array(
                'text' => t('Enter as Text'),
                'upload' => t('Upload Certificate'),
            ),
            '#default_value' => 'text',
            '#prefix' => '<div id="miniorange_saml_idp_x509_certificate_start"><div class="container-inline">',
            '#suffix' => '</div>',
        );

        $form['mo_saml_service_provider_metadata']['miniorange_saml_idp_x509_certificate'] = array(
            '#type' => 'textarea',
            '#default_value' => \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_idp_x509_certificate'),
            '#attributes' => array('placeholder' => '-----BEGIN CERTIFICATE-----'.PHP_EOL.'XXXXXXXXXXXXXXXXXXXXXXXXXXX'.PHP_EOL.'-----END CERTIFICATE-----'),
            '#states' => array('visible' => array(':input[name = "myradios"]' => array( 'value' => 'text' ),),),
            '#suffix' => '</div>'
        );

        $form['mo_saml_service_provider_metadata']['mo_saml_cert_file'] = array(
            '#type' => 'file',
            '#title' => t('Upload Certificate'),
            '#prefix' => '<div class="container-inline">',
            '#states' => array('visible' => array(':input[name = "myradios"]' => array( 'value' => 'upload' ),),),
        );

        $form['mo_saml_service_provider_metadata']['metadata_upload_cert_file'] = array(
            '#type' => 'submit',
            '#button_type' => 'primary',
            '#value' => t('Upload'),
            '#submit' => array('::mo_saml_upload_certificate'),
            '#states' => array('visible' => array(':input[name = "myradios"]' => array( 'value' => 'upload' ),),),
            '#suffix' => '</div>',
        );

        $form['mo_saml_service_provider_metadata']['miniorange_saml_enable_login'] = array(
            '#type' => 'checkbox',
            '#title' => t('Enable login with SAML'),
            '#default_value' => \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_enable_login'),
            '#description'=>t('This will add SSO link - ({Base_URL}/samllogin) on Drupal login page.'),
            '#prefix' => '<div id="enable_login_with_saml">',
            '#suffix' => '</div>'
        );

        $form['mo_saml_service_provider_metadata']['miniorange_saml_character_encoding'] = array(
            '#type' => 'checkbox',
            '#title' => t('Character Encoding'),
            '#default_value' => \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_character_encoding'),
            '#description' => 'Convert X.509 certificate into appropriate encoding using iconv.',
        );

        $form['mo_saml_service_provider_metadata']['miniorange_saml_sign_request'] = array(
            '#type' => 'checkbox',
            '#title' => t('Sign SSO and SLO requests <b><a href="' . $base_url . MiniorangeSAMLConstants::LICENSING_TAB_URL .'"> [Standard, Premium and Enterprise]</a></b>'),
            '#disabled' => TRUE,
        );

        $form['mo_saml_service_provider_metadata']['security_signature_algorithm'] = array(
            '#type' => 'select',
            '#title' => t('SAML Request Signature algorithm <a href="' . $base_url . MiniorangeSAMLConstants::LICENSING_TAB_URL .'">[Enterprise]</a>'),
            '#options' => array('RSA_SHA256'=>t('SHA256'),'RSA_SHA384'=>t('SHA384'), 'RSA_SHA512'=>t('SHA512'), 'RSA_SHA1'=>t('SHA1')),
            '#attributes' => array('style' => 'width: 90%'),
            '#disabled' => FALSE,
            '#suffix' => '<br>',
        );

        $form['mo_saml_service_provider_metadata']['miniorange_saml_idp_config_submit'] = array(
            '#type' => 'submit',
            '#value' => t('Save Configuration'),
        );

        $form['mo_saml_service_provider_metadata']['miniorange_saml_test_config_button'] = array(
            '#markup' => '<span id="testConfigButton" class="button">Test Configuration</span>',
        );

        $form['mo_saml_service_provider_metadata']['miniorange_saml_test_show_SAML_request_button'] = array(
            '#markup' => '<span id="showSAMLrequestButton" class="button button--primary">SAML Request</span>',
        );

        $form['mo_saml_service_provider_metadata']['miniorange_saml_test_show_SAML_response_button'] = array(
            '#markup' => '<span id="showSAMLresponseButton" class="button button--primary">SAML Response</span>',
            '#suffix' => '</div>',
        );

        Utilities::spConfigGuide($form, $form_state);

        return $form;
    }

    public function miniorange_saml_form_alter(array &$form, FormStateInterface $form_state, $form_id){
        $form['actions']['submit']['#submit'][] = 'test';
	    return $form;
	}

    function mo_saml_upload_certificate(array &$form, FormStateInterface $form_state) {
        $form_values = $form_state->getValues();
        $certificate = $_FILES['files']['tmp_name']['mo_saml_cert_file'];
        if( !empty( $certificate ) ) {
            $file_name = $_FILES['files']['name']['mo_saml_cert_file'];
            list($name_without_extention, $extention) = explode('.', $file_name);
            if($extention == 'crt' || $extention == 'cer' || $extention == 'cert')  {

                $cert_content = Utilities::sanitize_certificate( file_get_contents( $certificate ) );
                $idp_name  = $form_values['miniorange_saml_idp_name'];
                $issuer    = $form_values['miniorange_saml_idp_issuer'];
                $login_url = $form_values['miniorange_saml_idp_login_url'];
                \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_idp_name', $idp_name)->save();
                \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_idp_issuer', str_replace(' ', '', $issuer))->save();
                \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_idp_login_url', str_replace(' ', '', $login_url))->save();
                \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_idp_x509_certificate', $cert_content)->save();
                \Drupal::messenger()->addMessage(t('x.509 Certificate Value successfully updated.'));
                return;
            }else {
                \Drupal::messenger()->addMessage(t('<b style="color: red">File type is not compatible</b> <br> Please Select <b style="color: red">".crt"</b>  or <b style="color: red">".cert"</b> extended file to upload Configuration!'),'error');
                return;
            }
        }else {
            \Drupal::messenger()->addMessage(t( 'Please select a file to upload.'),'error');
            return;
        }
    }

    /**
    * Configure IdP.
    */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        global $base_url;
        $form_values        = $form_state->getValues();
        $issuer             = $form_values['miniorange_saml_idp_issuer'];
        $idp_name           = $form_values['miniorange_saml_idp_name'];
        $nameid_format      = 'urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified';
        $login_url          = $form_values['miniorange_saml_idp_login_url'];
        $x509_cert_value = isset( $form_values['miniorange_saml_idp_x509_certificate'] ) ? Utilities::sanitize_certificate( $form_values['miniorange_saml_idp_x509_certificate'] ) : '';
        $enable_login_value = $form_values['miniorange_saml_enable_login'];
        $character_encoding     = $form_values['miniorange_saml_character_encoding'];
        if( empty( $idp_name ) || empty( $issuer ) || empty( $login_url ) ) {
            \Drupal::messenger()->addMessage(t('The <b><u>Identity Provider Name, IdP Entity ID or Issuer</u></b> and <b><u>SAML Login URL</u></b> fields are mandatory.'), 'error');
            return;
        }
        $character_encoding = $character_encoding == 1;
        $enable_login    = $enable_login_value == 1 ;
        $sp_issuer = $base_url . '/samlassertion';

        \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_base', $base_url )->save();
        \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_idp_name', $idp_name )->save();
        \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_sp_issuer', $sp_issuer )->save();
        \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_idp_issuer', str_replace(' ', '', $issuer))->save();
        \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_nameid_format', $nameid_format )->save();
        \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_idp_login_url', str_replace(' ', '', $login_url))->save();
        \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_idp_x509_certificate', $x509_cert_value )->save();
        \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_enable_login', $enable_login )->save();
        \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_character_encoding',$character_encoding)->save();
        \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_module_configured','Yes')->save();

        \Drupal::messenger()->addStatus(t('Identity Provider Configuration successfully saved'));
        $response = new RedirectResponse($base_url . '/admin/config/people/miniorange_saml/sp_setup/');
        $response->send();
    }

    function miniorange_saml_upload_file(array &$form, FormStateInterface $form_state) {
    	$file_name = $_FILES['files']['tmp_name']['metadata_file'];
    	if( empty( $file_name ) ) {
            \Drupal::messenger()->addMessage(t('Please Provider valid metadata file.'),'error');
            return;
        }
    	$file = file_get_contents( $file_name );
    	self::upload_metadata( $file );
    }

    function miniorange_saml_fetch_metadata(array &$form, FormStateInterface $form_state) {
        $form_values        = $form_state->getValues();
        $url = filter_var( $form_values['metadata_URL'],FILTER_SANITIZE_URL );
        if( empty( $url ) ) {
            \Drupal::messenger()->addMessage(t('Please Provider valid metadata URL.'),'error');
            return;
        }
    	$arrContextOptions = array(
    	    "ssl"=>array(
				"verify_peer"=>false,
				"verify_peer_name"=>false,
            ),
        );
	    $file = file_get_contents( $url, false, stream_context_create( $arrContextOptions ) );
	    self::upload_metadata( $file );
    }

    public function upload_metadata( $file ) {

        global $base_url;
        $idp_name_stored = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_idp_name');
        if ( empty( $idp_name_stored ) ) {
            \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_idp_name', 'Identity Provider')->save();
        }

    	$document = new \DOMDocument();
		$document->loadXML($file);
		restore_error_handler();
		$first_child = $document->firstChild;

		if( !empty( $first_child ) ) {
			$metadata = new MetadataReader($document);
			$identity_providers = $metadata->getIdentityProviders();
			if( empty( $identity_providers ) ) {
                \Drupal::messenger()->addMessage(t('Please provide a valid metadata file.'),'error');
		    	return;
			}

			foreach($identity_providers as $key => $idp) {
				$saml_login_url = $idp->getLoginURL('HTTP-Redirect');
				if(empty($saml_login_url)) {
					$saml_login_url = $idp->getLoginURL('HTTP-POST');
				}
				$saml_issuer = $idp->getEntityID();
				$saml_x509_certificate = $idp->getSigningCertificate();
				$sp_issuer = $base_url;

                \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_sp_issuer', $sp_issuer)->save();
                \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_idp_issuer', $saml_issuer)->save();
                \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_idp_login_url', $saml_login_url)->save();
                \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_idp_x509_certificate', $saml_x509_certificate[0])->save();
			}
            \Drupal::messenger()->addStatus(t('Identity Provider Configuration successfully saved.'));
            \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_module_configured','Yes')->save();
			return;
		} else {
            \Drupal::messenger()->addError(t('Please provide a valid metadata file.'));
		    return;
		}
  }

    function miniorange_add_new_idp(array &$form, FormStateInterface $form_state) {
      global $base_url;
      $response = new RedirectResponse($base_url . '/admin/config/people/miniorange_saml/add_new_idp');
      $response->send();
    }
}
