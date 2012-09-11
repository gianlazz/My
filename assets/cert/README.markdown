Generating a Certificate for Google Apps
========================================

Run the following commands in this directory:

    openssl genrsa -des3 -out server.key 1024
    openssl rsa -in server.key -out server.pem
    openssl req -new -key server.key -out server.csr
    openssl x509 -req -days 9999 -in server.csr -signkey server.key -out server.crt

Then go to your Google Apps control panel, Advanced tools, SSO, and upload your server.crt.

Sign-in page URL is [app-base]/google_apps/saml
Sign-out page URL is [app-base]/login/bye
Change password URL is [app-base]/user/password

Do not enable domain-specific issuer or Network Masks
