alias c := clear-jobs-logs
alias s := supervisor-status
alias S := supervisor-stop
alias r := supervisor-restart
alias l := logs
alias j := laravel-clear-jobs
alias cmpsr := composer

default:
    just --list

clear-jobs-logs:
    sudo truncate -s 0 storage/logs/queue*

supervisor *ARG:
    sudo supervisorctl {{ARG}}

supervisor-status: (supervisor 'status')
supervisor-stop: (supervisor 'stop all')
supervisor-restart: (supervisor 'restart all')

composer CMD *ARGS:
    composer {{CMD}} --ignore-platform-reqs {{ARGS}}

logs:
    sudo tail -f storage/logs/*.log

laravel-clear-jobs:
    php artisan queue:clear
    php artisan queue:prune-failed
    php artisan queue:prune-batches