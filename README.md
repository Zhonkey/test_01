Test Yii2 todo-list.

# Makefile Docker + Yii2

Краткий список команд для работы с проектом.

---

## Сборка и запуск

```bash
# Собрать образы Docker
make build
```

```bash
# Поднять контейнеры и инициализировать проект
make up
```

```bash
# Открыть bash в контейнере queue
make sh
```

```bash
# Подключиться к MariaDB
make db
```

```bash
# Остановить и удалить контейнеры
make down
```

# Создать тестовые задачи (seed)
./yii seed

# Очистить все задачи
./yii seed/clear