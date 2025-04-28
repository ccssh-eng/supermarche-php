#!/bin/bash

echo "=== NGINX Error Log ==="
docker compose exec nginx cat /var/log/nginx/error.log 2>/dev/null || echo "Pas de log Nginx trouvé."

echo ""
echo "=== PHP Logs ==="
docker compose exec php tail -n 30 /var/log/php_errors.log 2>/dev/null || echo "Pas de log PHP trouvé."
