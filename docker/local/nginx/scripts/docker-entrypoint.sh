#!/bin/sh

USER_ID=${UID:-1000}
GROUP_ID=${GID:-1000}

echo "starting with USER_ID: ${USER_ID}, GROUP_ID: ${GROUP_ID}..."

if [[ "${GROUP_ID}" != $(id -g nginx) ]]; then
    groupmod -g ${GROUP_ID} nginx
fi

if [[ "${USER_ID}" != $(id -u nginx) ]]; then
    usermod -u ${USER_ID} -g nginx nginx
fi

if [[ ! -d /var/tmp/nginx ]]; then
    mkdir -p /var/tmp/nginx
fi

chown -R nginx:nginx /cfms /var/tmp/nginx

if [[ $# -eq 0 ]]; then
    /usr/sbin/nginx
else
    exec "$@"
fi
