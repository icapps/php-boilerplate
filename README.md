# ⚡&nbsp; Symfony template project

This repository provides the recommended **project template for starting a new icapps PHP project**.
The project template follows the best-practices of the [Symfony](https://symfony.com/) framework and builds upon tho official [symfony/skeleton](https://github.com/symfony/skeleton) template.

## 🚀&nbsp; Installation and Documentation

This project is set-up using [Symfony Local Webserver](https://symfony.com/doc/current/setup/symfony_server.html):

Copy the necessary environment variables from `.env.dist` and, run:

```bash
docker-compose up -d --build
symfony proxy:start
symfony server:start --dir=symfony
```

## icapps ❤️ PHP

For further questions, ask the icapps PHP team.

## ✅&nbsp; Requirements

This project requires a **PHP version higher or equal to 7.2** and is compatible with every **Symfony version starting from 4.3**.


## 📘&nbsp; License
Symfony is released under the under terms of the [MIT License](LICENSE).
