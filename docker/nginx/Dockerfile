FROM nginx:1.23.3-alpine

# copy the public directory
COPY ./app/public/ /app/public/

# copy the nginx config file
COPY ./docker/nginx/nginx.conf /etc/nginx/conf.d/default.conf

# Change user pid, gid
ARG UID=1000
ARG GID=1000
RUN apk --no-cache add shadow \
    && usermod -u $UID nginx \
    && groupmod -g $GID nginx \
    && apk --purge del shadow

## add permissions for non-root
RUN chown -R nginx:nginx /var/cache/nginx && \
    chown -R nginx:nginx /var/log/nginx && \
    chown -R nginx:nginx /etc/nginx/conf.d
RUN touch /var/run/nginx.pid && \
    chown -R nginx:nginx /var/run/nginx.pid

# switch to non-root user
USER nginx