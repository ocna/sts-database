#STS Database Technical Documentation
This document details the development and maintenance of the [STS Database Application](http://stsdatabase.org) and is divided into the following sections:

- [Resources](#resources)
- [Development](#development)
- [Deployment](#deployment)

## [Resources](id:resources)
###Project Management
The project is managed using a Trello board here: [https://trello.com/board/sts-database/5050b7c870f409436916693b](https://trello.com/board/sts-database/5050b7c870f409436916693b).

Other relevant files are shared in dropbox here: [https://www.dropbox.com/sh/bmmwnqhdlc4xpes/HDz2bwupfa](https://www.dropbox.com/sh/bmmwnqhdlc4xpes/HDz2bwupfa)

###Code Hosting
The code is hosted at GitHub under an OCNA account. The account details are:

Billing Email: `asilberman@ovariancancer.org`
Owners: TBD
Password: TBD

The code is hosted at GitHub here: [https://github.com/ocna/sts-database](https://github.com/ocna/sts-database)


###Server
The main environments are located on an AWS EC2 Instance. The server directories are located under `/var/www/`.

####Accessing the AWS Management Console

[https://console.aws.amazon.com](https://console.aws.amazon.com)

- Username: `jason.robertfox@gmail.com`
- Password: `@wS40Cn@`

####SSH Access to the Server
- If your public key is added: `ssh ubuntu@23.21.64.30`
- If you have a key file: `ssh -i deploy.pem ubuntu@23.21.64.30`

####Accessing the Zend Server Management Console
The server is running an instance of Zend Server CE

- Administration Console: [http://23.21.64.30:10081/](http://23.21.64.30:10081/)
- Password: `0cn@z3nD`

####Logs

- PHP Error Log: `/tmp/logs/php.log`
- MongoDB Log: `/var/log/mongodb/mongod.log`

####Virtual Host Configuration

- Virtual host configuration files are stored in the `/etc/apache2/sites-available/`
- Sites are enabled by creating symbolic links to the configuration files in the directory `/etc/apache2/sites-enabled/`

####MongoDB
The production database running on the AWS instance is MongoDb.

Configuration `/etc/mongodb.conf`:

```
fork = true
dbpath = /var/lib/mongodb
logpath = /var/log/mongodb/mongodb.log
logappend = true
journal = true
auth = true
```

- host: `23.21.64.30:27017`
- Admin Username: `admin`
- Admin Password: `0cn@M0nG0`
- Database: `sts-production`
- sts-production Username: `sts`
- sts-production Password: `sT6D9tA01`

###Domain
The domain is managed at [Network Solutions](http://neworksolutions.com):

username: `24845043`
password: `ocna2010`

###Transactional Email
This is managed by [Mandrill](https://mandrillapp.com/)

username: `jason.robertfox@gmail.com`
password: `0Cn@MnDr11`

> **Note**: This line must be added in the Mandrill `call` function if there are ssl problems:
> 
>     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

The STS Database Application has the following environments:

- Local Development
- Staging
- Beta
- Production

## [Development](id:development)
###Developing Features
The project uses [gitflow](https://github.com/nvie/gitflow) and the [gitflow branching model](http://nvie.com/posts/a-successful-git-branching-model/) to manage feature development and release preparation. 

> **Note:** You should set up gitflow with the version prefix `v` and the trello card number for commits, for example: `tr-123`.

Example:

```
git flow feature start tr-123
git flow feature finish tr-123

git flow release start 1.0.0
git flow release finish 1.0.0
```

> **Note:** Releases should be named `<major>.<minor>.<fix>`. During the release process you must update the `/design/scripts/partials/app-footer.phtml` with the current version number prior to deployment.

###Local Development
This application is intended to be installed locally for development purposes.

1. Clone the repository
2. Use the target `install` to install dependencies and configure the application:

    ```
    ant install -Denv=dev
    ```

3. Create a virtual host file to point to the `public` folder, example:

    ```
    <VirtualHost *:80>
        DocumentRoot "/Users/jason/Development/sts-database/public"
        ServerName dev.sts.ovariancancer.org

        SetEnv APPLICATION_ENV development

        <Directory "/Users/jason/Development/sts-database/public">
            DirectoryIndex index.php index.html
            AllowOverride All
            Order allow,deny
            Allow from all
        </Directory>

   </VirtualHost>
   ```

####Logs

- MongoDB Log: `/var/log/mongod.log`
- PHP Error Log: `/tmp/php.log`
- Apache Error Log: `/tmp/apache-error.log`
- Apache Access Log: `/tmp/apache-access.log`
- Application Log: `/var/log/sts-database/application.log`

####MongoDB
Local database connection is used for development purposes, mongo can be simply configured locally using a `mongodb.conf` file such as:

```
fork = true
bind_ip = 127.0.0.1
port = 27017
quiet = false
dbpath = /data/db/
logpath = /tmp/logs/mongod.log
logappend = true
journal = true
```
###Managing Persistence
The STS Database Application uses [MongoDB](http://www.mongodb.org/) for persistence.

Importing Datasets:

```
mongoexport -v -d sts-development -c area --jsonArray -o ../collections/area.json

mongoimport -v -h 23.21.64.30:27017 -d sts-production -c area -u sts -p sT6D9tA01 --jsonArray --file area.json

```

Running Command Sets:

```
mongo localhost:27017/sts-development data/.../my_commands.js

mongo -u sts -p sT6D9tA01 23.21.64.30:27017/sts-production data/.../my_comands.js
```

Connecting to the db on production and listing all collections
```
mongo -u sts -p sT6D9tA01 sts-production
show collections
```

Export all collections in production database to a directory, one file per collection
```
mongodump -u sts -p sT6D9tA01 -d sts-production -o ./sts-production-dump
```

Restore database dump to another instance, assumes you copy the sts-production-dump 
from production to a local path.
```
mongorestore -v -d sts-dev /local/path/to/sts-production-dump
```
## [Deployment](id:deployment)
Deployment of the application is handled with **ant** and the settings are stored in a `<env>.deploy.properties` file local to the developer that is deploying. If this is set up you can just follow the examples below.
Deployment is simple, ensure you have an appropriately configured .

> **Note**: You must have your public key added to the known_hosts of the server in order to deploy.

Examples:

```
ant -propertyfile stg.deploy.properties -f deploy.xml deploy -Dgit.branch=develop
ant -propertyfile stg.deploy.properties -f deploy.xml deploy -Dgit.branch=release/1.0
ant -propertyfile beta.deploy.properties -f deploy.xml deploy -Dgit.branch=release/1.0
```

There is a wrapper shell script in `src/build-deploy/` to handle deployments to production/staging so you can do:

The following to deploy from the `develop` branch to the staging site:

~~~~
./deploy.sh dev
~~~~

or the following to deploy from the `master` branch to the production site:

~~~~
./deploy.sh prod
~~~~

###Environments
There are two environments that you may deploy to:


####Staging
The staging location is a http auth protected public server for previewing updates, it points to its own database "sts-stage" but should /not be used for production.

***Location:*** [http://23.21.64.30:8080/](http://23.21.64.30:8080/)

- HTTP Auth Username: `admin`
- HTTP Auth Password: `stsdemo`

***Environment Details:***

- APPLICATION_ENV : `staging`

***Deployment Details:***

- rsync.user:   `ubuntu`
- rsync.host:   `23.21.64.30`
- rsync.dest:   `/var/www/sts-stg-01`
- env:          `stg`

***Logs:***

- Apache Error Log: `/tmp/logs/sts-stg-01/apache-error.log`
- Application Log: `/tmp/logs/sts-stg-01/application.log`

####Production
At some point, the paths calling this "beta" should be renamed to production.

***Location:*** [http://stsdatabase.org/](http://stsdatabase.org/)

***Environment Details:***

- APPLICATION_ENV : `beta`

***Deployment Details:***

- rsync.user:   `ubuntu`
- rsync.host:   `23.21.64.30`
- rsync.dest:   `/var/www/sts-beta-01`
- env:          `beta`

***Logs:***

- Apache Error Log: `/tmp/logs/sts-beta-01/apache-error.log`
- Application Log: `/tmp/logs/sts-beta-01/application.log`

