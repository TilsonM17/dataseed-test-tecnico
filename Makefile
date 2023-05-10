cp:
	cp .env.example .env
up:
	docker-compose -f "docker/docker-compose.yml" --env-file .env up -d

stop:
	docker-compose -f "docker/docker-compose.yml" --env-file .env stop

down:
	docker-compose -f "docker/docker-compose.yml" --env-file .env down

shell:
	docker exec -it php8 bash
