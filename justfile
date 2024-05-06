alias c := clear-jobs-logs
alias s := supervisor-status
alias S := supervisor-stop
alias r := supervisor-restart
alias l := logs
alias j := laravel-retry-jobs
alias cmpsr := composer

default:
    just --list

clear-jobs-logs:
    sudo truncate -s 0 storage/logs/*.log /var/log/nginx/*

supervisor *ARG:
    sudo supervisorctl {{ARG}}

supervisor-status: (supervisor 'status')
supervisor-stop: (supervisor 'stop all')
supervisor-restart: (supervisor 'restart all')

composer CMD *ARGS:
    composer {{CMD}} --ignore-platform-reqs {{ARGS}}

logs:
    sudo tail -f storage/logs/*.log /var/log/nginx/*

laravel-retry-jobs:
    php artisan queue:retry all
