#STS Database Technical Documentation
This document details the development and maintenance of the [STS Database Application](http://stsdatabase.org) and is divided into the following sections:

- [Resources](#resources)
- [Development](#development)
- [Deployment](#deployment)

## [Resources](id:resources)

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

- Get access from Alison Silberman: `asilberman@ocrfa.org`

####SSH Access to the Server
- If your public key is added: `ssh ubuntu@54.210.34.83`
- If you have a key file: `ssh -i sts.pem ubuntu@54.210.34.83`
- You need to have an existing admin add your key to the server for either of these work.

####Logs

- PHP Error Log: `/tmp/logs/php.log`
- MongoDB Log: `/var/log/mongodb/mongod.log`

####Virtual Host Configuration

- Virtual host configuration files are stored in the `/etc/apache2/sites-available/`
- Sites are enabled by creating symbolic links to the configuration files in the directory `/etc/apache2/sites-enabled/`

####MongoDB
The production database running on the AWS instance is MongoDb.

Configuration `/etc/mongod.conf`:

```
fork = true
dbpath = /var/lib/mongodb
logpath = /var/log/mongodb/mongodb.log
logappend = true
journal = true
auth = true
```

- host: `54.210.34.83:27017`
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
This is managed by [SparkPost](https://www.sparkpost.com)

username: `ocna@ocrfa.org`
password: `Ocrfa2016!`

## [Development](id:development)

The STS Database Application has the following environments:

- Local Development
- Staging
- Production

###Local Development

This application is intended to be installed on a Vagrant-based virtual machine for development purposes

- Clone the project
- Make sure you have Vagrant and VirtualBox installed.
    - [Vagrant](https://www.vagrantup.com)
    - [VirtualBox](https://www.virtualbox.org)
- In the top-level directory of the project (sts-database), issue
 
```
vagrant up
```

    - If prompted, enter your administrator password to enable NFS mounts.
- Vagrant will download the appropriate box and run the Ansible playbooks to install the various packages.

Once everything is installed (this may take several minutes the first time), SSH to your new box:

```
vagrant ssh
```

#### First Time Only
##### Import Database
The first time you launch your VM, you'll need to import the production database.

```
cd /vagrant/build-deploy/
tar xzf sts-production.tgz
mongorestore dump/sts-production
```

##### Copy SSH Key

```
cd ~/
cp /vagrant/build-deploy/ocnasts.pem ./
```

##### Install Ant
```
sudo apt-get install -y ant
```

Answer "y" when it asks if you're sure.

##### Set config files

1. Copy `build-deploy/config-templates/template.core.xml` to `application/config/core.xml`. 
1. Copy `build-deploy/config-templates/dev.application.ini` to `application/config/application.ini`. 
1. The SparkPost configuration should be done for you.
1. Tou will need to enter your MongoDB information in `core.xml`. By default, the `host` is `localhost` and the `port` is `27017`. Your local configuration may look like:

```
<db>
    <mongodb>
        <host>localhost</host>
        <port>27017</port>
        <username></username>
        <password></password>
        <dbname>sts-production</dbname>
    </mongodb>
</db>
```

##### Run Composer Install
From the top level of the project, run `composer install`. This is much faster than `composer update` and gets you versions of everything in `composer.lock`.

If you update the requirements in `composer.json`, you'll want to do `composer update` to get the latest versions of everything that match the rules in `composer.json`.

##### Update your hosts file
In your hosts file (`/etc/hosts` on Mac and Linux), enter the following rule:

```
192.168.33.101    dev.sts.ovariancancer.org
```

To test, visit [http://dev.sts.ovariancancer.org](http://dev.sts.ovariancancer.org)

####Logs

- MongoDB Log: `/var/log/mongod.log`
- PHP Error Log: `/tmp/php.log`
- Apache Error Log: `/tmp/apache-error.log`
- Apache Access Log: `/tmp/apache-access.log`
- Application Log: `/var/log/sts-database/application.log`

###Managing Persistence
The STS Database Application uses [MongoDB](http://www.mongodb.org/) for persistence.

Running Command Sets:

```
mongo localhost:27017/sts-development data/.../my_commands.js

mongo -u sts -p sT6D9tA01 54.210.34.83:27017/sts-production data/.../my_comands.js
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
ant -propertyfile stg.deploy.properties -f deploy.xml deploy -Dgit.branch=master
ant -propertyfile production.deploy.properties -f deploy.xml deploy -Dgit.branch=master
```


###Environments
There are two environments that you may deploy to:


####Staging
The staging location is a http auth protected public server for previewing updates, it points to its own database "sts-stage" but should /not be used for production.

***Location:*** [http://54.210.34.83:8080/](http://54.210.34.83:8080/)

- HTTP Auth Username: `admin`
- HTTP Auth Password: `stsdemo`

***Environment Details:***

- APPLICATION_ENV : `staging`

***Deployment Details:***

- rsync.user:   `ubuntu`
- rsync.host:   `54.210.34.83`
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
- rsync.host:   `54.210.34.83`
- rsync.dest:   `/var/www/sts-beta-01`
- env:          `beta`

***Logs:***

- Apache Error Log: `/tmp/logs/sts-beta-01/apache-error.log`
- Application Log: `/tmp/logs/sts-beta-01/application.log`

