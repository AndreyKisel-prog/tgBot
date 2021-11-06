## Installation
```sh
docker-compose -up
docker-compose exec php-fpm composer install
```
## Работа с GitHub
- Создаем новую ветку для отдельной задачи ```sh git checkout -b название_ветки ``` (перед выполнением команды убедись, что находишься на актуальной версии main)
- Грамотная работа с комитами (адекватное именование, комит логически атомарных выполненных задач)
- После завершения задачи и создания последнего комита делаем команду ```sh git pull origin main ``` для подтягивания актуальной версии основной ветки
- Решаем конфликты, если таковые есть
- Пушим ветку ```sh git push origin название_ветки ```