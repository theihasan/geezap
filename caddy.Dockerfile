FROM caddy:2.10-alpine

COPY Caddyfile /etc/caddy/Caddyfile

COPY --from=geezap/app:latest /app /app
