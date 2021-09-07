# roistat_test
Тестовое задание

Для запуска:
  1. В файле hosts прописать "127.0.0.1 roistat-test.xip.io" без кавычек
  2. docker-compose up -d --build (Пункты 1 и 2 нужны только в первый раз для запуска)
  3. docker exec -ti roistat-test bash
  4. php parser.php /storage/access_log
