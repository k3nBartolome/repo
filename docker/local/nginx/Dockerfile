FROM alpine:latest
ARG BUILD_ENV=local

WORKDIR /cfms

RUN apk update && \
    apk add shadow nginx tzdata --no-cache && \
    cp /usr/share/zoneinfo/Asia/Tokyo /etc/localtime && \
    apk del tzdata && \
    rm -rf /var/cache/apk/*

RUN apk add --update nodejs npm

COPY ["./", "./"]
COPY ["./docker/local/nginx/config/nginx.${BUILD_ENV}.conf", "/etc/nginx/nginx.conf"]
COPY ["./docker/local/nginx/scripts/docker-entrypoint.sh", "/docker-entrypoint.sh"]

EXPOSE 80

ENTRYPOINT ["/docker-entrypoint.sh"]
