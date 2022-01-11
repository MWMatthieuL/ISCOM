# Projet ISCOM

## Install project

1. Get sources

    ```bash
    git clone git@github.com:MatthieuLV/TP-Docker.git
    ```  

3. Specific docker configuration

    You need to override the configuration of Docker. To do this, just copy `docker-compose-dev.yml.dist` to
    `docker-compose-dev.yml` and modify services as you need.

4. Start project

    ```
    make start
    ```

5. Add host to your system

    Add `127.0.0.1    iscom.local` in your hosts file
    - `/etc/hosts` on MacOS and Linux
    - `C:\WINDOWS\system32\drivers\etc\hosts` on Windows

6. Go to 

- http://iscom.local/ to see the project
    
- http://iscom.local:8080 to see database (username: iscom, password: iscom)
    
- http://iscom.local:1080 to see catched emails

## Make commands

| Command                     | Usage                                     |
|-----------------------------|-------------------------------------------|
| make start                  | Start the project                         |
| make up                     | Start all containers                      |
| make stop                   | Stop all containers                       |
| make rm                     | Stop and Delete all containers            |
| make ssh                    | Connect to app container on ssh           |
| make run c='bash command'   | Run any bash command                      |
| make sf c=' '               | Run any bin/console command               |
| make db                     | Init database with data fixtures          |
| make db-migrate             | Execute doctrine migrations               |
| make cc                     | Clear cache                               |
| make assets                 | Regenerate assets files (css+js)          |
| make assets-watch           | Regenerate assets files and watch changes |
| make perm                   | Give permissions                          |
| make vendor                 | Install dependencies PHP + Node           |
