.PHONY: build up down restart logs php-logs nginx-logs prune

build:
	sudo docker compose build

up:
	sudo docker compose up --build -d

down:
	sudo docker compose down --volumes --remove-orphans

restart: down up

logs:
	sudo docker compose logs -f --tail=50

php-logs:
	sudo docker compose exec php tail -n 50 /var/log/php_errors.log || echo "Pas de log PHP trouvé."

nginx-logs:
	sudo docker compose exec nginx cat /var/log/nginx/error.log || echo "Pas de log Nginx trouvé."

prune:
	sudo docker system prune --all --volumes -f
