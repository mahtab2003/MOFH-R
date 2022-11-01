<?php 

class Selfsigned extends CI_Model
{
	function new_csr($domain, $email, $bits = 2048)
	{
		$key = openssl_pkey_new([
			 "private_key_bits" => $bits,
			 "private_key_type" => OPENSSL_KEYTYPE_RSA,
		]);
		openssl_pkey_export($key, $privkey);
		$dn = array(
			"commonName" => $domain,
			"emailAddress" => $email
		);
		$csr = openssl_csr_new($dn, $privkey, array('digest_alg' => 'sha256'));
		openssl_csr_export($csr, $csrout);
		openssl_free_key($key);
		return [
			'csr' => $csrout,
			'privkey' => $privkey
		];
	}
	
	function issue_crt($csr, $privkey)
	{
		$domain = openssl_csr_get_subject($csr);
		$domain = $domain['CN'];
		$sign = openssl_csr_sign($csr, NULL, $privkey, 90, array('digest_alg' => 'sha256'));
		openssl_x509_export($sign, $crt);
		openssl_x509_free($sign);
		return [
			'csr' => $csr,
			'privkey' => $privkey,
			'crt' => $crt
		];
	}
}

?>