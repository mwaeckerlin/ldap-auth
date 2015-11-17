# NGINX LDAP Authentication Server

To be used with mwaeckerlin/nginx as follows:

    docker run -d --name ldap-auth mwaeckerlin/ldap-auth
    docker run -d -p 80:80 \
           --name nginx \
           --link ldap-auth:ldap \
           -e LDAP_HOST="my.ldap-host.com" \
           -e LDAP_BASE_DN="ou=people,dc=my,dc=ldap-host,dc=com" \
           -e LDAP_BIND_DN="uid=nginx-bind,ou=system,ou=people,dc=my,dc=ldap-host,dc=com" \
           -e LDAP_BIND_PASS="the-secret-of-nginx-bind" \
           mwaeckerlin/nginx

The configuration is done through the NGINX server that connects to the LDAP Authentication Server.