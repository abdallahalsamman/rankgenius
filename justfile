alias cjl := clear-jobs-logs
alias ss := supervisor-status
alias sS := supervisor-stop
alias sr := supervisor-restart
alias l := logs
alias ljp := laravel-jobs-prune

default:
    just --list

clear-jobs-logs:
    sudo truncate -s 0 storage/logs/queue*

supervisor-status:
    sudo supervisorctl status

supervisor-stop:
    sudo supervisorctl stop all

supervisor-restart: 
    supervisor restart all

supervisor ARG:
    sudo supervisorctl {{ARG}}

logs:
    sudo tail -f storage/logs/*.log

laravel-jobs-prune:
    php artisan queue:clear
    php artisan queue:prune-failed
    php artisan queue:prune-batches