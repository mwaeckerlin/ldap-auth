FROM ubuntu
MAINTAINER mwaeckerlin

EXPOSE 8888

WORKDIR /

RUN apt-get install -y git python-ldap
RUN git clone https://github.com/nginxinc/nginx-ldap-auth.git

WORKDIR /nginx-ldap-auth

CMD ./nginx-ldap-auth-daemon.py
