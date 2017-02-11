# open-golgo

Replacement system of golgo-bot written by daiz 2012-2017

## usage

```
$ git clone https://github.com/edoge/open-golgo.git
$ cd open-golgo
```

## by docker

requirements

* docker

### boot

```
$ docker build -t open-golgo .
$ docker run -d --rm open-golgo
```

### login shell

```
$ dokcer ps
$ docker exec -it <container id> ash
```

### down

```
$ dokcer ps
$ docker stop <container id>
```

## by php

requirements

* php 7.0
* composer

### setup

```
$ cd src
$ composer install
```

### boot crawler

```
$ php artinsan boot:crawl
```

### boot irc bot

```
$ php artisan boot:irc
```

### repl

```
$ php artisan tinker
```





