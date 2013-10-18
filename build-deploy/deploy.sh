#!/bin/sh

if [ "$1" == "dev" ]
then
    echo "Deploying to Dev"
    ant -propertyfile stg.deploy.properties -f deploy.xml deploy -Dgit.branch=develop
fi

if [ "$1" == "prod" ]
then
    echo "Deploying to Production"
    ant -propertyfile production.deploy.properties -f deploy.xml deploy -Dgit.branch=master
fi