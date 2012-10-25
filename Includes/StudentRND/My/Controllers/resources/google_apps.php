<?php

namespace StudentRND\My\Controllers\resources;

use \StudentRND\My\Models;

class google_apps extends \CuteControllers\Base\Rest
{
    public function before()
    {
        if (!\StudentRND\My\Models\User::current()->studentrnd_email_enabled) {
            throw new \CuteControllers\HttpError(403);
        }

        $gapps_user = \StudentRND\My\Util::get_gapps_api_client()->retrieveUser(Models\User::current()->username);
        if ($gapps_user === NULL) {
            $gapps_user = \StudentRND\My\Util::get_gapps_api_client()->createUser(Models\User::current()->username,
                               Models\User::current()->first_name,
                               Models\User::current()->last_name,
                               hash('md5', time() . rand(0,10000) . Models\User::current()->username) . '!1!');
        }

        if ($gapps_user->login->admin !== Models\User::current()->is_admin) {
            $gapps_user->login->admin = Models\User::current()->is_admin;
            $gapps_user = $gapps_user->save();
        }

        $this->user = \StudentRND\My\Models\User::current();
    }

    public function __get_settings()
    {
        include(TEMPLATE_DIR . '/Home/resources/google_apps/change_password.php');
    }

    public function __post_settings()
    {
        $confirm = $this->request->post('confirm');
        $password = $this->request->post('password');

        if (!$confirm || !$password) {
            $error = "Please enter your My.StudentRND password, and your desired Apps password.";
            include(TEMPLATE_DIR . '/Home/resources/google_apps/change_password.php');
        } else if(!Models\User::current()->validate_password($confirm)) {
            $error = "Please enter your correct My.StudentRND password to confirm your identity.";
            include(TEMPLATE_DIR . '/Home/resources/google_apps/change_password.php');
        } else {
            try {
                $gapps_user = \StudentRND\My\Util::get_gapps_api_client()->retrieveUser(Models\User::current()->username);
                $gapps_user->login->password = $password;
                $gapps_user = $gapps_user->save();
                $this->redirect('/');
            } catch (\Exception $ex) {
                $error = $ex->getMessage();
                include(TEMPLATE_DIR . '/Home/resources/google_apps/change_password.php');
            }
        }
    }

    public function __get_login($to)
    {
        global $config;
        if ($to === NULL) {
            $to = 'mail';
        }
        $to = preg_replace('/[^a-zA-Z0-9\s]/', '', $to);
        $this->redirect('http://' . $to . '.google.com/a/' . $config['app']['domain']);
    }

    public function __get_saml()
    {
        $authnrequest = $this->parse_saml_request();
        $redirect_url = $authnrequest['AssertionConsumerServiceURL'];
        $relay_state = $this->request->request('RelayState');
        $saml_response = $this->get_saml_provider_response();

        // Why is this a thing?
        $form = <<<FORM
<h3>Redirecting you to Google</h3>
 <form method="post" action="$redirect_url">
   <input type="hidden" name="RelayState" value="$relay_state" />
   <input type="hidden" name="SAMLResponse" value="$saml_response" />
   <input type="submit" value="Continue..." />
 </form>
 <script>document.forms[0].submit();</script>
FORM;
        print $form;
    }

    /**
     * Decodes the SAML request
     * @return array Decoded SAML request
     */
    private function parse_saml_request()
    {
        $incoming = base64_decode($this->request->request('SAMLRequest'));
        if(!$xml_string = gzinflate($incoming)){
            $xml_string = $incoming;
        }
        $xml = new \DOMDocument();
        $xml->loadXML($xml_string);
        if($xml->hasChildNodes() && ($node = $xml->childNodes->item(0))){
            $authnrequest = array();
            foreach($node->attributes as $attr){
                $authnrequest[$attr->name] = $attr->value;
            }
            if($node->hasChildNodes()){
                foreach($node->childNodes as $childnode){
                    if($childnode->hasAttributes()){
                        $authnrequest[$childnode->nodeName]=array();
                        foreach($childnode->attributes as $attr){
                            $authnrequest[$childnode->nodeName][$attr->name] = $attr->value;
                        }
                    }else{
                        $authnrequest[$childnode->nodeName]=$childnode->nodeValue;
                    }
                }
            }
        }

        return $authnrequest;
    }

    /**
     * Signs a ticket asserting who the user is
     * @return string SAML Response
     */
    private function get_saml_provider_response()
    {
        // Load the XML security libs needed to encrypt this
        require_once(INCLUDES_DIR . '/xmlseclibs.php');

        global $config;

        $authnrequest = $this->parse_saml_request();

        // Ugh...

        // First, we'll set some expiration params, since they're the easiest
        $response_params = array();
        $time = time();
        $response_params['IssueInstant'] = str_replace('+00:00', 'Z', gmdate("c",$time));
        $response_params['NotOnOrAfter'] = str_replace('+00:00', 'Z', gmdate("c",$time+300));
        $response_params['NotBefore'] = str_replace('+00:00', 'Z', gmdate("c",$time-30));
        $response_params['AuthnInstant'] = str_replace('+00:00', 'Z', gmdate("c",$time-120));
        $response_params['SessionNotOnOrAfter'] = str_replace('+00:00', 'Z', gmdate("c",$time+3600*8));

        // Ticket ID - we could theoretically use this for something in the future but the spec doesn't say what or why we would ever need this, so
        // we'll just generate it randomly and pretend it never existed.
        $response_params['ID'] = $this->generate_unique_id(40);
        $response_params['assertID'] = $this->generate_unique_id(40);

        // Who even are we
        $response_params['issuer'] = $config['app']['domain']; //Doesn't seem to actually matter
        $response_params['x509'] = file_get_contents(ASSETS_DIR . '/cert/server.crt'); // Public
        $private_key = file_get_contents(ASSETS_DIR . '/cert/server.pem'); // Private

        // Set the username
        // This is essentially the only substance in this entire thing
        $response_params['email'] = $this->user->username . '@' . $config['app']['domain'];

        // SAML garbage:
        $xml = new \DOMDocument('1.0', 'utf-8');
        $resp = $xml->createElementNS('urn:oasis:names:tc:SAML:2.0:protocol', 'samlp:Response');

        $resp->setAttribute('ID', $response_params['ID']);
        $resp->setAttribute('InResponseTo', $authnrequest['ID']);
        $resp->setAttribute('Version', '2.0');
        $resp->setAttribute('IssueInstant', $response_params['IssueInstant']);
        $resp->setAttribute('Destination', $authnrequest['AssertionConsumerServiceURL']);
        $xml->appendChild($resp);

        $issuer = $xml->createElementNS('urn:oasis:names:tc:SAML:2.0:assertion', 'samlp:Issuer', $response_params['issuer']);
        $resp->appendChild($issuer);

        $status = $xml->createElementNS('urn:oasis:names:tc:SAML:2.0:protocol', 'samlp:Status');
        $resp->appendChild($status);

        $statusCode = $xml->createElementNS('urn:oasis:names:tc:SAML:2.0:protocol', 'samlp:StatusCode');
        $statusCode->setAttribute('Value',  'urn:oasis:names:tc:SAML:2.0:status:Success');
        $status->appendChild($statusCode);

        $assertion = $xml->createElementNS('urn:oasis:names:tc:SAML:2.0:assertion', 'saml:Assertion');
        $assertion->setAttributeNS('http://www.w3.org/2000/xmlns/',  'xmlns:saml',  'urn:oasis:names:tc:SAML:2.0:assertion');
        $assertion->setAttribute('ID', $response_params['assertID']);
        $assertion->setAttribute('IssueInstant', $response_params['IssueInstant']);
        $assertion->setAttribute('Version', '2.0');
        $resp->appendChild($assertion);

        $assertion->appendChild($xml->createElement('saml:Issuer', $response_params['issuer']));

        $subject = $xml->createElement('saml:Subject');
        $assertion->appendChild($subject);

        $nameid = $xml->createElement('saml:NameID', $response_params['email']);

        $nameid->setAttribute('Format', 'urn:oasis:names:tc:SAML:2.0:nameid-format:email');
        $nameid->setAttribute('SPNameQualifier', 'google.com');
        $subject->appendChild($nameid);

        $confirmation = $xml->createElement('saml:SubjectConfirmation');
        $confirmation->setAttribute('Method', 'urn:oasis:names:tc:SAML:2.0:cm:bearer');
        $subject->appendChild($confirmation);

        // Put in the params from before
        $confirmationdata = $xml->createElement('saml:SubjectConfirmationData');
        $confirmationdata->setAttribute('InResponseTo', $authnrequest['ID']);
        $confirmationdata->setAttribute('NotOnOrAfter', $response_params['NotOnOrAfter']);
        $confirmationdata->setAttribute('Recipient', $authnrequest['AssertionConsumerServiceURL']);
        $confirmation->appendChild($confirmationdata);

        $condition = $xml->createElement('saml:Conditions');
        $condition->setAttribute('NotBefore', $response_params['NotBefore']);
        $condition->setAttribute('NotOnOrAfter', $response_params['NotOnOrAfter']);
        $assertion->appendChild($condition);

        $audiencer = $xml->createElement('saml:AudienceRestriction');
        $condition->appendChild($audiencer);

        $audience = $xml->createElement('saml:Audience', 'google.com');
        $audiencer->appendChild($audience);

        $authnstat = $xml->createElement('saml:AuthnStatement');
        $authnstat->setAttribute('AuthnInstant', $response_params['AuthnInstant']);
        $authnstat->setAttribute('SessionIndex', '_'.$this->generate_unique_id(30));//$response_params['assertID']
        $authnstat->setAttribute('SessionNotOnOrAfter', $response_params['SessionNotOnOrAfter']);
        $assertion->appendChild($authnstat);

        $authncontext = $xml->createElement('saml:AuthnContext');
        $authnstat->appendChild($authncontext);

        $authncontext_ref = $xml->createElement('saml:AuthnContextClassRef', 'urn:oasis:names:tc:SAML:2.0:ac:classes:Password');
        $authncontext->appendChild($authncontext_ref);


        // Load the private key from the string
        $objKey = new \XMLSecurityKey(\XMLSecurityKey::RSA_SHA1, array('type' => 'private'));
        $objKey->loadKey($private_key);

        //Sign the Assertion
        $secobj = new \XMLSecurityDSig();
        $secobj->setCanonicalMethod(\XMLSecurityDSig::EXC_C14N);
        $secobj->addReferenceList(array($assertion), \XMLSecurityDSig::SHA1,
            array('http://www.w3.org/2000/09/xmldsig#enveloped-signature',  \XMLSecurityDSig::EXC_C14N), array('id_name'=>'ID', 'overwrite'=>false));
        $secobj->sign($objKey);
        $secobj->add509Cert($response_params['x509']);
        $secobj->insertSignature($assertion, $subject);


        $res = $xml->saveXML();
        $res = str_replace('<?xml version="1.0"?>',  '',  $res); // Google doesn't like this; not mentioned in the spec
        $res = base64_encode(stripslashes($res)); //We assume post binding - the response is not deflated

        // Cannot believe I actually got this to work
        return $res;
    }

    /**
     * Generates a unique HEX ID for use in a SAML request
     * @param  int    $length Length of the string
     * @return string         Random string
     */
    private function generate_unique_id($length) {
        $chars = "abcdef0123456789";
        $chars_len = strlen($chars);
        $uniqueID = "";
        for ($i = 0; $i < $length; $i++) {
            $uniqueID .= substr($chars,rand(0,15),1);
        }
        return 'a'.$uniqueID;
    }
}
