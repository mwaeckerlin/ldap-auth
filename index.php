<?php
function ldap_die($ldapconn, $txt) {
  error_log($txt);
  if ($ldapconn) error_log(ldap_errno($ldapconn).": ".ldap_error($ldapconn));
  unset($_SERVER['PHP_AUTH_USER']);
  unset($_SERVER['PHP_AUTH_PW']);
  header('WWW-Authenticate: Basic realm="'.$_SERVER['LDAP_REALM'].'"');
  header('HTTP/1.0 401 Unauthorized');
  exit;
}

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="'.$_SERVER['LDAP_REALM'].'"');
    header('HTTP/1.0 401 Unauthorized');
} else {
  $ldapconn = ldap_connect($_SERVER['LDAP_HOST'], 389)
    or ldap_die($ldapconn, "connection to $ldaphost failed");
  ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3)
    or ldap_die($ldapconn, "failed to set protocol version 3");
  ldap_start_tls($ldapconn)
    or ldap_die($ldapconn, "cannot start TLS");
  $ldapbind = @ldap_bind($ldapconn, $_SERVER['LDAP_BIND_DN'], $_SERVER['LDAP_BIND_PASS'])
    or ldap_die($ldapconn, "login failed for ".$_SERVER['LDAP_BIND_DN']);
  $res = ldap_search($ldapconn, $_SERVER['LDAP_BASE_DN'], "uid=".$_SERVER['PHP_AUTH_USER'], array("dn"))
    or ldap_die($ldapconn, "error looking up user ".$_SERVER['PHP_AUTH_USER']);
  $info = ldap_get_entries($ldapconn, $res);
  $info['count'] == 0
    and ldap_die($ldapconn, "user ".$_SERVER['PHP_AUTH_USER']." not found");
  $info['count'] != 1
    and ldap_die($ldapconn, "user ".$_SERVER['PHP_AUTH_USER']." is ambiguous");
  $ldapbind = @ldap_bind($ldapconn, $info[0]['dn'], $_SERVER['PHP_AUTH_PW'])
    or ldap_die($ldapconn, "login as ".$info[0]['dn']." failed");
}
?>