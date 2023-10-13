<?php

namespace Drupal\miniorange_saml;

/**
 * @file
 * This class represents constants used throughout project.
 */
class MiniorangeSAMLConstants
{
    const BASE_URL = 'https://login.xecurify.com';
    const DEFAULT_CUSTOMER_ID = "16555";
    const DEFAULT_API_KEY = "fFd2XcvTGDemZvbw1bcUesNJWEqKbbUq";

    const CUSTOMER_CHECK_API = self::BASE_URL . '/moas/rest/customer/check-if-exists';
    const CUSTOMER_CREATE_API = self::BASE_URL . '/moas/rest/customer/add';
    const CUSTOMER_GET_KEYS = self::BASE_URL . '/moas/rest/customer/key';
    const SUPPORT_QUERY = self::BASE_URL . '/moas/rest/customer/contact-us';
    const AUTH_CHALLENGE_API = self::BASE_URL . '/moas/api/auth/challenge';
    const AUTH_VALIDATE_API = self::BASE_URL . '/moas/api/auth/validate';
    const FEEDBACK_API = self::BASE_URL . '/moas/api/notify/send';
    const SERVER_TIME_API = self::BASE_URL . '/moas/rest/mobile/get-timestamp';

    const LICENSING_TAB_URL = '/admin/config/people/miniorange_saml/Licensing';
    const AREA_OF_INTEREST = 'Drupal 8 SAML Plugin';
    const SUPPORT_EMAIL = 'drupalsupport@xecurify.com';
    const SUPPORT_NAME = 'drupalsupport';
    const USER_ATTRIBUTE = '/admin/config/people/accounts/fields';

    const MODULE_INFO = [
        'name' => 'SAML SP',
        'saml_features' => 'https://plugins.miniorange.com/drupal-saml-single-sign-on-sso',
        'setup_guides' => 'https://www.drupal.org/docs/contributed-modules/saml-sp-single-sign-on-sso-saml-service-provider-by-miniorange',
        'video_links' => 'https://youtube.com/playlist?list=PL2vweZ-PcNpeh1ZcPGGtyg6M6DmEe0046',
        'case_studies' => 'https://www.drupal.org/node/3196471/case-studies',
        'landing_page' => 'https://plugins.miniorange.com/drupal',
        'customers' => 'https://plugins.miniorange.com/drupal-customers',
        'drupalsupport' => self::SUPPORT_EMAIL,
    ];

    /*
     * SETUP GUIDES
     */
    const SETUP_GUIDE = [
        'azure_ad' => 'https://www.drupal.org/docs/contributed-modules/saml-sp-single-sign-on-sso-saml-service-provider-by-miniorange/guide-for-drupal-single-sign-on-sso-using-azure-ad-as-identity-provider-idp',
        'adfs' => 'https://www.drupal.org/docs/contributed-modules/saml-sp-single-sign-on-sso-saml-service-provider-by-miniorange/guide-for-drupal-saml-single-sign-on-sso-using-adfs-as-identity-provider-idp',
        'okta' => 'https://www.drupal.org/docs/contributed-modules/saml-sp-single-sign-on-sso-saml-service-provider-by-miniorange/guide-for-drupal-single-sign-on-sso-using-okta-as-identity-provider-idp',
        'google_apps' => 'https://www.drupal.org/docs/contributed-modules/saml-sp-single-sign-on-sso-saml-service-provider-by-miniorange/guide-for-drupal-single-sign-on-sso-using-google-apps-as-identity-provider-idp',
        'salesforce' => 'https://www.drupal.org/docs/contributed-modules/saml-sp-single-sign-on-sso-saml-service-provider-by-miniorange/guide-for-drupal-single-sign-on-sso-using-salesforce-as-identity-provider-idp',
        'miniorange' => 'https://www.drupal.org/docs/contributed-modules/saml-sp-single-sign-on-sso-saml-service-provider-by-miniorange/guide-for-drupal-single-sign-on-sso-using-miniorange-as-identity-provider-idp',
        'pingone' => 'https://www.drupal.org/docs/contributed-modules/saml-sp-single-sign-on-sso-saml-service-provider-by-miniorange/guide-for-drupal-single-sign-on-sso-using-pingone-as-identity-provider-idp',
        'onelogin' => 'https://www.drupal.org/docs/contributed-modules/saml-sp-single-sign-on-sso-saml-service-provider-by-miniorange/guide-for-drupal-single-sign-on-sso-using-onelogin-as-identity-provider-idp',
        'bitium' => 'https://www.drupal.org/docs/contributed-modules/saml-sp-single-sign-on-sso-saml-service-provider-by-miniorange/guide-for-drupal-single-sign-on-sso-using-bitium-as-identity-provider-idp',
        'centrify' => 'https://www.drupal.org/docs/contributed-modules/saml-sp-single-sign-on-sso-saml-service-provider/guide-for-drupal-single-sign-on-sso-using-centrify-as-identity-provider-idp',
        'shibboleth2' => 'https://plugins.miniorange.com/guide-to-setup-shibboleth2-as-idp-with-drupal',
        'shibboleth3' => 'https://plugins.miniorange.com/guide-to-setup-shibboleth3-as-idp-with-drupal',
        'pingfederate' => 'https://www.drupal.org/docs/contributed-modules/saml-sp-single-sign-on-sso-saml-service-provider/guide-for-drupal-single-sign-on-sso-using-pingfederate-as-identity-provider-idp',
        'openam' => 'https://www.drupal.org/docs/contributed-modules/saml-sp-single-sign-on-sso-saml-service-provider/guide-for-drupal-single-sign-on-sso-using-openam-as-identity-provider-idp',
        'auth0' => 'https://www.drupal.org/docs/contributed-modules/saml-sp-single-sign-on-sso-saml-service-provider-by-miniorange/guide-for-drupal-single-sign-on-sso-using-auth0-as-identity-provideridp-with-miniorange',
        'oracle' => 'https://www.drupal.org/docs/contributed-modules/saml-sp-single-sign-on-sso-saml-service-provider/guide-for-drupal-single-sign-on-sso-using-oracle-as-identity-provider-idp',
    ];

    const MODULE_FEATURES = [
        'SAML Single Logout',
        'Multisite SSO setup',
        'Add multiple IdP',
        'Forced login for all user by IdP credentials only',
        'Redirect users to specific page after login or logout',
        'Mandate SSO to access any page of the site',
        'Allow SSO based on user roles',
        'Allow SSO based on domain of the user email',
        'Assign user roles in Drupal based on SAML attributes (Role Mapping)',
        'SAML attributes to Drupal user field mapping (Attribute Mapping)',
        'Profile module attribute mapping',
        'Generate custom X.509 certificates',
    ];
}
