## Chronolabs Cooperative presents

# Short URL Deployment API

## Version: 1.0.1 (pre-alpha)

### Author: Dr. Simon A. Roberts <wishcraft@users.sourceforge.net>

### Sourcerer: https://sourcerer.io/draroberts

#### Demo: http://deploy.jump.snails.email

# Cron Jobs / Scheduled Tasks
This is the functions for the cron jobs, to access the cron tab in ubuntu/debian type the following:

    $ sudo crontab -e
    
## Crontab entries
These are to go in you're crontab console

    */10 * * * * sh /var/www/deploy.jump.snails.email/crons/jumpapi-crons-1.sh
    */10 * * * * sh /var/www/deploy.jump.snails.email/crons/jumpapi-crons-2.sh
    */10 * * * * sh /var/www/deploy.jump.snails.email/crons/jumpapi-crons-3.sh
    */10 * * * * sh /var/www/deploy.jump.snails.email/crons/jumpapi-crons-4.sh
    */20 * * * * sh /var/www/deploy.jump.snails.email/crons/awstats-crons-1.sh
    */20 * * * * sh /var/www/deploy.jump.snails.email/crons/awstats-crons-2.sh
    */20 * * * * sh /var/www/deploy.jump.snails.email/crons/awstats-crons-3.sh
    */20 * * * * sh /var/www/deploy.jump.snails.email/crons/awstats-crons-4.sh
    */3 * * * * sh /var/www/deploy.jump.snails.email/crons/configure.sh
    */2 * * * * sh /var/www/deploy.jump.snails.email/crons/sql.sh
    */13 * * * * php -q /var/www/deploy.jump.snails.email/crons/configure.php
    */1 * * * * php -q /var/www/deploy.jump.snails.email/crons/apache2-configure.php
    */1 * * * * php -q /var/www/deploy.jump.snails.email/crons/apache2-ssl-configure.php
    */1 * * * * php -q /var/www/deploy.jump.snails.email/crons/awstats-configure.php
    */17 * * * * php -q /var/www/deploy.jump.snails.email/crons/awstats-crons.php
    */8 * * * * php -q /var/www/deploy.jump.snails.email/crons/jumpapi-crons.php
    */13 */2 * * * php -q /var/www/deploy.jump.snails.email/crons/github-configure.php
    */15 * * * * php -q /var/www/deploy.jump.snails.email/crons/ssl-configure.php
