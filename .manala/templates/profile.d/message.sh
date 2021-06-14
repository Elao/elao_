printf "\n"
printf "          \033[33m|\033[0m\n"
printf "        \033[33m\ _ /\033[0m     Lazy - Symfony\n"
printf "      \033[33m-= (_) =-\033[0m\n"
printf "        \033[33m/   \ \033[0m        \033[32m_\/_\033[0m\n"
printf "          \033[33m|\033[0m           \033[32m//o\ \033[0m \033[32m_\/_\033[0m\n"
printf "\033[34m   _____ _ __ __ ____ _ \033[32m| \033[34m__\033[32m/o\ \033[34m_\033[0m\n"
printf "\033[34m =-=-_-__=_-= _=_=-=_,-'\033[32m|\033[34m\"'\"\"-\033[32m|\033[34m-,_\033[0m\n"
printf "\033[34m  =- _=-=- -_=-=_,-\"          \033[32m|\033[34m\"\033[0m\n"
printf "\033[34m    =- =- -=.--\"\033[0m\n"

printf "\n"
printf " \033[36m‣ nginx \033[35m1.18\033[0m\n"
printf " \033[36m‣ php \033[35m7.4\033[0m\n"
printf " \033[36m‣ nodejs \033[35m12\033[0m\n"

printf "\n"

printf " \033[36m• php-xdebug \033[35m[on|off] \033[37m- enable/disable php xdebug\033[0m\n"
php-xdebug () {
  if [ "$1" = on ]; then
    sudo phpenmod xdebug
    sudo s6-svc -r /etc/services/php
  elif [ "$1" = off ]; then
    sudo phpdismod xdebug
    sudo s6-svc -r /etc/services/php
  else
    if $(php -m | grep -q Xdebug); then
        echo on
    else
        echo off
    fi
  fi
}
